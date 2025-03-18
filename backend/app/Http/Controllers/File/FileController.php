<?php

namespace App\Http\Controllers\File;

use App\Enums\FileModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\FileDeleteRequest;
use App\Http\Requests\File\FileReorderRequest;
use App\Http\Requests\File\FileStoreRequest;
use App\Http\Resources\File\FileCollection;
use App\Http\Responses\DeletedJsonResponse;
use App\Http\Responses\NotExistsJsonResponse;
use Illuminate\Http\JsonResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * Загрузка файлов в медиатеку, collection_name по умолчанию равен default.
     * Проверяется наличие модели к которой крепится файл по enum справочника файлов
     *
     * @param FileStoreRequest $request
     * @return FileCollection|NotExistsJsonResponse|JsonResponse
     */
    public function store(FileStoreRequest $request): FileCollection|NotExistsJsonResponse|JsonResponse
    {
        $attr = $request->validated();
        $className = FileModel::from($attr['model_type'])->name();
        if (class_exists($className)) {
            $object = $className::find($attr['model_id']);
            $object->addAllMediaFromRequest()
                ->each(function ($fileAdder) use ($attr) {
                    if (blank($attr['collection']) || $attr['collection'] === 'default') {
                        $fileAdder->toMediaCollection();
                    } else {
                        $fileAdder->toMediaCollection($attr['collection']);
                    }
                });
            return new FileCollection($object->getMedia('*'));
        }
        return new NotExistsJsonResponse();
    }

    /**
     * Массовое удаление файлов по его id
     *
     * @param FileDeleteRequest $request
     * @return DeletedJsonResponse
     */
    public function destroy(FileDeleteRequest $request): DeletedJsonResponse
    {
        $attr = $request->validated();
        Media::whereIn('id', $attr['ids'])->delete();
        return new DeletedJsonResponse();
    }

    /**
     * Сортировка файлов по переданному порядку их id
     *
     * @param FileReorderRequest $request
     * @return JsonResponse
     */
    public function reorder(FileReorderRequest $request): JsonResponse
    {
        Media::setNewOrder($request->toArray());
        return response()->json();
    }

    /**
     * Скачивание файла по его id
     *
     * @param Media $media
     * @return BinaryFileResponse
     */
    public function download(Media $media): BinaryFileResponse
    {
        return response()->download($media->getPath(), $media->file_name);
    }
}
