<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Mail\StockMail;
use Mail;

class StockController extends Controller
{
    public function index()
    {
        $client = new Client();
        $response = $client->get('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json');

        $companies = json_decode($response->getBody(), true);
        // $companies = [['Company Name' => 'dsdfsdf', 'Symbol' => 'sdasd']];
        return view('welcome', compact('companies'));
    }

    public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'company' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'email' => 'required|email',
        ]);

        $company = explode(' - ', $validatedData['company']);
        $startDate = $validatedData['start_date'];
        $endDate = $validatedData['end_date'];

        // Fetch historical data from the API
        $client = new Client();
        $response = $client->get('https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data', [
            'headers' => [
                'X-RapidAPI-Key' => '8a1a72f3c5mshf5b79c70369b2c9p14201ajsn6b543db643c8',
                'X-RapidAPI-Host' => 'yh-finance.p.rapidapi.com',
            ],
            'query' => [
                'symbol' => $company[0],
                'region' => 'US',
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        
        // Mail::to($request->email)->send(new StockMail($request->company, $startDate, $endDate));

        return view('results', compact('company', 'startDate', 'endDate', 'data'));
    }
}
