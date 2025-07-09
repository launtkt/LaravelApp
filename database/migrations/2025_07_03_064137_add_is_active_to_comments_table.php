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
        Schema::table('comments', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('status');
            // Adding is_active column to comments table with default value true
            // This column will be used to determine if a comment is active or not
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('is_active');
            // Dropping is_active column from comments table
            // This will remove the column if the migration is rolled back
        });
    }
};
