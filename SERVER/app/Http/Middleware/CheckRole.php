<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->roles == 0) {
                // Nếu role là 0, cho phép request tiếp tục
                return $next($request);
            } else {
                // Nếu role không phải là 0, trả về phản hồi lỗi
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không có quyền truy cập.'
                ], 403); // HTTP status code 403: Forbidden
            }
        } else {
            // Nếu người dùng chưa xác thực, trả về phản hồi lỗi
            return response()->json([
                'status' => 'error',
                'message' => 'Yêu cầu xác thực.'
            ], 401); // HTTP status code 401: Unauthorized
        }
    }
}
