<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Election;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // Get upcoming public volunteer events
        $upcomingEvents = Event::where('visibility', 'public')
            ->where('end_date', '>=', $now)
            ->orderBy('start_date')
            ->take(5)
            ->get();

        // Get the user's upcoming shifts
        $upcomingShifts = $user->shifts()
            ->with('event')
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->get()
            ->groupBy('event.name');

        // Get active elections (nomination or voting period)
        $activeElections = Election::where(function ($query) use ($now) {
            $query->where(function ($q) use ($now) {
                // Elections in nomination period
                $q->where('nomination_start_date', '<=', $now)
                  ->where('nomination_end_date', '>=', $now);
            })->orWhere(function ($q) use ($now) {
                // Elections in voting period
                $q->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now);
            });
        })->get();

        // Convert markdown to HTML using Parsedown for dashboard elections
        // For dashboard, only show content until first line break
        $parsedown = new \Parsedown();
        foreach ($activeElections as $election) {
            $firstParagraph = explode("\n\n", $election->description)[0];
            $firstLine = explode("\n", $firstParagraph)[0];
            $election->parsedDescription = $parsedown->text($firstLine);
        }

        return view('dashboard', compact('upcomingEvents', 'upcomingShifts', 'activeElections'));
    }
}
