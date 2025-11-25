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
        Schema::create('autre_mesures', function (Blueprint $table) {
            $table->id();
            $table->string('Reference')->nullable();
            $table->string('Type')->nullable();
            $table->string('designation')->nullable();
            $table->text('Description')->nullable();
            $table->json('Model_mesure')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('set null');
            $table->foreignId('etape_id')->nullable()->constrained('etape_productions')->onDelete('set null');
            $table->boolean('status')->default(false);
            $table->boolean('is_archived')->nullable()->default(false);
            $table->float('total_produit')->nullable()->default(0);
            $table->float('main_oeuvre')->nullable()->default(0);
            $table->float('prix_couture')->nullable()->default(0);
            $table->float('prix_vente')->nullable()->default(0);
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
        Schema::dropIfExists('autre_mesures');
    }
};
