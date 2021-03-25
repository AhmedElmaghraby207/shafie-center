@extends('emails.templates.main')

@section('content')

    @include ('emails.templates.partials.heading', [
            'heading' => $announcement->subject,
            'level' => 'h1',
        ])

    @include('emails.templates.partials.contentStart')

    <p> Dear {{ $patient->first_name }} {{ $patient->last_name }},</p>
    <p>{{ $announcement->message }}</p>

    @include('emails.templates.partials.contentEnd')

@stop
