<?php

namespace Tests\Feature;

use App\Models\Allocation;
use App\Models\AuditLog;
use App\Models\Bed;
use App\Models\Patient;
use App\Models\Role;
use App\Models\TriageRecord;
use App\Models\User;
use App\Models\Ward;
use App\Services\AllocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllocationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_recommends_the_first_vacant_bed_in_the_matching_ward(): void
    {
        $role = Role::create(['name' => 'Triage Nurse']);
        $nurse = User::factory()->create(['role_id' => $role->id]);
        $ward = Ward::create(['name' => 'General Ward', 'specialization' => 'General', 'total_beds' => 2]);
        $otherWard = Ward::create(['name' => 'Surgical Ward', 'specialization' => 'Surgery', 'total_beds' => 1]);

        Bed::create(['ward_id' => $otherWard->id, 'bed_number' => 'S-01', 'status' => 'vacant']);
        $targetBed = Bed::create(['ward_id' => $ward->id, 'bed_number' => 'G-01', 'status' => 'vacant']);

        $patient = Patient::create(['name' => 'Jane Doe']);
        $triageRecord = TriageRecord::create([
            'patient_id' => $patient->id,
            'nurse_id' => $nurse->id,
            'resp_rate' => 16,
            'spo2' => 98,
            'systolic_bp' => 120,
            'heart_rate' => 80,
            'consciousness' => 'A',
            'temperature' => 36.8,
            'computed_score' => 0,
            'urgency_level' => 'Non-urgent',
        ]);

        $allocation = (new AllocationService())->recommend($triageRecord, 'General');

        $this->assertInstanceOf(Allocation::class, $allocation);
        $this->assertSame($targetBed->id, $allocation->recommended_bed_id);
        $this->assertSame('pending', $allocation->status);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'allocation_recommended',
            'subject_type' => Allocation::class,
            'subject_id' => $allocation->id,
        ]);
    }

    public function test_it_prefers_general_capacity_for_lower_urgency_cases_when_specialized_wards_are_available(): void
    {
        $generalWard = Ward::create(['name' => 'General Ward', 'specialization' => 'General', 'total_beds' => 1]);
        $surgicalWard = Ward::create(['name' => 'Surgical Ward', 'specialization' => 'Surgery', 'total_beds' => 1]);

        $generalBed = Bed::create(['ward_id' => $generalWard->id, 'bed_number' => 'Z-99', 'status' => 'vacant']);
        Bed::create(['ward_id' => $surgicalWard->id, 'bed_number' => 'A-01', 'status' => 'vacant']);

        $patient = Patient::create(['name' => 'Stable Patient']);
        $triageRecord = TriageRecord::create([
            'patient_id' => $patient->id,
            'nurse_id' => User::factory()->create(['role_id' => Role::firstOrCreate(['name' => 'Triage Nurse'])->id])->id,
            'resp_rate' => 16,
            'spo2' => 98,
            'systolic_bp' => 120,
            'heart_rate' => 78,
            'consciousness' => 'A',
            'temperature' => 36.8,
            'computed_score' => 0,
            'urgency_level' => 'Non-urgent',
        ]);

        $allocation = (new AllocationService())->recommend($triageRecord, 'Surgery');

        $this->assertSame($generalBed->id, $allocation->recommended_bed_id);
    }

    public function test_it_persists_presenting_complaint_when_creating_a_triage_record(): void
    {
        $nurse = User::factory()->create(['role_id' => Role::firstOrCreate(['name' => 'Triage Nurse'])->id]);
        $this->actingAs($nurse);

        $response = $this->post(route('triage-records.store'), [
            'patient_name' => 'Patricia Smith',
            'patient_age' => 42,
            'patient_sex' => 'Female',
            'patient_hospital_id' => 'TABAS-111',
            'patient_contact' => '08000000011',
            'presenting_complaint' => 'Shortness of breath',
            'ward_specialization' => 'General',
            'resp_rate' => 24,
            'spo2' => 91,
            'systolic_bp' => 96,
            'heart_rate' => 120,
            'consciousness' => 'A',
            'temperature' => 37.2,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('triage_records', [
            'presenting_complaint' => 'Shortness of breath',
        ]);
    }
}