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

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        if($user) {
            return response()->json([
                'success' => true,
                'user'    => $user,  
            ], 201);
        }

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

    public function refresh(Request $request) {
        $rules = [
            'token' => 'required'
        ];

        $isValid = Validator::make($request->all(),$rules);
        if($isValid->fails())
            return response(['errors' => $isValid->errors()], 422);
        
        
        $refresh_token = auth('api')->setToken($request->input('token'))->payload();
        if($refresh_token->get('xtype') == 'refresh'){
            $today = date('ymdhis');
            if($today < date('ymdhis',$refresh_token->get('exp'))){
                    $access_token = auth('api')->claims(['xtype' => 'auth'])->refresh(true,true);
                    auth('api')->setToken($access_token); 
                    return $this->createNewToken($access_token);
            }else{
                return response(['error' => 'Refresh Token expired'], 422);
            }
        }else{
            return response(['Refresh token invalid'], 422);
        }
    }

    protected function createNewToken($access_token){
    
        $refresh_token = auth('api')->claims([
            'xtype' => 'refresh',
            'xpair' => auth('api')->payload()->get('jti')
            ])->setTTL(auth('api')->factory()->getTTL() * 3)->tokenById(auth('api')->user()->id);
    
        
        $rt = new Token;
        $rt->id_user = auth('api')->user()->id;
        $rt->value = $refresh_token;
        $rt->jti = auth('api')->setToken($refresh_token)->payload()->get('jti');
        $rt->type = auth('api')->setToken($refresh_token)->payload()->get('xtype');
        $rt->save();
    
        $response_array =[
            'accessToken' => $access_token,
            'refreshToken' => $refresh_token
        ];
    
        return response()->json($response_array);
    }
}
