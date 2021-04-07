<?php

namespace App\Transformers;

use Illuminate\Support\Arr;
use League\Fractal;

class BranchTransformer extends Fractal\TransformerAbstract
{
    protected $lang;
    protected $fields;

    public function __construct($lang = 'en', $fields = null)
    {
        $this->lang = $lang;
        $this->fields = $fields;
    }

    public function transform($item)
    {
        $res = [
            'id' => $item['id'],
            'name' => $this->lang == 'en' ? $item['name_en'] : $item['name_ar'],
            'phone' => $item['phone'],
            'address' => $this->lang == 'en' ? $item['address_en'] : $item['address_ar'],
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
