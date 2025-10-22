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
        Schema::create('distribution_agences', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->dateTime('date_operation');
            $table->foreignId('agence_id')->constrained('agences')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_valide')->default(false);
            $table->foreignId('validateur_id')->nullable()->constrained('agences')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_agences');
    }
};
