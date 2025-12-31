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
        Schema::create('papa_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('papa_id')->constrained('papas')->onDelete('cascade');
            $table->integer('numero');
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->string('statut', 20)->default('brouillon');
            $table->dateTime('date_creation')->nullable();
            $table->dateTime('date_verrouillage')->nullable();
            $table->boolean('verrouille')->default(false);
            $table->timestamps();
            
            $table->unique(['papa_id', 'numero']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('papa_versions');
    }
};
