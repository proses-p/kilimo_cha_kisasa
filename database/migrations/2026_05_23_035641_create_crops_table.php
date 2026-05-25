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
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')
                  ->constrained('farms')
                  ->onDelete('cascade');
            $table->string('crop_name', 100);
            $table->date('planting_date');
            $table->date('harvest_date')->nullable();
            $table->enum('status', [
                'planted',
                'growing',
                'harvested',
                'failed'
            ])->default('planted');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crops');
    }
};
