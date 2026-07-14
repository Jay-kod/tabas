<?php

namespace App\Services;

use App\Models\Allocation;
use App\Models\AuditLog;
use App\Models\Bed;
use App\Models\TriageRecord;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AllocationService
{
    public function recommend(TriageRecord $triageRecord, ?string $specialization = null): ?Allocation
    {
        $wardSpecialization = $specialization ?: 'General';

        $bed = Bed::query()
            ->where('status', 'vacant')
            ->whereHas('ward', function ($query) use ($wardSpecialization) {
                $query->where('specialization', $wardSpecialization)
                    ->orWhere('name', $wardSpecialization);
            })
            ->orderBy('bed_number')
            ->first()
            ?? Bed::query()->where('status', 'vacant')->orderBy('bed_number')->first();

        $allocation = Allocation::create([
            'triage_record_id' => $triageRecord->id,
            'recommended_bed_id' => $bed?->id,
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => $triageRecord->nurse_id,
            'action' => 'allocation_recommended',
            'subject_type' => Allocation::class,
            'subject_id' => $allocation->id,
        ]);

        AuditLog::create([
            'user_id' => $triageRecord->nurse_id,
            'action' => 'recommendation_created_for_triage',
            'subject_type' => TriageRecord::class,
            'subject_id' => $triageRecord->id,
        ]);

        return $allocation;
    }

    public function accept(Allocation $allocation, User $actor): Allocation
    {
        return DB::transaction(function () use ($allocation, $actor) {
            $allocation->update([
                'status' => 'accepted',
                'decided_by' => $actor->id,
                'decided_at' => now(),
                'reason' => null,
            ]);

            if ($allocation->recommendedBed) {
                $allocation->recommendedBed->update(['status' => 'occupied']);
            }

            AuditLog::create([
                'user_id' => $actor->id,
                'action' => 'allocation_accepted',
                'subject_type' => Allocation::class,
                'subject_id' => $allocation->id,
            ]);

            return $allocation;
        });
    }

    public function override(Allocation $allocation, User $actor, string $reason): Allocation
    {
        return DB::transaction(function () use ($allocation, $actor, $reason) {
            $allocation->update([
                'status' => 'overridden',
                'decided_by' => $actor->id,
                'decided_at' => now(),
                'reason' => $reason,
            ]);

            AuditLog::create([
                'user_id' => $actor->id,
                'action' => 'allocation_overridden',
                'subject_type' => Allocation::class,
                'subject_id' => $allocation->id,
            ]);

            return $allocation;
        });
    }
}