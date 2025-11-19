<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consensus_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('triggered_by')->constrained('users'); // Area Manager ID
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consensus_logs');
    }
};