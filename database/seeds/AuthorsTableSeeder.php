<?php

use Illuminate\Database\Seeder;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $a1 = new \App\Author();
        $a1->firstName = "Max";
        $a1->lastName = "Maier";
        $a1->save();

        $a1 = new \App\Author();
        $a1->firstName = "Fritz";
        $a1->lastName = "Huber";
        $a1->save();

        $a1 = new \App\Author();
        $a1->firstName = "Heinz";
        $a1->lastName = "Gruber";
        $a1->save();
    }
}
