<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('order')->paginate(12);

        return view('portfolio.index', compact('portfolios'));
    }

    public function show(Portfolio $portfolio)
    {
        $related = Portfolio::where('id', '!=', $portfolio->id)
            ->orderBy('order')
            ->take(3)
            ->get();

        return view('portfolio.show', compact('portfolio', 'related'));
    }
}
