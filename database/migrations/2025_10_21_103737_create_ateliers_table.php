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
        Schema::create('ateliers', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->string('nom');
            $table->string('adresse')->nullable();
            $table->string('telephone');
            $table->string('contact')->nullable();
            $table->string('ville')->nullable();
            $table->string('email')->nullable();
            $table->text('logo')->nullable();
            $table->text('pied_page')->nullable();
            $table->boolean('status')->default(1);
            $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
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
        Schema::dropIfExists('ateliers');
    }
};
