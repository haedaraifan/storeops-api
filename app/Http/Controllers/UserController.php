<?php

namespace App\Http\Controllers;

use App\Helpers\ExceptionResponseHelper;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Authentication;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function generateFileName($image): string
    {
        $extension = $image->getClientOriginalExtension();
        $fileName = now()->format("ymdHisu") . '.' . $extension;
        return $fileName;
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $role = Role::whereName($data["role"])->first();

        if(User::whereEmail($data["email"])->first()) {
            ExceptionResponseHelper::throwInvariantError("Email telah terdaftar.");
        }
        if(User::whereName($data["name"])->first()) {
            ExceptionResponseHelper::throwInvariantError("Nama telah terdaftar.");
        }

        $user = new User($data);
        $user->password = Hash::make($data["password"]);
        $user->role_id = $role->id;
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): UserResource
    {
        $data = $request->validated();
        $user = User::whereName($data["name"])->first();

        if(!$user || !Hash::check($data["password"], $user->password)) {
            ExceptionResponseHelper::throwAuthenticationError("Email atau password salah.");
        }

        $token = Str::uuid()->toString();
        $expiredAt = now()->addYear();
        $user->token = $token;

        $authentication = new Authentication();
        $authentication->user_id = $user->id;
        $authentication->token = $token;
        $authentication->expired_at = $expiredAt;
        $authentication->save();

        return new UserResource($user);
    }

    public function get(Request $request): UserResource
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request): UserResource
    {
        $data = $request->validated();
        $user = Auth::user();

        if(isset($data["name"])) {
            $user->name = $data["name"];
        }

        if(isset($data["password"])) {
            $user->password = Hash::make($data["password"]);
        }

        if($request->hasFile("image")) {
            $image = $request->file("image");
            $fileName = $this->generateFileName($image);
            $imagePath = $image->storeAs("images", $fileName, "public");
            $imageUrl = asset('storage/' . $imagePath);
            $user->image = $imageUrl;
        }

        $user->save();
        return new UserResource($user);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->header("AUTHORIZATION");
        Authentication::whereToken($token)->delete();

        return response()->json([
            "message" => "Logout berhasil."
        ])->setStatusCode(200);
    }
}
