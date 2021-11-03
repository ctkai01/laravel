<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Libraries\Utilities;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NotAuthController extends Controller
{
    //
    public function signup(SignUpRequest $request, User $userModel) {
        try {
            DB::beginTransaction();

            $data = [];    
            if (is_numeric($request->phoneOrEmail)) {
                $data['phone'] = Utilities::clearXSS($request->phoneOrEmail);
            } else {
                $data['email'] = Utilities::clearXSS($request->phoneOrEmail);
            }
    
            $data['name'] = Utilities::clearXSS($request->full_name);
            $data['user_name'] = Utilities::clearXSS($request->user_name);
            $data['password'] = bcrypt(Utilities::clearXSS($request->password));
           
            $user = $userModel->createNewUser($data);
            
            if (!$user) {
                return response()->json([
                    'message' => __('api.signup_fail')
                ], 500);
            }

            DB::commit();
            return response()->json([
                'message' => __('api.signup_success'),
                'user' => $user
            ], 200);

        } catch(Exception $ex) {
            DB::rollBack();
            throw new \Exception($ex->getMessage(), 500);
        }
    }

    public function login(LoginRequest $request, User $userModel) {
        try {
            $user = $userModel->getUserByEmailOrPhone($request->phoneOrEmail);
            
            if (!$user) {
                return response()->json([
                    'message' => __('api.phone_or_email_incorrect')
                ], 500);
            }

    
            if (!Hash::check(Utilities::clearXSS($request->password), $user->password)) {
                return response()->json([
                    'message' => __('api.password_incorrect') 
                ], 500);
            }

            $token = $this->createToken($user);

            return response()->json([
                'message' => __('api.login_success'),
                'token' => config("constants.TOKEN_TYPE") . $token->accessToken,
                'user' => $user,
                'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString(),
            ]);

        } catch(Exception $ex) {
            throw new Exception($ex->getMessage(), 500);
        }
    }

    public function createToken($user) {
        $tokenResult = $user->createToken(config('constants.USER_TOKEN'));
        $tokenResult->token->expires_at = Carbon::now()->addDays(10);
        $tokenResult->token->save();
        return $tokenResult;
    }

}
