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
        Schema::create('detail_aprovisionnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approvisionnement_id')->constrained('approvisionnements')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->decimal('quantite')->default(0);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('total', 10, 2);
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_aprovisionnements');
    }
};
