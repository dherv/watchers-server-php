<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = ['title',
        'api_id',
        'rating',
        'release_date',
        'description',
        'poster_path',
        'backdrop_path'];
}
