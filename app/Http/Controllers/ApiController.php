<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponser;
    public function __construct()
    {
    	// Middleware para un cliente valido en el sistema
    	// $this->middleware('client.credentials');
    	// Middleware para, le mandamos el guard de api, que a la vez esta ligado con passport
    	// Lo desabilitamos ya que estamos protegiendo por un grant type de tipo client_credentials
    	// Grant Type Password
    	$this->middleware('auth:api');
    }
}
