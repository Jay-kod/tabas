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
        Schema::table('triage_records', function (Blueprint $table) {
            $table->text('presenting_complaint')->nullable()->after('nurse_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('triage_records', function (Blueprint $table) {
            $table->dropColumn('presenting_complaint');
        });
    }
};
