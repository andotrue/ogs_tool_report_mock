<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ReportTestTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ImageController extends Controller
{

  public $functionName = "画像管理";
  public $functionSubName = "";

  public function __construct()
  {
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
    $images = ReportTestTable::orderBy('id', 'desc')->paginate(10);

    $this->data = compact('images');

    $this->setFunctionName();

    return view('image.index', $this->data);
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

    return view('image.create', $this->data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return Response
   */
  public function store(Request $request)
  {
    //$file_uploads = $this->file_upload($request, "image", 99);exit;

    $rules = [
      'imagename' => 'required|max:255',
      'open_flg' => 'required',
      'open_date' => 'required|date_format:Y/m/d H:i:s',
      'close_date' => 'date_format:Y/m/d H:i:s|after:open_date',
      'linkurl' => 'url',
      //'image1' => 'image|max:3000',
    ];
    $this->validate($request, $rules);

    $image = new ReportTestTable();
    $image->parent_user_id = "imageinsert";
    $image->child_user_id = "imageinsert";
    $image->imagename = $request->input("imagename");
    $image->open_flg = $request->input("open_flg");
    $image->open_date = $request->input("open_date");
    $image->close_date = $request->input("close_date") ? $request->input("close_date") : null;
    $image->category = $request->input("category");

    $image->save();
    $insert_id = $image->id;
    //echo "insert id : " . $insert_id;

    //新規作成したレコードに対して画像ファイルの更新を行う
    $file_uploads = $this->file_upload($request, "image", $insert_id);
    $image = ReportTestTable::findOrFail($insert_id);
    $image->imagefiledetail = $file_uploads[1];
    $image->save();

    return redirect()->route('image.index')->with('message', 'Item created successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  int $id
   * @return Response
   */
  public function show($id)
  {
    $image = ReportTestTable::findOrFail($id);

    $this->data = compact('image');
    $this->functionSubName = "View";
    $this->setFunctionName();

    return view('image.show', $this->data);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int $id
   * @return Response
   */
  public function edit($id)
  {
    $image = ReportTestTable::findOrFail($id);

    $this->data = compact('image');

    $this->functionSubName = "編集";
    $this->setFunctionName();

    return view('image.edit', $this->data);
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
    //var_dump($request->file("image1"));
    //var_dump($request->input("hd_image1"));
    //exit;

    $image = ReportTestTable::findOrFail($id);

    $rules = [
      'imagename' => 'required|max:255',
      'open_flg' => 'required',
      'open_date' => 'required|date_format:Y/m/d H:i:s',
      'close_date' => 'date_format:Y/m/d H:i:s|after:open_date',
      'linkurl' => 'url',
      'image1' => 'image|max:3000',
    ];
    $this->validate($request, $rules);

    $image->imagename = $request->input("imagename");
    $image->open_flg = $request->input("open_flg");
    $image->open_date = $request->input("open_date");
    $image->close_date = $request->input("close_date") ? $request->input("close_date") : null;
    $image->category = $request->input("category");
    //$image->created_tool_user_id = \Auth::user()->id;
    $image->updated_tool_user_id = \Auth::user()->id;

    //PDF関連ファイルの更新を行う
    $file_uploads = $this->file_upload($request, "image", $id, "update");
    $image->imagefiledetail = $file_uploads[1];

    //\DB::connection()->enableQueryLog();

    //処理
    $image->save();
    //echo "<pre>";var_dump(\DB::getQueryLog());echo "<pre>";exit;

    return redirect()->route('image.index')->with('message', 'Item updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return Response
   */
  public function destroy($id)
  {
    $image = ReportTestTable::findOrFail($id);
    $image->delete();

    return redirect()->route('image.index')->with('message', 'Item deleted successfully.');
  }


  /*
   * ファイルのアップロード
   */
  public function file_upload($request, $formname = "", $insert_id = null, $func = "")
  {
    $max_filecount = 1;
    $ret = array();
    if ($formname && $insert_id) {

      $filedata = array();
      for ($i = 1; $i <= $max_filecount; $i++) {
        $ufiles = $request->file($formname . $i);
        $hd_ufiles = $request->input("hd_" . $formname . $i);
        $linkurl = $request->input("linkurl");
        $link_new_window = $request->input("link_new_window");

        echo "$i / func:$func<br>";
        echo "hidden image / ";
        var_dump($hd_ufiles);
        echo "<br>";
        if ($func == "update" && $ufiles == null) {
          echo "file / ";
          var_dump($ufiles);
          echo "<br>";
          $hd_ufile = @json_decode($hd_ufiles, true);
          //変更なし
          if (isset($hd_ufile)) {
            $filedata[] = array(
              "filename" => $hd_ufile['filename'],
              "mimetype" => $hd_ufile['mimetype'],
              "filesize" => $hd_ufile['filesize'],
              "linkurl" => $linkurl,
              "link_new_window" => $link_new_window,
            );
          } //削除設定されたばあい
          else {

          }
          echo "<hr>";
          continue;
        }
        if ($ufiles) {
          foreach ($ufiles as $ufile) {
            if ($ufile->isValid()) {
              $filename = $ufile->getClientOriginalName();
              $mimetype = $ufile->getMimeType();
              $filesize = $ufile->getClientSize();
              echo "file / ";
              var_dump($ufile);
              echo "<br>";
              $destinationPath = base_path() . "/public/img/image/" . $insert_id;
              $ufile->move($destinationPath, $filename);

              $filedata[] = array(
                "filename" => $filename,
                "mimetype" => $mimetype,
                "filesize" => $filesize,
                "linkurl" => $linkurl,
                "link_new_window" => $link_new_window,
              );
            }
            echo "<hr>";
          }
        }
      }
      if ($filedata) {
        //var_dump($filenames);echo "<br>";
        $ret[0] = $ufiles;
        $filenamesJ = json_encode($filedata);
        echo "filenamesJ:" . $filenamesJ;
        echo "<hr>";
        $ret[1] = $filenamesJ;
        return $ret;
      } else {
        return null;
      }
    } else {
      return null;
    }
  }

}
