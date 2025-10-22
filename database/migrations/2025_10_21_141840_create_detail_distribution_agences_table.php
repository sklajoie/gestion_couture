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
        Schema::create('detail_distribution_agences', function (Blueprint $table) {
            $table->id();
            $table->float('quantite')->default(0);
            $table->foreignId('distribution_agence_id')->constrained('distribution_agences')->onDelete('cascade');
            $table->foreignId('stock_entreprise_id')->constrained('stock_entreprises')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_distribution_agences');
    }
};
