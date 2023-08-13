<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\StockMail;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Contracts\Mail\Mailer;

class StockController extends Controller
{
    protected $http;
    protected $mailer;

    public function __construct(HttpClient $http, Mailer $mailer)
    {
        $this->http = $http;
        $this->mailer = $mailer;
    }

    public function index()
    {
        $response = $this->http->get('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json');
        $companies = $response->json();
        
        return view('welcome', compact('companies'));
    }

    public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'company' => 'required',
            'start_date' => 'required|date|before_or_equal:end_date|before_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:today',
            'email' => 'required|email',
        ]);

        $company = explode(' - ', $validatedData['company']);
        $startDate = $validatedData['start_date'];
        $endDate = $validatedData['end_date'];

        // Fetch historical data from the API
        $response = $this->http->withHeaders([
            'X-RapidAPI-Key' => env('RAPIDAPI_KEY'),
            'X-RapidAPI-Host' => 'yh-finance.p.rapidapi.com',
        ])->get('https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data', [
            'symbol' => $company[0],
            'region' => 'US',
        ]);

        $data = $response->json();

        $this->mailer->to($request->email)->send(new StockMail($request->company, $startDate, $endDate));

        return view('results', compact('company', 'startDate', 'endDate', 'data'));
    }
}
