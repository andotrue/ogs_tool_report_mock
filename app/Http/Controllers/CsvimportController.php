<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Store;
use App\ReportTestTable;
use App\Testmailaddress;
use Illuminate\Http\Request;

class CsvimportController extends Controller
{

  public $functionName = "CSVインポート";
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
    $stores = Store::orderBy('id')->pluck('storename','id');
    //$testmailaddress = Testmailaddress::orderBy('id')->pluck('email','id');
    */

    $this->data = compact('users', 'stores', 'testmailaddress');

    $this->setFunctionName();

    //var_dump($this->data);
    return view('csvimport.index', $this->data);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $stores = Store::orderBy('id')->pluck('storename', 'id');
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
    //#################################################################確認画面
    if ($request['action'] == "confirm") {
      $regist_list1 = $request['regist_list1'];
      $regist_list2 = $request['regist_list2'];

      $reportTestTable = new ReportTestTable;
      foreach ($regist_list1 as $key => $val) {
        \DB::table('report_test_table')->insert(
          ['parent_user_id' => $val, 'child_user_id' => $regist_list2[$key]]
        );
      }

      $this->functionSubName = "完了";
      $this->setFunctionName();
      return view('csvimport.complate', $this->data);
      exit;
    }


    //#################################################################ファイルアップロード
    if (!$request['target']) {
      return redirect('/csvimport/')->with('error', 'パラメタエラー');
    }
    $target = "csvfile" . $request['target'];
    // アップロードファイルに対してのバリデート
    $validator = $this->validateUploadFile($request, $target);
    if ($validator->fails() === true) {
      return redirect()->route('csvimport.index')->with('errors', $validator->errors());
    }

    // CSVファイルをサーバーに保存
    $temporary_csv_file = $request->file($target)->store('csv');

    $fp = fopen(storage_path('app/') . $temporary_csv_file, 'r');

    // 一行目（ヘッダ）読み込み
    $headers = fgetcsv($fp);
    //var_dump($headers);exit;

    $column_names = [];

    // CSVヘッダ確認
    foreach ($headers as $header) {
      $result = self::retrieveColumnsByValue($header, 'SJIS-win');
      if ($result === null) {
        fclose($fp);
        \Storage::delete($temporary_csv_file);
        return redirect('/csvimport/')->with('error', '登録に失敗しました。CSVファイルのフォーマットが正しいことを確認してださい。');
      }
      $column_names[] = $result;
    }

    $registration_list = array();
    $exist_list = array();
    $i = 0;
    while ($row = fgetcsv($fp)) {
      //var_dump($row);exit;

      //mb_convert_variables('UTF-8', 'SJIS-win', $row);// Excelで編集されるのが多いと思うのでSJIS-win→UTF-8へエンコード

      $exist_cnt = ReportTestTable::where('parent_user_id', '=', $row[0])->where('child_user_id', '=', $row[1])->count();
      if (!$exist_cnt) {
        //データがなければ登録
        $registration_list[] = $row;
      } else {
        //データがあれば
        $exist_list[] = $row;
      }
      /*
      $is_registration_row = false;

      foreach ($column_names as $column_no => $column_name) {
          $is_registration_row = true;
        }

        // 新規登録か更新かのチェック
        if($is_registration_row === true){
          if ($column_name !== 'id') {
            $registration_csv_list[$i][$column_name] = $row[$column_no] === '' ? null : $row[$column_no];
          }
        } else {
          $update_csv_list[$i][$column_name] = $row[$column_no] === '' ? null : $row[$column_no];
        }
      }

      // バリデーションチェック
      $validator = \Validator::make(
        $is_registration_row === true ? $registration_csv_list[$i] : $update_csv_list[$i],
        $this->defineValidationRules(),
        $this->defineValidationMessages()
      );

      if ($validator->fails() === true) {
        if ($is_registration_row === true) {
          $registration_errors_list[$i + 2] = $validator->errors()->all();
        } else {
          $update_errors_list[$i + 2] = $validator->errors()->all();
        }
      }

      $i++;
      */
    }

    $this->data = compact('registration_list', 'exist_list');
    $this->functionSubName = "確認";
    $this->setFunctionName();

    return view('csvimport.confirm', $this->data);

    exit;


    /*
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
    */
  }

  /**
   * アップロードファイルのバリデート
   */
  private function validateUploadFile(Request $request, $target)
  {
    return \Validator::make($request->all(), [
      $target => 'required|file|mimetypes:text/plain|mimes:csv,txt',
    ], [
        $target . '.required' => 'ファイルを選択してください。',
        $target . '.file' => 'ファイルアップロードに失敗しました。',
        $target . '.mimetypes' => 'ファイル形式が不正です。',
        $target . '.mimes' => 'ファイル拡張子が異なります。',
      ]
    );
  }

  /**
   * バリデーションの定義
   */
  private function defineValidationRules()
  {
    return [
      // CSVデータ用バリデーションルール
      'content' => 'required',
    ];
  }


  /**
   * CSVヘッダ項目の定義値があれば定義配列のkeyを返す
   *
   * @param string $header
   * @param string $encoding
   * @return string|null
   */
  public static function retrieveColumnsByValue(string $header, string $encoding)
  {
    // CSVヘッダとテーブルのカラムを関連付けておく
    $list = [
      'parent_user_id' => "parent_user_id",
      'child_user_id' => "child_user_id",
    ];

    foreach ($list as $key => $value) {
      if ($header === mb_convert_encoding($value, $encoding)) {
        return $key;
      }
    }
    return null;
  }

  /**
   * Display the specified resource.
   *
   * @param  int $id
   * @return Response
   */
  public function show()
  {
    $user = User::findOrFail($id);

    $this->data = compact('user');
    $this->functionSubName = "View";
    $this->setFunctionName();

    return view('import.show', $this->data);
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
    $user = User::findOrFail($id);

    if ($request->input("name") == $user->name) {
      $rules = [
        'name' => 'required|between:5,10|alpha_num_custom',
        'email' => 'email|max:100',
        'password' => 'min:5|confirmed',
        'role' => 'required',
        'store_id' => 'required',
        'shop_name' => 'max:100',
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
    $this->validate($request, $rules);

    $user->name = $request->input("name");
    $user->email = $request->input("email");
    if ($request->input("password")) {
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
   * @param  int $id
   * @return Response
   */
  public function destroy($id)
  {
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('user.index')->with('message', 'Item deleted successfully.');
  }
}
