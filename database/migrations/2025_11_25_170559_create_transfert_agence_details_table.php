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
        Schema::create('transfert_agence_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfert_agence_id')->constrained('transfert_agences')->onDelete('cascade');
            $table->foreignId('stock_entreprise_id')->constrained('stock_entreprises')->onDelete('cascade');
            $table->float('quantite')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfert_agence_details');
    }
};
