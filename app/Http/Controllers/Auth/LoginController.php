<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\User;
use App\Client;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login / registration.
   * 認証後のリダイレクト先の場所を定義
   * @var string
   */
  protected $redirectTo = '/tool';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $mg = "(DEBUG!)" . __FILE__ . "---->" . __FUNCTION__ . ":" . __LINE__;logger($mg);

    //$this->authenticate();
    $this->middleware('guest', ['except' => 'logout']);
  }

  //認証用のキー(+ password)とするカラムを設定
  //↓のmethodをOverride
  //vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php
  public function username()
  {
    //return 'email';
    return 'login_id';
  }

  /*
  public function validateCredentials(UserContract $user, array $credentials)
  {
    $plain = $credentials['login_password'];

    return $this->hasher->check($plain, $user->getAuthPassword());
  }
  protected function validateLogin(Request $request)
  {
    $this->validate($request, [
      $this->username() => 'required|string',
      'login_password' => 'required|string',
    ]);
  } protected function credentials(Request $request)
  {
    return $request->only($this->username(), 'login_password');
  }
  */

  //↓のmethodをOverride
  //vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php
  public function showLoginForm()
  {
    $mg = "(DEBUG!)" . __FILE__ . "---->" . __FUNCTION__ . ":" . __LINE__;logger($mg);
    /*
    $stores = Store::orderBy('id')->pluck('storename', 'id');

    $all_storeId = @array_search('全施設', $stores->toArray());
    $stores_imgd = Store::orderBy('id')->pluck('imagedetail', 'id');
    $all_store_logo = @$stores_imgd[$all_storeId];
    $all_store_logo = json_decode($all_store_logo, true);
    $all_store_logo = $all_storeId . "/" . @$all_store_logo[0]['filename'];
    */

    \View::share('page_title', "ログイン画面");
    //\View::share('all_store_logo', $all_store_logo);
    return view('auth.login');



  }

  //↓のmethodをOverride
  //vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php
  protected function sendFailedLoginResponse(Request $request)
  {
    $mg = "(DEBUG!)" . __FILE__ . "---->" . __FUNCTION__ . ":" . __LINE__;logger($mg);

    return redirect()->back()
      ->withInput($request->only($this->username(), 'remember'))
      ->withErrors([
        $this->username() => Lang::get('名前またはパスワードが違います'),
      ]);
  }


  public function login(Request $request)
  {
    $mg = "(DEBUG!)" . __FILE__ . "---->" . __FUNCTION__ . ":" . __LINE__;logger($mg);

    $mg = '';
    if ($request->isMethod('post')) {
      $login_id = $request->login_id;
      $password = $request->password;
      $mg = "(DEBUG!)" . __FILE__ . "---->" . __FUNCTION__ . ":" . __LINE__;logger($mg);

      $user = Client::where('login_id', $login_id)->first();
      //if (!empty($user) && $password == $user->login_password) //平文認証
      if (!empty($user) && password_verify($password, $user->login_password))
      {
        //if (Hash::check($password, $user->login_password)){
        $ret = Auth::loginUsingId($user->id);
        logger("ret:".$ret);
        logger("id:".Auth::id());
        return redirect('/');
      }
      else {
        $mg = "(DEBUG!)" . __FILE__ . "---->" . __FUNCTION__ . ":" . __LINE__;logger($mg);
        $message = 'ログインに失敗しました。';
      }

      /*
      $user = Client::where('login_id', $login_id)->first();
      var_dump(password_hash($password, PASSWORD_DEFAULT)) ;
      var_dump(password_verify($password, $user->login_password));
      //exit;
      $credentials = $request->only('login_id', 'password');
      if (Auth::attempt($credentials)) {
        // 認証に成功した
        return redirect()->intended('/');
      }
      */
    }
    $mg = "(DEBUG!)" . __FILE__ . "---->" . __FUNCTION__ . ":" . __LINE__;logger($mg);
    return redirect()->back()
      ->withInput($request->only($this->username(), 'remember'))
      ->withErrors([
        $this->username() => Lang::get('名前またはパスワードが違います'),
      ]);
  }

  public function logout(Request $request)
  {
    echo $referer = $request->headers->get('referer');
    $referers = parse_url($referer);
    $path = $referers['path'];
    if (!preg_match('/^\/tool*/', $path)) $this->redirectTo = "/";

    // vendor/laravel/framework/src/Illuminate/Auth/Authenticatable.php
    // protected $rememberTokenName = 'remember_token';
    // ↓
    // protected $rememberTokenName = '';
    $this->guard()->logout();
    $request->session()->flush();
    $request->session()->regenerate();
    return redirect($this->redirectTo);
  }

  /*
  protected function credentials(Request $request)
  {
    return $request->only($this->username(), 'login_password');
  }

  protected function attemptLogin(Request $request)
  {
    return $this->guard()->attempt(
      $this->credentials($request)
    );
  }
  */
}
