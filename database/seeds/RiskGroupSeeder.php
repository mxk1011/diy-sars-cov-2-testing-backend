<?php

use Illuminate\Database\Seeder;

class RiskGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = new \App\Model\RiskGroup();
        $group->label = 'Ich habe Diabetes';
        $group->save();

        $group = new \App\Model\RiskGroup();
        $group->label = 'Ich bin über 50 Jahre alt';
        $group->save();
    }
}
