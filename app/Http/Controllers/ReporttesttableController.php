<?php
namespace App\Http\Controllers;

use Log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ReportTestTable;
use App\Shop;
use Illuminate\Http\Request;

class ReporttesttableController extends Controller{

  public $functionName = "テストテーブル管理";
  public $functionSubName = "";

  public function __construct()
  {
    //認証をさせる場合
    $this->middleware('auth');
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
    $message = "(DEBUG!)" . __FILE__ . "---->" . __FUNCTION__ . ":" . __LINE__;
    \Log::debug($message);

    $testtables = ReportTestTable::orderBy('id', 'asc')->paginate(25);
    $testmailaddress = ReportTestTable::where('email', '!=', '')->orderBy('id')->pluck('email','id');

    $this->data = compact('testtables','testmailaddress');

    $this->setFunctionName();

    return view('reporttesttable.index', $this->data);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $this->functionSubName = "作成";
    $this->setFunctionName();

    return view('reporttesttable.create', $this->data);
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
      /*
      'name' => 'required|between:5,10|alpha_num_custom|unique:users',
      'email' => 'email|max:100',
      'password' => 'required|min:5|confirmed',
      'role' => 'required',
      'store_id' => 'required',
      'shop_name' => 'max:100',
      */
      'parent_user_id' => 'required|between:5,10|alpha_num_custom',
      'child_user_id' => 'required|between:5,10|alpha_num_custom',
    ];

    $this->validate($request, $rules);

    $testtable = new TestTable();

    $testtable->parent_user_id = $request->input("parent_user_id");
    $testtable->child_user_id = $request->input("child_user_id");

    $testtable->save();

    return redirect()->route('reporttesttable.index')->with('message', 'Item created successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int $id
   * @return Response
   */
  public function show($id)
  {
    $testtable = ReportTestTable::findOrFail($id);

    $this->data = compact('testtable');
    $this->functionSubName = "View";
    $this->setFunctionName();

    return view('reporttesttable.show', $this->data);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int $id
   * @return Response
   */
  public function edit($id)
  {
    $testtable = ReportTestTable::findOrFail($id);
    $shops = Shop::orderBy('id')->pluck('name', 'id');

    $this->data = compact('testtable', 'shops');
    $this->functionSubName = "編集";
    $this->setFunctionName();

    return view('reporttesttable.edit', $this->data);

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int $id
   * @param Request $request
   * @return Response
   */
  public function update(Request $request, $id)
  {
    $testtable = ReportTestTable::findOrFail($id);

    // 'alpha_num_custom'は
    // app/Providers/AppServiceProvider.php
    /*
    if ($request->input("name") == $testtable->name) {
      $rules = [
        'name' => 'required|between:5,10|alpha_num_custom',
        'email' => 'email|max:100',
        'password' => 'min:5|confirmed',
        'role' => 'required',
        'store_id' => 'required',
        'shop_name' => 'max:100',
        'child_user_id' => 'required|between:5,10|alpha_num_custom',
      ];
    } else {
      $rules = [
        'name' => 'required|between:5,10|alpha_num_custom|unique:users',
        'email' => 'email|max:100',
        'password' => 'min:5|confirmed',
        'role' => 'required',
        'store_id' => 'required',
        'shop_name' => 'max:100',
      ];
    }
    */
    $rules = [
      'parent_user_id' => 'required|between:5,10|alpha_num_custom',
      'child_user_id' => 'required|between:5,10|alpha_num_custom',
    ];
    $this->validate($request, $rules);

    $testtable->parent_user_id = $request->input("parent_user_id");
    $testtable->child_user_id = $request->input("child_user_id");

    $testtable->save();

    return redirect()->route('reporttesttable.index')->with('message', 'Item updated successfully.');

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return Response
   */
  public function destroy($id)
  {
    $testtable = ReportTestTable::findOrFail($id);
    $testtable->delete();

    return redirect()->route('reporttesttable.index')->with('message', 'Item deleted successfully.');
  }

  /**
   * テストメール宛先追加
   */
  public function testmailaddress_add(Request $request)
  {
    $result = array();

    $email = $request->input("temail");
    \Log::debug("testmailaddress_add debug:" . $email);

    \DB::enableQueryLog();
    $testmailaddress = ReportTestTable::where('email', '=', $email)->first();
    \Log::debug(\DB::getQueryLog());
    //\Log::debug(print_r($testmailaddress, true));
    //\Log::debug(print_r($testmailaddress->id, true));

    if (!empty($testmailaddress)) {
      $result['status'] = 'error';
      $result['message'] = 'email exist';

      goto end;
    } else {
      $rules = [
        'temail' => 'required|email|max:100',
      ];

      //$this->validate($request, $rules);
      $validator = \Validator::make($request->all(), $rules);

      if ($validator->fails()) {
        $errors = $validator->errors();
        $err_msg = $errors->first('temail');
        \Log::debug(print_r($err_msg, true));

        $result['status'] = 'error';
        $result['message'] = $err_msg;

        goto end;
      }
    }

    $testmailaddress = new ReportTestTable();
    $testmailaddress->parent_user_id = "xxxxxx";
    $testmailaddress->child_user_id = "123456";
    $testmailaddress->email = $email;
    $testmailaddress->save();
    $id = $testmailaddress->id;

    $result['status'] = 'success';
    $result['id'] = $id;
    $result['message'] = '';
    $result['email'] = $email;


    end:

    $result = json_encode($result);
    return $result;
  }


  /**
   * テストメール宛先削除
   */
  public function testmailaddress_del($id)
  {
    \Log::debug("debug");

    $result = array();

    \DB::enableQueryLog();
    $testmailaddress = ReportTestTable::findOrFail($id);
    \Log::debug(\DB::getQueryLog());

    if ($testmailaddress) {
      if ($testmailaddress->delete()) {
        $result['id'] = $id;
        $result['status'] = 'success';
      } else {
        $result['status'] = 'error';
      }
    } else {
      $result['status'] = 'error';
    }
    $result = json_encode($result);

    return $result;
  }
}
