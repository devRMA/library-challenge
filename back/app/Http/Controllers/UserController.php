<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogoutOtherDevicesRequest;
use App\Http\Resources\DeviceResource;
use App\Http\Resources\UserResource;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Realiza o logout de todos os dispositivos, exceto o dispositivo atual.
     *
     * @return JsonResponse
     */
    public function logoutOtherDevices(
        LogoutOtherDevicesRequest $request
    ): JsonResponse {
        Auth::logoutOtherDevices($request->validated('password'));
        Session::query()
            ->where('user_id', auth()->id())
            ->where('id', '!=', session()->getId())
            ->delete();

        return response()->json('');
    }
}
