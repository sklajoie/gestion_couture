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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code_barre')->unique();
            $table->text('description')->nullable();
            $table->float('prix_achat')->nullable();
            $table->float('prix_vente')->nullable();
            $table->integer('stock_minimum')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('archived')->default(0);
            $table->string('type')->nullable();
            $table->string('unite');
            $table->boolean('stockable')->default(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('categorie_produit_id')->constrained('categories_produits')->onDelete('cascade');
            $table->text('image')->nullable();
            $table->foreignId('marque_id')->nullable()->constrained('marques')->onDelete('set null');
            $table->foreignId('couleur_id')->nullable()->constrained('couleurs')->onDelete('set null');
            $table->foreignId('taille_id')->nullable()->constrained('tailles')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
