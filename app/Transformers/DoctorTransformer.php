<?php

namespace App\Transformers;

use Illuminate\Support\Arr;
use League\Fractal;

class DoctorTransformer extends Fractal\TransformerAbstract
{
    protected $lang;
    protected $fields;
    protected $availableIncludes = [];

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
            'email' => $item['email'],
            'phone' => $item['phone'],
            'image' => $item['image'],
            'signature' => $item['signature'],
            'about' => $this->lang == 'en' ? $item['about_en'] : $item['about_ar'],
            'clinic_name' => $this->lang == 'en' ? $item['clinic_name_en'] : $item['clinic_name_ar'],
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
