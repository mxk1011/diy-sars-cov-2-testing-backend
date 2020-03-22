<?php

namespace App\Http\Controllers;

use App\Http\Resource\Producer\RiskGroupResource;
use App\Model\RiskGroup;
use Illuminate\Http\Request;

class RiskGroupController extends Controller
{


    public function list() {
        return RiskGroupResource::collection(RiskGroup::all());
    }
}
