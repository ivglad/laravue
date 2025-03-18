<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Http\Resources\Activity\ActivityCollection;
use App\Http\Responses\AccessDeniedJsonResponse;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Список изменений моделей
     *
     * @return ActivityCollection|AccessDeniedJsonResponse
     */
    public function index(): ActivityCollection|AccessDeniedJsonResponse
    {
        if (Auth::user()->can('activity.index')) {
            $activities = Activity::with('causer')->filter()->latest()->paginate($this->perPage);

            return new ActivityCollection($activities);
        }
        return new AccessDeniedJsonResponse();
    }

    /**
     * Справочник событий и моделей, по которым можно выполнить фильтрацию журнала изменений
     *
     * @return JsonResponse
     */
    public function handbook(): JsonResponse
    {
        $subjects = [];
        foreach (Activity::SUBJECTS as $subject) {
            $subjects[$subject['type']] = $subject['translate'];
        }
        $handbook = [
            'events' => Activity::EVENT_TRANSLATIONS,
            'subject_types' => $subjects,
        ];
        return response()->json($handbook);
    }
}
