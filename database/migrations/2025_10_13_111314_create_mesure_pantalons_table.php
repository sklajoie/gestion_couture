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
        Schema::create('mesure_pantalons', function (Blueprint $table) {
            $table->id();
            $table->string('Reference')->nullable();
            $table->string('designation')->nullable();
              $table->float('nombre')->nullable();
            $table->string('Tour_taille')->nullable();
            $table->string('Tour_bassin')->nullable();
            $table->string('Tour_cuisse')->nullable();
            $table->string('Tour_genou')->nullable();
            $table->string('Tour_bas')->nullable();
            $table->string('Hauteur_genou')->nullable();
            $table->string('Hauteur_cheville')->nullable();
            $table->string('Entre_jambe')->nullable();
            $table->string('Longueur_pantalon')->nullable();
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
            $table->foreignId('etape_id')->nullable()->constrained('etape_productions')->onDelete('set null');
            $table->foreignId('couleur_id')->nullable()->constrained('couleurs')->onDelete('set null');
            $table->foreignId('taille_id')->nullable()->constrained('tailles')->onDelete('set null');
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesure_pantalons');
    }
};
