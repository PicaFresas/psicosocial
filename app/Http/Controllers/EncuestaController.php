<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EncuestaController extends Controller
{
    public function create()
    {
        return view('encuestas.create');
    }
}
