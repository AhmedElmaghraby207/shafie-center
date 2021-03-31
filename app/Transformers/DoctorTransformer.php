<?php

namespace App\Transformers;

use Illuminate\Support\Arr;
use League\Fractal;

class DoctorTransformer extends Fractal\TransformerAbstract
{
    protected $fields;
    protected $availableIncludes = [];

    public function __construct($fields = null)
    {
        $this->fields = $fields;
    }

    public function transform($item)
    {
        $res = [
            'id' => $item['id'],
            'name' => $item['name'],
            'email' => $item['email'],
            'phone' => $item['phone'],
            'image' => $item['image'],
            'signature' => $item['signature'],
            'about' => $item['about'],
            'clinic_name' => $item['clinic_name'],
            'facebook' => $item['facebook'],
            'instagram' => $item['instagram'],
            'twitter' => $item['twitter'],
            'youtube' => $item['youtube'],
            'website' => $item['website'],
        ];

        if ($this->fields != null) {
            return Arr::only($res, $this->fields);
        }
        return $res;
    }
}
