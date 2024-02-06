<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function get()
    {
        return 'Order';
    }

    public function getAll()
    {
        try {
            $orders = Order::getAll();
            return response()->json([
                'message' => 'success',
                'data' => $orders
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $customMessage = [
                'bill_id.required' => 'Mã đơn hàng không được để trống.',
                'status' => 'Mã trạng thái không được để trống.',
                'order_address' => 'Địa chỉ nhận hàng không được để trống'
            ];

            $validate = Validator::make($request->all(), [
                'bill_id' => 'required',
                'status' => 'required',
                'order_address' => 'required'
            ], $customMessage);

            if ($validate->fails()) {
                $errors = $validate->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            } else {
                $order = new Order();
                $order->bill_id = $request->bill_id;
                $order->status = $request->status;
                $order->user_id = auth()->user()->id;
                $order->order_address = $request->order_address;
                $order->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tạo đơn hàng thành công'
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            Order::where('order_id', $id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Hủy đơn hàng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function count()
    {
        try {
            $user_id = auth()->user()->id;
            $total = Order::where('user_id', $user_id)->count();
            return response()->json([
                'status' => 'success',
                'total' => $total
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
