@extends('layouts.anonymous_master')

@section('content')

    <div class="login-branding">
        <a href="#"><img src="/assets/images/logo-full.png" alt="logo"></a>
    </div>




    <div class="login-container" id="login">

        <h3 class="intro-text text-center">FORGOT PASSWORD</h3>

        <p class="input-instruction">Enter your email address and we'll send you a link to reset your password.</p>
        {!! Form::open(['url' => 'photographer/forgotpassword', 'method' => 'post', 'role' => 'form']) !!}

        <hr/>
        <br/>


        @if(session()->has('error'))
            @include('partials/alert_message', ['type' => 'danger', 'message' => session('error')])
        @endif
        @if(session()->has('success'))
            @include('partials/alert_message', ['type' => 'success', 'message' => session('success')])
        @endif

        <input type="email" name="email" value="{{ old('email') }}" required class="form-control floatlabel"
               placeholder="Enter Email">

        <button type="submit" id="" class="btn btn-primary ">Send</button>

    {!! Form::close() !!}
    <!-- END LOGIN FORM -->


    </div>

@stop
