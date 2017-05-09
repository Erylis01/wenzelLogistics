<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        if (Auth::check()){
            $lastname = Auth::user()->lastname;
            $firstname = Auth::user()->firstname;
            $username=Auth::user()->username;
            $email=Auth::user()->email;
            return view('auth.profile', compact('lastname', 'firstname', 'username', 'email'));
        }
        else{
            return view('/');
        }

    }

    public function update(){

    }
    public function delete(){

    }
}
