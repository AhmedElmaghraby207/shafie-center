<?php

namespace App\Transformers;

use Illuminate\Support\Arr;
use League\Fractal;

class CaseTransformer extends Fractal\TransformerAbstract
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
            'case_name' => $item['case_name'],
            'image_before' => $item['image_before'],
            'image_after' => $item['image_after'],
        ];

        if ($this->fields != null) {
            return Arr::only($res, $this->fields);
        }
        return $res;
    }
}
