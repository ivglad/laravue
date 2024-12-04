<?php

declare(strict_types=1);

namespace App\Traits\Commands;

use Illuminate\Support\Facades\Storage;

trait GeneralPath
{
    protected string $dirPath;

    protected string $basePath = 'generated/';

    public function __construct()
    {
        $this->dirPath = Storage::disk('public')->path($this->basePath);
        if (!is_dir($this->dirPath)) {
            mkdir($this->dirPath, 0777, true);
        }
        parent::__construct();
    }
}
