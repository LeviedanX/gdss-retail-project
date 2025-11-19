<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topsis_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->float('preference_value', 8, 4); // Nilai V
            $table->integer('rank'); // Ranking individu
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topsis_results');
    }
};