<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', Rules\Password::defaults()],
                'role' => ['required', 'string', 'max:255']
            ]);

            DB::beginTransaction();
            try {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]);
                $user['access_token'] = $user->createToken('jala-tech')->accessToken;
                event(new Registered($user));
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->sendError($e->getMessage(), $e->getMessage());
            }
            DB::commit();
            return $this->sendResponse(new UserResource($user));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage(), $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);

            if (Auth::attempt($request->toArray())) {
                $user = User::find(Auth::id());

                $user['access_token'] = $user->createToken('jala-tech')->accessToken;
                return $this->sendResponse(new UserResource($user));
            } else {
                return $this->sendError('Email or password is wrong', "", 401);
            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
    }

}
