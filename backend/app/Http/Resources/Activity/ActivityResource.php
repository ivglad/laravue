<?php

namespace App\Http\Resources\Activity;

use App\Http\Resources\User\UserShortResource;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Lang;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $properties = $this->prepareChanges();

        return [
            'id' => $this->id,
            'causer' => new UserShortResource($this->whenLoaded('causer')),
            'created_at' => $this->created_at,
            'description' => $this->description,
            'event' => $this->event,
            'subject_id' => $this->subject_id,
            'subject_type' => Activity::SUBJECTS[$this->subject_type],
            'properties' => $properties,
        ];
    }

    private function prepareChanges(): array
    {
        $langNames = Lang::get('validation.attributes');
        $properties = [];
        foreach (['attributes', 'old'] as $root)
            if (isset($this->properties[$root])) {
                $fieldChanges = $this->properties[$root];
                foreach ($fieldChanges as $key => $attribute) {
                    // Установка Да/Нет булевым значениям
                    if (is_bool($attribute)) {
                        $fieldChanges[$key] = $attribute ? 'Да' : 'Нет';
                    }
                    // Подмена полей идентификаторов на их значения
                    if (str_contains($key, '_id')) {
                        $relationMethod = explode('_id', $key)[0];
                        if (method_exists($this->subject, $relationMethod)) {
                            $className = get_class($this->subject->$relationMethod()->getRelated());
                            if (isset(Activity::RELATIONS[$className])) {
                                if (is_string(Activity::RELATIONS[$className])) {
                                    // если задано только одно поле
                                    $fieldChanges[$relationMethod] = $this->subject->$relationMethod()
                                        ->select(Activity::RELATIONS[$className])
                                        ->first()
                                        ->Activity::RELATIONS[$className];
                                } elseif (is_array(Activity::RELATIONS[$className])) {
                                    // если задан массив полей, то конкатенируем в заданном порядке значения полей, убираем аттрибуты
                                    $relation = $this->subject->$relationMethod()->select(Activity::RELATIONS[$className])
                                        ->first()
                                        ->toArray();
                                    foreach ($relation as $keyRelation => $field) {
                                        if (!in_array($keyRelation, Activity::RELATIONS[$className])) {
                                            unset($relation[$keyRelation]);
                                        }
                                    }
                                    $fieldChanges[$relationMethod] = implode(' ', $relation);
                                }
                            }
                        }
                    }
                    // Переименование полей - оставлять последним в цикле из-за unset
                    if (isset(Activity::CUSTOM_FIELDS[$this->subject_type][$key])) {
                        $fieldChanges[Activity::CUSTOM_FIELDS[$this->subject_type][$key]] = $attribute;
                        unset($fieldChanges[$key]);
                    } elseif (isset($langNames[$key])) {
                        $fieldChanges[$langNames[$key]] = $attribute;
                        unset($fieldChanges[$key]);
                    }
                }
                $properties[$root] = $fieldChanges;
            }

        return $properties;
    }
}
