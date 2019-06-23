<?php

namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sort = request()->query('sort');
        $order = request()->query('order');
        $page = (int)request()->query('page');

        $per_page = 23;
        $fromDate = new Carbon('last month');
        $toDate = new Carbon('next month');

        // Laravel does not play nicely with orderBy() and paginate() along Cache. 
        // Each page will be cached and sorting will happen inside each page instead of the whole set.
        // the number per page is fixed. so use offset and limit to do the pagination
        $query = Movie::whereBetween('release_date', [$fromDate->toDateTimeString(), $toDate->toDateTimeString()]);

        $data = Cache::remember(
            'movies' . $sort . $order . $page,
            1,
            function () use ($fromDate, $toDate, $sort, $order, $page, $query, $per_page) {
                $count = $query->count();
                $data = $query->orderBy($sort, $order)
                    ->skip(($page - 1) * $per_page)
                    ->take($per_page)
                    ->get();

                $current_page = $page;
                $previous_page = $page - 1;
                $next_page = $page + 1;
                return compact('data', 'count', 'current_page', 'previous_page', 'next_page');
            }
        );

        return response()->json($data);
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
