<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Mail\websitemail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function dashboard_user()
    {
        return view('dashboard_user');
    }

    public function dashboard_admin()
    {
        return view('dashboard_admin');
    }

    public function settings()
    {
        return view('settings');
    }

    public function login()
    {
        return view('login');
    }

    public function login_submit(Request $request)
    {
        $credentials = [
            'email'=>$request->email,
            'password'=>$request->password,
            'status'=>'Active',
        ];
        if(Auth::attempt($credentials)){

            if(Auth::guard('web')->user()->role == 1){
                return redirect()->route('dashboard_admin');
            }else {
                return redirect()->route('dashboard_user');
            }
            
        }else{
            return redirect()->route('login');
        }

    }

    public function registration()
    {
        return view('registration');
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        return redirect()->route('login');
    }

    public function forget_password()
    {
        return view('forget_password');
    }

    public function forget_password_submit(Request $request)
    {        
        // echo $request->email ;
        $token = hash('sha256',time());

        $user = User::where('email',$request->email)->first();
        if(!$user){
            dd('em n f');
        }

        $user->token = $token;
        $user->update();

        $reset_link = url('reset-password/'.$token.'/'.$request->email);
        $subject = 'Reset Password';
        $message = 'Please click on the following link: <br><a href="'.$reset_link.'">Click here</a>';

        \Mail::to($request->email)->send(new Websitemail($subject,$message));

        echo 'Check your Email';

    }

    public function reset_password($token,$email)
    {
        $user = User::where('token',$token)->where('email',$email)->first();
        if(!$user){
           return redirect()->route('login') ;
        }

        return view('reset_password', compact('token','email'));
    }

    public function reset_password_submit(Request $request)
    {
        // echo $request->token;
        $user = User::where('token',$request->token)->where('email',$request->email)->first();

        $user->token = '';
        $user->password = Hash::make($request->new_password);
        $user->update();

        echo 'Password is reset';
    }

    public function registration_submit(Request $request)
    {
        // echo $request->name;
        $token = hash('sha256',time());

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->status = 'Pending';
        $user->role = 2;
        $user->token = $token;

        $user->save();

        $verification_link = url('registration/verify/'.$token.'/'.$request->email);
        $subject = 'Registration Confirmation';
        $message = 'Please click on this link: <br><a href="'.$verification_link.'">Click here</a>';

        \Mail::to($request->email)->send(new Websitemail($subject,$message));

        echo 'Email id sent';

    }
    
    public function registration_verify($token, $email)
    {
        $user = User::where('token',$token)->where('email',$email)->first();
        if(!$user)
        {
            return redirect()->route('login');
        }

        $user->status = 'Active';
        $user->token = '';
        $user->update();

        echo 'Registration verification success';
    }
}
