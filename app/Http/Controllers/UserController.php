<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegRequest;
use App\Http\Resources\UserResources;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegRequest $request):JsonResponse{
        $data = $request->validated();

        if(User::where('email', $data['email'])->count()==1){
            throw new HttpResponseException(response([
                "errors"=>[
                    "email"=>[
                        "Email sudah terdaftar"
                    ]
                ]
            ],400));
        }

        $user = new User($data);
        $user->password= Hash::make($data['password']);
        $user->save();

        return (new UserResources($user))-> response() ->setStatusCode(201);
    }
}
