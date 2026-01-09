<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Notifications\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $service
    ) {}

    public function index(Request $request)
    {
        return response()->json([
            'data' => $this->service->getAll($request->user()->id)
        ]);
    }

    public function markAsRead(Request $request, string $id)
    {
        $success = $this->service->markAsRead($id, $request->user()->id);

        if (!$success) {
            return response()->json(['message' => 'الإشعار غير موجود'], 404);
        }

        return response()->json(['message' => 'تم تعليم الإشعار كمقروء']);
    }
}
