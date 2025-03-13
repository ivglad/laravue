<?php

namespace App\Models;

use App\Http\Filters\Activity\ActivityFilter;
use App\Traits\Models\Filterable;

/**
 * Переопределение модели журнала изменений.
 * Для нормальной работы, необходимо определить константы в этом классе.
 * Константы используются в ресурсах для преобразования полей в читаемый вид.
 */
class Activity extends \Spatie\Activitylog\Models\Activity
{
    use Filterable;

    public static string $filterModel = ActivityFilter::class;

    /**
     * Подмена поля subject_type, также используется для фильтрации, чтобы клиент не задавал полные имена моделей.
     * Необходимая структура:
     * <code>
     * <?
     * public const array SUBJECTS = [
     *     User::class => [
     *         'translate' => 'Пользователь',
     *         'type' => 'user',
     *     ],
     * ];
     * </code>
     * Если заполнять с маленькой буквы translate, то необходимо в фильтре учесть преобразование в поиске по всем полям.
     *
     * @var array
     */
    public const array SUBJECTS = [
        User::class => [
            'translate' => 'Пользователь',
            'type' => 'user',
        ],
    ];

    /**
     * Подмена изменений идентификаторов связей на значения их полей,
     * принимается поле в виде строки или массив полей.
     * Отражается в заданном порядке.
     * Необходимая структура:
     * <code>
     * <?
     * public const array RELATIONS = [
     *     User::class => ['surname', 'name', 'patronymic'],
     *     Status::class => 'name',
     * ];
     * </code>
     *
     * @var array
     */
    public const array RELATIONS = [
        User::class => ['surname', 'name', 'patronymic'],
    ];

    /**
     * Именование полей, которые не сходятся по ключу в lang/ru/validation.php
     * Необходимая структура:
     * <code>
     * <?
     * public const array CUSTOM_FIELDS = [
     *     User::class => [
     *         'name' => 'имя',
     *     ],
     * ];
     * </code>
     *
     * @var array
     */
    public const array CUSTOM_FIELDS = [
        User::class => [
            'name' => 'имя',
        ],
    ];

    /**
     * Преобразование английских событий на русский язык.
     * Необходимая структура:
     * <code>
     * <?
     * public const array EVENT_TRANSLATIONS = [
     *     'created' => 'создан',
     *     'updated' => 'обновлен',
     *     'deleted' => 'удален',
     * ];
     * </code>
     *
     * @var array
     */
    public const array EVENT_TRANSLATIONS = [
        'created' => 'создан',
        'updated' => 'обновлен',
        'deleted' => 'удален',
    ];

    /**
     * Используется в методе getActivitylogOptions отслеживаемой модели в качестве замыкания с помощью выражения:
     * <code>
     * <?
     * LogOptions::defaults()->setDescriptionForEvent(fn(string $eventName) => Activity::logEventNameToRu($eventName))
     * </code>
     *
     *
     * @param string $eventName
     * @return string
     */
    public static function logEventNameToRu(string $eventName): string
    {
        return self::EVENT_TRANSLATIONS[$eventName] ?? $eventName;
    }
}
