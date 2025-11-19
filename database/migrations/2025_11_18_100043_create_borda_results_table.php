<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borda_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consensus_log_id')->constrained('consensus_logs')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->integer('total_points');
            $table->integer('final_rank');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borda_results');
    }
};