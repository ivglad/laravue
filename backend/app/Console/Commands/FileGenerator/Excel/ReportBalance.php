<?php

namespace App\Console\Commands\FileGenerator\Excel;

use App\Contracts\Commands\HasGenerator;
use App\Enums\ReportType;
use App\Models\Agreement\Agreement;
use App\Models\Handbook\Counterparty;
use App\Traits\Commands\GeneralPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportBalance extends Command implements HasGenerator
{
    use GeneralPath;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:excel-report-balance {--counterparty_ids=} {--agreement_ids=} {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an Excel file of an report balance';

    private const POSITIVE_COLOR = '087D0D';
    private const NEGATIVE_COLOR = 'E22739';
    private const BORDER_COLOR = 'D5DAE1';
    private const BACKGROUND_STRONG_COLOR = 'E7EAF4';
    private const BACKGROUND_MEDIUM_COLOR = 'F0F1F7';
    private const BACKGROUND_LIGHT_COLOR = 'F8F9FD';
    private const FONT_COLOR = '322C3F';

    private const TABLE_DATA_START_ROW = 3;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $attr = [];
        if (!blank($this->option('counterparty_ids'))) {
            $attr['counterparty_ids'] = explode(',', $this->option('counterparty_ids'));
        }
        if (!blank($this->option('agreement_ids'))) {
            $attr['agreement_ids'] = explode(',', $this->option('agreement_ids'));
        }
        if (!blank($this->option('from'))) {
            $attr['from'] = $this->option('from');
        }
        if (!blank($this->option('to'))) {
            $attr['to'] = $this->option('to');
        }

        $result = (new (ReportType::from(ReportType::Balance->value)->name())($attr))->run();

        if (!isset($result['income']['sum']) && !isset($result['expense']['sum'])) {
            $this->info('');
        }
        else {
            $this->info($this->generateFile($result));
        }
    }

    public function generateFile($data): string
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setColor(new Color(self::FONT_COLOR));
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);

        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $createdDate = date('d.m.Y');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
        $sheet->setCellValue('A1', 'Отчет от ' . $createdDate);

        $row = self::TABLE_DATA_START_ROW;
        if (!blank($this->option('counterparty_ids'))) {
            $counterpartyIds = explode(',', $this->option('counterparty_ids'));
            $counterparties = Counterparty::withTrashed()->whereIn('id', $counterpartyIds)->get();
            if (!blank($counterparties)) {
                $names = '';
                $flagFirst = true;
                foreach ($counterparties as $counterparty) {
                    if ($flagFirst) {
                        $names .= $counterparty->name;
                        $flagFirst = false;
                    }
                    else {
                        $names .= ", \n" . $counterparty->name;
                    }
                }
                $sheet->setCellValue('A' . $row, 'Контрагент(ы):');
                $sheet->getStyle('B' . $row)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('B' . $row, $names);
                $row++;
            }
        }
        if (!blank($this->option('agreement_ids'))) {
            $agreementIds = explode(',', $this->option('agreement_ids'));
            $agreements = Agreement::withTrashed()->whereIn('id', $agreementIds)->get();
            if (!blank($agreements)) {
                $names = '';
                $flagFirst = true;
                foreach ($agreements as $agreement) {
                    if ($flagFirst) {
                        $names .= $agreement->name;
                        $flagFirst = false;
                    }
                    $names .= ", \n" . $agreement->name;
                }
                $sheet->setCellValue('A' . $row, 'Договор(ы):');
                $sheet->getStyle('B' . $row)->getAlignment()->setWrapText(true);
                $sheet->setCellValue('B' . $row, $names);
                $row++;
            }
        }
        if (!blank($this->option('from')) && !blank($this->option('to'))) {
            $sheet->setCellValue('A' . $row, 'Интервал (от/до):');
            $sheet->setCellValue('B' . $row, date('d.m.Y', strtotime($this->option('from'))) . ' - ' . date('d.m.Y', strtotime($this->option('to'))));
            $row++;
        } elseif (!blank($this->option('from'))) {
            $sheet->setCellValue('A' . $row, 'Интервал (от):');
            $sheet->setCellValue('B' . $row, date('d.m.Y', strtotime($this->option('from'))));
            $row++;
        } elseif (!blank($this->option('to'))) {
            $sheet->setCellValue('A' . $row, 'Интервал (до):');
            $sheet->setCellValue('B' . $row, date('d.m.Y', strtotime($this->option('to'))));
            $row++;
        }
        if ($row !== self::TABLE_DATA_START_ROW) {
            $this->setBorder($sheet, 'A3:B' . $row - 1);
        }
        $row++;

        $startRow = $row;
        $monthYears = [];
        $sheet->mergeCells('A' . $row . ':C' . $row);
        $this->setBorder($sheet, 'A' . $row . ':C' . $row);
        $this->setBackgroundColor($sheet, 'A' . $row . ':C' . $row, self::BACKGROUND_STRONG_COLOR);
        $this->setBorder($sheet, 'A' . $row + 1);
        $this->setBackgroundColor($sheet, 'A' . $row + 1, self::BACKGROUND_STRONG_COLOR);
        $sheet->setCellValue('A' . $row + 1, 'Контрагент');
        $this->setBorder($sheet, 'B' . $row + 1);
        $this->setBackgroundColor($sheet, 'B' . $row + 1, self::BACKGROUND_STRONG_COLOR);
        $sheet->setCellValue('B' . $row + 1, 'Договор');
        $this->setBorder($sheet, 'C' . $row + 1);
        $this->setBackgroundColor($sheet, 'C' . $row + 1, self::BACKGROUND_STRONG_COLOR);
        $sheet->setCellValue('C' . $row + 1, 'Номер заказа');
        $incomeExist = false;
        if (isset($data['income']['row'])) {
            $incomeExist = true;
        }
        $expenseExist = false;
        if (isset($data['expense']['row'])) {
            $expenseExist = true;
        }
        if ($incomeExist || $expenseExist) {
            foreach ($data[$incomeExist ? 'income' : 'expense']['row'] as $item) {
                $dates = array_keys($item['balance']);
                $tmpNewDates = array_diff($dates, $monthYears);
                $monthYears = array_merge($monthYears, $tmpNewDates);
            }
            usort($monthYears, function ($a, $b) {
                $a = explode(' ', $a);
                $b = explode(' ', $b);
                if ($a[1] === $b[1]) {
                    $a[0] = getMonth($a[0], true);
                    $b[0] = getMonth($b[0], true);
                    return (int)$a[0] > (int)$b[0];
                }
                return (int)$a[1] > (int)$b[1];
            });
            $columnIndex = 3; // D
            $dateColumnIndex = [];
            foreach ($monthYears as $date) {
                $dateColumnIndex[$date] = $columnIndex;
                $sheet->getColumnDimension(num2alpha($columnIndex))->setAutoSize(true);
                $sheet->mergeCells(num2alpha($columnIndex) . $row . ':' . num2alpha($columnIndex + 2) . $row);
                $sheet->setCellValue(num2alpha($columnIndex) . $row, $date);
                $sheet->setCellValue(num2alpha($columnIndex) . $row + 1, 'План');
                $sheet->setCellValue(num2alpha($columnIndex + 1) . $row + 1, 'Факт');
                $sheet->setCellValue(num2alpha($columnIndex + 2) . $row + 1, 'Отклонение');

                $this->setBorder($sheet, num2alpha($columnIndex) . $row . ':' . num2alpha($columnIndex + 2) . $row);
                $this->setBorder($sheet, num2alpha($columnIndex) . $row + 1);
                $this->setBorder($sheet, num2alpha($columnIndex + 1) . $row + 1);
                $this->setBorder($sheet, num2alpha($columnIndex + 2) . $row + 1);

                $sheet->getColumnDimension(num2alpha($columnIndex))->setAutoSize(true);
                $sheet->getColumnDimension(num2alpha($columnIndex + 1))->setAutoSize(true);
                $sheet->getColumnDimension(num2alpha($columnIndex + 2))->setAutoSize(true);

                $columnIndex += 3;
            }

            $row += 2;
            $columnIndexMax = $columnIndex - 1;

            foreach ($dateColumnIndex as $date => $columnIndex) {
                $sumPlanIncome[$date] = 0;
                $sumFactIncome[$date] = 0;
                $sumDeviationIncome[$date] = 0;
                $sumPlanExpense[$date] = 0;
                $sumFactExpense[$date] = 0;
                $sumDeviationExpense[$date] = 0;
            }

            if ($incomeExist) {
                $sheet->mergeCells('A' . $row . ':' . num2alpha($columnIndexMax) . $row);
                $sheet->getStyle('A' . $row)->getFont()->setSize(16);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, 'Входящие платежи');
                $row++;

                [$row, $sumPlanIncome, $sumFactIncome, $sumDeviationIncome] = $this->setTableData($data, 'income', $sheet, $dateColumnIndex, $row, $sumPlanIncome, $sumFactIncome, $sumDeviationIncome);
            }
            if ($expenseExist) {
                $sheet->mergeCells('A' . $row . ':' . num2alpha($columnIndexMax) . $row);
                $sheet->getStyle('A' . $row)->getFont()->setSize(16);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, 'Исходящие платежи');
                $row++;

                [$row, $sumPlanExpense, $sumFactExpense, $sumDeviationExpense] = $this->setTableData($data, 'expense', $sheet, $dateColumnIndex, $row, $sumPlanExpense, $sumFactExpense, $sumDeviationExpense);
            }
            $sheet->mergeCells('A' . $row . ':C' . $row);
            $this->setBorder($sheet, 'A' . $row . ':C' . $row);
            $sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);
            $this->setBackgroundColor($sheet, 'A' . $row . ':C' . $row, self::BACKGROUND_STRONG_COLOR);
            $sheet->setCellValue('A' . $row, 'Дефицит/профицит');

            foreach ($dateColumnIndex as $date => $dColumnIndex) {
                $resultPlan = $sumPlanIncome[$date] - $sumPlanExpense[$date];
                $resultFact = $sumFactIncome[$date] - $sumFactExpense[$date];
                $resultDeviation = $sumDeviationIncome[$date] - $sumDeviationExpense[$date];

                $sheet->setCellValue(num2alpha($dColumnIndex) . $row, $resultPlan);
                $sheet->setCellValue(num2alpha($dColumnIndex + 1) . $row, $resultFact);
                $sheet->setCellValue(num2alpha($dColumnIndex + 2) . $row, $resultDeviation);

                $this->setNumericFontColor($sheet, num2alpha($dColumnIndex) . $row, $resultPlan);
                $this->setNumericFontColor($sheet, num2alpha($dColumnIndex + 1) . $row, $resultFact);
                $this->setNumericFontColor($sheet, num2alpha($dColumnIndex + 2) . $row, $resultDeviation);

                $sheet->getStyle(num2alpha($dColumnIndex) . $row)->getFont()->setBold(true);
                $sheet->getStyle(num2alpha($dColumnIndex + 1) . $row)->getFont()->setBold(true);
                $sheet->getStyle(num2alpha($dColumnIndex + 2) . $row)->getFont()->setBold(true);

                $this->setBorder($sheet, num2alpha($dColumnIndex) . $row);
                $this->setBorder($sheet, num2alpha($dColumnIndex + 1) . $row);
                $this->setBorder($sheet, num2alpha($dColumnIndex + 2) . $row);
            }

            $i = 0;
            foreach ($dateColumnIndex as $dColumnIndex) {
                foreach (range($startRow, $row) as $rowIndex) {
                    $this->setBackgroundColorThreeColumn($i, $sheet, $dColumnIndex, $rowIndex);
                }
                $i++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'report_balance_' . date('H.i.s_d.m.Y') . '_' . Str::random(4) . '.xlsx';
        $filePath = $this->dirPath . $fileName;
        $writer->save($filePath);
        return Storage::url($this->basePath . $fileName);
    }

    private function setTableData(array $data, string $incomeExpense, Worksheet $sheet, array $dateColumnIndex, int $row, array $sumPlan, array $sumFact, array $sumDeviation): array
    {
        foreach ($data[$incomeExpense]['row'] as $item) {
            $this->setBorder($sheet, 'A' . $row);
            $this->setBackgroundColor($sheet, 'A' . $row , self::BACKGROUND_STRONG_COLOR);
            $sheet->setCellValue('A' . $row, $item['counterparty']);
            $this->setBorder($sheet, 'B' . $row);
            $this->setBackgroundColor($sheet, 'B' . $row , self::BACKGROUND_STRONG_COLOR);
            $sheet->setCellValue('B' . $row, $item['agreement']);
            $this->setBackgroundColor($sheet, 'C' . $row, self::BACKGROUND_STRONG_COLOR);
            $sheet->getStyle('C' . $row)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_TEXT);
            $this->setBorder($sheet, 'C' . $row);

            $sheet->setCellValue('C' . $row, $item['order_number'] . ' ');

            foreach ($dateColumnIndex as $columnIndex) {
                $sheet->setCellValue(num2alpha($columnIndex) . $row, 0);
                $sheet->setCellValue(num2alpha($columnIndex + 1) . $row, 0);
                $sheet->setCellValue(num2alpha($columnIndex + 2) . $row, 0);
                $this->setBorder($sheet, num2alpha($columnIndex) . $row);
                $this->setBorder($sheet, num2alpha($columnIndex + 1) . $row);
                $this->setBorder($sheet, num2alpha($columnIndex + 2) . $row);
            }

            foreach ($item['balance'] as $date => $balance) {
                if (isset($dateColumnIndex[$date])) {
                    $sheet->setCellValue(num2alpha($dateColumnIndex[$date]) . $row, $balance['plan']);
                    $sheet->setCellValue(num2alpha($dateColumnIndex[$date] + 1) . $row, $balance['fact']);
                    $sheet->setCellValue(num2alpha($dateColumnIndex[$date] + 2) . $row, $balance['deviation']);

                    $this->setNumericFontColor($sheet, num2alpha($dateColumnIndex[$date]) . $row, $balance['plan']);
                    $this->setNumericFontColor($sheet, num2alpha($dateColumnIndex[$date] + 1) . $row, $balance['fact']);
                    $this->setNumericFontColor($sheet, num2alpha($dateColumnIndex[$date] + 2) . $row, $balance['deviation']);

                    $sumPlan[$date] += $balance['plan'];
                    $sumFact[$date] += $balance['fact'];
                    $sumDeviation[$date] += $balance['deviation'];
                } else {
                    $sheet->setCellValue(num2alpha($dateColumnIndex[$date]) . $row, 0);
                    $sheet->setCellValue(num2alpha($dateColumnIndex[$date] + 1) . $row, 0);
                    $sheet->setCellValue(num2alpha($dateColumnIndex[$date] + 2) . $row, 0);
                }

                $this->setBorder($sheet, num2alpha($dateColumnIndex[$date]) . $row);
                $this->setBorder($sheet, num2alpha($dateColumnIndex[$date] + 1) . $row);
                $this->setBorder($sheet, num2alpha($dateColumnIndex[$date] + 2) . $row);
            }
            $row++;
        }
        $sheet->mergeCells('A' . $row . ':C' . $row);
        $this->setBorder($sheet, 'A' . $row . ':C' . $row);
        $sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);
        $this->setBackgroundColor($sheet, 'A' . $row . ':C' . $row, self::BACKGROUND_STRONG_COLOR);
        $sheet->setCellValue('A' . $row, 'ИТОГО:');
        foreach ($dateColumnIndex as $date => $dColumnIndex) {
            $sheet->setCellValue(num2alpha($dColumnIndex) . $row, $sumPlan[$date]);
            $sheet->setCellValue(num2alpha($dColumnIndex + 1) . $row, $sumFact[$date]);
            $sheet->setCellValue(num2alpha($dColumnIndex + 2) . $row, $sumDeviation[$date]);

            $this->setNumericFontColor($sheet, num2alpha($dColumnIndex) . $row, $sumPlan[$date]);
            $this->setNumericFontColor($sheet, num2alpha($dColumnIndex + 1) . $row, $sumFact[$date]);
            $this->setNumericFontColor($sheet, num2alpha($dColumnIndex + 2) . $row, $sumDeviation[$date]);

            $sheet->getStyle(num2alpha($dColumnIndex) . $row)->getFont()->setBold(true);
            $sheet->getStyle(num2alpha($dColumnIndex + 1) . $row)->getFont()->setBold(true);
            $sheet->getStyle(num2alpha($dColumnIndex + 2) . $row)->getFont()->setBold(true);

            $this->setBorder($sheet, num2alpha($dColumnIndex) . $row);
            $this->setBorder($sheet, num2alpha($dColumnIndex + 1) . $row);
            $this->setBorder($sheet, num2alpha($dColumnIndex + 2) . $row);
        }
        $row++;
        return [$row, $sumPlan, $sumFact, $sumDeviation];
    }

    private function setBorder(Worksheet $sheet, string $coordinates): void
    {
        $sheet->getStyle($coordinates)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color(self::BORDER_COLOR));
    }

    private function setNumericFontColor(Worksheet $sheet, string $coordinates, float $value): void
    {
        if ($value < 0) {
            $sheet->getStyle($coordinates)
                ->getFont()
                ->setColor(new Color(self::NEGATIVE_COLOR));
        } elseif ($value > 0) {
            $sheet->getStyle($coordinates)
                ->getFont()
                ->setColor(new Color(self::POSITIVE_COLOR));
        } else {
            $sheet->getStyle($coordinates)
                ->getFont()
                ->setColor(new Color(self::FONT_COLOR));
        }
    }

    public function setBackgroundColor(Worksheet $sheet, string $coordinates, string $color): void
    {
        $sheet->getStyle($coordinates)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB($color);
    }

    /**
     * @param int $i
     * @param Worksheet $sheet
     * @param int $n
     * @param mixed $row
     * @return void
     */
    public function setBackgroundColorThreeColumn(int $i, Worksheet $sheet, int $n, string $row): void
    {
        if ($i % 2 === 1) {
            $color = self::BACKGROUND_LIGHT_COLOR;
        } else {
            $color = self::BACKGROUND_MEDIUM_COLOR;
        }

        $this->setBackgroundColor($sheet, num2alpha($n) . $row, $color);
        $this->setBackgroundColor($sheet, num2alpha($n + 1) . $row, $color);
        $this->setBackgroundColor($sheet, num2alpha($n + 2) . $row, $color);
    }
}
