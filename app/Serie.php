<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = ['title',
        'api_id',
        'rating',
        'release_date',
        'image_path'];
}
