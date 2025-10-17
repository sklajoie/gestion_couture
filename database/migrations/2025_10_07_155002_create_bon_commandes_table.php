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
        Schema::create('bon_commandes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->date('date_commande');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->decimal('total_brut', 10, 2)->default(0);
            $table->decimal('remise', 10, 2)->default(0);
            $table->decimal('total_hors_taxes', 10, 2)->default(0);
            $table->decimal('tva', 10, 2)->default(0);
            $table->decimal('total_ttc', 10, 2)->default(0);
            $table->decimal('avance', 10, 2)->default(0);
            $table->decimal('solde', 10, 2)->default(0);
            $table->string('statut')->default('en attente');
            $table->text('remarques')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_commandes');
    }
};
