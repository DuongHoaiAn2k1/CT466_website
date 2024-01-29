<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }


    public function login(Request $request)
    {
        // $credentials = request(['email', 'password']);
        if (isset($request->email)) {
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
                $credentials = $request->only('email', 'password');
            }
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $user_id = auth()->user()->id;
            $refreshToken = $this->createRefreshToken();
            return $this->respondWithToken($token, $refreshToken, $user_id);
        } else {
            // return response()->json([
            //     'data' => $request->username
            // ], 200);
            $customerMessages = [
                'username.required' => "Email không được để trống",
                'password.required' => 'Mật khẩu không được để trống',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'password.max' => 'Mật khẩu tối đa 32 ký tự',
            ];

            $validator = Validator::make($request->all(), [
                'username' => 'required',
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
                $credentials = $request->only('username', 'password');
            }
            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $user_id = auth()->user()->id;


            $refreshToken = $this->createRefreshToken();
            return $this->respondWithToken($token, $refreshToken, $user_id);
        }
    }



    public function me()
    {
        try {
            return response()->json(auth()->user());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        // return $this->respondWithToken(auth()->refresh());
        $refreshToken = request()->refresh_Token;
        try {
            $decoded = JWTAuth::getJWTProvider()->decode($refreshToken);
            $user = User::find($decoded['sub']);
            if (!$user) {
                return response()->json([
                    "message" => "User Invalid"
                ], 404);
            }

            auth()->invalidate();
            $token = auth()->login($user);
            // $refreshToken = $this->createRefreshToken();
            // return response()->json(['refreshToken: ' => $refreshToken], 201);
            return $this->respondWithToken($token, $refreshToken, $decoded['sub']);
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
            // return response()->json(['message' => "RefreshToken Invalid"], 500);
        }
    }


    protected function respondWithToken($token, $refreshToken, $userId)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'user_id' => $userId,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    private function createRefreshToken()
    {
        $data = [
            'sub' => auth()->user()->id,
            'random' => rand() . time(),
            'exp' => time() + config('jwt.refresh_ttl')
        ];

        $refreshToken = JWTAuth::getJWTProvider()->encode($data);
        return $refreshToken;
    }
}
