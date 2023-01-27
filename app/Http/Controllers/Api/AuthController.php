<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Token;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|confirmed'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        //return response JSON user is created
        if($user) {
            return response()->json([
                'success' => true,
                'user'    => $user,  
            ], 201);
        }

        //return JSON process insert failed 
        return response()->json([
            'success' => false,
        ], 409);
    }

    public function login(Request $request){
        $credentials = request(['email', 'password']);
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $access_token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($access_token);
    }

    public function refresh() {
        $access_token = auth('api')->claims(['xtype' => 'auth'])->refresh(true,true);
        auth('api')->setToken($access_token); 

        return $this->createNewToken($access_token);
    }

    protected function createNewToken($access_token){
        // $response_array = [
        //     'accessToken' => $access_token,
        //     // 'token_type' => 'bearer',
        //     // 'access_expires_in' => auth('api')->factory()->getTTL() * 60,
        //     // 'user' => auth('api')->user(),
        // ];
    
        // $access_token_obj = Token::create([
        //     'user_id' => auth('api')->user()->id,
        //     'value' => $access_token, //or auth('api')->getToken()->get();
        //     'jti' => auth('api')->payload()->get('jti'),
        //     'type' => auth('api')->payload()->get('xtype'),
        //     'payload' => auth('api')->payload()->toArray(),
        // ]);
    
        $refresh_token = auth('api')->claims([
            'xtype' => 'refresh',
            'xpair' => auth('api')->payload()->get('jti')
            ])->setTTL(auth('api')->factory()->getTTL() * 3)->tokenById(auth('api')->user()->id);
    
        
    
        $rt = new Token;
        $rt->user_id = auth('api')->user()->id;
        $rt->value = $refresh_token;
        $rt->jti = auth('api')->setToken($refresh_token)->payload()->get('jti');
        $rt->type = auth('api')->setToken($refresh_token)->payload()->get('xtype');
        // $rt->payload = auth('api')->setToken($refresh_token)->payload()->toArray();
        $rt->save();
    
        // $access_token_obj->pair = $refresh_token_obj->id;
        // $access_token_obj->save();
        $response_array =[
            'accessToken' => $access_token,
            'refreshToken' => $refresh_token,
            'payload' => auth('api')->setToken($refresh_token)->payload()->toArray()
            // 'refresh_expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    
        return response()->json($response_array);
    }
}
