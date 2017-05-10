<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the right view.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $id = $user->id;
            $lastname = $user->lastname;
            $firstname = $user->firstname;
            $username = $user->username;
            $email = $user->email;
            return view('auth.profile', compact('lastname', 'firstname', 'username', 'email'));
        } else {
            return view('/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        $user = Auth::user();
$id=$user->id;
        $lastname = Input::get('lastname');
        $firstname = Input::get('firstname');
        $username = Input::get('username');
        $email = Input::get('email');
        $password = Input::get('password');
        $new_password = Input::get('new_password');

        if (isset($password)) {
            // validate
            // read more on validation at http://laravel.com/docs/validation
            $rules = array(
                'lastname' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'required|string|min:6|confirmed',
                'password' => 'hash:' . $user->password,
                'new_password' => 'required|different:password|confirmed|string|min:6'
            );
            $validator = Validator::make(Input::all(), $rules);

            // process the login
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput(Input::except('password'));
            } else {
                // store
                $user = User::find($id);
                $user->password = bcrypt($new_password);
                $user->save();
                // redirect
                session()->flash('messageUpdatePassword', 'Successfully updated password profile!');
                return redirect('/profile');
            }
        } else {
            // validate
            // read more on validation at http://laravel.com/docs/validation
            $rules = array(
                'lastname' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            );
            $validator = Validator::make(Input::all(), $rules);
            // process the login
            if ($validator->fails()) {
                return redirect('/profile')
                    ->withErrors($validator)
                    ->withInput(Input::except('password'));
            } else {
                // store
                $user = User::find($id);
                $user->lastname = $lastname;
                $user->firstname = $firstname;
                $user->username = $username;
                $user->email = $email;
                $user->save();
                // redirect
                session()->flash('messageUpdate', 'Successfully updated profile!');
                return redirect()->back()->with('successMessage', 'lalaa');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy()
    {

        $id = Auth::user()->id;

        // delete
        $user = User::find($id);
        $user->delete();

        // redirect
        session()->flash('messageDelete', 'Successfully deleted the user!');
        return redirect('/login');
    }
}
