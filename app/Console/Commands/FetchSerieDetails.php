<?php

namespace App\Console\Commands;

use App\Serie;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchSerieDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serie:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch details of each serie';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $series = Serie::all();

        foreach ($series as $serie) {
            $id = $serie->id;
            $key = env('TMDB_KEY');
            $serie = $client->request('GET', "https://api.themoviedb.org/3/tv/{$serie->api_id}?api_key={$key}&language=en-US", [
                'delay' => 250,
            ]);
            $serie = json_decode($serie->getBody(), true);
            $update = Serie::find($id);
            $update->release_date = $serie['last_air_date'];
            $update->save();

        }

    }
}
