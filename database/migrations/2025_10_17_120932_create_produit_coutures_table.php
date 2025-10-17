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
        Schema::create('produit_coutures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->decimal('quantite')->default(0);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('detail')->nullable();
            $table->foreignId('mesure_chemise_id')->nullable()->constrained('mesure_chemises')->onDelete('cascade');
            $table->foreignId('mesure_robe_id')->nullable()->constrained('mesure_robes')->onDelete('cascade');
            $table->foreignId('mesure_pantalon_id')->nullable()->constrained('mesure_pantalons')->onDelete('cascade');
            $table->foreignId('mesure_ensemble_id')->nullable()->constrained('mesure_ensembles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_coutures');
    }
};
