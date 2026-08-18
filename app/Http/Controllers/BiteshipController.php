<?php

namespace App\Http\Controllers;

use App\Services\BiteshipService;
use Illuminate\Http\Request;

class BiteshipController extends Controller
{
    protected $biteship;

    public function __construct(BiteshipService $biteship)
    {
        $this->biteship = $biteship;
    }

    public function searchArea(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3'
        ]);

        $areas = $this->biteship->searchArea($request->q);

        return response()->json([
            'success' => true,
            'areas' => $areas
        ]);
    }

    public function getRates(Request $request)
    {
        $request->validate([
            'destination_area_id' => 'required',
            'items' => 'required|array',
        ]);

        // Calculate total weight
        $weight = 0;
        foreach ($request->items as $item) {
            $itemWeight = isset($item['weight']) ? $item['weight'] : 0;
            $itemQty = isset($item['quantity']) ? $item['quantity'] : 1;
            $weight += ($itemWeight * $itemQty);
        }

        // Ensure minimum weight of 100g (or whatever appropriate)
        if ($weight <= 0) $weight = 1000;

        $rates = $this->biteship->getRates(
            $request->destination_area_id,
            $weight,
            $request->items
        );

        if (isset($rates['error'])) {
            return response()->json([
                'success' => false,
                'message' => $rates['error']
            ], 400);
        }

        return response()->json([
            'success' => true,
            'pricing' => $rates
        ]);
    }
}
