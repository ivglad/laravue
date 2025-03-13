<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\ReadNotificationRequest;
use App\Http\Resources\Notification\NotificationCollection;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Получить список уведомлений пользователя в порядке, где сверху непрочитанные новые
     *
     * @return NotificationCollection
     */
    public function index(): NotificationCollection
    {
        return new NotificationCollection(
            Notification::with('user')
                ->where('user_id', Auth::id())
                ->orderBy('is_read')
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage)
        );
    }

    /**
     * Запрос отвечающий за отметку уведомлений как прочитанные
     *
     * @param ReadNotificationRequest $request
     * @return JsonResponse
     */
    public function read(ReadNotificationRequest $request): JsonResponse
    {
        $attr = $request->validated();
        Notification::where('user_id', Auth::id())->whereIn('id', $attr['ids'])->update(['is_read' => true]);
        return response()->json(['message' => 'Уведомления прочитаны']);
    }
}
