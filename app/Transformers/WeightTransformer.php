<?php

namespace App\Transformers;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use League\Fractal;

class WeightTransformer extends Fractal\TransformerAbstract
{
    protected $fields;

    public function __construct($fields = null)
    {
        $this->fields = $fields;
    }

    public function transform($item)
    {
        $res = [
            'id' => $item['id'],
            'weight' => $item['weight'],
            'created_at' => Carbon::parse($item['created_at'])->toDateTimeString(),
        ];

        if ($this->fields != null) {
            return Arr::only($res, $this->fields);
        }
        return $res;
    }
}
