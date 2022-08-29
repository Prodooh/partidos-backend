<?php

namespace App\Http\Controllers\PublicController;

use App\Http\Controllers\Controller;
use App\Partido;

class PartidoPublicController extends Controller
{
    public function show()
    {
        return Partido::findOrFail(1);
    }
}
