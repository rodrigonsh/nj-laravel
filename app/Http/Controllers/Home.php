<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Edicao;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Home extends Controller
{

    public function index()
    {
        return 'oie';
    }

}

