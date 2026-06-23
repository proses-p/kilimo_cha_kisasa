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
        Schema::table('crop_activities', function (Blueprint $table) {
            $table->date('scheduled_date')->nullable()->after('activity_date');
            $table->boolean('is_completed')->default(false)->after('scheduled_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crop_activities', function (Blueprint $table) {
            $table->dropColumn('scheduled_date');
        });
    }
};
