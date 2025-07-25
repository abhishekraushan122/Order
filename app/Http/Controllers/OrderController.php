<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Jobs\ProcessOrder;
use App\Jobs\ProcessO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',

        ] );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $request->input('user_id'),
                'total_amount' => $request->input('total_amount'),
                'status' => 'pending'
            ]);

            foreach ($request->input('items') as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            $Order_id= $order->id;
            // dd($order->id);
            // ProcessOrder::dispatch($Order_id);
            ProcessO::dispatch($Order_id);
            // DB::commit();


            return response()->json(['message' => 'Order created successfully'], 200);

        } catch (\Exception $e) {
            // DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
