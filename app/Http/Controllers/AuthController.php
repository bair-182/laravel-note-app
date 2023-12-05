<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * @OA\Post(
     *         path="/api/login",
     *         summary="Авторизация пользователя(получение токена)",
     *         tags={"Auth"},
     *
     *         @OA\RequestBody(
     *             @OA\JsonContent(
     *                 allOf={
     *                     @OA\Schema (
     *                         @OA\Property (property="email", type="string", example="ivanov@mail.ru"),
     *                         @OA\Property (property="password", type="string", example="12345678"),
     *                     ),
     *                 }
     *             ),
     *         ),
     *         @OA\Response(
     *             response=200,
     *             description="OK",
     *             @OA\JsonContent(
     *                 @OA\Property (property="user", type="object",
     *                     @OA\Property (property="id", type="integer", example=1),
     *                     @OA\Property (property="name", type="string", example="Ivan Ivanov"),
     *                     @OA\Property (property="email", type="string", example="ivanov@mail.ru"),
     *                     @OA\Property (property="email_verified_at", type="timestamp", example=null),
     *                     @OA\Property (property="created_at", type="timestamp", example="2023-11-28T18:56:11.000000Z"),
     *                     @OA\Property (property="updated_at", type="timestamp", example="2023-11-28T18:56:11.000000Z"),
     *                     @OA\Property (property="role", type="string", example="user"),
     *                     @OA\Property (property="token", type="string", example="tWSGolP01gKzmIQVFP7fHe7s970wv7H6vvvYeeaDOE6dOmCXoqdzSImdV9s-_wJb4IrslyeNbxU4SFeiEhMS1TDpBfjjA6y1H7IuOGAvUgry1zmv1240gy--vmdikomjFKrXnylTKhbKBVFeZG5wbNkpGBrcLAtCTRpZ2oqmDOsLkvpp0Ed_WrClbFV9O4NuoQ"),
     *                 ),
     *             ),
     *         ),
     *    ),
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (auth()->attempt($credentials)) {
            $user = Auth::user();
            $user['token'] = $user->createToken('SoftEngineering')->accessToken;
            return response()->json([
                'user' => $user
            ], 200);
        }
        return response()->json([
            'message' => 'Invalid credentials'
        ], 402);
    }


    /**
     * @OA\Post(
     *        path="/api/register",
     *        summary="Регистрация пользователя",
     *        tags={"Auth"},
     *
     *        @OA\RequestBody(
     *            @OA\JsonContent(
     *                allOf={
     *                    @OA\Schema (
     *                        @OA\Property (property="name", type="string", example="Ivan Ivanov"),
     *                        @OA\Property (property="email", type="string", example="ivanov@mail.ru"),
     *                        @OA\Property (property="password", type="string", example="12345678"),
     *                    ),
     *                }
     *            ),
     *        ),
     *        @OA\Response(
     *            response=200,
     *            description="OK",
     *            @OA\JsonContent(
     *                @OA\Property (property="message", type="string", example="User created successfully."),
     *                @OA\Property (property="user", type="object",
     *                    @OA\Property (property="name", type="string", example="Ivan Ivanov"),
     *                    @OA\Property (property="email", type="string", example="ivanov@mail.ru"),
     *                    @OA\Property (property="updated_at", type="timestamp", example="2023-11-28T18:56:11.000000Z"),
     *                    @OA\Property (property="created_at", type="timestamp", example="2023-11-28T18:56:11.000000Z"),
     *                    @OA\Property (property="id", type="integer", example=1)
     *                ),
     *            ),
     *        ),
     *   ),
     *
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    /**
     * @OA\Post(
     *          path="/api/logout",
     *          summary="Выйти из системы",
     *          tags={"Auth"},
     *          security={{ "bearerAuth": {} }},
     *
     *          @OA\Response(
     *              response=200,
     *              description="OK",
     *              @OA\JsonContent(
     *                  @OA\Property (property="message", type="string", example="Successfully logged out"),
     *              ),
     *          ),
     *     ),
     */
    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
