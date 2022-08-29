<?php

namespace App\Http\Controllers\Phrase;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Mail\Message;
use App\Phrase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class PhraseController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        // $this->middleware( 'can:get-all-data' )->only( 'index' );
        // $this->middleware( 'can:create-data' )->only( 'store' );
        // $this->middleware( 'can:update-data' )->only( 'update' );
        // $this->middleware( 'can:delete-data' )->only( 'delete' );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $phrases;

        if (auth()->user()->role === 'ADMIN') {
            $phrases = Phrase::orderBy( 'id', 'desc' )->get();
        } else {
            $phrases = auth()->user()->phrases;
        }

        return $this->showAll( Collection::make($phrases) );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Phrase $phrase)
    {
        return $this->showOne($phrase);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $sentence = Phrase::create(
            [
                'user_id' => auth()->user()->id,
                'phrase_one' => request('phrase_one'),
                'phrase_two' => request('phrase_two'),
                'img' => request('img'),
                'sizes' => request('sizes'),
                'date_range' => request('date_range')
            ]
        );

        // Enviar Mail
        Mail::to(['gustavo@prodooh.com', 'fernandobautista@prodooh.com'])->send( new Message($sentence) );

        return response()->json([ 'data' => $sentence ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Phrase  $phrase
     * @return \Illuminate\Http\Response
     */
    public function update(Phrase $phrase)
    {
        $phrase->fill(request()->only(
            'phrase_one',
            'phrase_two',
            'img',
            'sizes',
            'date_range'
        ));

        $phrase->save();

        Mail::to(['gustavo@prodooh.com', 'fernandobautista@prodooh.com'])->send( new Message($phrase) );

        return $this->showOne($phrase);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Phrase  $phrase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Phrase $phrase)
    {
        $phrase->delete();
        return $this->showOne($phrase);
    }


    public function saveImage() {
         $rules = [
             'car'         =>   ['required', 'string'],
             'img'         =>   ['required', 'string'],
             'size'        =>   ['required', 'string'],
        ];
        $this->validate(request(), $rules);

        $image = request('img');
        $image = explode(";", $image)[1];
        $image = explode(",", $image)[1];
        $image = str_replace(" ", "+", $image);

        $file = base64_decode($image);

        $path = '';

        $jpg_url = request('size').".jpg";

        if (auth()->user()->role === 'ADMIN') {
            if (!file_exists(public_path().'/img/'.request('car'))) {
                \File::makeDirectory(public_path().'/img/'.request('car'));
            }
            $path = public_path().'/img/'.request('car').'/'.$jpg_url;

        } else {
            if (!file_exists(public_path().'/img/'. auth()->user()->company . '/' .request('car'))) {
                \File::makeDirectory(public_path().'/img/'. auth()->user()->company . '/' .request('car'));
            }
            $path = public_path().'/img/'. auth()->user()->company . '/' .request('car').'/'.$jpg_url;
        }


        file_put_contents($path, $file);

        $response = array(
            'status' => 'success',
        );
        return $this->showOne( $response  );
    }
}
