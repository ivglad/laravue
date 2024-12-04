<?php

declare(strict_types=1);

namespace App\Enums;

use App\Console\Commands\FileGenerator\Excel\Estimate;
use App\Console\Commands\FileGenerator\Excel\ReportBalance;
use App\Contracts\Enums\HasData;
use App\Traits\Enums\Objects;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum GenerateType: string implements HasData
{
    use Names, Values, Options, Objects;

    case Estimate = 'estimate';
    case ReportBalance = 'report-balance';

    public function data(): array
    {
        return match ($this) {
            self::Estimate => [
                'xlsx' => Estimate::class,
                'options' => [
                    'model_id',
                ],
            ],
            self::ReportBalance => [
                'xlsx' => ReportBalance::class,
                'options' => [
                    'counterparty_ids',
                    'agreement_ids',
                    'from',
                    'to',
                ],
            ],
        };
    }

}
