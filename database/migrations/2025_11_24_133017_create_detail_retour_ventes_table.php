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
        Schema::create('detail_retour_ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_entreprise_id')->constrained('stock_entreprises')->onDelete('cascade');
            $table->foreignId('retour_vente_id')->constrained('retour_ventes')->onDelete('cascade');
            $table->foreignId('detail_vente_id')->nullable()->constrained('detail_ventes')->onDelete('cascade');
            $table->float('quantite')->default(0);
            $table->float('prix_unitaire')->default(0);
            $table->float('montant')->default(0);
            $table->string('motif')->nullable(); // motif spécifique à l’article
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_retour_ventes');
    }
};
