<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Services\Auth\Dtos\CreateUserDto;
use App\Services\Auth\Actions\LoginAction;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\Actions\LogoutAction;
use App\Services\Auth\Actions\RegisterAction;

class AuthController extends Controller
{
    public function __construct(
        protected LoginAction $loginAction,
        protected LogoutAction $logoutAction,
        protected RegisterAction $registerAction
    ) {}

    /**
     * @OA\Post(
     * path="/api/auth/register",
     * operationId="Register",
     * tags={"Register"},
     * summary="User Register",
     * description="User Register here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","surname", "username", "password", "password_confirmation"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="surname", type="text"),
     *               @OA\Property(property="username", type="text"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="password_confirmation", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = CreateUserDto::fromRequest($request);
        $this->registerAction->run($dto);

        return $this->response([
            'message' => [config('register.you_have_successfully_registered')]
        ], 201);
    }

    /**
     * @OA\Post(
     * path="/api/auth/login",
     * operationId="authLogin",
     * tags={"Login"},
     * summary="User Login",
     * description="Login User Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"username", "password"},
     *               @OA\Property(property="username", type="username"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $username = $request->getUsername();
        $password = $request->getUserPassword();

        $result = $this->loginAction->run($username, $password);

        return $this->response(['data' => $result->toArray($request)]);
    }

    /**
     * @OA\Post(
     * path="/v1/logout",
     * summary="Logout",
     * description="Logout user and invalidate token",
     * operationId="authLogout",
     * tags={"auth"},
     * security={ {"bearerAuth": {}} },
     * @OA\Response(
     *    response=200,
     *    description="Success"
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Not authorized"),
     *    )
     * )
     * )
     */

    /**
     * @OA\SecurityScheme(
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="bearerAuth"
     * )
     */
    public function logout(LogoutRequest $request): JsonResponse
    {
        $this->logoutAction->run($request->getAuthUser());

        return $this->response(['message' => config('logout.user_successfully_signed_out')]);
    }
}
