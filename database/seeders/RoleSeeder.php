<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    private const ROLES = [
        'Admin',
        'Triage Nurse',
        'Bed Manager',
        'Doctor',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::ROLES as $roleName) {
            Role::updateOrCreate(['name' => $roleName]);
        }
    }
}
