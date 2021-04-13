<?php

namespace App\Transformers;

use Illuminate\Support\Arr;
use League\Fractal;

class OperationTransformer extends Fractal\TransformerAbstract
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
            'name' => $this->lang == 'ar' ? $item['name_ar'] : $item['name_en'],
            'description' => $this->lang == 'ar' ? $item['description_ar'] : $item['description_en'],
        ];

        if ($this->fields != null) {
            return Arr::only($res, $this->fields);
        }
        return $res;
    }
}
