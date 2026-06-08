<?php

namespace App\Http\Requests\Vendor;

use App\Enums\RoleName;
use Illuminate\Foundation\Http\FormRequest;


class VendorApplicationRequest extends FormRequest
{
     public function authorize(): bool
     {
        return auth()->check();
     }

    public function rules(): array
    {
        return [
            'shop_name' => 'required|string|min:5|max:50',
            'description' => 'required|string|min:20|max:1000',
            'phone' => 'required|string|max:13',
            'location' => 'required|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'shop_name.required' => 'Please provide a name for your shop.',
            'shop_name.min' => 'Shop name must be at least 5 characters.',

            'description.required' => 'Please provide a description of your shop.',
            'phone.required' => 'Please provide a contact phone number.',
            'location.required' => 'Please provide the location of your shop.',
        ];
    }
}