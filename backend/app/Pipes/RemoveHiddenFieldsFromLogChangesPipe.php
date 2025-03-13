<?php

declare(strict_types=1);

namespace App\Pipes;

use Closure;
use Illuminate\Support\Arr;
use Spatie\Activitylog\Contracts\LoggablePipe;
use Spatie\Activitylog\EventLogBag;

/**
 * Исключает сохранение в логах полей, которые заданы в hidden.
 * Для использования необходимо добавить в модель trait GetHiddenFields.
 */
final class RemoveHiddenFieldsFromLogChangesPipe implements LoggablePipe
{
    public function handle(EventLogBag $event, Closure $next): EventLogBag
    {
        $hiddenFields = $event->model::getHiddenFields();
        foreach ($hiddenFields as $hiddenField) {
            Arr::forget($event->changes, ["attributes.{$hiddenField}", "old.{$hiddenField}"]);
        }

        return $next($event);
    }
}
