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
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('status');
            // Adding is_active column to reviews table with default value true
            // This column will be used to determine if a review is active or not
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('is_active');
            // Dropping is_active column from reviews table
            // This will remove the column if the migration is rolled back
        });
    }
};
