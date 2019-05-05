<?php

use Illuminate\Database\Seeder;
use App\Order;
use App\OrdersPosition;
use App\States;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User holen
        $marlene = App\User::where('id', '=', '2')->first();

        // Order anlegen
        $order1 = new Order;
        $order1->user()->associate($marlene);
        //$order1->states()->associate($states); -> wird nicht benötigt, da default "bestellt"
        $order1->save();

        // --------- Order positions ---------- //
        // Buch holen
        $book = App\Book::where('id', '=', '4')->first();

        // Order position anlegen
        $orderPos1 = new OrdersPosition;
        $orderPos1->amount = 2;
        $orderPos1->order()->associate($order1);
        $orderPos1->book()->associate($book);
        $orderPos1->save();

        // get price
        $orderPos1->currentNettoCopy = $book->currentNetto;
        $orderPos1->save();

        // --------- Price for Order --------- //
        $order1->nettoPrice = $orderPos1->currentNettoCopy*$orderPos1->amount;

        // get tax
        $marlenesWholeData = App\User::join('adresses', 'adresses.id', '=', 'users.adress_id')->join('countries', 'countries.id', '=', 'adresses.country_id')->select('users.*', 'adresses.*', 'countries.*')->where('users.id', '=', '2')->first();
        $tax = $marlenesWholeData->tax+100;

        // calculate brutto
        $order1->bruttoPrice = $order1->nettoPrice*$tax/100;
        $order1->save();

        // --------- State -------- //
        $states = new States;
        $states->comment = "Bestellung über System abgeschickt.";
        $states->order()->associate($order1);
        $states->save();
    }
}
