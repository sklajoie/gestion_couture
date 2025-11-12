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
        Schema::create('cloture_ateliers', function (Blueprint $table) {
            $table->id();
             $table->string('reference');
            $table->date('date');
            $table->float('montant_total');
            $table->foreignId('employe_id')->nullable()->constrained('employes')->onDelete('set null');
            $table->foreignId('atelier_id')->nullable()->constrained('ateliers')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cloture_ateliers');
    }
};
