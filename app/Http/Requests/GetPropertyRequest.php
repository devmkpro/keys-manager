<?php

namespace App\Http\Requests;


class GetPropertyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'realtor_code' => 'required|string|exists:users,code',
        ];
    }
}
