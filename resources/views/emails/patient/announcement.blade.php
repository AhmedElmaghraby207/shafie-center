@extends('beautymail::templates.shafey')

@section('content')

    @include ('beautymail::templates.shafey.heading' , [
            'heading' => 'Announcement',
            'level' => 'h1',
        ])

    @include('beautymail::templates.shafey.contentStart')

    <p> Dear {{ $patient->first_name }} {{ $patient->last_name }},</p>
    <p>{{ $announcement->message }}</p>

    @include('beautymail::templates.shafey.contentEnd')

@stop




