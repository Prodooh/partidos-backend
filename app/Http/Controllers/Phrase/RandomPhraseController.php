<?php

namespace App\Http\Controllers\Phrase;

use App\Http\Controllers\Controller;
use App\Phrase;
use Illuminate\Http\Request;

class RandomPhraseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function random()
    {
        $phrase = Phrase::inRandomOrder()->first();
        return response()->json([ 'data' => $phrase ], 200);
    }
}
