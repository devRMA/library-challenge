<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeviceResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Retorna os dados do perfil do usuário logado.
     *
     * @return JsonResponse
     */
    public function profileInformation(): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        return response()
            ->json(new UserResource($user));
    }

    /**
     * Retorna os dispositivos que estão logados na conta do usuário.
     *
     * @return JsonResponse
     */
    public function devices(): JsonResponse
    {
        /** @var User */
        $user = auth()->user();

        return response()
            ->json(DeviceResource::collection($user->sessions));
    }
}
