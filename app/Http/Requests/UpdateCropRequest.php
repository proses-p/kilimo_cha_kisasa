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
            'farm_id' => 'sometimes|required|exists:farms,id',
            'crop_name' => 'sometimes|required|string|max:100',
            'planting_date' => 'sometimes|required|date',
            'harvest_date' => 'nullable|date|after_or_equal:planting_date',
            'status' => 'sometimes|required|in:planted,growing,harvested,failed',
            'notes' => 'nullable|string',
        ];
    }
}
