@extends('beautymail::templates.shafey')

@section('content')

    @include ('beautymail::templates.shafey.heading' , [
            'heading' => 'Verify  Email',
            'level' => 'h1',
        ])

    @include('beautymail::templates.shafey.contentStart')

    <p> Dear {{ $patient->first_name }} {{ $patient->last_name }},</p>
    <p>Please click below to confirm your email.<span
            style="color: #999;"> or copy and paste this link in your browser:</span></p>

    <a href="#" style="color: #666; text-decoration: none;">
        {{ URL::to('patient/verifyemail', array($token)) }}
    </a>

    @include('beautymail::templates.shafey.contentEnd')

    @include('beautymail::templates.shafey.button', [
    'title' => 'Confirm Email',
    'link' =>  URL::to('patient/verifyemail', array($token))
    ])

@stop




