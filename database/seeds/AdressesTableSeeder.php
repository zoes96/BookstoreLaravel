<?php

use Illuminate\Database\Seeder;
use App\Adress;
use App\Country;

class AdressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // countries holen
        $austria= App\Country::where("name", "=", "Österreich")->first(); // funktioniert nur mit first(), nicht mit get()
        $germany = App\Country::where('name', '=', 'Deutschland')->first();
        $italy = App\Country::where('name', '=', 'Italien')->first();
        $croatia = App\Country::where('name', '=', 'Kroatien')->first();

        // adress1
        $adress1 = new Adress;
        $adress1->street = 'Weingarten 6';
        $adress1->postcode = '4232';
        $adress1->city = 'Hagenberg';
        $adress1->country()->associate($austria); //country zuweisen
        $adress1->save();

        // adress2
        $adress2 = new Adress;
        $adress2->street = 'Hochstrasse 37';
        $adress2->postcode = '10115';
        $adress2->city = "Berlin";
        $adress2->country()->associate($germany);
        $adress2->save();

        // adress3
        $adress3 = new Adress;
        $adress3->street = 'Via Nuova del Campo 44';
        $adress3->postcode = '98030';
        $adress3->city = "Messina";
        $adress3->country()->associate($italy);
        $adress3->save();

        // adress4
        $adress4 = new Adress;
        $adress4->street = 'Sokolska 2';
        $adress4->postcode = '10000';
        $adress4->city = "Zagreb";
        $adress4->country()->associate($croatia);
        $adress4->save();
    }
}
