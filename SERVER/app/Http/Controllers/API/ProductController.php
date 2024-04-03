<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $listProduct = Product::get();
            $dataLength = count($listProduct);
            return response()->json([
                'status' => 'success',
                'message' => ' Lấy danh sách sản phẩm thành công',
                'listProduct' => $listProduct,
                'length' => $dataLength
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

    public function getProductByName(Request $request)
    {
        try {
            $productName = $request->product_name;
            if (empty($productName)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Lấy danh sách sản phẩm thành công',
                    'data' => ""
                ], 200);
            }
            $products = Product::where('product_name', 'like', "%$productName%")->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách sản phẩm thành công',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function decreaseProductQuantity(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,product_id',
                'quantity' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            } else {
                $product = Product::find($request->product_id);

                if (!$product) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Product not found'
                    ], 404);
                }

                $newQuantity = $product->product_quantity - $request->quantity;
                if ($newQuantity < 0) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Not enough quantity for product: ' . $product->product_name
                    ], 422);
                }

                $product->product_quantity = $newQuantity;
                $product->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Decreased product quantity successfully'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function increaseProductQuantity(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                '*.product_id' => 'required|exists:products,product_id',
                '*.quantity' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            } else {
                foreach ($request->all() as $item) {
                    $product = Product::find($item['product_id']);

                    if (!$product) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Product not found'
                        ], 404);
                    }

                    $newQuantity = $product->product_quantity + $item['quantity'];

                    $product->product_quantity = $newQuantity;
                    $product->save();
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Increased product quantities successfully'
                ], 200);
            }
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

    public function increase_product_view_count($product_id)
    {
        try {
            $product = Product::find($product_id);

            if ($product) {
                $product->increment('product_views');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Increased product view count successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductByCondition(Request $request)
    {
        try {
            $condition = $request->condition;
            $query = Product::query();

            switch ($condition) {
                case 1:
                    // Lấy danh sách tất cả sản phẩm cùng với tổng số lượt bán và tổng tất cả total_order_detail của từng sản phẩm
                    $products = $query->select('products.product_id', 'products.product_name', 'products.product_img', 'products.product_des', 'products.category_id', 'products.product_price', 'products.product_quantity', 'products.product_views', 'products.created_at', 'products.updated_at')
                        ->join('order_detail', 'products.product_id', '=', 'order_detail.product_id')
                        ->join('orders', 'order_detail.order_id', '=', 'orders.order_id')
                        ->where('orders.status', '=', 3)
                        ->groupBy('products.product_id', 'products.product_name', 'products.product_img', 'products.product_des', 'products.category_id', 'products.product_price', 'products.product_quantity', 'products.product_views', 'products.created_at', 'products.updated_at')
                        ->selectRaw('products.*, SUM(order_detail.quantity) as total_quantity, SUM(order_detail.total_cost_detail) as total_cost_detail')
                        ->get();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Lấy danh sách tất cả sản phẩm cùng với tổng số lượt bán và tổng tất cả total_order_detail thành công',
                        'data' => $products
                    ], 200);
                    break;
                case 2:
                    // Lấy danh sách sản phẩm được bán trong tháng này
                    $currentMonth = date('m');
                    $currentYear = date('Y');

                    $products = $query->select('products.product_id', 'products.product_name', 'products.product_img', 'products.product_des', 'products.category_id', 'products.product_price', 'products.product_quantity', 'products.product_views', 'products.created_at', 'products.updated_at')
                        ->join('order_detail', 'products.product_id', '=', 'order_detail.product_id')
                        ->join('orders', 'order_detail.order_id', '=', 'orders.order_id')
                        ->where('orders.status', '=', 3)
                        ->whereMonth('order_detail.created_at', '=', $currentMonth)
                        ->whereYear('order_detail.created_at', '=', $currentYear)
                        ->groupBy('products.product_id', 'products.product_name', 'products.product_img', 'products.product_des', 'products.category_id', 'products.product_price', 'products.product_quantity', 'products.product_views', 'products.created_at', 'products.updated_at')
                        ->selectRaw('products.*, SUM(order_detail.quantity) as total_quantity, SUM(order_detail.total_cost_detail) as total_cost_detail')
                        ->get();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Lấy danh sách sản phẩm được bán trong tháng này thành công',
                        'data' => $products
                    ], 200);
                    break;
                case 3:
                    // Lấy thời gian bắt đầu và kết thúc của tháng trước
                    $currentMonth = date('m');
                    $currentYear = date('Y');

                    if ($currentMonth == 1) {
                        $lastMonth = 12;
                        $year = $currentYear - 1;
                    } else {
                        $lastMonth = $currentMonth - 1;
                        $year = $currentYear;
                    }

                    // Xác định ngày đầu tiên của tháng trước
                    $startDate = date('Y-m-01', strtotime("$year-$lastMonth"));

                    // Xác định ngày cuối cùng của tháng trước
                    $endDate = date('Y-m-t', strtotime("$year-$lastMonth"));

                    // Thêm thời gian vào ngày cuối cùng
                    $endDate .= ' 23:59:59';

                    // Lấy danh sách sản phẩm được bán trong tháng trước
                    $products = $query->select('products.product_id', 'products.product_name', 'products.product_img', 'products.product_des', 'products.category_id', 'products.product_price', 'products.product_quantity', 'products.product_views', 'products.created_at', 'products.updated_at')
                        ->join('order_detail', 'products.product_id', '=', 'order_detail.product_id')
                        ->join('orders', 'order_detail.order_id', '=', 'orders.order_id')
                        ->where('orders.status', '=', 3)
                        ->whereBetween('order_detail.created_at', [$startDate, $endDate])
                        ->groupBy('products.product_id', 'products.product_name', 'products.product_img', 'products.product_des', 'products.category_id', 'products.product_price', 'products.product_quantity', 'products.product_views', 'products.created_at', 'products.updated_at')
                        ->selectRaw('products.*, SUM(order_detail.quantity) as total_quantity, SUM(order_detail.total_cost_detail) as total_cost_detail')
                        ->get();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Lấy danh sách sản phẩm được bán trong tháng trước thành công',
                        'data' => $products
                    ], 200);
                    break;

                case 4:
                    // Lấy danh sách sản phẩm được bán trong năm 2024
                    $products = $query->select('products.product_id', 'products.product_name', 'products.product_img', 'products.product_des', 'products.category_id', 'products.product_price', 'products.product_quantity', 'products.product_views', 'products.created_at', 'products.updated_at')
                        ->join('order_detail', 'products.product_id', '=', 'order_detail.product_id')
                        ->join('orders', 'order_detail.order_id', '=', 'orders.order_id')
                        ->where('orders.status', '=', 3)
                        ->whereYear('order_detail.created_at', '=', 2024)
                        ->groupBy('products.product_id', 'products.product_name', 'products.product_img', 'products.product_des', 'products.category_id', 'products.product_price', 'products.product_quantity', 'products.product_views', 'products.created_at', 'products.updated_at')
                        ->selectRaw('products.*, SUM(order_detail.quantity) as total_quantity, SUM(order_detail.total_cost_detail) as total_cost_detail')
                        ->get();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Lấy danh sách sản phẩm được bán trong năm 2024 thành công',
                        'data' => $products
                    ], 200);
                    break;
                case 5:
                    // Lấy danh sách sản phẩm được bán trong năm 2023
                    $products = $query->select('products.product_id', 'products.product_name', 'products.product_img', 'products.product_des', 'products.category_id', 'products.product_price', 'products.product_quantity', 'products.product_views', 'products.created_at', 'products.updated_at')
                        ->join('order_detail', 'products.product_id', '=', 'order_detail.product_id')
                        ->join('orders', 'order_detail.order_id', '=', 'orders.order_id')
                        ->where('orders.status', '=', 3)
                        ->whereYear('order_detail.created_at', '=', 2023)
                        ->groupBy('products.product_id', 'products.product_name', 'products.product_img', 'products.product_des', 'products.category_id', 'products.product_price', 'products.product_quantity', 'products.product_views', 'products.created_at', 'products.updated_at')
                        ->selectRaw('products.*, SUM(order_detail.quantity) as total_quantity, SUM(order_detail.total_cost_detail) as total_cost_detail')
                        ->get();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Lấy danh sách sản phẩm được bán trong năm 2023 thành công',
                        'data' => $products
                    ], 200);
                    break;

                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Condition không hợp lệ'
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateQuantity(Request $request, $product_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_quantity' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            } else {
                $product = Product::find($product_id);

                if (!$product) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Product not found'
                    ], 404);
                }

                $product->product_quantity = $request->product_quantity;
                $product->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Updated product quantity successfully'
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
