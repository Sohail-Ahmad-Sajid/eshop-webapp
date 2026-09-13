<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
        public function registerForm(){
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
           
        ]);
        if($request->password == $request->confirmpassword){
        User::create([
            'name' => $request->name,
            'email' =>$request->email,
            'password'=> Hash::make( $request->password),
            'role'=>'customer',
        ]);

        return redirect('/login')->with('success', 'Account Created Successfully');
        }
        else{
            return back()->with('error','password and confirm password are not same');
        }
    }

    public function loginForm(){
        return view('auth.login');
    }
    public function login(Request $request){
        $request->validate(['email'=>'required|email',
        'password'=>'required']);
        $user = User::where('email',$request->email)->first();
        if($user && Hash::check($request->password, $user->password)){
            session(['user_id'=>$user->id, 'user_name'=>$user->name, 'role'=>$user->role]);
            return redirect($user->role=='admin' ? '/admin/dashboard' : '/shop');
        }
        return back()->with('error','Invalid credentials');

    }
    public function logout(){
        session()->flush();
        return redirect('/login');
    }
}
