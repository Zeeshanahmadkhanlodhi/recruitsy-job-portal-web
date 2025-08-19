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
            'location' => ['nullable', 'string', 'max:100'],
            'job_type' => ['nullable', 'string', 'max:100'],
            'experience_level' => ['nullable', 'string', 'max:100'],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'frequency' => ['required', 'in:daily,weekly,monthly'],
        ]);
        
        // Set default values for empty fields
        $data = array_map(function($value) {
            return $value === '' ? null : $value;
        }, $data);
        
        $alert = JobAlert::create(array_merge($data, [
            'user_id' => Auth::id(),
            'is_active' => true
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'Job alert created successfully',
            'alert' => $alert
        ]);
    }

    public function update(Request $request, string $id)
    {
        $alert = JobAlert::where('user_id', Auth::id())->findOrFail($id);
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:100'],
            'job_type' => ['nullable', 'string', 'max:100'],
            'experience_level' => ['nullable', 'string', 'max:100'],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'frequency' => ['nullable', 'in:daily,weekly,monthly'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        
        // Set default values for empty fields
        $data = array_map(function($value) {
            return $value === '' ? null : $value;
        }, $data);
        
        $alert->fill($data)->save();
        return response()->json([
            'success' => true,
            'status' => 'updated',
            'message' => 'Job alert updated successfully'
        ]);
    }

    public function destroy(string $id)
    {
        $alert = JobAlert::where('user_id', Auth::id())->findOrFail($id);
        $alert->delete();
        return response()->json([
            'success' => true,
            'status' => 'deleted',
            'message' => 'Job alert deleted successfully'
        ]);
    }
}


