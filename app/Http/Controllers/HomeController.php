<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Podcast;
use App\Models\Live;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $events = Event::where('etat', 1)->orderBy('event_date', 'desc')->get();
        $podcasts = Podcast::where('etat', 1)->orderBy('created_at', 'desc')->get();

        $live = Live::where('is_active', true)
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->first();

        if ($live) {
            \Log::info("Live found: ", ['id' => $live->id, 'start_time' => $live->start_time]);
        } else {
            \Log::info("No active live found");
        }

        $startTimeJs = $live ? $live->start_time->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z') : null;

        return view('home', compact('events', 'podcasts', 'live', 'startTimeJs'));
    }

    public function showPodcasts()
    {
        $podcast = Podcast::where('etat', 1)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('podcasts', compact('podcast'));
    }

    public function abouts()
    {
        return view('abouts');
    }

    public function events()
    {
        $events = Event::where('etat', 1)->orderBy('event_date')->get();
        return view('events', compact('events'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function shows()
    {
        return view('shows');
    }

    public function blogs()
    {
        return view('blogs');
    }

    // public function showLives()
    // {
    //     $lives = Live::where('is_active', true)->latest()->get();
    //     return view('home', compact('lives'));
    // }
}
