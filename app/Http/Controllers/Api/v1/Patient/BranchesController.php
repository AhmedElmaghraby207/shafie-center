<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Branch;
use App\Transformers\BranchTransformer;
use Illuminate\Http\Request;
use Spatie\Fractal\Facades\Fractal;

class BranchesController extends PatientApiController
{

    function __construct(Request $request)
    {
        parent::__construct();
    }

    public function list(Request $request)
    {
        $branches = Branch::all();

        $branches = Fractal::collection($branches)
            ->transformWith(new BranchTransformer([
                'id', 'name', 'phone', 'address', 'location', 'location_url'
            ]))
            ->withResourceName('')
            ->parseIncludes([])->toArray();

        return response()->json($branches);

    }
}
