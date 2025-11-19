<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criterias', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // C1, C2, dst
            $table->string('name');
            $table->enum('type', ['benefit', 'cost']);
            $table->float('weight'); // Bobot awal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criterias');
    }
};