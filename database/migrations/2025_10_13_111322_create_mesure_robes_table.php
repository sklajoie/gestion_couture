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
        Schema::create('mesure_robes', function (Blueprint $table) {
            $table->id();
            $table->string('Reference')->nullable();
            $table->string('Epaule')->nullable();
            $table->string('Tour_poitrine')->nullable();
            $table->string('Tour_taille')->nullable();
            $table->string('Tour_bassin')->nullable();
            $table->string('Longueur_bassin')->nullable();
            $table->string('Carrure_dos')->nullable();
            $table->string('Longueur_buste')->nullable();
            $table->string('Tour_bras')->nullable();
            $table->string('Longueur_manche')->nullable();
            $table->string('Tour_manche')->nullable();
            $table->string('Longueur_robe')->nullable();
            $table->string('Type')->nullable();
            $table->text('Description')->nullable();
             $table->json('Model_mesure')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('set null');
            $table->boolean('status')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->decimal('total_produit', 10, 2)->nullable();
            $table->decimal('main_oeuvre', 10, 2)->nullable();
            $table->float('prix_couture')->nullable();
            $table->float('prix_vente')->nullable();
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
        Schema::dropIfExists('mesure_robes');
    }
};
