<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    use UserTrait;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('phone', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return $this->returnError('001','Phone or Password NOT Correct');
        }

        $user = Auth::user();
        // return  response()->json([
        //     'token' => $token,
        // ]);
        return $this->returnData('user',$user,$token,"Logged On Successfully!");

    }

    public function register(Request $request){

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|numeric|min:11|unique:users',
            'password' => 'required|string|min:8',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return $this->returnData('user',$user,"Logged On Successfully!");
    }

    public function logout(Request $request)
    {
        return $token = $request->header('auth-token');
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => ' You are Successfully logged out!',
        ]);
    }

    public function getUser(Request $request)
    {
        $user = User::where('phone' ,$request->phone)->first();
        if(!$user){
            return $this->returnError('E001','No user with this Phone Number');
        }
        return $this->returnData('user',$user);
    }
}
