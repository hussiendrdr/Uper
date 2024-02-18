<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginNeedVerification;
use http\Env\Response;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function submit(Request $request){
        $request->validate([
           'phone'=>'required|numeric|min:10'
        ]);
        $user=User::firstOrCreate([
            'phone'=>$request->phone
        ]);

        if(!$user){
            return response()->json(['message'=>'error with your phone number'],401);


        }

        $user->notify(new LoginNeedVerification());
        return  response()->json(['message'=>'SMS has been sent']);
    }
    public function verify(Request $request){
        $request->validate([
            'phone'=>'required|numeric|min:10',
            'login_code'=>'required|numeric|between:11111,99999'
        ]);
        $user=User::query()->where('phone',$request->phone)
            ->where('login_code',$request->login_code)->first();

        if($user){
            $user->update([
               'login_code'=>null,
            ]);
            return $user->createToken($request->login_code)->plainTextToken;
        }
        return \response()->json(['message'=>'invalid verification code'],401);

    }
}
