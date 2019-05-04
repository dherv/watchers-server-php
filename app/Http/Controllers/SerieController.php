<?php

namespace App\Http\Controllers;

use App\Serie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SerieController extends Controller
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
        $query = Serie::whereBetween('release_date', [$fromDate->toDateTimeString(), $toDate->toDateTimeString()]);

        $data = Cache::remember(
            'series' . $sort . $order . $page,
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
     * @param  \App\Serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function show(Serie $serie)
    {
        return response()->json($serie);
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
