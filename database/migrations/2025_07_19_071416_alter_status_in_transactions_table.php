<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        ALTER TABLE transactions
        MODIFY COLUMN status
        ENUM('pending','processing','completed','cancelled')
        NOT NULL DEFAULT 'pending'
    ");
    }

    public function down(): void
    {
        DB::statement("
        ALTER TABLE transactions
        MODIFY COLUMN status
        ENUM('pending','preprocessing','paid','cancelled')
        NOT NULL DEFAULT 'pending'
    ");
    }
};
