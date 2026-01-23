<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Opportunite;
use App\Models\Bachelier;
use App\Models\Partenaire;
use App\Models\Article;

class LandingController extends Controller
{
    public function index()
    {
        // Get some statistics for the landing page
        $stats = [
            'bacheliers_count' => Bachelier::count(),
            'opportunites_count' => Opportunite::published()->count(),
            'partenaires_count' => Partenaire::verifies()->count(),
            'satisfaction_rate' => 95 // This could be calculated from actual data
        ];

        // Get featured opportunities
        $featured_opportunities = Opportunite::published()
            ->latest()
            ->take(6)
            ->get();

        // Get verified partners for display
        $featured_partners = Partenaire::verifies()
            ->latest()
            ->take(8)
            ->get();

        // Get featured articles for the news section
        $featured_articles = Article::published()
            ->latest('date_publication')
            ->take(3)
            ->get();

        return view('pages.landing', compact('stats', 'featured_opportunities', 'featured_partners', 'featured_articles'));
    }
} 