<?php

namespace App\Http\Controllers\Auth;



use App\Mail\registrationConfirmation;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $initialsLastname = substr($data['lastname'], 0, 2);
        $initialsFirstname = substr($data['firstname'], 0, 2);

        return User::create([
            'lastname' => $data['lastname'],
            'firstname' => $data['firstname'],
            'username' => $data['username'],
            'initials' => $initialsLastname . $initialsFirstname,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'email_token' => str_random(10),
        ]);
    }

    public function verify($token)
    {
        // The verified method has been added to the user model and chained here
        // for better readability
        User::where('email_token', $token)->firstOrFail()->verified();
        Session::flash('messageSuccessRegistration', 'You are now well registered');
        return redirect('/login');

    }

    public function fillDolibarr(){
        $username=Input::get('username');
        $userDolibarr=DB::table('llx_user')->where('login', '=', $username)->first();

        if($userDolibarr==null){
            Session::flash('dolibarr', null);
            Session::flash('messageFillDolibarrError', "This username doesn't match any login from Dolibarr");
            return view('auth.register');
        }else{
            $lastname=$userDolibarr->lastname;
            $firstname=$userDolibarr->firstname;
            $email=$userDolibarr->email;
            Session::flash('dolibarr', true);
            return view('auth.register', compact( 'username', 'lastname', 'firstname', 'email'));

        }
    }

    public function register(Request $request)
    {
        // Laravel validation
        $validator = $this->validator($request->all());
        if ($validator->fails())
        {
            $this->throwValidationException($request, $validator);
        }

        DB::beginTransaction();
        try
        {
            $user = $this->create($request->all());

            // After creating the user send an email with the random token generated in the create method above
//            $user->notify(new registrationConfirmation($data));

            $email = new registrationConfirmation(new User(['email_token' => $user->email_token, 'username' => $user->username]));
            Mail::to($user->email)->send($email);
            DB::commit();
            Session::flash('messageRegistration', 'We have sent you a verification email !');
            return redirect('/login');
        }
        catch(Exception $e)
        {
            DB::rollback();
            return back();
        }
    }
}