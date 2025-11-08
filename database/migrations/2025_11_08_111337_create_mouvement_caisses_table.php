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
        Schema::create('mouvement_caisses', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('type_mouvement');
            $table->string('montant');
            $table->string('mode_reglement');
            $table->text('detail')->nullable();
            $table->string('structure_type')->nullable();
            $table->unsignedBigInteger('structure_id')->nullable();
            $table->date('date');
            $table->foreignId('mouvement_nature_id')->constrained('mouvement_natures')->onDelete('set null');
            $table->foreignId('caisse_id')->nullable()->constrained('caisses')->onDelete('set null');
            $table->foreignId('employe_id')->constrained('employes')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('set null');
            $table->string('cloture')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_caisses');
    }
};
