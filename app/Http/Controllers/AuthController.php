<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Token\Parser;

class AuthController extends Controller
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['result' => $success, 'success'=> true], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|unique:users|email',
            'password' => 'required',
            'confrim_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
        return response()->json(['result' => $success, 'success'=> true], $this->successStatus);
    }
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function user()
    {
        $user = Auth::user();
        return response()->json(['result' => $user, 'success'=> true], $this->successStatus);
    }

    public function  refreshToken()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $client = DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success, 'success'=> true], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
        
        // $response = Http::asForm()->post('http://127.0.0.1:8000', [
        //     'grant_type' => 'refresh_token',
        //     'refresh_token' => 'the-refresh-token',
        //     'client_id' => 'client-id',
        //     'client_secret' => 'client-secret',
        //     'scope' => '',
        // ]);
        // return response()->json(['success' => $response], 200);
    }
}
