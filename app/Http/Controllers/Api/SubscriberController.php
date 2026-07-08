<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $subscriber = Subscriber::updateOrCreate(
            ['email' => $data['email']],
            ['locale' => $request->attributes->get('locale'), 'status' => 'subscribed']
        );

        return response()->json(['data' => $subscriber], 201);
    }
}
