<?php

namespace App\Models;

use App\Pipes\RemoveHiddenFieldsFromLogChangesPipe;
use App\Traits\Models\GetHiddenFields;
use Illuminate\Database\Eloquent\Model as ModelBase;
use Spatie\Activitylog\Traits\LogsActivity;

abstract class Model extends ModelBase
{
    use GetHiddenFields;

    protected static function booted(): void
    {
        // Проверяет, подключен ли трейт логирования изменений к модели, для подключения пайпа исключающего из логов скрытые поля
        if (in_array(LogsActivity::class, class_uses_recursive(static::class))) {
            static::addLogChange(new RemoveHiddenFieldsFromLogChangesPipe);
        }
    }
}
