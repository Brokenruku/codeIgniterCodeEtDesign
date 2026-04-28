<?php

namespace App\Controllers;

class loginController extends BaseController
{
    public function index(): string
    {
        return view('login');
    }
}
