<?php

namespace Database\Seeders;

use App\Models\Allocation;
use App\Models\Bed;
use App\Models\Patient;
use App\Models\Role;
use App\Models\TriageRecord;
use App\Models\User;
use App\Models\Ward;
use App\Services\AllocationService;
use App\Services\TriageScoringService;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $roles = Role::pluck('id', 'name');

        $users = [
            ['name' => 'TABAS Admin', 'email' => 'admin@tabas.test', 'role' => 'Admin'],
            ['name' => 'Amina Yusuf', 'email' => 'nurse@tabas.test', 'role' => 'Triage Nurse'],
            ['name' => 'David Okafor', 'email' => 'bedmanager@tabas.test', 'role' => 'Bed Manager'],
            ['name' => 'Dr. Chidi Nwosu', 'email' => 'doctor@tabas.test', 'role' => 'Doctor'],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $roles[$userData['role']] ?? null,
                ]
            );
        }

        $wards = [
            ['name' => 'General Ward', 'specialization' => 'General', 'total_beds' => 8],
            ['name' => 'Surgical Ward', 'specialization' => 'Surgery', 'total_beds' => 6],
            ['name' => 'Isolation Ward', 'specialization' => 'Infectious Diseases', 'total_beds' => 4],
        ];

        foreach ($wards as $wardData) {
            $ward = Ward::updateOrCreate(
                ['name' => $wardData['name']],
                $wardData
            );

            for ($index = 1; $index <= $ward->total_beds; $index++) {
                Bed::firstOrCreate(
                    ['ward_id' => $ward->id, 'bed_number' => sprintf('%02d', $index)],
                    ['status' => 'vacant']
                );
            }
        }

        $triageService = new TriageScoringService();
        $allocationService = new AllocationService();
        $nurse = User::where('email', 'nurse@tabas.test')->first();

        $demoCases = [
            [
                'patient' => ['name' => 'Jane Doe', 'age' => 36, 'sex' => 'Female', 'hospital_id' => 'TABAS-001', 'contact' => '08030000001'],
                'vitals' => ['resp_rate' => 32, 'spo2' => 89, 'systolic_bp' => 82, 'heart_rate' => 138, 'consciousness' => 'U', 'temperature' => 39.4],
                'ward_specialization' => 'General',
            ],
            [
                'patient' => ['name' => 'Aminu Bala', 'age' => 51, 'sex' => 'Male', 'hospital_id' => 'TABAS-002', 'contact' => '08030000002'],
                'vitals' => ['resp_rate' => 18, 'spo2' => 97, 'systolic_bp' => 122, 'heart_rate' => 84, 'consciousness' => 'A', 'temperature' => 36.7],
                'ward_specialization' => 'General',
            ],
        ];

        if ($nurse) {
            foreach ($demoCases as $demoCase) {
                $patient = Patient::updateOrCreate(
                    ['hospital_id' => $demoCase['patient']['hospital_id']],
                    $demoCase['patient']
                );

                $score = $triageService->score($demoCase['vitals']);

                $triageRecord = TriageRecord::updateOrCreate(
                    ['patient_id' => $patient->id],
                    [
                        'nurse_id' => $nurse->id,
                        ...$demoCase['vitals'],
                        'computed_score' => $score['score'],
                        'urgency_level' => $score['category'],
                    ]
                );

                Allocation::where('triage_record_id', $triageRecord->id)->delete();
                $allocationService->recommend($triageRecord, $demoCase['ward_specialization']);
            }
        }
    }
}
