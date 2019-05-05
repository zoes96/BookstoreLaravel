<?php

use Illuminate\Database\Seeder;
use App\Country;


class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Austria
        $austria = new Country;
        $austria->name = 'Österreich';
        $austria->tax = 20;
        $austria->save();

        // Germany
        $germany = new Country;
        $germany->name = 'Deutschland';
        $germany->tax = 19;
        $germany->save();

        // Italy
        $italy = new Country;
        $italy->name = 'Italien';
        $italy->tax = 22;
        $italy->save();

        // Croatia
        $croatia = new Country;
        $croatia->name = 'Kroatien';
        $croatia->tax = 25;
        $croatia->save();
    }
}
