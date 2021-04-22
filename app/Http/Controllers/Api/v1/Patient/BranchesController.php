<?php

namespace App\Http\Controllers\Api\v1\Patient;

use App\Branch;
use App\Transformers\BranchTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractal\Facades\Fractal;

class BranchesController extends PatientApiController
{
    protected $lang;

    function __construct(Request $request)
    {
        parent::__construct();
        $this->lang = $request->header('x-lang-code');
    }

    public function branchesList(Request $request)
    {
        $branches = Branch::query();

        $keyword = $request->keyword;
        if ($keyword) {
            $branches = $branches->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('address', 'like', '%' . $keyword . '%');
        }
        $branches = $branches->get();

        $branches = Fractal::collection($branches)
            ->transformWith(new BranchTransformer($this->lang, [
                'id', 'name', 'phone', 'address', 'location', 'location_url', 'image'
            ]))
            ->withResourceName('')
            ->parseIncludes([])->toArray();

        return response()->json($branches);

    }

    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "branch_id" => "required"
        ]);
        if ($validator->fails())
            return self::errify(400, ['validator' => $validator]);

        $branch = Branch::find($request->branch_id);

        if (!$branch)
            return response()->json(['error' => 'branch not found']);

        $branch = Fractal::item($branch)
            ->transformWith(new BranchTransformer([
                'id', 'name', 'phone', 'address', 'location', 'location_url'
            ]))
            ->withResourceName('')
            ->parseIncludes([])->toArray();

        return response()->json($branch);
    }
}
