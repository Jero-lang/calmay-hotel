<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Show one representative room per type (the 4 main types first)
        $featuredRooms = Room::where('is_available', true)
            ->orderByRaw("FIELD(type, 'single_bed','double_bed','king_bed','majesty_bed')")
            ->get()
            ->unique('type')
            ->values();

        $totalRooms = Room::count();
        
        $amenities = [
            ['icon' => 'fa-wifi', 'name' => 'Free WiFi', 'description' => 'High-speed internet throughout the hotel'],
            ['icon' => 'fa-swimming-pool', 'name' => 'Swimming Pool', 'description' => 'Outdoor pool with river view'],
            ['icon' => 'fa-spa', 'name' => 'Spa & Wellness', 'description' => 'Relaxing spa treatments available'],
            ['icon' => 'fa-utensils', 'name' => 'Restaurant', 'description' => 'Fine dining with local cuisine'],
            ['icon' => 'fa-parking', 'name' => 'Free Parking', 'description' => 'Secure parking for guests'],
            ['icon' => 'fa-concierge-bell', 'name' => '24/7 Concierge', 'description' => 'Round-the-clock guest service'],
        ];
        
        return view('home', compact('featuredRooms', 'totalRooms', 'amenities'));
    }

    public function faq()
    {
        return view('faq');
    }
}