<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\AuthInterface;
use App\Models\User;
use App\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    private AuthInterface $authInterface;

    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }

    public function register(RegisterRequest $registerRequest)
    {
        $data = [
            'name' => $registerRequest->name,
            'email' => $registerRequest->email,
            'password' => $registerRequest->password,
            'passwordConfirm' => $registerRequest->passwordConfirm,
        ];

        DB::beginTransaction();
        try {
            $user = $this->authInterface->register($data);

            DB::commit();
            // [new UserResource($user)] la data qu'on envoie
            return ApiResponse::sendResponse(true, [new UserResource($user)], 'Opération effectuée.', 201);
        } catch (\Throwable $th) {
            
             return ApiResponse::rollback($th);

        }
    }

    public function login(LoginRequest $loginRequest)
    {
        $data = [
            'email' => $loginRequest->email,
            'password' => $loginRequest->password,
        ];

        DB::beginTransaction();
        try {
            $user = $this->authInterface->login($data);

            DB::commit();

            return ApiResponse::sendResponse(
                $user,
                [],
                'Vous êtes connecté.',
                $user ? 200 : 401
            );
        } catch (\Throwable $th) {

            return ApiResponse::rollback($th);
        }
    }

    public function logout(){
        $user = User::find(auth()->user()->getAuthIdentifier());
        $user->tokens()->delete();

        return ApiResponse::sendResponse(
            true,
            [],
            'utilisateur déconnecté',
            200
        );
    }


    public function otpCode(Request $request)
    {
        $data = [
            'email' => $request->email,
            'code' => $request->code
        ];

        DB::beginTransaction();
        try {
            $user = $this->authInterface->checkOtpCode($data);

            DB::commit();

            if (!$user) {
                return ApiResponse::sendResponse(
                    false,
                    [],
                    'code de confirmation invalide.',
                    200
                );
            }

            return ApiResponse::sendResponse(
                true,
                [new UserResource($user)],
                'opération effectuée.',
                200
            );
        } catch (\Throwable $th) {
            
            return ApiResponse::rollback($th);
        }
    }
}
