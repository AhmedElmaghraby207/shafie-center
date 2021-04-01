<?php

namespace App\Transformers;

use Illuminate\Support\Arr;
use League\Fractal;

class BranchTransformer extends Fractal\TransformerAbstract
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
            'name' => $item['name'],
            'phone' => $item['phone'],
            'address' => $item['address'],
            'location' => [
                'lat' => $item['lat'],
                'lng' => $item['lng']
            ],
            'location_url' => $item['location_url'],
        ];

        if ($this->fields != null) {
            return Arr::only($res, $this->fields);
        }
        return $res;
    }
}
