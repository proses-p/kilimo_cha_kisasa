<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCropRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'farm_id' => 'required|exists:farms,id',
            'crop_name' => 'required|string|max:100',
            'planting_date' => 'required|date',
            'harvest_date' => 'nullable|date|after_or_equal:planting_date',
            'status' => 'required|in:planted,growing,harvested,failed',
            'notes' => 'nullable|string',
        ];
    }
}
