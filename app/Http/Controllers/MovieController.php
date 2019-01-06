<?php

namespace App\Http\Controllers;

use App\Movie;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = new Client();
        $response_1 = $client->request('GET', env('TMDB_URL_1'));
        $response_2 = $client->request('GET', env('TMDB_URL_2'));
        $response_3 = $client->request('GET', env('TMDB_URL_3'));
        $response_4 = $client->request('GET', env('TMDB_URL_4'));
        $movies_1 = json_decode($response_1->getBody(), true)['results'];
        $movies_2 = json_decode($response_2->getBody(), true)['results'];
        $movies_3 = json_decode($response_3->getBody(), true)['results'];
        $movies_4 = json_decode($response_4->getBody(), true)['results'];
        $movies = array_merge($movies_1, $movies_2, $movies_3, $movies_4);

        foreach ($movies as $movie) {
            Movie::firstOrCreate(
                ['api_id' => $movie['id']],
                [
                    'api_id' => $movie['id'],
                    'title' => $movie['title'],
                    'rating' => $movie['vote_average'],
                    'release_date' => $movie['release_date'],
                    'image_path' => "https://image.tmdb.org/t/p/@size" . $movie['backdrop_path'],
                ]);
        }

        $responseMovies = Movie::all();
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
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        return response()->json($movie);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        //
    }
}
