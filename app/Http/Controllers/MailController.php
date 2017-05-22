<?php

namespace App\Http\Controllers;

use App\Mail\registrationConfirmation;
use App\Mail\resetPasswordConfirmation;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
//    public function resetPasswordProfile()
//    {
//        $user=Auth::user();
//        $lastname=$user->lastname;
//        $firstname=$user->firstname;
//        Mail::to($user)->send(new resetPasswordConfirmation(), ['lastname'=>$lastname, 'firstname'=>$firstname])->subject('reset password confirmation');
//    }
//
//    public function register()
//    {
//        $user=Auth::user();
//        $lastname=$user->lastname;
//        $firstname=$user->firstname;
//        Mail::to($user)->send(new registrationConfirmation(), ['lastname'=>$lastname, 'firstname'=>$firstname])->subject('registration confirmation');
//    }
}
