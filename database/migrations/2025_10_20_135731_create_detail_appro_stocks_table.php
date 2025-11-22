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
        Schema::create('detail_appro_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approvisionnement_stock_id')->constrained('approvisionnement_stocks')->onDelete('cascade');
            $table->string('reference')->nullable();
            $table->string('mesure_type')->nullable();
            $table->unsignedBigInteger('mesure_id')->nullable();
            $table->decimal('quantite')->default(0);
            $table->float('prix_unitaire')->nullable();
            $table->float('total')->nullable();
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_appro_stocks');
    }
};
