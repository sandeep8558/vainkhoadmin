<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    public function administrator(){
        return view("administrator.administrator");
    }
}
