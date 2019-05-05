<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Adressen holen
        $adress1 = App\Adress::where("id", "=", "1")->first();
        $adress2 = App\Adress::where("id", "=", "2")->first();
        $adress3 = App\Adress::where("id", "=", "3")->first();
        $adress4 = App\Adress::where("id", "=", "4")->first();

        // neuen User erstellen - Admin
        $admin = new User;
        $admin->firstName = 'Zoe';
        $admin->lastName = 'Springer';
        $admin->email = 'zoe@gmail.com';
        $admin->password = bcrypt('secret');
        $admin->isAdmin = true;
        $admin->adress()->associate($adress1);
        $admin->save();

        // weitere User
        $user1 = new User;
        $user1->firstName = 'Marlene';
        $user1->lastName = 'Bogner';
        $user1->email = 'marlene@gmail.com';
        $user1->password = bcrypt('secret');
        $user1->isAdmin = false;
        $user1->adress()->associate($adress2);
        $user1->save();

        $user2 = new User;
        $user2->firstName = 'Verena';
        $user2->lastName = 'Fritz';
        $user2->email = 'verena@gmail.com';
        $user2->password = bcrypt('secret');
        $user2->isAdmin = false;
        $user2->adress()->associate($adress3);
        $user2->save();

        $user3 = new User;
        $user3->firstName = 'Sarah';
        $user3->lastName = 'Brugger';
        $user3->email = 'sarah@gmail.com';
        $user3->password = bcrypt('secret');
        $user3->isAdmin = false;
        $user3->adress()->associate($adress4);
        $user3->save();
    }
}
