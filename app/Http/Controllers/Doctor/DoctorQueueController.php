<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use Inertia\Inertia;

class DoctorQueueController extends Controller
{
    public function index()
    {
        return Inertia::render('Doctor/Queue', [
            'pendingAllocations' => Allocation::with(['triageRecord.patient', 'recommendedBed.ward'])
                ->where('status', 'pending')
                ->latest()
                ->get(),
        ]);
    }
}
