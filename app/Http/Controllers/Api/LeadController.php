<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewLeadNotification;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'need' => ['nullable', 'string', 'in:lms,exam,ai,analytics,consulting,other'],
            'message' => ['nullable', 'string', 'max:5000'],
            'source_url' => ['nullable', 'string', 'max:2048'],
        ]);

        $lead = Lead::create([
            ...$data,
            'locale' => $request->attributes->get('locale'),
        ]);

        Mail::to(config('mail.admin_address'))->queue(new NewLeadNotification($lead));

        return response()->json([
            'message' => 'Thank you, we will get back to you soon.',
            'lead_id' => $lead->id,
        ], 201);
    }
}
