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
        Schema::table('users', function (Blueprint $table) {
          // Add 'administrator_id' column
          if (!Schema::hasColumn('users', 'administrator_id')) {
            $table->unsignedBigInteger('administrator_id')->nullable();
            $table->foreign('administrator_id')->references('id')->on('administrators')->onDelete('cascade');
        }

        // Add foreign key constraint

                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key constraint
        $table->dropForeign(['administrator_id']);

        // Drop the 'administrator_id' column
        $table->dropColumn('administrator_id');
        });
    }
};
