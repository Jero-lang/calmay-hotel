<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL requires redefining the full enum to add new values
        DB::statement("ALTER TABLE rooms MODIFY COLUMN type ENUM(
            'single_bed','double_bed','king_bed','majesty_bed',
            'twin_beds','queen_bed','two_beds','family_room',
            'suite','river_view_suite'
        ) NOT NULL DEFAULT 'single_bed'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE rooms MODIFY COLUMN type ENUM(
            'single_bed','twin_beds','queen_bed',
            'king_bed','two_beds','family_room',
            'suite','river_view_suite'
        ) NOT NULL DEFAULT 'single_bed'");
    }
};
