<?php

namespace App\Http\Controllers;

use App\Serie;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = new Client();
        // TODO: the cron job should be separate in two because tmdb seems to limit the number of request
        // 1. every day or week 2.one number 1 start every 5 min call each page with the algorithm below
        $response = $client->request('GET', env('TMDB_SERIES'));
        $series = json_decode($response->getBody(), true)['results'];
        foreach ($series as $serie) {
            $key = env('TMDB_KEY');
            $serie = $client->request('GET', "https://api.themoviedb.org/3/tv/{$serie['id']}?api_key={$key}&language=en-US");
            $serie = json_decode($serie->getBody(), true);
            Serie::firstOrCreate(
                ['api_id' => $serie['id']],
                [
                    'api_id' => $serie['id'],
                    'title' => $serie['name'],
                    'rating' => $serie['vote_average'],
                    'release_date' => $serie['last_air_date'],
                    'image_path' => "https://image.tmdb.org/t/p/@size" . $serie['backdrop_path'],
                ]);
        }

        $responseMovies = Serie::all();
        return response()->json($responseMovies);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function show(Serie $serie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function edit(Serie $serie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Serie $serie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Serie $serie)
    {
        //
    }
}
