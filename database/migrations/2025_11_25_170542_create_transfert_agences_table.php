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
        Schema::create('transfert_agences', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->foreignId('agence_id')->constrained('agences')->onDelete('cascade');
            $table->foreignId('agence_destination_id')->constrained('agences')->onDelete('cascade');
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
            $table->boolean('etat')->default(false); // état du retour (traité ou non)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('validateur_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('date_transfert');
            $table->date('date_validation')->nullable();
            $table->string('statut')->nullable();
            $table->text('detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfert_agences');
    }
};
