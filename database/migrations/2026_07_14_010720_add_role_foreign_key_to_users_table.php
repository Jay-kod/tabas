<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Intentionally left blank: role_id already exists on users and is indexed in the base migration.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left blank.
    }
};
