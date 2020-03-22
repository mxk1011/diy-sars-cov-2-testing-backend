<?php

namespace App\Http\Controllers;

use App\Commands\AddTest;
use App\Http\Requests\AddTestRequest;
use App\Http\Resource\TestResource;
use App\Model\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function add(AddTestRequest $request, AddTest $addTest) {
        $test = $addTest->run($request->test_number);
        return new TestResource($test);
    }

    public function latest() {
        $test = Test::whereUserId(auth()->user())
            ->orderBy('created_at', 'desc')
            ->first();

        if($test === null) {
            return $this->toJsonApiResponse([], [], ['message' => 'No tests yet'], 404);
        }

        return new TestResource($test);
    }

    public function list() {
        return TestResource::collection(Test::whereUserId(auth()->user())
            ->orderBy('created_at', 'desc')
            ->get());
    }

    public function get(string $id) {
        return new TestResource(Test::whereUserId(auth()->user())
            ->where('test_number', '=', $id)
            ->firstOrFail());
    }
}
