<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();
        
        $validate = Validator::make($registrationData, [
            'namaUser' => 'required|max:64',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required',
            'jenisKelamin' => 'required',
            'noTelpUser' => 'required|numeric|digits_between:10,13|phoneRules',
        ]);

        if($validate->fails())
        {
            return response(['message' => $validate->errors()], 400);
        }

        $user = User::create([
            'namaUser' => $registrationData['namaUser'],
            'email' => $registrationData['email'],
            'password' => bcrypt($registrationData['password']),
            'jenisKelamin' => $registrationData['jenisKelamin'],
            'noTelpUser' => $registrationData['noTelpUser'],
        ]);

        event(new Registered($user));

        return response([
            'message' => 'Register Success',
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]); 

        if ($validate->fails())
            return response(['message' => $validate->error()], 400); 
        
        if (!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'], 401); 

        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken; 
        
        return response([
            'message' => 'Authenticated',
            'data' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]); 
    }


    // FUNGSI UGD

    public function index()
    {
        $users = User::all();

        if(count($users) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $users
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $user = User::find($id);

        if(!is_null($user))
        {
            return response([
                'message' => 'Retrieve user Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'user Not Found',
            'data' => null
        ], 404);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if(is_null($user))
        {
            return response([
                'message' => 'user Not Found',
                'data' => null
            ], 404);
        }

        if($user->delete())
        {
            return response([
                'message' => 'Delete user Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Delete user Failed',
            'data' => null
        ], 400);
    }

    public function Update(Request $request, $id)
    {
        $user = User::find($id);

        if(is_null($user))
        {
            return response([
                'message' => 'user Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'namaUser' => 'required|max:64',
            'jenisKelamin' => 'required',
            'noTelpUser' => 'required|numeric|digits_between:10,13|phoneRules',
            'saldo' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $user->namaUser = $updateData['namaUser'];
        $user->jenisKelamin = $updateData['jenisKelamin'];
        $user->noTelpUser = $updateData['noTelpUser'];
        $user->urlGambar = $updateData['urlGambar'];
        $user->saldo = $updateData['saldo'];

        if($user->save())
        {
            return response([
                'message' => 'Update user Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Update user Failed',
            'data' => null
        ], 400);
    }
}