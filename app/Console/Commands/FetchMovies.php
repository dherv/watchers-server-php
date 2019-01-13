<?php

namespace App\Console\Commands;

use App\Movie;
use App\Serie;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:fetch {type} {--queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all movies to display';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function fetchMovies($movies)
    {
        foreach ($movies as $movie) {
            $image_path = $movie['backdrop_path'] ? "https://image.tmdb.org/t/p/@size" . $movie['backdrop_path'] : null;
            Movie::firstOrCreate(
                ['api_id' => $movie['id']],
                [
                    'api_id' => $movie['id'],
                    'title' => $movie['title'],
                    'rating' => $movie['vote_average'],
                    'release_date' => $movie['release_date'],
                    'image_path' => $image_path,
                ]);
        }
    }

    private function fetchSeries($series)
    {

        foreach ($series as $serie) {
            $image_path = $serie['backdrop_path'] ? "https://image.tmdb.org/t/p/@size" . $serie['backdrop_path'] : null;

            Serie::firstOrCreate(
                ['api_id' => $serie['id']],
                [
                    'api_id' => $serie['id'],
                    'title' => $serie['name'],
                    'rating' => $serie['vote_average'],
                    // 'release_date' => $serie['last_air_date'],
                    'image_path' => "https://image.tmdb.org/t/p/@size" . $serie['backdrop_path'],
                ]);
        }

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $page = 1;
        $type = $this->argument('type');
        $url = $type === 'movie' ? env('TMDB_URL') : env('TMDB_SERIES');
        while ($page < 5) {
            $response = $client->request('GET', $url . $page);
            $results = json_decode($response->getBody(), true)['results'];
            $type === 'movie' ? $this->fetchMovies($results) : $this->fetchSeries($results);
            $page += 1;
        }

    }
}
