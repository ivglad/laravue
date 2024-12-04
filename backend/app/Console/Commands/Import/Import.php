<?php

namespace App\Console\Commands\Import;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:1c {--all}';
    const COUNTERPARTY = 'counterparty';
    const AGREEMENT = 'agreement';
    const PRODUCT = 'product';
    const SERVICE = 'service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports json files';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $s = time();
        $options = $this->prepareOptions();
        $dirPath = storage_path('import');
        if (file_exists($dirPath) && is_dir($dirPath)) {
            $filePaths = scandir($dirPath);
            $filePaths = array_diff($filePaths, ['.', '..', '.gitignore']);
            $filePaths = array_map(fn ($item) => $dirPath . '/' . $item, $filePaths);
            $filePaths = array_filter($filePaths, fn ($item) => is_file($item));
            $filePaths = array_filter($filePaths, fn ($item) => pathinfo($item, PATHINFO_EXTENSION) === 'json');
            $filePaths = array_values($filePaths);
            usort($filePaths, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            foreach ($filePaths as $filePath) {
                $this->info('File: ' . date('Y-m-d H:i:s', filemtime($filePath)) . ' ' . $filePath);
            }
            $countFiles = count($filePaths);
            $importedFiles = 0;
            $errorFiles = 0;
            $this->info('Importing in progress...');
            $bar = $this->output->createProgressBar($countFiles);
            foreach ($filePaths as $filePath) {
                foreach ($options as $option) {
                    if (str_contains($filePath, $option)) {
                        $commandClass = __NAMESPACE__ . '\Import' . Str::ucfirst(Str::plural($option));
                        $commandObject = new $commandClass();
                        try {
                            Artisan::call($commandObject::class, ['--path' => $filePath]);
                            rename($filePath, $dirPath . '/backup/' . basename($filePath));
                        } catch (Exception $exception) {
                            $this->error('Error while importing ' . $option . ' model, file: ' . $filePath);
                            $this->error($exception->getMessage());
                            rename($filePath, $dirPath . '/failed/' . basename($filePath));
                            $importedFiles--;
                            $errorFiles++;
                        }
                    }
                }
                $importedFiles++;
                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
            $this->info('Files detected: ' . $countFiles);
            $this->info('Files imported: ' . $importedFiles);
            $this->info('Files with errors: ' . $errorFiles);
            Log::info('[IMPORT]: ' . $countFiles . ' files detected,' . $importedFiles . ' files imported, ' . $errorFiles . ' files with errors');
        }
        $executedTime = time() - $s;
        $memory = memory_get_peak_usage() / 1024 / 1024;
        $this->info('Seconds: ' . $executedTime);
        $this->info('Memory (MB): ' . $memory);
        Log::info('[IMPORT]: ' . $executedTime . ' seconds');
        Log::info('[IMPORT]: ' . $memory . ' MB');
    }

    /**
     *
     *
     * @return array|string[]
     */
    public function prepareOptions(): array
    {
        $options = [
            self::COUNTERPARTY,
            self::AGREEMENT,
            self::PRODUCT,
            self::SERVICE,
        ];
        if (!$this->option('all')) {
            $options = [$this->choice(
                'Which model do you want to import?',
                [
                    self::COUNTERPARTY,
                    self::AGREEMENT,
                    self::PRODUCT,
                    self::SERVICE,
                ]
            )];
        }
        return $options;
    }
}
