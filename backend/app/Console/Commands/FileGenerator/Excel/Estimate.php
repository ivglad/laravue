<?php

namespace App\Console\Commands\FileGenerator\Excel;

use App\Contracts\Commands\HasGenerator;
use App\Enums\CurrencyCode;
use App\Enums\PositionType;
use App\Traits\Commands\GeneralPath;
use Illuminate\Console\Command;
use App\Models\Order\Estimate as EstimateModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Estimate extends Command implements HasGenerator
{
    use GeneralPath;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:excel-estimate {--model_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an Excel file of an estimate for an order';

    public array $columnTitles = [
        'B' => "№\nп/п",
        'C' => 'Наименование изделия',
        'D' => 'Контрагент',
        'E' => 'Документ - основание',
        'F' => "Кол-во,\nшт",
        'G' => 'Масса, тн/шт',
        'H' => 'Цена, {{currency}}/шт',
        'I' => "Сумма,\nруб. без НДС",
        'J' => "Сумма,\nруб. с НДС (20%)",
        'K' => 'Период оплаты',
    ];
    public array $currencies = [
        CurrencyCode::RUB->value => 'руб.',
        CurrencyCode::CNY->value => 'юаней',
        CurrencyCode::EUR->value => 'евро',
        CurrencyCode::USD->value => 'дол.',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->option('model_id');
        $estimates = EstimateModel::with([
            'order',
            'order.user',
            'counterparty_to',
            'counterparty_from',
            'positions',
            'positions.position',
            'positions.payment_periods',
            'payment_periods',
        ])->where('order_id', $orderId)
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        if (!blank($estimates)) {
            $this->info($this->generateFile($estimates));
        }
        $this->info('');
    }

    public function generateFile($data): string
    {
        $order = $data[0]['order'];
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(1.57);
        $sheet->getColumnDimension('B')->setWidth(4.29);
        $sheet->getColumnDimension('C')->setWidth(41.43);
        $sheet->getColumnDimension('D')->setWidth(18.57);
        $sheet->getColumnDimension('E')->setWidth(26.86);
        $sheet->getColumnDimension('F')->setWidth(7.57);
        $sheet->getColumnDimension('G')->setWidth(12.57);
        $sheet->getColumnDimension('H')->setWidth(17.71);
        $sheet->getColumnDimension('I')->setWidth(18.86);
        $sheet->getColumnDimension('J')->setWidth(24.86);
        $sheet->getColumnDimension('K')->setWidth(24.57);

        $sheet->getRowDimension('1')->setRowHeight(25.5);
        $sheet->getRowDimension('2')->setRowHeight(26);
        $sheet->getRowDimension('3')->setRowHeight(22.5);
        $sheet->getRowDimension('4')->setRowHeight(25);
        $sheet->getRowDimension('5')->setRowHeight(21);

        $sheet->mergeCells('B1:J1');
        $sheet->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('B1', 'СМЕТА ДОХОДОВ И РАСХОДОВ № ' . $order['number']);
        $createdDate = date('d.m.Y');
        $sheet->getStyle('K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('K1', $createdDate);
        $estimateChains = [];
        $dataTmp = $data;
        $dataTmp = array_reverse($dataTmp);
        foreach ($dataTmp as $k => $item) {
            $estimateChains[] = $item['counterparty_from']['name'];
            if (array_key_last($data) === $k) {
                $estimateChains[] = $item['counterparty_to']['name'];
            }
        }
        $estimateChains = implode(' → ', $estimateChains);
        $sheet->mergeCells('E2:K2');
        $sheet->getStyle('E2')->getFont()->setBold(true);
        $sheet->getStyle('E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('E2', $estimateChains);

        $sheet->mergeCells('C3:D4');
        $sheet->mergeCells('H3:J3');
        $sheet->getStyle('H3:K3')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('E6B8B7');
        $sheet->getStyle('H3:K5')
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000000'));
        $sheet->getStyle('H3:K5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('H3', 'ВАЛОВАЯ ПРИБЫЛЬ');
        $sheet->mergeCells('H4:J4');
        $sheet->setCellValue('H4', 'РАСХОДЫ');
        $sheet->mergeCells('H5:J5');
        $sheet->setCellValue('H5', 'РЕНТАБЕЛЬНОСТЬ ПРОДУКЦИИ');

        $totalGrossProfit = 0;
        $sumSumExpenseTotal = 0;
        $expenseProductIds = [];
        $row = 7;
        $font11 = [];

        $dataTotalExpense = EstimateModel::with([
            'positions',
            'positions.position',
        ])->where('order_id', $order['id'])
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        foreach ($dataTotalExpense as $item) {
            if ($this->hasExpense($item['positions'])) {
                foreach ($item['positions'] as $positionEstimate) {
                    if (!$positionEstimate['is_income']) {
                        if ($positionEstimate['position']['type'] === PositionType::Product->value &&
                            !in_array($positionEstimate['position']['id'], $expenseProductIds)) {
                            $expenseProductIds[] = $positionEstimate['position']['id'];
                            $sumSumExpenseTotal += $positionEstimate['sum'];
                        } elseif ($positionEstimate['position']['type'] === PositionType::Service->value) {
                            $sumSumExpenseTotal += $positionEstimate['sum'];
                        }
                    }
                }
            }
        }
        foreach ($data as $k => $item) {
            $point = 1;
            $sheet->mergeCells('B' . $row . ':C' . $row);
            $sheet->getStyle('B' . $row . ':C' . $row)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('DAEEF3');
            $sheet->getStyle('B' . $row . ':C' . $row)->getFont()->setBold(true);
            $sheet->setCellValue('B' . $row, '1.' . $k + 1 . '. ' . $item['counterparty_from']['name'] . ' → ' . $item['counterparty_to']['name']);
            $row++;

            if ($this->hasIncome($item['positions'])) {
                $sheet->getStyle('B' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $row)->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);
                $pointString = $point . '. ';
                $sheet->setCellValue('B' . $row, $pointString);
                $point++;
                $sheet->getStyle('C' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->setCellValue('C' . $row, 'ПРОДАЖА (ДОХОДЫ) ' . $item['counterparty_from']['name']);
                $row++;

                $firstTableRow = $row;
                $font11[] = 'B' . $row . ':K' . $row;
                $sheet->getRowDimension($row)->setRowHeight(-1);
                foreach ($this->columnTitles as $column => $title) {
                    if ($column === 'H') {
                        $title = str_replace('{{currency}}', $this->currencies[$item['currency_code']], $title);
                    }
                    $sheet->setCellValue($column . $row, $title);
                }
                $row++;
                $sumWeight = 0;
                $sumSum = 0;
                $sumSumTax = 0;
                $k = 1;
                foreach ($item['positions'] as $positionEstimate) {
                    if ($positionEstimate['is_income']) {
                        //$sheet->getRowDimension($row)->setRowHeight(-1);
                        $sheet->getStyle('B' . $row)
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->setCellValue('B' . $row, $k);
                        $sheet = $this->setTableRow($sheet, $row, $positionEstimate);
                        $sumWeight += $positionEstimate['weight'];
                        $sumSum += $positionEstimate['sum'];
                        $sumSumTax += $positionEstimate['sum_tax'];
                        $row++;
                        $k++;
                    }
                }
                if (isset($item['payment_periods']) && count($item['payment_periods']) > 0) {
                    $sheet = $this->setGeneralPaymentPeriodsColumn($sheet, $firstTableRow + 1, $row - 1, $item['payment_periods']);
                }
                $sheet->mergeCells('B' . $row . ':C' . $row);
                $sheet->getStyle('B' . $row . ':C' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $row . ':C' . $row)->getFont()->setBold(true);
                $sheet->setCellValue('B' . $row, 'ИТОГО');
                $sheet->getStyle('G' . $row)->getFont()->setBold(true);
                if ($sumWeight !== 0) {
                    $sheet->setCellValue('G' . $row, round($sumWeight, 2));
                }
                $sheet->getStyle('I' . $row)->getFont()->setBold(true);
                if ($sumSum !== 0) {
                    $sheet->setCellValue('I' . $row, round($sumSum, 2));
                }
                $sheet->getStyle('J' . $row)->getFont()->setBold(true);
                if ($sumSumTax !== 0) {
                    $sheet->setCellValue('J' . $row, round($sumSumTax, 2));
                }

                $sheet->getStyle('B' . $firstTableRow . ':K' . $row)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color('000000'));
                $sheet->getStyle('B' . $firstTableRow . ':K' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C' . $firstTableRow . ':C' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $row++;
                $sumSumIncome = $sumSum;
            }

            if ($this->hasExpense($item['positions'])) {
                $sheet->getStyle('B' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $row)->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);
                $pointString = $point . '. ';
                $sheet->setCellValue('B' . $row, $pointString);
                $point++;
                $sheet->getStyle('C' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->setCellValue('C' . $row, 'ПОКУПКА (РАСХОДЫ) ' . $item['counterparty_from']['name']);
                $row++;

                $firstTableRow = $row;
                $font11[] = 'B' . $row . ':K' . $row;
                $sheet->getRowDimension($row)->setRowHeight(-1);
                foreach ($this->columnTitles as $column => $title) {
                    if ($column === 'H') {
                        $title = str_replace('{{currency}}', $this->currencies[$item['currency_code']], $title);
                    }
                    $sheet->setCellValue($column . $row, $title);
                }
                $row++;
                $sumWeight = 0;
                $sumSum = 0;
                $sumSumTax = 0;
                $k = 1;
                foreach ($item['positions'] as $positionEstimate) {
                    if (!$positionEstimate['is_income']) {
                        //$sheet->getRowDimension($row)->setRowHeight(-1);
                        $sheet->getStyle('B' . $row)
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->setCellValue('B' . $row, $k);
                        $sheet = $this->setTableRow($sheet, $row, $positionEstimate);
                        $sumWeight += $positionEstimate['weight'];
                        $sumSum += $positionEstimate['sum'];
                        $sumSumTax += $positionEstimate['sum_tax'];
                        $row++;
                        $k++;
                    }
                }
                if (isset($item['payment_periods']) && count($item['payment_periods']) > 0) {
                    $sheet = $this->setGeneralPaymentPeriodsColumn($sheet, $firstTableRow + 1, $row - 1, $item['payment_periods']);
                }

                $sheet->mergeCells('B' . $row . ':C' . $row);
                $sheet->getStyle('B' . $row . ':C' . $row)->getFont()->setBold(true);
                $sheet->getStyle('B' . $row . ':C' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('B' . $row, 'ИТОГО');
                $sheet->getStyle('G' . $row)->getFont()->setBold(true);
                if ($sumWeight !== 0) {
                    $sheet->setCellValue('G' . $row, round($sumWeight, 2));
                }
                $sheet->getStyle('I' . $row)->getFont()->setBold(true);
                if ($sumSum !== 0) {
                    $sheet->setCellValue('I' . $row, round($sumSum, 2));
                }
                $sheet->getStyle('J' . $row)->getFont()->setBold(true);
                if ($sumSumTax !== 0) {
                    $sheet->setCellValue('J' . $row, round($sumSumTax, 2));
                }

                $sheet->getStyle('B' . $firstTableRow . ':K' . $row)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color('000000'));
                $sheet->getStyle('B' . $firstTableRow . ':K' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C' . $firstTableRow . ':C' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $row++;
                $sumSumExpense = $sumSum;
            }

            if ($this->hasIncome($item['positions']) && $this->hasExpense($item['positions'])) {
                $sheet->getStyle('B' . $row . ':I' . $row + 1)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('E6B8B7');
                $sheet->getStyle('B' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $row)->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);
                $pointString = $point . '. ';
                $sheet->setCellValue('B' . $row, $pointString);
                $point++;
                $sheet->setCellValue('C' . $row, 'ВАЛОВАЯ ПРИБЫЛЬ ' . $item['counterparty_from']['name']);
                $row++;

                $grossProfit = $sumSumIncome - $sumSumExpense;
                $sheet->setCellValue('C' . $row, 'Итого (доходы) - Итого (расходы)');
                $sheet->getStyle('I' . $row)->getFont()->setBold(true);
                $sheet->getStyle('I' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('I' . $row, round($grossProfit, 2));
                $sheet->getStyle('I' . $row)
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color('000000'));
                $row++;

                $sheet->getStyle('B' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $row)->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_TEXT);
                $pointString = $point . '. ';
                $sheet->setCellValue('B' . $row, $pointString);
                $sheet->setCellValue('C' . $row, 'РЕНТАБЕЛЬНОСТЬ ПРОДУКЦИИ ' . $item['counterparty_to']['name']);
                $row++;

                $profitability = round(($grossProfit / $sumSumExpense) * 100, 2);
                $sheet->setCellValue('B' . $row, '');
                $sheet->setCellValue('C' . $row, '(Валовая прибыль / Себестоимость) * 100 %');
                $sheet->getStyle('I' . $row)->getFont()->setBold(true);
                $sheet->getStyle('I' . $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('I' . $row, $profitability);
                $sheet->getStyle('I' . $row)
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color('000000'));
                $row++;

                $totalGrossProfit += $grossProfit;
                // $totalExpense += $sumSumExpenseTotal;
                // $totalProfitability += $profitability;
            }
            $row++;
        }

        if ($sumSumExpenseTotal > 0) {
            $totalProfitability = round(($totalGrossProfit / $sumSumExpenseTotal) * 100, 2);
        } else {
            $totalProfitability = '';
            $sumSumExpenseTotal = '';
        }
        $sheet->setCellValue('K3', $totalGrossProfit);
        $sheet->setCellValue('K4', $sumSumExpenseTotal);
        $sheet->setCellValue('K5', $totalProfitability);

        $sheet->getRowDimension($row - 1)->setRowHeight(9.75);
        $sheet->getRowDimension($row)->setRowHeight(21);
        $sheet->getStyle('B' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('B' . $row, 'Период отгрузки: ');

        $sheet->getStyle('H' . $row)->getFont()->setBold(true);
        // $sheet->setCellValue('H' . $row, 'Примечание: Курс на ' . $createdDate . ' - ' . 'руб/');
        $row++;
        $sheet->getRowDimension($row)->setRowHeight(15.75);
        $row++;

        $sheet->getRowDimension($row)->setRowHeight(21);
        $sheet->setCellValue('C' . $row, 'Исполнитель');
        $sheet->setCellValue('E' . $row, getShortFullName($order['user']));
        $row++;

        $sheet->getRowDimension($row)->setRowHeight(21);
        $sheet->setCellValue('C' . $row, 'Экономист');
        $sheet->setCellValue('E' . $row, 'Мележникова Н. М.');
        $row++;

        $sheet->getRowDimension($row)->setRowHeight(21);
        $sheet->setCellValue('C' . $row, 'Согласовано');
        $sheet->setCellValue('E' . $row, 'Куц В. В.');

        $sheet->getStyle('A1:K' . $row)
            ->getFont()
            ->setName('Times New Roman')
            ->setSize(12);
        $sheet->getStyle('A1:K' . $row)
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('K1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('E2')->getFont()->setBold(true)->setSize(13);
        foreach ($font11 as $coordinate) {
            $sheet->getStyle($coordinate)->getFont()->setSize(11);
            $sheet->getStyle($coordinate)->getAlignment()->setWrapText(true);
            $sheet->getStyle($coordinate)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $sheet->getStyle('K')->getAlignment()->setWrapText(true);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'estimate_' . date('H.i.s_d.m.Y') . '_' . Str::random(4) . '.xlsx';
        $filePath = $this->dirPath . $fileName;
        $writer->save($filePath);
        return Storage::url($this->basePath . $fileName);
    }

    public function hasIncome(array $data): bool
    {
        foreach ($data as $item) {
            if ($item['is_income']) {
                return true;
            }
        }
        return false;
    }

    public function hasExpense(array $data): bool
    {
        foreach ($data as $item) {
            if (!$item['is_income']) {
                return true;
            }
        }
        return false;
    }

    public function setTableRow($sheet, $row, $positionEstimate)
    {
        $sheet->setCellValue('C' . $row, $positionEstimate['position']['name']);
        $sheet->getStyle('C' . $row)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('D' . $row, '');
        $sheet->getStyle('D' . $row)->getAlignment()->setWrapText(true);
        if (!empty($positionEstimate['contract'])) {
            $sheet->setCellValue('E' . $row, $positionEstimate['contract']);
            $sheet->getStyle('E' . $row)->getAlignment()->setWrapText(true);
        }
        if (!empty($positionEstimate['quantity'])) {
            $sheet->setCellValue('F' . $row, round($positionEstimate['quantity'], 2));
            $sheet->getStyle('F' . $row)->getAlignment()->setWrapText(true);
        }
        if (!empty($positionEstimate['weight'])) {
            $sheet->setCellValue('G' . $row, round($positionEstimate['weight'], 3));
            $sheet->getStyle('G' . $row)->getAlignment()->setWrapText(true);
        }
        if (!empty($positionEstimate['price'])) {
            $sheet->setCellValue('H' . $row, round($positionEstimate['price'], 2));
            $sheet->getStyle('H' . $row)->getAlignment()->setWrapText(true);
        }
        if (!empty($positionEstimate['sum'])) {
            $sheet->setCellValue('I' . $row, round($positionEstimate['sum'], 2));
            $sheet->getStyle('I' . $row)->getAlignment()->setWrapText(true);
        }
        if (!empty($positionEstimate['sum_tax'])) {
            $sheet->setCellValue('J' . $row, round($positionEstimate['sum_tax'], 2));
            $sheet->getStyle('J' . $row)->getAlignment()->setWrapText(true);
        }
        $paymentPeriodsPrepared = [];
        foreach ($positionEstimate['payment_periods'] as $paymentPeriod) {
            $monthIndex = date('m', strtotime($paymentPeriod['payment_date']));
            $month = getMonth($monthIndex);
            $year = date( 'Y', strtotime($paymentPeriod['payment_date']));
            $paymentPeriodsPrepared[] = $paymentPeriod['percent'] . '% - ' . $month . ' ' . $year;
        }
        $sheet->getStyle('K' . $row)->getAlignment()->setWrapText(true);
        $paymentPeriodsPrepared = implode("\n", $paymentPeriodsPrepared);
        $sheet->setCellValue('K' . $row, $paymentPeriodsPrepared);
        return $sheet;
    }

    public function setGeneralPaymentPeriodsColumn($sheet, $startRow, $finishRow, $paymentPeriods)
    {
        $paymentPeriodsPrepared = [];
        foreach ($paymentPeriods as $paymentPeriod) {
            $monthIndex = date('m', strtotime($paymentPeriod['payment_date']));
            $month = getMonth($monthIndex);
            $year = date( 'Y', strtotime($paymentPeriod['payment_date']));
            $paymentPeriodsPrepared[] = $paymentPeriod['percent'] . '% - ' . $month . ' ' . $year;
        }
        $paymentPeriodsPrepared = implode("\n", $paymentPeriodsPrepared);
        $sheet->mergeCells('K' . $startRow . ':K' . $finishRow);
        $sheet->getStyle('K' . $startRow)->getAlignment()->setWrapText(true);
        $sheet->setCellValue('K' . $startRow, $paymentPeriodsPrepared);
        return $sheet;
    }
}
