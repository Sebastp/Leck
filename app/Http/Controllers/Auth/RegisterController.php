<?php

namespace leck\Http\Controllers\Auth;

use leck\User;
use leck\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
     protected $redirectTo = '/';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function register_view(){
      $data = array(
      'view_type' => 'signup',
      );

      return view('auth.signs')->with($data);

    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $request)
    {
      $rules = array(
        'token' => 'required',
        'user_name' => 'required|min:2|max:50',
        'email' => 'required|email|min:6|max:255|unique:users',
        'password' => 'required|min:6|max:30',
      );

      $messages = [
      'user_name.max' => 'User name is too long',
      'user_name.min' => 'User name is too short',
      'email.email' => 'Email is not valid',
      'email.unique' => 'Email has been already used',
      'password.max' => 'Password is too long',
      'password.min' => 'Password is too short',
      ];

      $vaildator = Validator::make([
        'token' => $request['_token'],
        'user_name' => $request['username'],
        'email' => $request['email'],
        'password' => $request['password'],
      ], $rules, $messages);

      return $vaildator;
    }


    protected function is_valid(Request $request)
    {
      $toVaild['_token'] = $request->_token;
      $toVaild['username'] = $request->username;
      $toVaild['email'] = $request->email;
      $toVaild['password'] = $request->password;

      $vaildator = $this->validator($toVaild);

      if ($vaildator->fails()) {
        return response()->json([
          'valid' => false,
          'errors' => $vaildator->errors()->toJson()
        ]);
      }else {
        return response()->json([
          'valid' => true
        ]);
      }
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $request)
    {
      return User::create([
        'str_id' => self::createStr_id($request['username']),
        'nickname' => $request['username'],
        'email' => $request['email'],
        'password' => bcrypt($request['password']),
      ]);
    }



    public function createStr_id($user_nm)
    {
      $pure_url = strtolower($user_nm);
      $req = DB::table('users')->select('str_id')->where('str_id', 'like', $pure_url.'%')->count();
      if ($req == 0) {
        return $pure_url;
      }else {
        return $pure_url.'.'.$req;
      }
    }
}
