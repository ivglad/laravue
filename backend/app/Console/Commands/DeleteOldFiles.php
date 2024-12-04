<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteOldFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:delete {--days=7} {--path=import/backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleting files for a period of a certain number of days or one week.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $seconds = $this->option('days') < 0 ? 7 * 24 * 60 * 60 : $this->option('days') * 24 * 60 * 60;
        $path = $this->option('path');
        $this->info('Files that are ' . $seconds / (24 * 60 * 60) . ' day(s) old are being deleted.');
        $dirPath = storage_path($path);
        if (file_exists($dirPath) && is_dir($dirPath)) {
            $filePaths = scandir($dirPath);
            $filePaths = array_diff($filePaths, ['.', '..', '.gitignore']);
            $countFiles = count($filePaths);
            $deletedFiles = 0;
            $bar = $this->output->createProgressBar($countFiles);
            foreach ($filePaths as $i => $fileName) {
                $filePath = $dirPath . '/' . $fileName;
                $lastModified = filemtime($filePath);
                if ((time() - $lastModified) > $seconds && is_file($filePath)) {
                    unlink($filePath);
                    $deletedFiles++;
                }
                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
            $this->info('Files detected: ' . $countFiles);
            $this->info('Files deleted: ' . $deletedFiles);
        }
    }
}
