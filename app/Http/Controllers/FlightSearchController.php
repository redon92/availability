<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FlightSearchController extends Controller
{
    //

    public function index()
    {
        return view('flight');
    }

    public function searchFlights(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'origin' => 'string|nullable',
            'destination' => 'string|nullable'
        ]);

        $origin = 'MXP';
        $destination = 'VIE';
        if (isset($request->origin)){
            $origin = $request->origin;
        }
        if (isset($request->destination)){
            $destination = $request->destination;
        }

        $date = $request->input('date');
        $client = new Client();
        try {
            $response = $client->get("https://www.ryanair.com/api/booking/v4/en-en/availability", [
                'query' => [
                    'ADT' => 1,
                    'CHD' => 0,
                    'DateIn' => '',
                    'DateOut' => $date,
                    'Origin' => $origin,
                    'Destination' => $destination,
                    'Disc' => 0,
                    'INF' => 0,
                    'TEEN' => 0,
                    'ToUs' => 'AGREED',
                    'IncludeConnectingFlights' => 'false',
                    'RoundTrip' => 'false'
                ]
            ]);
            $data = json_decode($response->getBody(), true);
        }
        catch (BadResponseException $e) {
            return $e->getResponse()->getBody()->getContents();
        }

        $flights = []; // Extract flights from the API response

        if (isset($data['trips']) && count($data['trips']) > 0) {
            $trips = $data['trips'][0]['dates'][0]['flights'];
            foreach ($trips as $trip) {
                $flights[] = [
                    'farePrice' => $trip['regularFare']['fares'][0]['amount'],
                    'departureTime' => $trip['time'][0],
                    'arrivalTime' => $trip['time'][1],
                    'duration' => $trip['duration'],
                    'flightNumber' => $trip['flightNumber'],
                ];
            }
        }

        return response()->json($flights);

    }
}
