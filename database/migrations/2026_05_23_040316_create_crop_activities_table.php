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
        Schema::create('crop_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_id')
                  ->constrained('crops')
                  ->onDelete('cascade');
            $table->enum('activity_type', [
                'watering',
                'fertilizing',
                'weeding',
                'spraying',
                'pruning'            
            ]);
            $table->text('description')->nullable();
            $table->date('activity_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_activities');
    }
};
