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
        Schema::create('triage_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nurse_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedSmallInteger('resp_rate');
            $table->unsignedSmallInteger('spo2');
            $table->unsignedSmallInteger('systolic_bp');
            $table->unsignedSmallInteger('heart_rate');
            $table->string('consciousness', 20);
            $table->decimal('temperature', 4, 1);
            $table->unsignedSmallInteger('computed_score');
            $table->string('urgency_level');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triage_records');
    }
};
