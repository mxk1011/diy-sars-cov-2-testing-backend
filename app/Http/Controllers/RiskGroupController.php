<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRiskGroupsRequest;
use App\Http\Resource\RiskGroupResource;
use App\Model\RiskGroup;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;

class RiskGroupController extends Controller
{
    const DELIMITER = ';';

    public function list() {
        return RiskGroupResource::collection(RiskGroup::all());
    }

    public function add(AddRiskGroupsRequest $request) {
        $user = auth()->user();

        collect(explode(self::DELIMITER, $request->riskgroups))->each(function($item) use ($user) {
            $riskgroup = RiskGroup::whereId($item)->first();

            if($riskgroup === null) {
                throw new InvalidArgumentException(sprintf('Riskgroup %s not found!', $item));
            }

            $user->riskGroups()->attach($riskgroup);
        });

        $user->save();
        return $this->toJsonApiResponse([], [], [], 201);
    }
}
