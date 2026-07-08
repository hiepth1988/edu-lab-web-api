<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;

class SubscriberController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Subscriber::latest()->paginate(50));
    }
}
