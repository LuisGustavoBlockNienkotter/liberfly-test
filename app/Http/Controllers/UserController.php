<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/user",
     *     description="Create a new user",
     *     @OA\RequestBody(
     *       required=true,
     *       description="Pass user credentials",
     *       @OA\JsonContent(
     *         required={"name","email","password"},
     *         @OA\Property(property="name", type="string", example="Name Lastname"),
     *         @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *         @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       ),
     *     ),
     *     @OA\Response(response="200", description="Created user"),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="The given data was invalid."),
     *            @OA\Property(
     *               property="errors",
     *               type="object",
     *               @OA\Property(
     *                  property="email",
     *                  type="array",
     *                  collectionFormat="multi",
     *                  @OA\Items(
     *                     type="string",
     *                     example={"The email field is required.","The email must be a valid email address."},
     *                  )
     *               ),
     *               @OA\Property(
     *                  property="password",
     *                  type="array",
     *                  collectionFormat="multi",
     *                  @OA\Items(
     *                     type="string",
     *                     example={"The password field is required."},
     *                  )
     *               ),
     *               @OA\Property('
     *                  property="name",
     *                  type="array",
     *                  collectionFormat="multi",
     *                  @OA\Items(
     *                     type="string",
     *                     example={"The name field is required."},
     *                  )
     *               ),
     *            )
     *         )
     *      )
     * )
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user->toArray());
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     description="List all users",
     *     security={
     *          {"bearerAuth":{}},
     *      },
     *     @OA\Response(response="200", description="List users"),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         )
     *      )
     * )
     */
    public function list(Request $request): JsonResponse
    {
        return response()->json(User::all()->toArray());
    }

    /**
     * @OA\Get(
     *     path="/api/user/{id}",
     *     description="Find the user by id",
     *     security={
     *          {"bearerAuth":{}},
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *     ),
     *     @OA\Response(response="200", description="Return user"),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         )
     *      )
     * )
     */
    public function find(User $user, Request $request): JsonResponse
    {
        return response()->json($user->toArray());
    }

    /**
     * @OA\Post(
     *     path="/api/token",
     *     description="Generate the access token for the user",
     *     @OA\Response(response="200", description="User access token"),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="The given data was invalid."),
     *            @OA\Property(
     *               property="errors",
     *               type="object",
     *               @OA\Property(
     *                  property="email",
     *                  type="array",
     *                  collectionFormat="multi",
     *                  @OA\Items(
     *                     type="string",
     *                     example={"The email field is required.","The email must be a valid email address."},
     *                  )
     *               ),
     *               @OA\Property(
     *                  property="password",
     *                  type="array",
     *                  collectionFormat="multi",
     *                  @OA\Items(
     *                     type="string",
     *                     example={"The password field is required."},
     *                  )
     *               ),
     *               @OA\Property(
     *                  property="device_name",
     *                  type="array",
     *                  collectionFormat="multi",
     *                  @OA\Items(
     *                     type="string",
     *                     example={"The device_name field is required."},
     *                  )
     *               ),
     *            )
     *         )
     *      )
     * )
     */
    public function generateToken(LoginUserRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'access_token' => $user->createToken($request->device_name)->plainTextToken,
            'access_type' => 'Bearer'
        ]);
    }
}