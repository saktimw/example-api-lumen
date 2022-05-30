<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ], 
        [ 'required' => 'atribut harus diisi' ]);

        if ($validator->fails())
        {
            return response() ->json([
                'response' => [
                    'code' => 406,
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ]
            ]);
        }

        $credentials = User::where('username', $request->username)
            ->where('password', md5($request->password))
            ->first();

        if (empty($credentials))
        {
            return response() ->json([
                'response' => [
                    'code' => 406,
                    'status' => 'error',
                    'message' => 'Tidak memiliki akses',
                ]
            ]); 
        }

        $token = Auth::login($credentials);
        $metadata = [
            'response' => [
                'code' => 200,
                'status' => 'ok'
            ]
        ];        

        return $this->respondWithToken($token, $metadata);

    }

    public function logout()
    {
        return response()->json(Auth::logout());
    }

    public function me()
    {
        return auth()->refresh();
    }

}