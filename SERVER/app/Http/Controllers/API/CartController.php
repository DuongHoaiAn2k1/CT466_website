<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return "Hi";
    }

    public function getByUserId($user_id)
    {
        try {
            $listCart = Cart::where('user_id', $user_id)->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy giỏ hàng thành công',
                'data' => $listCart
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request)
    {
        // return response()->json([
        //     "data" => $request->all()
        // ], 202);
        try {
            $existsCart = Cart::where('user_id', $request->user_id)->where('product_id', $request->product_id)->first();
            if (!$existsCart) {
                $cart = new Cart();
                $cart->user_id = $request->user_id;
                $cart->product_id = $request->product_id;
                $cart->quantity = $request->quantity;
                $cart->save();
            } else {
                if ($existsCart['quantity'] < 10) {
                    Cart::where('cart_id', $existsCart['cart_id'])->update([
                        'quantity' => $existsCart['quantity'] + 1
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Qúa số lượng cho phép'
                    ], 500);
                }
            }


            return response()->json([
                'status' => 'success',
                'message' => 'Thêm vào giỏ hàng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function decrease($cart_id)
    {
        try {
            $cart = Cart::where("cart_id", $cart_id)->first();

            if ($cart['quantity'] > 1) {
                Cart::where("cart_id", $cart_id)->update([
                    'quantity' => $cart['quantity'] - 1
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Giam san pham thanh cong'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đã đến giới hạn giảm cho phép'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function increase($cart_id)
    {
        try {
            $cart = Cart::where("cart_id", $cart_id)->first();

            if ($cart['quantity'] < 10) {
                Cart::where("cart_id", $cart_id)->update([
                    'quantity' => $cart['quantity'] + 1
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tăng số lượng thành công'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đã đến giới hạn tăng cho phép'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($cart_id)
    {
        try {
            Cart::where('cart_id', $cart_id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Xoá sản phẩm trong giỏ hàng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function count()
    {
        try {
            $user_id = auth()->user()->id;
            $number = Cart::where('user_id', $user_id)->count();
            return response()->json([
                'status' => 'success',
                'number' => $number
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
