<?php

namespace App\Controllers;

class StatController extends BaseController
{
    public function index(): string
    {
        return view('stat');
    }
}
