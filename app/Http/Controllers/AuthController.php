<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\BearerTokenServiceProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        Log::info($request);
        $validator = Validator::make($request->all(), [
            'v_e' => 'required|in:V,E',
            'ced' => 'required|min:1|max:8',
            'p_a' => 'required|string',
            's_a' => 'nullable|string',
            'p_n' => 'required|string',
            's_n' => 'nullable|string',
            'email' => 'required|email',
            
            'password' => [ 'required',
                            Password::min(8)
                            ->letters()
                            ->mixedCase()
                            ->numbers()
                            ->symbols()
                          ],
            'id_rol' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

         $exists = User::where([
            "login" => $request->login
        ])->get();

        if ($exists->count()) {
            return response()->json(['error' => 'User exists in DB', 'code' => '002'], 401);
        }

        DB::beginTransaction();
        try {
            $user = new User();
            $user = $user->register($request);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return response()->json(['user' => $user], 200);
    }

    public function registerEmployee(Request $request)
    {
        Log::info($request);
        $validator = Validator::make($request->all(), [
            'v_e' => 'required|in:V,E',
            'ced' => 'required|min:1|max:8',
            'p_a' => 'required|string',
            's_a' => 'nullable|string',
            'p_n' => 'required|string',
            's_n' => 'nullable|string',
            'email' => 'required|email',
            'id_rol' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

         $exists = User::where([
            "login" => $request->login
        ])->get();

        if ($exists->count()) {
            return response()->json(['error' => 'User exists in DB', 'code' => '002'], 401);
        }

        DB::beginTransaction();
        try {
            $user = new User();
            $user = $user->registerEmployee($request);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return response()->json(['user' => $user], 200);
    }

    // User login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        $exists = User::where([
            "login" => $request->login
        ])->get();

        if (!$exists->count()) {
            return response()->json(['error' => 'User does not exists in DB', 'code' => '000'], 401);
        }

        $active = User::where('login', $request->login)->value('active');
        
        if ($active == false) {
            return response()->json(['error' => 'Inactive User', 'code' => '001'], 400);
        }
        else {
            $token = auth()->attempt($credentials);
    
            if (!$token) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Incorrect Credentials'
                ], 401);
            }
        
            return response()->json([
                'user' => auth()->user(),
                'token' => $token,
                'expire_in' => auth()->factory()->getTTL() * 60
            ]);
        }
    }

    // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refreshOTP(Request $request)
    {
        Log::info($request);
        $validator = Validator::make($request->all(), [
            'login' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::where([
            'login' => $request['login']
        ])->get();
    
        if (!$user->count()) {
            return response()->json(['error' => 'User does not exists in Data Base', 'code' => '000'], 400);
        }

        $otp = User::where('login', $request->login)->value('otp');
        
        if (!empty($otp)) {
            try {
            $new_otp = new User();
            
            //ENVIAR CORREO...

            $new_otp = $new_otp->refreshOTP($request);
            } catch (Exception $e) {
                return $e->getMessage();
            }   
        }else {
            return response()->json(['error' => 'This user does not need a OTP', 'code' => '003'], 400);
        }
        
        return response()->json([
            'message' => 'OTP Updated',
            'new_otp' => $new_otp,
            'login' => $request->login
            ], 200);
    }

    public function validateUser(Request $request)
    {
        Log::info($request);
        $validator = Validator::make($request->all(),[
            'login' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::where([
            'login' => $request['login']
        ])->get();
    
        if (!$user->count()) {
            return response()->json(['error' => 'User does not exists in Data Base', 'code' => '000'], 400);
        }

        DB::beginTransaction();
        try {
            $user = new User();
            $user = $user->validateUser($request);
            $app_url = env('APP_URL');
            
            //COLOCAR ENVIO DE CORREO...
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return response()->json(['message' => 'User Validation successfull'], 200);
    }

    public function validateEmail(Request $request)
    {
        Log::info($request);
        $validator = Validator::make($request->all(), [
            'otp' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $data = User::where([
            'otp' => $request['otp']
        ])->get();

        if ($data->count()) {
            try {
                $user = new User();

                //ENVIAR CORREO...

                $user->validateEmail($request); //Validar Email
                return response()->json(['message' => 'Email validated succesfull'], 200);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }else {
            return response()->json(['error' => 'Invalid code, please check it again', 'code' => '004'], 400);
        }
    }

    public function changePassword(Request $request)
    {
        Log::info($request);
        $validator = Validator::make($request->all(),[
            'login' => 'required',
            'password' => ['required',
                Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
            ]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::where([
            'login' => $request->login
        ])->get();

        if (!$user->count()) {
            return response()->json(['error' => 'User does not exists in DB', 'code' => '000'], 400);
        }

        try {
            $user = new User();

            //ENVIAR CORREO...

            $user->changePassword($request);
            return response()->json(['message' => 'Password renoved'], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function activeUser($login)
    {
        $data = User::query()->where('login', $login)->update(['active' => true]);

        return response()->json(['message' => 'User Actived successfull'], 200);
    }

    public function desactiveUser($login)
    {
        $data = User::query()->where('login', $login)->update(['active' => false]);

        return response()->json(['message' => 'User Desactived successfull'], 200);
    }
}
