<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderDetailController extends Controller
{
    public function get()
    {
        return 'Order detail';
    }

    public function create(Request $request)
    {
        try {
            $customMessage = [
                'quantity.required' => 'Số lượng không được để trống.',
                'total_price.required' => 'Tổng tiền không được để trống.',
                'order_id' => 'Mã đơn hàng không được để trống.',
                'product_id' => 'Id sản phẩm không được để trống.'

            ];

            $validate = Validator::make($request->all(), [
                'quantity' => 'required',
                'total_price' => 'required',
                'order_id' => 'required'
            ], $customMessage);

            if ($validate->fails()) {
                $errors = $validate->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'error' => $errors
                ], 422);
            } else {
                $orderDetail = new OrderDetail();
                $orderDetail->quantity = $request->quantity;
                $orderDetail->total_price = $request->total_price;
                $orderDetail->order_id = $request->order_id;
                $orderDetail->product_id = $request->product_id;
                $orderDetail->save();

                return response()->json([
                    'status' => 'success',
                    'messageg' => 'Tạo chi tiết đơn hàng thành công.'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
