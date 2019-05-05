<?php

namespace App\Http\Controllers;

use App\Adress;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

use App\Order;
use App\OrdersPosition;
use App\States;
use App\User;

use JWTAuth;

use App\Http\Controllers\Auth\ApiAuthController;

class OrderController extends Controller
{
    public function index() {
        // User holen, die Bestellungen haben, und nach user_id sortieren
        $users = Order::select('user_id')->distinct()->orderBy('user_id')->get();

        // zu jedem User seine Bestellungen speichern
        foreach ($users as $user){
            $user['orders'] = Order::with('states')
                ->where('user_id', '=', $user['user_id'])
                ->get();
            $user['userInfo'] = User::where('users.id', '=', $user['user_id'])
                ->join('adresses', 'adresses.id', '=', 'users.adress_id')
                ->join('countries', 'countries.id', '=', 'adresses.country_id')
                ->get();
        }
        return $users;
    }

    public function ordersByUser() {
        // User holen
        $user = JWTAuth::parseToken()->authenticate();

        // Bestellungen holen
        $orders = Order::with('states', 'order_positions')
            ->where('user_id', '=', $user['id'])
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('adresses', 'adresses.id', '=', 'users.adress_id')
            ->join('countries', 'countries.id', '=', 'adresses.country_id')
            ->select('orders.id', 'nettoPrice', 'bruttoPrice', 'orders.created_at',
                'orders.updated_at', 'user_id', 'firstName', 'lastName', 'email', 'adress_id',
                'street', 'postcode', 'city', 'countries.name')
            ->get();

        return $orders;
    }

    public function listByState(string $stateName) {
        if($stateName == "ordered" || $stateName == "payed" || $stateName == "cancelled" || $stateName == "delivered"){
            if ($stateName == "ordered") $stateName = "Bestellt";
            else if ($stateName == "payed") $stateName = "Bezahlt";
            else if ($stateName == "cancelled") $stateName = "Storniert";
            else $stateName = "Geliefert";

            // alle Order, bei denen der aktuellste Status gleich $stateName ist
            // TODO FUNKTIONIERT NICHT
            $orders = Order::with('states')
                ->whereHas('states', function($query) use ($stateName)  {
                    $query
                        ->orderBy('id', 'desc')->first()
                        ->where('name', '=', $stateName)
                    ;
                })->get();

            return $orders;
        }
        return response()->json('state not valid', 420);
    }

    /**
     * create new order
     */
    public function order (Request $request) : JsonResponse {
        $user = JWTAuth::parseToken()->authenticate();
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            //$order = Order::create($request->all());
            $order = Order::create(['user_id' => $user['id']]);

            // order positions und state
            // save order positions
            if ($request['orders_positions'] && is_array($request['orders_positions'])) {
                foreach ($request['orders_positions'] as $pos) {
                    $orderPos = OrdersPosition::create([
                            'amount' => $pos['amount'],
                            'book_id' => $pos['book_id'],
                            'order_id' => $order->id,
                            'currentNettoCopy' => $pos['currentNettoCopy']
                        ]
                    );
                    // assign orderPos to order
                    $order->order_positions()->save($orderPos);
                    // add nettoPrice to total nettoPrice
                    $order->nettoPrice += $pos['currentNettoCopy']*$pos['amount'];
                }
            }
            // --------- State -------- //
            $states = new States;
            $states->comment = "Bestellung über System abgeschickt.";
            $states->order()->associate($order);
            $states->save();

            // get tax
            $usersWholeData = User::join('adresses', 'adresses.id', '=', 'users.adress_id')->join('countries', 'countries.id', '=', 'adresses.country_id')->select('users.*', 'adresses.*', 'countries.*')->where('users.id', '=', $order->user_id)->first();
            $tax = $usersWholeData->tax+100;

            // calculate brutto
            $order->bruttoPrice = $order->nettoPrice*$tax/100;

            $order->save();

            DB::commit();
            return response()->json($order, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json('saving order failed' . $e->getMessage(), 420);
        }
    }

    public function orderDetail(string $id){
        $order = Order::where('orders.id', $id)
            ->with(['order_positions', 'states'])
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('adresses', 'adresses.id', '=', 'users.adress_id')
            ->join('countries', 'countries.id', '=', 'adresses.country_id')
            ->select('orders.id', 'nettoPrice', 'bruttoPrice', 'orders.created_at',
                    'orders.updated_at', 'user_id', 'firstName', 'lastName', 'email', 'adress_id',
                    'street', 'postcode', 'city', 'countries.name', 'tax')
            ->first();
        return $order;
    }

    public function updateState(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try {
            $order = Order::where('id', '=', $request['order_id'])->first();
            if ($order != null) {
                $request = $this->parseRequest($request);

                //update states
                if (isset($request['name']) && isset($request['comment'])) {
                    //foreach ($request['states'] as $state) {
                        $orderState = States::create([
                                'name' => $request['name'],
                                'comment' => $request['comment'],
                                'order_id' => $request['order_id']
                            ]
                        );
                        $order->states()->save($orderState);
                    //}
                }
                $order->save();
            }
            DB::commit();
            // return a vaild http response
            return response()->json($order, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating state failed: " . $e->getMessage(), 420);

        }
    }

    public function getTax (string $adress_id) {
        $tax = Adress::where('adresses.id', '=', $adress_id)
            ->join('countries', 'adresses.country_id', '=', 'countries.id')
            ->select('tax')
            ->first();
        return $tax;
    }

    private function parseRequest (Request $request) : Request {
        $date = new \DateTime($request->published);
        $request['published'] = $date;
        return $request;
    }
}
