<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|unique:users,username|max:100',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ]);

            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => 'customer',
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(
                [
                    'message' => 'Đăng ký thành công',
                    'token' => $token,
                    'user' => $user,
                ],
                201,
                [],
                JSON_PRETTY_PRINT,
            );
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors(),
                ],
                422,
                [],
                JSON_PRETTY_PRINT,
            );
        } catch (\Exception $e) {
            Log::error('Register error: ' . $e->getMessage());
            return response()->json(
                [
                    'message' => 'Lỗi trong quá trình đăng ký',
                ],
                500,
                [],
                JSON_PRETTY_PRINT,
            );
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Sai tài khoản hoặc mật khẩu'], 401, [], JSON_PRETTY_PRINT);
            }

            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Đăng nhập thành công',
                'token' => $token,
                'user' => $user,
            ]);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $e->errors(),
                ],
                422,
                [],
                JSON_PRETTY_PRINT,
            );
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(
                [
                    'message' => 'Lỗi trong quá trình đăng nhập',
                ],
                500,
                [],
                JSON_PRETTY_PRINT,
            );
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Đăng xuất thành công']);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json(
                [
                    'message' => 'Lỗi trong quá trình đăng xuất',
                ],
                500,
                [],
                JSON_PRETTY_PRINT,
            );
        }
    }

    public function me(Request $request)
    {
        try {
            $user = $request->user();

            return response()->json(
                [
                    'message' => 'Lấy thông tin người dùng thành công',
                    'user' => $user,
                ],
                200,
                [],
                JSON_PRETTY_PRINT,
            );
        } catch (\Exception $e) {
            Log::error('Me error: ' . $e->getMessage());
            return response()->json(
                [
                    'message' => 'Lỗi trong quá trình lấy thông tin người dùng',
                ],
                500,
                [],
                JSON_PRETTY_PRINT,
            );
        }
    }
}
