<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResources;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(UserRegRequest $request):JsonResponse{
        $data = $request->validated();

        if(User::where('email', $data['email'])->count()==1){
            throw new HttpResponseException(response([
                "errors"=>[
                    "message"=>[
                        "Email sudah terdaftar"
                    ]
                ]
            ],400));
        }

        $user = new User($data);
        $user->password= Hash::make($data['password']);
        $user->token= Str::uuid()->toString();
        $user->save();

        return (new UserResources($user))-> response() ->setStatusCode(201);
    }

    public function login(UserLoginRequest $request):UserResources{
        $data=$request->validated();
        $user= User::where('email',$data['email'])->first();
        if(!$user || !Hash::check($data['password'],$user->password)){
            throw new HttpResponseException(response([
                "errors"=>[
                    "message"=>[
                        "Email or Password is wrong"
                    ]
                ]
            ],401));
        }
        $user->token= Str::uuid()->toString();
        $user->save();
        return new UserResources($user);
    }
    public function profile():UserResources{
       $user = Auth::user();
       return new UserResources($user);
    }

    public function update(UserUpdateRequest $request):UserResources
    {
        $data=$request->validated();
        $user = Auth::user();
        if(isset($data['name'])){
            $user->name=$data['name'];
        }

        if(isset($data['password'])){
            $user->password=Hash::make($data['password']);
        }

        if(isset($data['company'])){
            $user->company=$data['company'];
        }
        $user->save();
        return new UserResources($user);
    }
    public function logout():JsonResponse{
        $user = Auth::user();
        $user->token=null;
        $user->save();
        return response()->json([
            'data'=>true
            ]
        )->setStatusCode(200);
    }
}
