<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        return "Hello World";
    }
}
