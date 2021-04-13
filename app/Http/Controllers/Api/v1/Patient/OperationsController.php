<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Operation;
use App\Transformers\OperationTransformer;
use Illuminate\Http\Request;
use Spatie\Fractal\Facades\Fractal;

class OperationsController extends PatientApiController
{
    protected $lang;

    function __construct(Request $request)
    {
        parent::__construct();
        $this->lang = $request->header('x-lang-code');
    }

    public function list(Request $request)
    {
        $operations = Operation::all();

        $operations = Fractal::collection($operations)
            ->transformWith(new operationTransformer($this->lang, [
                'id', 'name', 'description'
            ]))
            ->withResourceName('')
            ->parseIncludes([])->toArray();

        return response()->json($operations);

    }
}
