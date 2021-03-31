<?php

namespace App\Transformers;

use Illuminate\Support\Arr;
use League\Fractal;

class FaqTransformer extends Fractal\TransformerAbstract
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
            'question' => $item['question'],
            'answer' => $item['answer'],
        ];

        if ($this->fields != null) {
            return Arr::only($res, $this->fields);
        }
        return $res;
    }
}
