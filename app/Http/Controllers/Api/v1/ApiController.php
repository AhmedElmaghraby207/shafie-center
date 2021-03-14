<?php

namespace App\Http\Controllers\Api\v1;

use App\Traits\Response;
use Illuminate\Routing\Controller as BaseController;

abstract class ApiController extends BaseController
{
    use Response;
}
