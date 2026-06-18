<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCropRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'planting_season' => 'nullable|string|max:255',
            'harvesting_period' => 'nullable|string|max:255',
        ];
    }
}
