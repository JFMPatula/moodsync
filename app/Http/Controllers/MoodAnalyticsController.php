<?php

namespace App\Http\Controllers;

use App\Models\MoodEntry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MoodAnalyticsController extends Controller
{
    public function index()
    {
        $entries = MoodEntry::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('MoodEntries', [
            'initialEntries' => $entries
        ]);
    }

    public function getAnalytics()
    {
        $entries = MoodEntry::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'entries' => $entries,
            'analytics' => [
                'trends' => $this->calculateTrends($entries),
                'correlations' => $this->analyzeCorrelations($entries),
                'patterns' => $this->identifyPatterns($entries)
            ]
        ]);
    }
}