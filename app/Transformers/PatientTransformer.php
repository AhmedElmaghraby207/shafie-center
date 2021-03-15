<?php

namespace App\Transformers;

use Illuminate\Support\Arr;
use League\Fractal;

class PatientTransformer extends Fractal\TransformerAbstract
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
            'first_name' => $item['first_name'],
            'last_name' => $item['last_name'],
            'email' => $item['email'],
            'phone' => $item['phone'],
            'is_active' => $item['is_active'] == 1,
            'image' => $item['image'],
            'age' => $item['age'],
            'weight' => $item['weight'],
            'gender' => $item['gender'] == 1 ? "Male" : "Female",
            'address' => $item['address'],
            'facebook_id' => $item['facebook_id'],
            'google_id' => $item['google_id'],
            'apple_id' => $item['apple_id'],
            'mobile_os' => $item['mobile_os'],
            'mobile_model' => $item['mobile_model'],
            'created_at' => $item['created_at'],
            'token' => $item['token'],
        ];

        if ($this->fields != null) {
            return Arr::only($res, $this->fields);
        }
        return $res;
    }
}
