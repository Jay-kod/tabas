<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTriageRecordRequest;
use App\Models\AuditLog;
use App\Models\Patient;
use App\Models\TriageRecord;
use App\Services\AllocationService;
use App\Services\TriageScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class TriageRecordController extends Controller
{
    public function store(
        StoreTriageRecordRequest $request,
        TriageScoringService $triageScoringService,
        AllocationService $allocationService,
    ): RedirectResponse
    {
        $scoreResult = $triageScoringService->score($request->validated());

        $triageRecord = DB::transaction(function () use ($request, $scoreResult) {
            $patient = Patient::create([
                'name' => $request->string('patient_name')->toString(),
                'age' => $request->integer('patient_age'),
                'sex' => $request->filled('patient_sex') ? $request->string('patient_sex')->toString() : null,
                'hospital_id' => $request->filled('patient_hospital_id') ? $request->string('patient_hospital_id')->toString() : null,
                'contact' => $request->filled('patient_contact') ? $request->string('patient_contact')->toString() : null,
            ]);

            return TriageRecord::create([
                'patient_id' => $patient->id,
                'nurse_id' => $request->user()->id,
                'resp_rate' => $request->integer('resp_rate'),
                'spo2' => $request->integer('spo2'),
                'systolic_bp' => $request->integer('systolic_bp'),
                'heart_rate' => $request->integer('heart_rate'),
                'consciousness' => $request->string('consciousness')->toString(),
                'temperature' => (float) $request->input('temperature'),
                'computed_score' => $scoreResult['score'],
                'urgency_level' => $scoreResult['category'],
            ]);
        });

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'triage_score_generated',
            'subject_type' => TriageRecord::class,
            'subject_id' => $triageRecord->id,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'patient_triage_created',
            'subject_type' => TriageRecord::class,
            'subject_id' => $triageRecord->id,
        ]);

        $allocation = $allocationService->recommend($triageRecord, $request->input('ward_specialization'))?->load('recommendedBed.ward');

        return back()->with([
            'triageResult' => $scoreResult,
            'triageAllocation' => $allocation,
        ]);
    }
}
