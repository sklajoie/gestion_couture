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
        Schema::create('stock_entreprises', function (Blueprint $table) {
            $table->id();
            $table->string('designation')->nullable();
            $table->string('code_barre')->unique()->nullable();
            $table->string('reference')->nullable();
            $table->string('type')->nullable();
            $table->integer('stock')->default(0);
            $table->float('prix')->default(0);
            $table->float('prix_achat')->nullable()->default(0);
            $table->text('image')->nullable();
            $table->integer('stock_alerte')->nullable()->default(0);
            $table->foreignId('couleur_id')->nullable()->constrained('couleurs')->onDelete('set null');
            $table->foreignId('taille_id')->nullable()->constrained('tailles')->onDelete('set null');
            $table->foreignId('marque_id')->nullable()->constrained('marques')->onDelete('set null');
            $table->foreignId('categorie_produit_id')->nullable()->constrained('categorie_produits')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_entreprises');
    }
};
