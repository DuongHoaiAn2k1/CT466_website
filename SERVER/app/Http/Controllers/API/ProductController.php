<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $listProduct = Product::get();
            return response()->json([
                'status' => 'success',
                'message' => ' Lấy danh sách sản phẩm thành công',
                'listProduct' => $listProduct
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get($product_id)
    {
        try {
            $product = Product::where("product_id", $product_id)->first();
            return response()->json([
                'status' => 'succsess',
                'message' => 'Lấy sản phẩm thành công',
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductByCategoryName(Request $request)
    {
        // return response()->json([
        //     "data" => $request->category_name
        // ], 200);
        try {
            $category_name = $request->category_name;
            $category = Category::where('category_name', $category_name)->first();
            $products = $category->products;
            return response()->json([
                'status' => 'succsess',
                'message' => 'Lấy sản phẩm thành công',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductByCategoryId($category_id)
    {
        // return response()->json([
        //     "data" => $request->category_name
        // ], 200);
        try {
            $category = Category::find($category_id);
            $products = $category->products;
            return response()->json([
                'status' => 'succsess',
                'message' => 'Lấy sản phẩm thành công',
                'data' => $products
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
        // Log::info('Received data from request:', $request->all());
        // return response()->json([
        //     'status' => 'hihi',
        //     'data' => $request->all()
        // ], 200);
        $customMessage = [
            'product_name.required' => 'Tên sản phẩm không được để trống',
            'product_img.required' => 'Hình ảnh không được để trống',
            'product_des.require' => 'Chi tiết sản phẩm không được để trống',
            'category_id.required' => 'Danh mục không được để trống',
            'product_price.require' => 'Giá sản phẩm không được để trống',
            'product_quantiy.required' => 'Số lượng sản phẩm không được để trống',

        ];

        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'product_img' => 'required',
            'product_des' => 'required',
            'category_id' => 'required',
            'product_price' => 'required',
            'product_quantity' => 'required'
        ], $customMessage);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        } else {
            try {
                if (Product::where('product_name', $request->product_name)->exists()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Tên sản phẩm đã tồn tại'
                    ], 422);
                } else {
                    $imagePaths = [];
                    foreach ($request->file('product_img') as $image) {
                        $imagePath = $image->store('uploads', 'public');
                        $imagePaths[] = $imagePath;
                    }
                    $product = new Product();
                    $product->product_name = $request->product_name;
                    $product->product_img = json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
                    $product->product_des = $request->product_des;
                    $product->category_id = $request->category_id;
                    $product->product_price = $request->product_price;
                    $product->product_quantity = $request->product_quantity;
                    $product->save();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Create product successfully'
                    ], 201);
                }
            } catch (\Exception $e) {

                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function update(Request $request, $product_id)
    {
        // $data = $request->all();
        // return response()->json([
        //     'status' => 'hihi',
        //     'data' => $data,
        //     'product_id' => $product_id
        // ], 200);
        // Log::info('Received data from request:', $request->all());
        if (isset($request->product_img)) {
            $customMessage = [
                'product_name.required' => 'Tên sản phẩm không được để trống',
                'product_des.required' => 'Chi tiết sản phẩm không được để trống',
                'category_id.required' => 'Danh mục không được để trống',
                'product_price.required' => 'Giá sản phẩm không được để trống',
                'product_quantiy.required' => 'Số lượng sản phẩm không được để trống',

            ];

            $validator = Validator::make($request->all(), [
                'product_name' => 'required',
                'product_des' => 'required',
                'category_id' => 'required',
                'product_price' => 'required',
                'product_quantity' => 'required'
            ], $customMessage);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            } else {
                try {

                    $existProduct = Product::where('product_name', $request->product_name)->where('product_id', '<>', $product_id)->first();
                    if ($existProduct) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Sản phẩm đã tồn tại'
                        ], 422);
                    } else {
                        $imagePaths = [];
                        foreach ($request->file('product_img') as $image) {
                            $imagePath = $image->store('uploads', 'public');
                            $imagePaths[] = $imagePath;
                        }
                        Product::where('product_id', $product_id)->update([
                            'product_name' => $request->product_name,
                            'product_img' => json_encode($imagePaths, JSON_UNESCAPED_SLASHES),
                            'product_des' => $request->product_des,
                            'category_id' => $request->category_id,
                            'product_price' => $request->product_price,
                            'product_quantity' => $request->product_quantity
                        ]);

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Updated product successfully'
                        ], 201);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ], 500);
                }
            }
        } else {
            $customMessage = [
                'product_name.required' => 'Tên sản phẩm không được để trống',
                'product_des.required' => 'Chi tiết sản phẩm không được để trống',
                'category_id.required' => 'Danh mục không được để trống',
                'product_price.required' => 'Giá sản phẩm không được để trống',
                'product_quantiy.required' => 'Số lượng sản phẩm không được để trống',

            ];

            $validator = Validator::make($request->all(), [
                'product_name' => 'required',
                'product_des' => 'required',
                'category_id' => 'required',
                'product_price' => 'required',
                'product_quantity' => 'required'
            ], $customMessage);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            } else {
                try {

                    $existProduct = Product::where('product_name', $request->product_name)->where('product_id', '<>', $product_id)->first();
                    if ($existProduct) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Sản phẩm đã tồn tại'
                        ], 422);
                    } else {

                        Product::where('product_id', $product_id)->update([
                            'product_name' => $request->product_name,
                            'product_des' => $request->product_des,
                            'category_id' => $request->category_id,
                            'product_price' => $request->product_price,
                            'product_quantity' => $request->product_quantity
                        ]);

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Updated product successfully'
                        ], 201);
                    }
                } catch (\Exception $e) {

                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ], 500);
                }
            }
        }
    }

    public function delete($product_id)
    {
        try {
            $response = Product::where('product_id', $product_id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa sản phẩm thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPrice($product_id)
    {
        try {
            $product = Product::where('product_id', $product_id)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy giá tiền thành công',
                'price' => $product['product_price']
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
