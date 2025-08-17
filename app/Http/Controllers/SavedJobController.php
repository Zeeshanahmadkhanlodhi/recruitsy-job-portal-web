<?php

namespace App\Http\Controllers;

use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    public function index(Request $request)
    {
        $saved = SavedJob::where('user_id', Auth::id())
            ->latest('saved_at')
            ->paginate(20);

        return response()->json($saved);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => ['required', 'string', 'max:50'],
            'external_id' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string', 'max:100'],
            'apply_url' => ['nullable', 'url'],
            'short_description' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
        ]);

        $saved = SavedJob::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'source' => $validated['source'],
                'external_id' => $validated['external_id'],
            ],
            array_merge($validated, [
                'saved_at' => now(),
            ])
        );

        return response()->json(['status' => 'saved', 'id' => $saved->id]);
    }

    public function destroy(Request $request, string $id)
    {
        $saved = SavedJob::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $saved->delete();
        return response()->json(['status' => 'deleted']);
    }
}


