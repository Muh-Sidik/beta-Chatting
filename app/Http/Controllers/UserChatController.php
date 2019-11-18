<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserChatController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('phone', 'password');
        $get = User::where('phone', $request->phone);
        $id = $get->first();
        
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return $this->respondWithToken($token, $id);
    }

    public function get_where_phone(Request $request)
    {
        $user = User::where('phone', $request->input('phone'))
        ->first();
        if($user == null) {
        return response()->json(['send' => 'NO', 404]);
        } else {
            return response()->json(['send' => 'YES', 200]);
        }
    }

    public function getWherePhone(Request $request)
    {
        $user = User::where('phone', $request->phone)
        ->first();
            return response()->json(compact('user'));
        

    }

    public function qr($phone, $password, Request $request)
    {
        $credentials = $request->only('phone', 'password');
        $get = User::where('phone', $phone)
        ->where('password', $password);
        $id = $get->first();
        
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return $this->respondWithToken($token, $id);
    }

    protected function respondWithToken($token, $id)
    {
    return response()->json([
        'access_token'  => $token,
        'token_type'    => 'bearer',
        'expires_in'    => auth('api')->factory()->getTTL() * 60,
        'detail_user'   => $id
    ]);
    }

    public function register(Request $request)
    {
        
        $user = User::create([
            'username'  => $request->input('username'),
            'phone'     => $request->input('phone'),
            'password'  => bcrypt($request->input('password')),
            'photo'     => asset('img/profil.jpg')
        ]);

        $token = auth()->login($user);
        $get = User::where('phone', $request->phone);
        $id = $get->first(); 
        return $this->respondWithToken($token, $id);

    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }

    public function update( Request $request)
    {
        
        // $user = User::find($request->id);
        // $user->username       = $request->input('username');
        // $user->bio            = $request->input('bio');
        // $user->phone          = $request->input('phone');
        // $user->password       =bcrypt($request->input('password'));
        // $user->photo          = $request->input('photo');
        
        // // $token = JWTAuth::fromUser($user);

        // if($user->save()) {
        //     return response()->json([$user],200);
        // }
        $array = [
            'username' => $request->input('username'),
            'phone'    => $request->input('phone')
        ];

        // $user->password       = bcrypt($request->input('password'));
        // $user->photo          = $request->input('photo');
        $user = User::where('phone', '=', $request->input('phone'))->update($array);
        if($user) {
            return response()->json($user , 200);
        }
    }

    public function update_password(Request $request)
    {
        $array = [
            'password' => bcrypt($request->input('password')),
        ];

        // $user->password       = bcrypt($request->input('password'));
        // $user->photo          = $request->input('photo');
        $user = User::where('phone', '=', $request->input('phone'))->update($array);
        if($user) {
            return response()->json($user , 200);
        }
    }

    public function update_status(Request $request)
    {
        $array = [
            'bio' => $request->input('status'),
        ];

        $user = User::where('phone', '=', $request->input('phone'))->update($array);
        if($user) {
            return response()->json($user , 200);
        }
    }

    public function update_photo(Request $request)
    {
        $array = [
            'photo' => $request->input('photo'),
        ];

        $user = User::where('phone', '=', $request->input('phone'))->update($array);
        if($user) {
            return response()->json($user , 200);
        }
        
    }

    public function delete_user($id)
    {
        $delete = User::find($id);
        if($delete->delete()) {
            return response()->json(['status' => 'user dihapus'], 200);
        }
    }

    public function search(Request $request)
    {
        $user = $request->input('username');
        $username = User::where('username', 'like', '%'.$user.'%')->get();

        return response()->json($username, 200);
    }
}
