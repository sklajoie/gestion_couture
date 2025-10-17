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
        Schema::create('etape_mesures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesure_chemise_id')->nullable()->constrained('mesure_chemises')->onDelete('cascade');
            $table->foreignId('mesure_robe_id')->nullable()->constrained('mesure_robes')->onDelete('cascade');
            $table->foreignId('mesure_pantalon_id')->nullable()->constrained('mesure_pantalons')->onDelete('cascade');
            $table->foreignId('mesure_ensemble_id')->nullable()->constrained('mesure_ensembles')->onDelete('cascade');
            $table->foreignId('etape_production_id')->constrained('etape_productions')->onDelete('set null');
            $table->foreignId('responsable_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_completed')->default(false);
            $table->datetime('date_debut')->nullable();
            $table->datetime('date_fin')->nullable();
            $table->string('temp_mis')->nullable();
            $table->text('comments')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etape_mesures');
    }
};
