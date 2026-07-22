<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'name',
        'description',
        'type',
        'price_per_night',
        'capacity',
        'is_available',
        'amenities',
        'image'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'is_available' => 'boolean',
        'amenities' => 'array',
    ];

    public static function getRoomTypes()
    {
        return [
            'single_bed'      => 'Single Bed',
            'double_bed'      => 'Double Bed',
            'king_bed'        => 'King Bed',
            'majesty_bed'     => 'Majesty Bed',
            'twin_beds'       => 'Twin Beds',
            'queen_bed'       => 'Queen Bed',
            'two_beds'        => 'Two Beds',
            'family_room'     => 'Family Room',
            'suite'           => 'Suite',
            'river_view_suite' => 'River View Suite',
        ];
    }

    public function getTypeLabelAttribute()
    {
        return self::getRoomTypes()[$this->type] ?? $this->type;
    }

    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price_per_night, 2);
    }

    public function getImageUrlAttribute()
    {
        // 1. Per-room image stored via storage (uploaded through admin)
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        // 2. Static image placed in public/images/rooms/{type}.jpg
        $typeImage = public_path('images/rooms/' . $this->type . '.jpg');
        if (file_exists($typeImage)) {
            return asset('images/rooms/' . $this->type . '.jpg');
        }
        // 3. Generic fallback
        $default = public_path('images/rooms/default.jpg');
        if (file_exists($default)) {
            return asset('images/rooms/default.jpg');
        }
        // 4. Absolute last resort — a grey placeholder via UI Avatars
        return 'https://placehold.co/600x400/1a1a2e/4fc3f7?text=' . urlencode($this->name);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}