<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFarmRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'sometimes|required|exists:users,id',
            'name' => 'sometimes|required|string|max:100',
            'location' => 'sometimes|required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'size_acres' => 'sometimes|required|numeric|min:0',
            'soil_type' => 'sometimes|required|in:clay,sandy,loamy,silty,peaty',
        ];
    }
}
