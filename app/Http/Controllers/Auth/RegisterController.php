<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Cache ;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            //'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'password' => bcrypt($data['password']),
        	'rec_id' => (int) $data['rec_id'] ,
        	'contact' => $data['contact'] ,
        	'qq' => $data['qq']
        ]);
    }
    
    public function register(Request $request) {
    	$mobile = $request->input('name');
    	$verify = $request->input('verify');
    	$key = "verify_" . $mobile . ":reg";
    	if( config('app.supper_verify') != $verify ) {
    		$code = Cache::get( $key );
    		if( !$code ) {
    			return response()->json(['errcode' => 10002 , 'msg' => '验证码失效']);
    		}
    		if( $verify != $code ) {
    			return response()->json(['errcode' => 10003 , 'msg' => '验证码不正确']);
    		}
    	}
    	 
    	$this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);
        if( !$this->registered($request, $user) ) {
        	
        }
		if( $request->ajax() ) {
			return response()->json(['errcode' => 0 , 'msg' => '注册成功']);
		}
		return redirect($this->redirectPath());
    }
}
