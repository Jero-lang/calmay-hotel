<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            // ── Single Bed ──────────────────────────────────────────────
            [
                'room_number'    => '101',
                'name'           => 'Single Bed Room',
                'description'    => 'A cozy single-bed room perfect for solo travelers. Enjoy a comfortable stay with all essential amenities and a relaxing river view.',
                'type'           => 'single_bed',
                'price_per_night' => 1000.00,
                'capacity'       => 1,
                'is_available'   => true,
                'amenities'      => ['Free WiFi', 'Air Conditioning', 'Private Bathroom', 'TV', 'Hot Shower'],
                'image'          => null,
            ],
            [
                'room_number'    => '102',
                'name'           => 'Single Bed Room',
                'description'    => 'A cozy single-bed room perfect for solo travelers. Enjoy a comfortable stay with all essential amenities and a relaxing river view.',
                'type'           => 'single_bed',
                'price_per_night' => 1000.00,
                'capacity'       => 1,
                'is_available'   => true,
                'amenities'      => ['Free WiFi', 'Air Conditioning', 'Private Bathroom', 'TV', 'Hot Shower'],
                'image'          => null,
            ],
            [
                'room_number'    => '103',
                'name'           => 'Single Bed Room',
                'description'    => 'A cozy single-bed room perfect for solo travelers. Enjoy a comfortable stay with all essential amenities and a relaxing river view.',
                'type'           => 'single_bed',
                'price_per_night' => 1000.00,
                'capacity'       => 1,
                'is_available'   => true,
                'amenities'      => ['Free WiFi', 'Air Conditioning', 'Private Bathroom', 'TV', 'Hot Shower'],
                'image'          => null,
            ],

            // ── Double Bed ───────────────────────────────────────────────
            [
                'room_number'    => '201',
                'name'           => 'Double Bed Room',
                'description'    => 'Spacious double-bed room ideal for couples or friends. Features a large comfortable bed, modern furnishings, and a beautiful river-side atmosphere.',
                'type'           => 'double_bed',
                'price_per_night' => 1500.00,
                'capacity'       => 2,
                'is_available'   => true,
                'amenities'      => ['Free WiFi', 'Air Conditioning', 'Private Bathroom', 'TV', 'Hot Shower', 'Mini Fridge'],
                'image'          => null,
            ],
            [
                'room_number'    => '202',
                'name'           => 'Double Bed Room',
                'description'    => 'Spacious double-bed room ideal for couples or friends. Features a large comfortable bed, modern furnishings, and a beautiful river-side atmosphere.',
                'type'           => 'double_bed',
                'price_per_night' => 1500.00,
                'capacity'       => 2,
                'is_available'   => true,
                'amenities'      => ['Free WiFi', 'Air Conditioning', 'Private Bathroom', 'TV', 'Hot Shower', 'Mini Fridge'],
                'image'          => null,
            ],
            [
                'room_number'    => '203',
                'name'           => 'Double Bed Room',
                'description'    => 'Spacious double-bed room ideal for couples or friends. Features a large comfortable bed, modern furnishings, and a beautiful river-side atmosphere.',
                'type'           => 'double_bed',
                'price_per_night' => 1500.00,
                'capacity'       => 2,
                'is_available'   => true,
                'amenities'      => ['Free WiFi', 'Air Conditioning', 'Private Bathroom', 'TV', 'Hot Shower', 'Mini Fridge'],
                'image'          => null,
            ],

            // ── King Bed ─────────────────────────────────────────────────
            [
                'room_number'    => '301',
                'name'           => 'King Bed Room',
                'description'    => 'Premium king-bed room offering extra space and luxury. Perfect for guests who appreciate comfort, featuring a grand king-size bed and premium amenities.',
                'type'           => 'king_bed',
                'price_per_night' => 3000.00,
                'capacity'       => 3,
                'is_available'   => true,
                'amenities'      => ['Free WiFi', 'Air Conditioning', 'Private Bathroom', 'Smart TV', 'Hot Shower', 'Mini Fridge', 'Bathtub', 'Room Service'],
                'image'          => null,
            ],
            [
                'room_number'    => '302',
                'name'           => 'King Bed Room',
                'description'    => 'Premium king-bed room offering extra space and luxury. Perfect for guests who appreciate comfort, featuring a grand king-size bed and premium amenities.',
                'type'           => 'king_bed',
                'price_per_night' => 3000.00,
                'capacity'       => 3,
                'is_available'   => true,
                'amenities'      => ['Free WiFi', 'Air Conditioning', 'Private Bathroom', 'Smart TV', 'Hot Shower', 'Mini Fridge', 'Bathtub', 'Room Service'],
                'image'          => null,
            ],

            // ── Majesty Bed ──────────────────────────────────────────────
            [
                'room_number'    => '401',
                'name'           => 'Majesty Bed Room',
                'description'    => 'The ultimate luxury experience at Calmay River Hotel. The Majesty suite features an oversized premium bed, panoramic river views, and world-class amenities for an unforgettable stay.',
                'type'           => 'majesty_bed',
                'price_per_night' => 4500.00,
                'capacity'       => 4,
                'is_available'   => true,
                'amenities'      => ['Free WiFi', 'Air Conditioning', 'Private Bathroom', 'Smart TV', 'Hot Shower', 'Mini Bar', 'Jacuzzi', 'Room Service', 'Balcony', 'River View', 'Complimentary Breakfast'],
                'image'          => null,
            ],
            [
                'room_number'    => '402',
                'name'           => 'Majesty Bed Room',
                'description'    => 'The ultimate luxury experience at Calmay River Hotel. The Majesty suite features an oversized premium bed, panoramic river views, and world-class amenities for an unforgettable stay.',
                'type'           => 'majesty_bed',
                'price_per_night' => 4500.00,
                'capacity'       => 4,
                'is_available'   => true,
                'amenities'      => ['Free WiFi', 'Air Conditioning', 'Private Bathroom', 'Smart TV', 'Hot Shower', 'Mini Bar', 'Jacuzzi', 'Room Service', 'Balcony', 'River View', 'Complimentary Breakfast'],
                'image'          => null,
            ],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(
                ['room_number' => $room['room_number']],
                $room
            );
        }
    }
}
