<?php

namespace App\Http\Controllers\Partido;

use App\Http\Controllers\ApiController;
use App\Partido;

class PartidoController extends ApiController
{
    public function index()
    {
        return $this->showAll(Partido::all());
    }

    public function update()
    {
        $match = Partido::where('id', 1)->firstOrFail();
        $match->update([
            'team_one'      => request('team_one'),
            'team_two'      => request('team_two'),
            'time'          => request('time'),
            'start_time'    => request('start_time'),
        ]);

        return $this->showOne($match);
    }

    public function show(Partido $partido) {
        return $this->showOne($partido);
    }

    public function saveImage() {
        $rules = [
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

        $path = public_path().'/img/partidos/' . $jpg_url;

        file_put_contents($path, $file);

        $response = array(
            'status' => 'success',
        );
        return $this->showOne( $response  );
    }
}
