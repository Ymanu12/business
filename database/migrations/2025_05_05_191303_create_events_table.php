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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');                     // Nom de l'événement
            $table->date('event_date');                 // Date de l'événement
            $table->time('event_time')->nullable();     // Heure de l'événement (ajouté)
            $table->string('location');                 // Lieu de l'événement
            $table->string('image_url')->nullable();    // URL de l'image uploadée
            $table->boolean('etat')->default(1);        // État (1 = actif, 0 = inactif)
            $table->timestamps();                       // created_at et updated_at
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
