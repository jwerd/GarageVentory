<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Item;

class WeeklyRevenueCheck extends Controller
{
    private $weeklyThreshold = 300; // this will change

    public function __invoke(Request $request)
    {
        // Get the sum total of all items for the week
        $sum = Item::where('available', false)
                ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->sum('price_sold');

        // Create flag for checking if the weekly threshold has been met
        $metThreshold = ($sum >= $this->weeklyThreshold) ? true : false;

        // Math
        $percent = $sum / $this->weeklyThreshold * 100;
        
        // Return data to user
        return response()->json([
            'max_total'    => (int) $this->weeklyThreshold,
            'total'        => $sum,
            'metThreshold' => $metThreshold, 
            'percent'      => $percent,
        ]);
    }
}