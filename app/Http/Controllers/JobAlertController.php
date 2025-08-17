<?php

namespace App\Http\Controllers;

use App\Models\JobAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobAlertController extends Controller
{
    public function index(Request $request)
    {
        $alerts = JobAlert::where('user_id', Auth::id())->latest()->paginate(20);
        return response()->json($alerts);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'job_type' => ['nullable', 'string', 'max:100'],
            'experience_level' => ['nullable', 'string', 'max:100'],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'frequency' => ['required', 'in:daily,weekly,monthly'],
        ]);
        $alert = JobAlert::create(array_merge($data, ['user_id' => Auth::id()]));
        return response()->json(['status' => 'created', 'id' => $alert->id]);
    }

    public function update(Request $request, string $id)
    {
        $alert = JobAlert::where('user_id', Auth::id())->findOrFail($id);
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'job_type' => ['nullable', 'string', 'max:100'],
            'experience_level' => ['nullable', 'string', 'max:100'],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'frequency' => ['nullable', 'in:daily,weekly,monthly'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $alert->fill($data)->save();
        return response()->json(['status' => 'updated']);
    }

    public function destroy(string $id)
    {
        $alert = JobAlert::where('user_id', Auth::id())->findOrFail($id);
        $alert->delete();
        return response()->json(['status' => 'deleted']);
    }
}


