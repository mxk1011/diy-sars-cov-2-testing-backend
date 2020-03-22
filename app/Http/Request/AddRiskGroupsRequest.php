<?php

namespace App\Http\Requests;


use App\Http\Request\BaseRequest;

/**
 * Class AddRiskGroupsRequest
 * @package App\Http\Requests
 */
class AddRiskGroupsRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'riskgroups' => 'required',
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
