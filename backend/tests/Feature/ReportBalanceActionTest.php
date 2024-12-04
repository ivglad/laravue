<?php

namespace Tests\Feature;

use App\Actions\Report\BalanceAction;
use App\Enums\CurrencyCode;
use App\Models\Agreement\Agreement;
use App\Models\Handbook\Counterparty;
use App\Models\Handbook\Position;
use App\Models\Order\Estimate;
use App\Models\Order\EstimatePosition;
use App\Models\Order\Order;
use App\Models\Order\Payment;
use App\Models\Order\PaymentPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportBalanceActionTest extends TestCase
{
    public function test_report_balance_expense_plan_fact_deviation(): void
    {
        $isIncome = false;
        $agreement = Agreement::factory()->create();
        $counterparty = Counterparty::factory()->create();
        $order = Order::factory()->create([
            'counterparty_id' => $counterparty->id,
            'agreement_id' => $agreement->id,
        ]);
        $position = Position::factory()->create();
        $estimate = Estimate::factory()->create([
            'counterparty_to_id' => $counterparty->id,
            'counterparty_from_id' => $counterparty->id,
            'order_id' => $order->id,
            'sort' => 1,
            'currency_code' => CurrencyCode::RUB,
        ]);
        $price = 1000;
        $quantity = 1;
        $sum = $price * $quantity;
        EstimatePosition::factory()->create([
            'estimate_id' => $estimate->id,
            'position_id' => $position->id,
            'quantity' => $quantity,
            'currency_code' => CurrencyCode::RUB,
            'currency_value' => 1,
            'currency_increase' => 0,
            'weight' => 1.0,
            'price' => $price,
            'sum' => $sum,
            'sum_tax' => $sum * 1.2,
            'agreement_id' => $agreement->id,
            'is_income' => true,
        ]);
        $price = 500;
        $quantity = 1;
        $sum = $price * $quantity;
        $estimatePosition = EstimatePosition::factory()->create([
            'estimate_id' => $estimate->id,
            'position_id' => $position->id,
            'quantity' => $quantity,
            'currency_code' => CurrencyCode::RUB,
            'currency_value' => 1,
            'currency_increase' => 0,
            'weight' => 1.0,
            'price' => $price,
            'sum' => $sum,
            'sum_tax' => $sum * 1.2,
            'agreement_id' => $agreement->id,
            'is_income' => false,
        ]);
        $percent = 50;
        $pp1 = PaymentPeriod::factory()->create([
            'estimate_position_id' => $estimatePosition->id,
            'estimate_id' => $estimate->id,
            'payment_date' => '2021-01-01',
            'percent' => $percent,
            'sum' => $price * $quantity * ($percent / 100),
            'is_income' => false,
            'currency_code' => CurrencyCode::RUB,
        ]);
        $pp2 = PaymentPeriod::factory()->create([
            'estimate_position_id' => $estimatePosition->id,
            'estimate_id' => $estimate->id,
            'payment_date' => '2021-02-01',
            'percent' => $percent,
            'sum' => $price * $quantity * ($percent / 100),
            'is_income' => false,
            'currency_code' => CurrencyCode::RUB,
        ]);
        $payment1 = Payment::factory()->create([
            'payment_period_id' => $pp1->id,
            'payment_date' => '2021-01-01',
            'amount' => $pp1->sum,
            'currency_value' => 1,
            'is_income' => false,
        ]);
        $payment2 = Payment::factory()->create([
            'payment_period_id' => $pp2->id,
            'payment_date' => '2021-02-01',
            'amount' => $pp2->sum / 2,
            'currency_value' => 1,
            'is_income' => false,
        ]);

        $attr = [
            'counterparty_id' => $counterparty->id,
            'agreement_id' => $agreement->id,
        ];

        $result = (new BalanceAction($attr))->run();

        $this->assertEquals($isIncome ? $pp1->sum : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['plan']);
        $this->assertEquals($isIncome ? $payment1->amount : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['fact']);
        $this->assertEquals($isIncome ? $pp1->sum - $payment1->amount : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['deviation']);
        $this->assertEquals($isIncome ? $pp2->sum : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['plan']);
        $this->assertEquals($isIncome ? $payment2->amount : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['fact']);
        $this->assertEquals($isIncome ? $pp2->sum - $payment2->amount : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['deviation']);

        $this->assertEquals($isIncome ? $pp1->sum : 0, $result['income']['sum']['Январь 2021']['plan']);
        $this->assertEquals($isIncome ? $payment1->amount : 0, $result['income']['sum']['Январь 2021']['fact']);
        $this->assertEquals($isIncome ? $pp1->sum - $payment1->amount : 0, $result['income']['sum']['Январь 2021']['deviation']);
        $this->assertEquals($isIncome ? $pp2->sum : 0, $result['income']['sum']['Февраль 2021']['plan']);
        $this->assertEquals($isIncome ? $payment2->amount : 0, $result['income']['sum']['Февраль 2021']['fact']);
        $this->assertEquals($isIncome ? $pp2->sum - $payment2->amount : 0, $result['income']['sum']['Февраль 2021']['deviation']);


        $this->assertEquals(!$isIncome ? $pp1->sum : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['plan']);
        $this->assertEquals(!$isIncome ? $payment1->amount : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['fact']);
        $this->assertEquals(!$isIncome ? $pp1->sum - $payment1->amount : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['deviation']);
        $this->assertEquals(!$isIncome ? $pp2->sum : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['plan']);
        $this->assertEquals(!$isIncome ? $payment2->amount : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['fact']);
        $this->assertEquals(!$isIncome ? $pp2->sum - $payment2->amount : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['deviation']);

        $this->assertEquals(!$isIncome ? $pp1->sum : 0, $result['expense']['sum']['Январь 2021']['plan']);
        $this->assertEquals(!$isIncome ? $payment1->amount : 0, $result['expense']['sum']['Январь 2021']['fact']);
        $this->assertEquals(!$isIncome ? $pp1->sum - $payment1->amount : 0, $result['expense']['sum']['Январь 2021']['deviation']);
        $this->assertEquals(!$isIncome ? $pp2->sum : 0, $result['expense']['sum']['Февраль 2021']['plan']);
        $this->assertEquals(!$isIncome ? $payment2->amount : 0, $result['expense']['sum']['Февраль 2021']['fact']);
        $this->assertEquals(!$isIncome ? $pp2->sum - $payment2->amount : 0, $result['expense']['sum']['Февраль 2021']['deviation']);
    }

    public function test_report_balance_income_plan_fact_deviation(): void
    {
        $isIncome = true;
        $agreement = Agreement::factory()->create();
        $counterparty = Counterparty::factory()->create();
        $order = Order::factory()->create([
            'counterparty_id' => $counterparty->id,
            'agreement_id' => $agreement->id,
        ]);
        $position = Position::factory()->create();
        $estimate = Estimate::factory()->create([
            'counterparty_to_id' => $counterparty->id,
            'counterparty_from_id' => $counterparty->id,
            'order_id' => $order->id,
            'sort' => 1,
            'currency_code' => CurrencyCode::RUB,
        ]);
        $price = 500;
        $quantity = 1;
        $sum = $price * $quantity;
        EstimatePosition::factory()->create([
            'estimate_id' => $estimate->id,
            'position_id' => $position->id,
            'quantity' => $quantity,
            'currency_code' => CurrencyCode::RUB,
            'currency_value' => 1,
            'currency_increase' => 0,
            'weight' => 1.0,
            'price' => $price,
            'sum' => $sum,
            'sum_tax' => $sum * 1.2,
            'agreement_id' => $agreement->id,
            'is_income' => false,
        ]);
        $price = 1000;
        $quantity = 1;
        $sum = $price * $quantity;
        $estimatePosition = EstimatePosition::factory()->create([
            'estimate_id' => $estimate->id,
            'position_id' => $position->id,
            'quantity' => $quantity,
            'currency_code' => CurrencyCode::RUB,
            'currency_value' => 1,
            'currency_increase' => 0,
            'weight' => 1.0,
            'price' => $price,
            'sum' => $sum,
            'sum_tax' => $sum * 1.2,
            'agreement_id' => $agreement->id,
            'is_income' => true,
        ]);
        $percent = 50;
        $pp1 = PaymentPeriod::factory()->create([
            'estimate_position_id' => $estimatePosition->id,
            'estimate_id' => $estimate->id,
            'payment_date' => '2021-01-01',
            'percent' => $percent,
            'sum' => $price * $quantity * ($percent / 100),
            'is_income' => true,
            'currency_code' => CurrencyCode::RUB,
        ]);
        $pp2 = PaymentPeriod::factory()->create([
            'estimate_position_id' => $estimatePosition->id,
            'estimate_id' => $estimate->id,
            'payment_date' => '2021-02-01',
            'percent' => $percent,
            'sum' => $price * $quantity * ($percent / 100),
            'is_income' => true,
            'currency_code' => CurrencyCode::RUB,
        ]);
        $payment1 =Payment::factory()->create([
            'payment_period_id' => $pp1->id,
            'payment_date' => '2021-01-01',
            'amount' => $pp1->sum,
            'currency_value' => 1,
            'is_income' => true,
        ]);
        $payment2 = Payment::factory()->create([
            'payment_period_id' => $pp2->id,
            'payment_date' => '2021-02-01',
            'amount' => $pp2->sum / 2,
            'currency_value' => 1,
            'is_income' => true,
        ]);

        $attr = [
            'counterparty_id' => $counterparty->id,
            'agreement_id' => $agreement->id,
        ];

        $result = (new BalanceAction($attr))->run();

        $this->assertEquals($isIncome ? $pp1->sum : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['plan']);
        $this->assertEquals($isIncome ? $payment1->amount : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['fact']);
        $this->assertEquals($isIncome ? $pp1->sum - $payment1->amount : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['deviation']);
        $this->assertEquals($isIncome ? $pp2->sum : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['plan']);
        $this->assertEquals($isIncome ? $payment2->amount : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['fact']);
        $this->assertEquals($isIncome ? $pp2->sum - $payment2->amount : 0, $result['income']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['deviation']);

        $this->assertEquals($isIncome ? $pp1->sum : 0, $result['income']['sum']['Январь 2021']['plan']);
        $this->assertEquals($isIncome ? $payment1->amount : 0, $result['income']['sum']['Январь 2021']['fact']);
        $this->assertEquals($isIncome ? $pp1->sum - $payment1->amount : 0, $result['income']['sum']['Январь 2021']['deviation']);
        $this->assertEquals($isIncome ? $pp2->sum : 0, $result['income']['sum']['Февраль 2021']['plan']);
        $this->assertEquals($isIncome ? $payment2->amount : 0, $result['income']['sum']['Февраль 2021']['fact']);
        $this->assertEquals($isIncome ? $pp2->sum - $payment2->amount : 0, $result['income']['sum']['Февраль 2021']['deviation']);


        $this->assertEquals(!$isIncome ? $pp1->sum : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['plan']);
        $this->assertEquals(!$isIncome ? $payment1->amount : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['fact']);
        $this->assertEquals(!$isIncome ? $pp1->sum - $payment1->amount : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Январь 2021']['deviation']);
        $this->assertEquals(!$isIncome ? $pp2->sum : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['plan']);
        $this->assertEquals(!$isIncome ? $payment2->amount : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['fact']);
        $this->assertEquals(!$isIncome ? $pp2->sum - $payment2->amount : 0, $result['expense']['row'][$counterparty->id . '-' . $order->agreement_id ?? 'null']['balance']['Февраль 2021']['deviation']);

        $this->assertEquals(!$isIncome ? $pp1->sum : 0, $result['expense']['sum']['Январь 2021']['plan']);
        $this->assertEquals(!$isIncome ? $payment1->amount : 0, $result['expense']['sum']['Январь 2021']['fact']);
        $this->assertEquals(!$isIncome ? $pp1->sum - $payment1->amount : 0, $result['expense']['sum']['Январь 2021']['deviation']);
        $this->assertEquals(!$isIncome ? $pp2->sum : 0, $result['expense']['sum']['Февраль 2021']['plan']);
        $this->assertEquals(!$isIncome ? $payment2->amount : 0, $result['expense']['sum']['Февраль 2021']['fact']);
        $this->assertEquals(!$isIncome ? $pp2->sum - $payment2->amount : 0, $result['expense']['sum']['Февраль 2021']['deviation']);
    }
}
