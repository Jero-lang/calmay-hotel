<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->string('name');
            $table->text('description');
            $table->enum('type', [
                'single_bed', 'double_bed', 'king_bed', 'majesty_bed',
                'twin_beds', 'queen_bed', 'two_beds', 'family_room',
                'suite', 'river_view_suite'
            ])->default('single_bed');
            $table->decimal('price_per_night', 10, 2);
            $table->integer('capacity');
            $table->boolean('is_available')->default(true);
            $table->json('amenities')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};