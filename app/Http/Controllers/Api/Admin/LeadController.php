<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $leads = Lead::with('notes')
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('need'), fn ($q, $need) => $q->where('need', $need))
            ->latest()
            ->paginate(20);

        return response()->json($leads);
    }

    public function update(Request $request, Lead $lead): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,contacted,qualified,closed'],
        ]);

        $lead->update($data);

        return response()->json(['data' => $lead]);
    }

    public function addNote(Request $request, Lead $lead): JsonResponse
    {
        $data = $request->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        $note = $lead->notes()->create([
            'note' => $data['note'],
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['data' => $note->load('user')], 201);
    }
}
