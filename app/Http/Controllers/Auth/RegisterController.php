<?php

namespace App\Http\Controllers\Auth;

use App\Notifications\SendVerificationCode;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notification;

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
    protected $redirectTo = '/home';

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
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verification_code' => Str::random(60),
            'status' => 0
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //$this->guard()->login($user);

        /*return $this->registered($request, $user)
            ?: redirect($this->redirectPath());*/

        return $this->registered($request, $user)
            ?: redirect('/login')->with('message', 'The email confirmation link has been emailed to your address. Please click on the link in the email to verify your account.');
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {

        $user->notify(new SendVerificationCode($user));

    }

    public function verify($code)
    {

        $user = User::whereVerificationCode($code)->first();

        if(!$user)
        {
            return redirect('/login')->with('error', '<strong>Invalid Code</strong>: Your verification code has been expired or invalid. <a href="#">Resend</a> verification code to my email.');
        }

        $user->status = 1;

        $user->verification_code = null;

        if($user->save())
        {
            return redirect('/login')->with('message', 'Email Verification Successful. Please login using your credentials.');
        }

    }

    public function resend()
    {

    }
}
