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

    private function fetchData($datas)
    {
        foreach ($datas as $data) {
            $backdrop_path = $data['backdrop_path'] ? "https://image.tmdb.org/t/p/@size" . $data['backdrop_path'] : null;
            $poster_path = $data['poster_path'] ? "https://image.tmdb.org/t/p/@size" . $data['poster_path'] : null;
            $type = $this->argument('type');
            $title = $type === 'movie' ? 'title' : 'name';
            $release_date = \array_key_exists('release_date', $data) ? $data['release_date'] : null;
            $model = $type === 'movie' ? (Movie::class) : (Serie::class);
            $model::firstOrCreate(
                ['api_id' => $data['id']],
                [
                    'api_id' => $data['id'],
                    'title' => $data[$title],
                    'description' => $data['overview'],
                    'rating' => (int)$data['vote_average'],
                    'release_date' => $release_date,
                    'backdrop_path' => $backdrop_path,
                    'poster_path' => $poster_path,
                ]
            );
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
        $url = $type === 'movie' ? config('cron.url') : config('cron.series');
        while ($page < 5) {
            $response = $client->request('GET', $url . $page);
            $results = json_decode($response->getBody(), true)['results'];
            $this->fetchData($results);
            $page += 1;
        }
    }
}
