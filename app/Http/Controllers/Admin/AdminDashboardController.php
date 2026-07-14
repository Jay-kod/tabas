<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\Bed;
use App\Models\Patient;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $acceptedAllocations = Allocation::with('triageRecord')
            ->where('status', 'accepted')
            ->whereDate('decided_at', $today)
            ->get();

        $averagePlacementMinutes = $acceptedAllocations
            ->filter(fn ($allocation) => $allocation->triageRecord && $allocation->decided_at)
            ->map(fn ($allocation) => $allocation->triageRecord->created_at->diffInMinutes($allocation->decided_at))
            ->avg() ?? 0;

        return Inertia::render('Admin/Dashboard', [
            'metrics' => [
                'patientsToday' => Patient::whereDate('created_at', $today)->count(),
                'bedsOccupied' => Bed::where('status', 'occupied')->count(),
                'bedsVacant' => Bed::where('status', 'vacant')->count(),
                'pendingAllocations' => Allocation::where('status', 'pending')->count(),
                'averagePlacementMinutes' => round($averagePlacementMinutes, 1),
            ],
            'latestPendingAllocations' => Allocation::with(['triageRecord.patient', 'recommendedBed.ward'])
                ->where('status', 'pending')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
