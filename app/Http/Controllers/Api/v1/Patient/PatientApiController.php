<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Http\Controllers\Api\v1\ApiController as BaseController;

abstract class PatientApiController extends BaseController
{
    function __construct()
    {
        $this->middleware('user.auth', ['except' => [
            'login', 'signup',
            'forgotPassword',
            'setDevice',
            'resendEmailVerification',
            'signupWithFacebook',
            'signupWithGoogle',
            'signupWithApple'
        ]]);
        $this->middleware('request.log', ['except' => [
        ]]);
    }
}
