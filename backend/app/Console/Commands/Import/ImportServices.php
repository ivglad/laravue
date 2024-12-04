<?php

namespace App\Console\Commands\Import;

use App\Enums\PositionType;
use App\Models\Handbook\Position;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nette\Utils\JsonException;

class ImportServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:services {--path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports json files for the positions (service type) model at the specified path';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle()
    {
        $filePath = $this->option('path');
        $stringFile = file_get_contents($filePath);
        if (json_validate($stringFile)) {
            $jsonData = json_decode($stringFile, true);
            $jsonData = $jsonData['Услуги'];
            $jsonData = array_map(function ($item) {
                return [
                    'external_id' => $item['GUID'],
                    'name' => $item['Наименование'],
                    'type' => PositionType::Service
                ];
            }, $jsonData);
            $objects = Position::where('type', PositionType::Service)->where('external_id', '!=', null)->get();
            $externalIdsInFile = array_column($jsonData, 'external_id');
            $externalIdsInDb = $objects->pluck('external_id')->toArray();
            foreach ($jsonData as $k => $item) {
                $object = $objects->where('external_id', $item['external_id'])->first();
                if (!blank($object)) {
                    $jsonData[$k]['id'] = $object->id;
                }
                else {
                    $jsonData[$k]['id'] = null;
                }
            }
            try {
                DB::beginTransaction();
                $jsonDataChunks = array_chunk($jsonData, 100, true);
                foreach ($jsonDataChunks as $chunk) {
                    Position::upsert($chunk, ['id']);
                }
                Position::whereIn('external_id', array_diff($externalIdsInDb, $externalIdsInFile))->delete();
                DB::commit();
                Log::info('[SUCCESS][IMPORT][SERVICES]: ' . $filePath);
            } catch (Exception $exception) {
                DB::rollBack();
                Log::error('[ERROR][IMPORT][SERVICES][DB]: ' . $filePath);
                throw $exception;
            }
        } else {
            Log::error('[ERROR][IMPORT][SERVICES][JSON]: ' . $filePath . ' --- ' . json_last_error_msg());
            throw new JsonException('Invalid JSON file');
        }
    }
}
