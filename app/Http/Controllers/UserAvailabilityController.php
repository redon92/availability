<?php

namespace App\Http\Controllers;

use App\Models\UserAvailability;
use Illuminate\Http\Request;

class UserAvailabilityController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'date' => 'required|date',
            'is_available' => 'required|boolean',
        ]);

        $userAvailability = UserAvailability::firstOrNew([
            'user_id' => $request->user_id,
            'date' => $request->date,
        ]);
        $userAvailability->is_available = $request->is_available;
        $userAvailability->save();

        return response()->json([
            'message' => 'User availability stored successfully',
            'user_availability' => $userAvailability
        ]);
    }

    public function updateAll(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|numeric',
            'is_available' => 'required|boolean',
        ]);

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth()->addMonth();

        for ($date = $startDate; $date <= $endDate; $date = $date->addDay()) {
            $userAvailability = UserAvailability::firstOrNew([
                'user_id' => $validatedData['user_id'],
                'date' => $date->toDateString(),
            ]);
            $userAvailability->is_available = $validatedData['is_available'];
            $userAvailability->save();
        }

        return response()->json([
            'message' => 'User availability updated for the current month'
        ]);
    }

    public function getAvailability(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $currentDate = new \DateTime($validatedData['date']);

//        it gets the availability of the 2 previous months and the 2 next months:
        $startDate = (new \DateTime())->setDate($currentDate->format('Y'), $currentDate->format('m'), 1)->modify('-2 months')->format('Y-m-d');
        $endDate = (new \DateTime())->setDate($currentDate->format('Y'), $currentDate->format('m'), $currentDate->format('t'))->modify('+2 months')->format('Y-m-d');

        $userAvailability = UserAvailability::where('user_id', $validatedData['user_id'])
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return $item->is_available;
            });

        return response()->json([
            'message' => 'User availability fetched successfully',
            'availability' => $userAvailability
        ]);
    }
}
