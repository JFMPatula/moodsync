<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoodEntry;

class MoodEntryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood_description' => 'required|string',
            'mood_intensity' => 'required|integer|min:1|max:10',
            'emotional_state' => 'required|string',
            'sleep_quality' => 'required|integer|min:1|max:4',
            'trigger_description' => 'required|string',
            'activities_description' => 'required|string',
            'gratitude_entry' => 'required|string',
            'goal_description' => 'required|string',
        ]);

        $moodEntry = MoodEntry::create($validated);

        return response()->json($moodEntry, 201);
    }

    public function index()
    {
        $entries = MoodEntry::orderBy('created_at', 'desc')->get();
        return response()->json($entries);
    }
}