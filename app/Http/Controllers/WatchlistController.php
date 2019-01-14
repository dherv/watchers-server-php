<?php

namespace App\Http\Controllers;

use App\Movie;
use App\User;
use App\Watchlist;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: try hasManyThrough or some eloquent method on User to get the movies straight away
        if (auth()->check()) {
            $user = auth()->user();
            $watchlist = $user->watchlist;
            $watchlist = $watchlist->map(function ($item) {
                return $item->movie;
            });

            return response()->json($watchlist);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Movie $movie)
    {
//TODO: need to build an authentication process with passport as the api cant store the current logged user
        if (auth()->check()) {
            Watchlist::firstOrCreate(['user_id' => auth()->user()->id, 'movie_id' => $movie->id], [
                'user_id' => auth()->user()->id,
                'movie_id' => $movie->id,
            ]);
            return response()->json(['message' => "movie added to your watchlist"], 204);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Watchlist  $watchlist
     * @return \Illuminate\Http\Response
     */
    public function show(Watchlist $watchlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Watchlist  $watchlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Watchlist $watchlist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Watchlist  $watchlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Watchlist $watchlist)
    {
        //
    }
}
