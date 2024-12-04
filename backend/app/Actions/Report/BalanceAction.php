<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\Contracts\Actions\HasRun;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

final readonly class BalanceAction implements HasRun
{
    private Collection $orders;

    public function __construct(private array $attr)
    {
        $ordersBuilder = Order::with(Order::RELATIONS);
        if (!empty($this->attr['counterparty_ids'])) {
            $ordersBuilder->whereIn('counterparty_id', $this->attr['counterparty_ids']);
        }
        if (!empty($this->attr['agreement_ids'])) {
            $ordersBuilder->whereIn('agreement_id', $this->attr['agreement_ids']);
        }
        $ordersBuilder->whereHas('estimates', function (Builder $queryEstimates) {
            $queryEstimates->whereHas('payment_periods', function (Builder $queryPaymentPeriods) {
                if (!empty($this->attr['from'])) {
                    $queryPaymentPeriods->where('payment_date', '>=', $this->attr['from']);
                }
                if (!empty($this->attr['to'])) {
                    $queryPaymentPeriods->where('payment_date', '<=', $this->attr['to']);
                }
            });
            $queryEstimates->orWhereHas('positions', function (Builder $queryPositions) {
                $queryPositions->whereHas('payment_periods', function (Builder $queryPaymentPeriods) {
                    if (!empty($this->attr['from'])) {
                        $queryPaymentPeriods->where('payment_date', '>=', $this->attr['from']);
                    }
                    if (!empty($this->attr['to'])) {
                        $queryPaymentPeriods->where('payment_date', '<=', $this->attr['to']);
                    }
                });
            });
        });
        $this->orders = $ordersBuilder->get();
    }

    public function run(): array
    {
        $data = [
            'income' => [],
            'expense' => [],
        ];
        $sum = [
            'income' => [],
            'expense' => [],
        ];
        foreach ($this->orders as $order) {
            foreach ($order->estimates as $estimate) {
                $plan = [];
                $fact = [];
                $deviation = [];
                $currencyPayments = [];
                $balance = [];
                if (blank($estimate->payment_periods)) {
                    foreach ($estimate->positions as $position) {
                        foreach ($position->payment_periods as $paymentPeriod) {
                            if (!empty($this->attr['from'])) {
                                if ($paymentPeriod->payment_date < $this->attr['from']) {
                                    continue;
                                }
                            }
                            if (!empty($this->attr['to'])) {
                                if ($paymentPeriod->payment_date > $this->attr['to']) {
                                    continue;
                                }
                            }
                            $month = date('m', strtotime($paymentPeriod->payment_date));
                            $year = date('Y', strtotime($paymentPeriod->payment_date));
                            $dateColumn = Str::ucfirst(getMonth($month) . ' ' . $year);
                            $isIncome = $paymentPeriod->is_income ? 'income' : 'expense';
                            if (isset($plan[$isIncome][$dateColumn])) {
                                $plan[$isIncome][$dateColumn] += $paymentPeriod->sum;
                            } else {
                                $plan[$isIncome][$dateColumn] = $paymentPeriod->sum;
                            }
                            if (!blank($paymentPeriod->payments)) {
                                foreach ($paymentPeriod->payments as $payment) {
                                    if (isset($fact[$isIncome][$dateColumn])) {
                                        $fact[$isIncome][$dateColumn] += $payment->amount * $payment->currency_value;
                                    } else {
                                        $fact[$isIncome][$dateColumn] = $payment->amount * $payment->currency_value;
                                    }
                                    $currencyPayments[] = $payment->currency_value;
                                }
                            } else {
                                $fact[$isIncome][$dateColumn] = 0;
                            }
                        }
                    }
                } else {
                    foreach ($estimate->payment_periods as $paymentPeriod) {
                        if (!empty($this->attr['from'])) {
                            if ($paymentPeriod->payment_date < $this->attr['from']) {
                                continue;
                            }
                        }
                        if (!empty($this->attr['to'])) {
                            if ($paymentPeriod->payment_date > $this->attr['to']) {
                                continue;
                            }
                        }
                        $month = date('m', strtotime($paymentPeriod->payment_date));
                        $year = date('Y', strtotime($paymentPeriod->payment_date));
                        $dateColumn = Str::ucfirst(getMonth($month) . ' ' . $year);
                        $isIncome = $paymentPeriod->is_income ? 'income' : 'expense';
                        if (isset($plan[$isIncome][$dateColumn])) {
                            $plan[$isIncome][$dateColumn] += $paymentPeriod->sum;
                        } else {
                            $plan[$isIncome][$dateColumn] = $paymentPeriod->sum;
                        }
                        if (!blank($paymentPeriod->payments)) {
                            foreach ($paymentPeriod->payments as $payment) {
                                if (isset($fact[$isIncome][$dateColumn])) {
                                    $fact[$isIncome][$dateColumn] += $payment->amount * $payment->currency_value;
                                } else {
                                    $fact[$isIncome][$dateColumn] = $payment->amount * $payment->currency_value;
                                }
                                $currencyPayments[] = $payment->currency_value;
                            }
                        } else {
                            $fact[$isIncome][$dateColumn] = 0;
                        }
                    }
                }
                $plan = $this->setZero($plan);
                $fact = $this->setZero($fact);

                $currencyChanges = [];
                foreach ($estimate->positions as $position) {
                    $currencyChanges[] = [
                        $position->currency_value => $currencyPayments,
                    ];
                }

                if (!empty($plan)) {
                    foreach (['income', 'expense'] as $isIncome) {
                        foreach ($plan[$isIncome] as $dateColumn => $planIncome) {
                            $deviation[$isIncome][$dateColumn] = round($fact[$isIncome][$dateColumn] - $planIncome, 2);

                            if (isset($sum[$isIncome][$dateColumn]['plan'])) {
                                $sum[$isIncome][$dateColumn]['plan'] += $planIncome;
                            } else {
                                $sum[$isIncome][$dateColumn]['plan'] = $planIncome;
                            }
                            if (isset($sum[$isIncome][$dateColumn]['fact'])) {
                                $sum[$isIncome][$dateColumn]['fact'] += $fact[$isIncome][$dateColumn];
                            } else {
                                $sum[$isIncome][$dateColumn]['fact'] = $fact[$isIncome][$dateColumn];
                            }
                            if (isset($sum[$isIncome][$dateColumn]['deviation'])) {
                                $sum[$isIncome][$dateColumn]['deviation'] += $deviation[$isIncome][$dateColumn];
                            } else {
                                $sum[$isIncome][$dateColumn]['deviation'] = $deviation[$isIncome][$dateColumn];
                            }
                        }
                    }
                    foreach (['income', 'expense'] as $isIncome) {
                        foreach ($plan[$isIncome] as $dateColumn => $planIncome) {
                            $balance[$isIncome][$dateColumn] = ['plan' => round($planIncome, 2)];
                            if (isset($fact[$isIncome][$dateColumn])) {
                                $balance[$isIncome][$dateColumn]['fact'] = round($fact[$isIncome][$dateColumn], 2);
                                $balance[$isIncome][$dateColumn]['deviation'] = round($deviation[$isIncome][$dateColumn], 2);
                                $balance[$isIncome][$dateColumn]['deviation_currency'] = $currencyChanges;
                            }
                        }
                    }
                }


                foreach (['income', 'expense'] as $isIncome) {
                    if (isset($balance[$isIncome])) {
                        uksort($balance[$isIncome], function ($a, $b) {
                            $a = explode(' ', $a);
                            $b = explode(' ', $b);
                            if ($a[1] === $b[1]) {
                                $a[0] = getMonth($a[0], true);
                                $b[0] = getMonth($b[0], true);
                                return (int)$a[0] > (int)$b[0];
                            }
                            return (int)$a[1] > (int)$b[1];
                        });

                        uksort($sum[$isIncome], function ($a, $b) {
                            $a = explode(' ', $a);
                            $b = explode(' ', $b);
                            if ($a[1] === $b[1]) {
                                $a[0] = getMonth($a[0], true);
                                $b[0] = getMonth($b[0], true);
                                return (int)$a[0] > (int)$b[0];
                            }
                            return (int)$a[1] > (int)$b[1];
                        });
                    }
                }
                foreach (['income', 'expense'] as $isIncome) {
                    if (isset($balance[$isIncome])) {
                        $data[$isIncome]['row'][$order->counterparty->id . '-' . Str::random(10)] = [
                            'counterparty' => $order->counterparty->name,
                            'agreement' => !blank($order->agreement) ? $order->agreement->name : null,
                            'order_number' => $order->number ?? '',
                            'balance' => $balance[$isIncome],
                        ];
                    }
                }
            }
        }
        $data['income']['sum'] = $sum['income'];
        $data['expense']['sum'] = $sum['expense'];
        return $data;
    }

    private function setZero(array $data): array
    {
        if (isset($data['income'])) {
            foreach ($data['income'] as $dateColumn => $planIncome) {
                if (!isset($data['expense'][$dateColumn])) {
                    $data['expense'][$dateColumn] = 0;
                }
            }
        }
        if (isset($data['expense'])) {
            foreach ($data['expense'] as $dateColumn => $planIncome) {
                if (!isset($data['income'][$dateColumn])) {
                    $data['income'][$dateColumn] = 0;
                }
            }
        }
        return $data;
    }
}
