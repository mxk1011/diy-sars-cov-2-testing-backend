<?php

namespace App\Commands;

use App\Model\Test;
use Laravel\Passport\Token;

class AddTest
{
	public function run(string $testNumber): Test
	{
		$test = new Test();

        /**
         * TODO: Validate test number
         *
         * Currently we accept all kind of test numbers as we don't know the format yet. As soon as we know it we will
         * add some kind of validation here and reject invalid numbers.
         */

		$test->test_number = $testNumber;
		$test->user_id = auth()->user()->id;

		$test->save();
        return $test;
	}
}
