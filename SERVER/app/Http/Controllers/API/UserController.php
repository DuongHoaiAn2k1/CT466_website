<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $data = User::find($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Fetch data successfully',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAll()
    {
        try {
            $role = auth()->user()->roles;
            if ($role != 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quyền này không thuộc về bạn'
                ], 500);
            }
            $users = User::all();
            return response()->json([
                'status' => 'success',
                'message' => 'Lấy danh sách người dùng thành công',
                'data' => $users,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        // 'email' => 'required|email|unique:users,email',

        $customMessages = [
            'name.required' => 'Họ tên không được để trống',
            'email.required' => 'Email không được để trống',
            // 'email.unique' => "Email đã tồn tại",
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.digits' => 'Số điện thoại cần có đúng 10 số',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu tối đa 32 ký tự',
            // 'address.required' => 'Địa chỉ không được để trống',
            // 'address.city' => 'Trường này không được để trống',
            // 'address.district' => 'Trường này không được để trống',
            // 'address.wards' => 'Trường này không được để trống',
            // 'address.street' => 'Trường này không được để trống',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|digits:10',
            'password' => 'required|min:8|max:32',
            // 'address.city' => 'required',
            // 'address.district' => 'required',
            // 'address.wards' => 'required',
            // 'address.street' => 'required',
        ], $customMessages);


        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 422);
        } else {
            try {
                if (User::where('email', $request->email)->exists()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Email đã tồn tại',
                    ], 422);
                } else {
                    if (isset($request->otp)) {
                        $storedOtp = Cache::get('otp_' . $request->email);
                        if ($storedOtp != $request->otp) {
                            return response()->json([
                                'status' => 'ErrorOTP',
                                'message' => 'Mã OTP không chính xác'
                            ]);
                        } else {

                            $user = new User();
                            $user->name = $request->name;
                            $user->email = $request->email;
                            $user->password = bcrypt($request->password);
                            $user->phone = $request->phone;
                            // $user->address = json_encode($request->address);
                            $user->roles = 1;
                            $user->point = 0;
                            $user->save();
                            return response()->json([
                                'status' => 'success',
                                'message' => 'Create customer successfully',
                            ], 201);
                        }
                    } else {
                        $otp = rand(100000, 999999);
                        Mail::to($request->email)->send(new OtpMail($otp));
                        Cache::put('otp_' . $request->email, $otp, now()->addMinutes(1));
                        return response()->json([
                            'status' => 'pending',
                            'message' => 'OPT đã được gửi tới email. Vui lòng xác minh email',
                        ], 201);
                    }
                }
            } catch (\Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => $e->getMessage()
                ], 500);
            }
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $customerMessages = [
            'email.required' => "Email không được để trống",
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.max' => 'Mật khẩu tối đa 32 ký tự',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|max:32'
        ], $customerMessages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 442);
        } else {
            try {
                if (!$token = auth()->attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 400);
                }

                $user = auth()->user();
                $role = $user->roles;

                $customClaims = ['role' => $role];
                $payload = JWTFactory::make(array_merge($customClaims, ['sub' => $user->id])); // 'sub' is the default subject claim
                $token = JWTAuth::encode($payload);


                return response()->json([
                    'status' => 'Login Successfully',
                    'token' => $token->get()
                ]);
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
        }
    }

    public function createAddress(Request $request)
    {
        try {
            $customerMessages = [
                'name.required' => 'Tên người nhận không được để trống.',
                'phone.required' => 'Số điện thoại không được để trống.',
                'city.required' => 'Thành phố không được để trống.',
                'district.required' => 'Quận huyện không được để trống.',
                'commue.required' => 'Phường xã không được để trống.',
                'address.required' => 'Địa chỉ cụ thể không được để trống'
            ];
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
                'city' => 'required',
                'name' => 'required',
                'district' => 'required',
                'address' => 'required',
            ], $customerMessages);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            }

            $data = $request->all();
            $userId = auth()->user()->id;
            $user = User::find($userId);
            $userAddresses = json_decode($user->address, true) ?? [];

            $userAddresses[] = $data;

            $user->update([
                'address' => json_encode($userAddresses)
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Thêm địa chỉ thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteAddress(Request $request, $index)
    {
        try {
            $userId = auth()->user()->id;
            $user = User::find($userId);
            $userAddresses = json_decode($user->address, true) ?? [];

            if ($index < 0 || $index >= count($userAddresses)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid index provided'
                ], 422);
            }

            array_splice($userAddresses, $index, 1);

            $user->update([
                'address' => json_encode($userAddresses)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đã xóa địa chỉ thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function incrementPoint()
    {
        try {
            $user_id = auth()->user()->id;
            $user = User::where('id', $user_id)->first();
            $user->point = $user->point + 10;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Point Increased Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function decrementPoint(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $user = User::where('id', $user_id)->first();
            $point_used = $request->point_used;

            $user->point = $user->point - $point_used;
            $user->point_used = $user->point_used + $point_used;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Point Updated Successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function restorePoint(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $user = User::where('id', $user_id)->first();
            $point_paid = $request->point_paid;
            $user->point_used = $user->point_used - $point_paid;
            $user->point = $user->point + $point_paid;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Restore successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCurrentPoint()
    {
        try {
            $user_id = auth()->user()->id;
            $user = User::where('id', $user_id)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Get Current Point SuccessFully',
                'point' => $user->point,
                'used_point' => $user->point_used
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
