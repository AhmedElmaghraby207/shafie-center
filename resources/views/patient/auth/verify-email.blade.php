@extends('layouts.public_empty_master')
@section('title')Email Confirmation

@stop
@section('content')

    <div class="card">
        <div class="card-body">

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-body printableArea">
                            <h3><b>Email Confirmation </b> <span class="pull-right"></span></h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 text-center">

                                    @if(isset($error))
                                        <p>{{ $error}}</p>
                                        <img src='{{ url("/images/failure.png")}}' alt="Failure"/>
                                    @endif
                                    @if(isset($success))
                                        <p>{{ $success}}</p>

                                        <img src='{{ url("/images/success.png")}}' alt="Success"/>
                                    @endif


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@stop
