<?php

namespace App\Http\Controllers\File;

use App\Enums\FileModel;
use App\Enums\GenerateType;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\FileDeleteRequest;
use App\Http\Requests\File\FileGenerateRequest;
use App\Http\Requests\File\FileStoreRequest;
use App\Http\Resources\File\FileCollection;
use App\Http\Responses\DeletedJsonResponse;
use App\Http\Responses\NotExistsJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileController extends Controller
{
    public function store(FileStoreRequest $request): FileCollection|NotExistsJsonResponse
    {
        $attr = $request->validated();
        $className = FileModel::from($attr['model_type'])->name();
        if (class_exists($className)) {
            $object = $className::find($attr['model_id']);
            $object->addAllMediaFromRequest()
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection();
                });
            return new FileCollection($object->getMedia());
        }
        return new NotExistsJsonResponse();
    }

    public function destroy(FileDeleteRequest $request): DeletedJsonResponse
    {
        $attr = $request->validated();
        Media::whereIn('id', $attr['ids'])->delete();
        return new DeletedJsonResponse();
    }

    public function generate(FileGenerateRequest $request): NotExistsJsonResponse|JsonResponse
    {
        $types = GenerateType::from($request->model_type)->data();
        if (array_key_exists($request->extension, $types) && $request->extension !== 'options') {
            $options = [];
            foreach ($types['options'] as $option) {
                if (isset($request->$option) && !blank($request->$option)) {
                    if (str_contains($option, 'ids')) {
                        $request->$option = implode(',', $request->$option);
                    }
                    $options['--' . $option] = $request->$option;
                }
            }
            Artisan::call($types[$request->extension], $options);
            $output = trim(Artisan::output(), "\n");
            if (!blank($output)) {
                return new JsonResponse(['path' => $output]);
            }
        }
        return new NotExistsJsonResponse();
    }
}
