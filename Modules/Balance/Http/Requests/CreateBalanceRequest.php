<?php

namespace Modules\Balance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBalanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'balance' => ['required', 'integer'],
            'user_id' => ['required', 'integer']
        ];
    }

}
