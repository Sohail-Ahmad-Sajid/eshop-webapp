@extends('layouts.app')
@section('content')
<section class="auth-shell">
    <div class="form-card auth-card">
        <p class="eyebrow">Start shopping</p>
        <h1>Create Account</h1>
        <form method= "post" action="/register">
            @csrf
            <label for="name">Name</label>
            <input id="name" type="text" name='name' placeholder="Enter your name" required>
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="Email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Password" required>

            </div>
            <label for="confirmpassword">Confirm Password</label>
            <input id="confirmpassword" type="password" name="confirmpassword" placeholder="Confirm Password" required>

            <button>Register</button>
        </form>
        <p class="auth-switch">Already have an account? <a href="/login" class="text-link">Login</a></p>
    </div>
</section>
@endsection
