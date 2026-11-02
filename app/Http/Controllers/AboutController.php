<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\TeamMember;
use App\Models\Testimonial;

class AboutController extends Controller
{
    public function index()
    {
        $team = TeamMember::where('is_active', true)->orderBy('order')->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('order')->get();
        $clients = Client::where('is_active', true)->orderBy('order')->get();

        return view('about', compact('team', 'testimonials', 'clients'));
    }
}
