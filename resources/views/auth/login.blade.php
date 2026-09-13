@extends('layouts.app')
@section('content')
<section class="auth-shell">
    <div class="form-card auth-card">
        <p class="eyebrow">Welcome back</p>
        <h1>Login</h1>
        <form method= "post" action="/login">
            @csrf
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="Email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Password" required>

            </div>
            <button>Login</button>
        </form>
        <p class="auth-switch">New to E Shop? <a href="/register" class="text-link">Create account</a></p>
    </div>
</section>
@endsection
