<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractRequest extends FormRequest
{
    public function load($dto)
    {
        foreach ($this->toArray() as $k => $v) {
            $dto->$k = $v;
        }

        return $dto;
    }
}
