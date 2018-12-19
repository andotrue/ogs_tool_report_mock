<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Store;
use App\Testmailaddress;
use Illuminate\Http\Request;

class UserController extends Controller {

	public $functionName = "ユーザー管理";
	public $functionSubName = "";

	public function __construct()
	{
		//認証をさせる場合
		//$this->middleware('auth.tool');
	}

	public function setFunctionName()
	{
		$this->data['functionName'] = $this->functionName;
		$this->data['functionSubName'] = $this->functionSubName;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	  /*
		if(\Auth::user()->role == "admin"){
			$users = User::orderBy('id', 'asc')->paginate(25);
		}
		if(\Auth::user()->role == "store"){
			$users = User::where('store_id','=',\Auth::user()->store_id)->orderBy('id', 'desc')->paginate(10);
		}
	  */
    $users = User::orderBy('id', 'asc')->paginate(25);
		$stores = Store::orderBy('id')->pluck('storename','id');
		//$testmailaddress = Testmailaddress::orderBy('id')->pluck('email','id');
		//var_dump($testmailaddress);
		
		$this->data = compact('users','stores','testmailaddress');

		$this->setFunctionName();

		return view('user.index', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$stores = Store::orderBy('id')->pluck('storename','id');
		$this->data = compact('stores');

		$this->functionSubName = "作成";
		$this->setFunctionName();

		return view('user.create', $this->data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		// 'alpha_num_custom'は
		// app/Providers/AppServiceProvider.php
		// 定義
		$rules = [
			'name' => 'required|between:5,10|alpha_num_custom|unique:users',
			'email' => 'email|max:100',
			'password' => 'required|min:5|confirmed',
			'role' => 'required',
			'store_id' => 'required',
			'shop_name' => 'max:100',
		];

		$this->validate($request, $rules);

		$user = new User();

		$user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->password = bcrypt($request->input("password"));
        $user->role = $request->input("role");
        $user->store_id = $request->input("store_id");
        $user->shop_name = $request->input("shop_name");

		$user->save();

		return redirect()->route('user.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::findOrFail($id);

		$this->data = compact('user');
		$this->functionSubName = "View";
		$this->setFunctionName();

		return view('user.show', $this->data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::findOrFail($id);
		$stores = Store::orderBy('id')->pluck('storename','id');

		$this->data = compact('user','stores');
		$this->functionSubName = "編集";
		$this->setFunctionName();

		return view('user.edit', $this->data);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$user = User::findOrFail($id);

		if($request->input("name") == $user->name){
			$rules = [
				'name' => 'required|between:5,10|alpha_num_custom',
				'email' => 'email|max:100',
				'password' => 'min:5|confirmed',
				'role' => 'required',
				'store_id' => 'required',
				'shop_name' => 'max:100',
			];
		}
		else{
			$rules = [
				'name' => 'required|between:5,10|alpha_num_custom|unique:users',
				'email' => 'email|max:100',
				'password' => 'min:5|confirmed',
				'role' => 'required',
				'store_id' => 'required',
				'shop_name' => 'max:100',
			];
		}
		$this->validate($request, $rules);

		$user->name = $request->input("name");
		$user->email = $request->input("email");
		if($request->input("password")){
			$user->password = bcrypt($request->input("password"));
		}
		$user->role = $request->input("role");
		$user->store_id = $request->input("store_id");
		$user->shop_name = $request->input("shop_name");

		$user->save();

		return redirect()->route('user.index')->with('message', 'Item updated successfully.');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = User::findOrFail($id);
		$user->delete();

		return redirect()->route('user.index')->with('message', 'Item deleted successfully.');
	}

	/*
	 * テストメール宛先追加
	 */
	public function testmailaddress_add(Request $request)
	{
		$result = array();

		$email = $request->input("temail");
		\Log::debug("testmailaddress_add debug:".$email);

		$testmailaddress = Testmailaddress::where('email','=',$email);
		\Log::debug(print_r(isset($testmailaddress->id), true));
		
		if(isset($testmailaddress)){
			$result['status'] = 'error';
			$result['message'] = 'email exist';

			goto end;
		}
		else{
			$rules = [
				'temail' => 'required|email|max:100',
			];

			//$this->validate($request, $rules);
			$validator = \Validator::make($request->all(),$rules);
			
			if ($validator->fails()) {
				$errors = $validator->errors();
				$err_msg = $errors->first('temail');
				\Log::debug(print_r($err_msg,true));
					
				$result['status'] = 'error';
				$result['message'] = $err_msg;
					
				goto end;
			}
		}
		
		$testmailaddress = new Testmailaddress();
		$id = $testmailaddress->email = $email;
		$testmailaddress->save();
		
		$result['status'] = 'success';
		$result['id'] = $id;
		$result['message'] = '';
		$result['email'] = $email;
		
		
		end:
	
		$result = json_encode($result);
		return $result;
	}
	
	
	/*
	 * テストメール宛先削除
	 */
	public function testmailaddress_del($id)
	{
		$result = array();

		$testmailaddress = Testmailaddress::findOrFail($id);
		if($testmailaddress){
			if($testmailaddress->delete()){
				$result['id'] = $id;
				$result['status'] = 'success';
			}
			else{
				$result['status'] = 'error';
			}
		}
		else{
			$result['status'] = 'error';
		}
		$result = json_encode($result);

		return $result;
	}
}
