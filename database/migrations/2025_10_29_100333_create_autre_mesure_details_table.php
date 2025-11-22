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
        Schema::create('autre_mesure_details', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('mesure');
            $table->text('detail')->nullable();
            $table->foreignId('autre_mesure_id')->nullable()->constrained('autre_mesures')->onDelete('set null');
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autre_mesure_details');
    }
};
