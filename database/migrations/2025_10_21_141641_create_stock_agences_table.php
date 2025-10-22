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
        Schema::create('stock_agences', function (Blueprint $table) {
            $table->id();
            $table->float('stock_alerte')->default(0);
            $table->float('stock')->default(0);
            $table->foreignId('stock_entreprise_id')->constrained('stock_entreprises')->onDelete('cascade');
            $table->foreignId('agence_id')->constrained('agences')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_agences');
    }
};
