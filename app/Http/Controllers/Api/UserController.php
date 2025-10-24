<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 

class UserController extends Controller
{
   
    public function show()
    {
      
        $user = User::find(1);

       
        return response()->json($user);
    }
}