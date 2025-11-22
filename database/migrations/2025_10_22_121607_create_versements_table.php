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
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('vente_id')->constrained('ventes')->onDelete('set null');
            $table->foreignId('agence_id')->constrained('agence')->onDelete('set null');
            $table->foreignId('caisse_id')->constrained('caisses')->onDelete('set null');
            $table->float('montant')->default(0);
            $table->string('mode_paiement')->nullable();
            $table->string('detail')->nullable();
            $table->string('cloture')->nullable();
             $table->foreignId('user_id')->constrained('users')->onDelete('set null');
             $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versements');
    }
};
