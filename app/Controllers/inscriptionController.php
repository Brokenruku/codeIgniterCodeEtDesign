<?php

namespace App\Controllers;

class inscriptionController extends BaseController
{
    public function index(): string
    {
        return view('inscription');
    }
}
