<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function logout(Request $request) {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => __('api.logout_success'),
        ], 200);
    }
}
