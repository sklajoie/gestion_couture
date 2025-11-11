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
        Schema::create('etape_ateliers', function (Blueprint $table) {
            $table->id();
            $table->string('mesure_type')->nullable();
            $table->unsignedBigInteger('mesure_id')->nullable();
            $table->foreignId('etape_production_id')->constrained('etape_productions')->onDelete('set null');
            $table->foreignId('employe_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('date');
            $table->foreignId('atelier_id')->nullable()->constrained('ateliers')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etape_ateliers');
    }
};
