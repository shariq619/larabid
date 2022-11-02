<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\GlobalEmailJob;
use App\User;
use App\Http\Controllers\Controller;
use App\UserVerify;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;

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
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $this->validator($request->all())->validate();
        $user = $this->create($request->all());


        $token = Str::random(64);

        UserVerify::create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        $admin = User::where('user_type', 'admin')->first();
        $owner = $user->property->seller_email ?? $admin->email;
        $content = 'New user has registered, Below are the details<br><br>';
        $content .= 'Email: ' . $user->email . "<br>";
        $content .= 'Property Selected: ' . $user->property->title ?? '-' . "<br>";

        GlobalEmailJob::dispatch($content, 'New Registration', $admin->email);
        GlobalEmailJob::dispatch($content, 'New Registration', $owner);

        $content_for_user = "Congratulations! You are registered successfully, Below are the details of the property you registered for:<br><br>";
        $content_for_user .= "Bidding property address: " . ($user->property->address ?? "") . "<br>";
        $content_for_user .= "Owner’s Name: " . ($user->property->seller_name ?? '') . "<br>";
        $content_for_user .= "Owner’s cell number: " . ($user->property->seller_phone ?? '') . "<br>";
        $content_for_user .= "Owner email: " . ($user->property->seller_email ?? '') . "<br>";


        GlobalEmailJob::dispatch($content_for_user, 'New Registration', $user->email);

        Mail::send('emails.emailVerificationEmail', ['token' => $token], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Email Verification Mail');
        });
//        event(new Registered($user));
        return response()->json(['success' => true], 200);
//        $this->validator($request->all())->validate();
//dd("sd");
//        if (get_option('enable_recaptcha_registration') == 1) {
//            $this->validate($request, array('g-recaptcha-response' => 'required'));
//
//            $secret = get_option('recaptcha_secret_key');
//            $gRecaptchaResponse = $request->input('g-recaptcha-response');
//            $remoteIp = $request->ip();
//
//            $recaptcha = new \ReCaptcha\ReCaptcha($secret);
//            $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
//            if (!$resp->isSuccess()) {
//                return redirect()->back()->with('error', 'reCAPTCHA is not verified');
//            }
//
//        }
//        $user = $this->create($request->all());
//        event(new Registered();
//        $this->guard()->login($user);

//        return response()->json(['success' => true], 200);
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }


    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';

        if (!is_null($verifyUser)) {

            $user = $verifyUser->user;

            if (!$user->is_email_verified) {
                User::where('id', $user->id)->update(['is_email_verified' => '1']);
                $message = "Your e-mail is verified. You can now login.";

                $admin = User::where('user_type', 'admin')->first();
                $owner = $user->property->seller_email ?? $admin->email;

                $content = 'Below detailed user has verified the email<br><br>';
                $content .= 'Email: ' . $user->email . "<br>";
                $content .= 'Property Selected: ' . $user->property->title ?? '-' . "<br>";

                GlobalEmailJob::dispatch($content, 'Email Verified', $admin->email);
                GlobalEmailJob::dispatch($content, 'Email Verified', $owner);


            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
        }

        return redirect()->route('login')->with('message', $message);
    }


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected
        $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public
    function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected
    function validator(array $data)
    {
        return Validator::make($data, [
//            'name' => 'required|string|max:255',
            'contact' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
//            'password' => 'required|string|min:6|confirmed',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected
    function create(array $data)
    {
        return User::create([
            'name' => "abc",
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => 'broker',
            'active_status' => '1',
            'property_id' => $data['property_id']
        ]);
    }
}
