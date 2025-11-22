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
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('agence_id')->constrained('agence')->onDelete('cascade');
            $table->float('montant_brut')->nullable()->default(0);
            $table->float('remise')->nullable()->default(0);
            $table->float('montant_hors_taxe')->nullable()->default(0);
            $table->float('tva')->nullable()->default(0);
            $table->float('montant_ttc')->nullable()->default(0);
            $table->float('avance')->nullable()->default(0);
            $table->float('solde')->nullable()->default(0);
            $table->string('statut')->nullable();
            $table->string('cloture')->nullable();
            $table->float('avoire')->nullable();
            $table->dateTime('date_vente');
            $table->string('code_avoir')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
