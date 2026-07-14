<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\TriageRecord;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NursePatientController extends Controller
{
    public function index(Request $request)
    {
        $triageRecords = TriageRecord::with(['patient', 'allocation.recommendedBed.ward'])
            ->whereDate('created_at', now()->toDateString())
            ->latest()
            ->get();

        return Inertia::render('User/Pages/Nurse/Patients', [
            'triageRecords' => $triageRecords,
        ]);
    }
}