<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phrase extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'phrase_one', 'phrase_two', 'img', 'sizes', 'date_range'
    ];

    // Cast Data
    protected $casts = [
        'sizes'  =>  'json'
    ];
}
