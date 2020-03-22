<?php

namespace App\Http\Requests;


use App\Http\Request\BaseRequest;

/**
 * Class AddRiskGroupsRequest
 * @package App\Http\Requests
 */
class AddTestRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'test_number' => 'string|required|unique:tests,test_number',
        ];
    }

    /**
     * @return array
     */
    public function authorize()
    {
        return true;
    }
}
