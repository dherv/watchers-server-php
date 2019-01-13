<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['title',
        'api_id',
        'rating',
        'release_date',
        'description',
        'poster_path',
        'backdrop_path',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->using('App\Watchlist');
    }
}
