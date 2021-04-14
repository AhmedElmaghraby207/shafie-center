<?php

namespace App\Transformers;

use Illuminate\Support\Arr;
use League\Fractal;

class FaqTransformer extends Fractal\TransformerAbstract
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
            'question' => $this->lang == 'ar' ? $item['question_ar'] : $item['question_en'],
            'answer' => $this->lang == 'ar' ? $item['answer_ar'] : $item['answer_en'],
        ];

        if ($this->fields != null) {
            return Arr::only($res, $this->fields);
        }
        return $res;
    }
}
