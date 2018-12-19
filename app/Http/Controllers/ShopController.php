<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller {

	public $functionName = "店舗管理";
	public $functionSubName = "";
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function setFunctionName()
	{
		$this->data['functionName'] = $this->functionName;
		$this->data['functionSubName'] = $this->functionSubName;
	}
	
	public function index()
	{
		$shops = Shop::orderBy('id', 'asc')->paginate(50);
		
		$this->data = compact('shops');
		$this->setFunctionName();
		
		return view('shop.index', $this->data);
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
		
		return view('shop.create', $this->data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$rules = [
			'storename' => 'required|max:255',
			//'image1' => 'mimes:image|max:3000',
		];
		
		$this->validate($request, $rules);

		$store = new Store();
		
		$store->storename = $request->input("storename");
        $store->comment = $request->input("comment");
        $store->created_tool_user_id = \Auth::user()->id;
        $store->updated_tool_user_id = \Auth::user()->id;

		$store->save();
		$insert_id = $store->id;
		
		//新規作成したレコードに対して画像ファイルの更新を行う
		$file_uploads = $this->file_upload($request, "image", $insert_id);
		$store = $store::findOrFail($insert_id);
		$store->imagedetail = $file_uploads[1];
		$store->save();

		return redirect()->route('shop.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$store = Store::findOrFail($id);
		
		$this->data = compact('store');
		$this->functionSubName = "View";
		$this->setFunctionName();
		
		return view('shop.show', $this->data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$shop = Shop::findOrFail($id);
		
		$this->data = compact('shop');
		$this->functionSubName = "編集";
		$this->setFunctionName();
		
		return view('shop.edit', $this->data);
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
		$store = Store::findOrFail($id);

		$rules = [
			'storename' => 'required|max:255',
			//'image1' => 'mimes:image|max:3000',
		];
		
		$this->validate($request, $rules);
		
		$store->storename = $request->input("storename");
        $store->comment = $request->input("comment");
        //$store->created_tool_user_id = $request->input("created_tool_user_id");
        $store->updated_tool_user_id = \Auth::user()->id;

		//PDF関連ファイルの更新を行う
        $file_uploads = $this->file_upload($request, "image", $id, "update");
		$store->imagedetail = $file_uploads[1];

        $store->save();

		return redirect()->route('shop.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$store = Store::findOrFail($id);
		$store->delete();

		return redirect()->route('shop.index')->with('message', 'Item deleted successfully.');
	}

	/*
	 * ファイルのアップロード
	 */
	public function file_upload($request, $formname = "", $insert_id = null, $func = "")
	{
		$max_filecount = 1;
		$ret = array();
		if($formname && $insert_id){
				
			$filedata = array();
			for($i=1; $i<=$max_filecount; $i++){
				$ufiles = $request->file($formname . $i);
				$hd_ufiles = $request->input("hd_".$formname . $i);
				echo "$i / func:$func<br>";
				echo "hidden image / ";var_dump($hd_ufiles);echo "<br>";
				if($func == "update" && $ufiles == null){
					echo "file / ";var_dump($ufiles);echo "<br>";
					$hd_ufile = @json_decode($hd_ufiles, true);
					//変更なし
					if(isset($hd_ufile)){
						$filedata[] = array(
								"filename"=>$hd_ufile['filename'],
								"mimetype"=>$hd_ufile['mimetype'],
								"filesize"=>$hd_ufile['filesize'],
						);
					}
					//削除設定されたばあい
					else{
	
					}
					echo "<hr>";
					continue;
				}
				if($ufiles){
					foreach($ufiles as $ufile){
						if ($ufile->isValid()) {
							$filename = $ufile->getClientOriginalName();
							$mimetype = $ufile->getMimeType();
							$filesize = $ufile->getClientSize();
							echo "file / ";var_dump($ufile);echo "<br>";
							$destinationPath = base_path()."/public/img/store/".$insert_id;
							$ufile->move($destinationPath,$filename);
								
							$filedata[] = array(
									"filename"=>$filename,
									"mimetype"=>$mimetype,
									"filesize"=>$filesize,
							);
						}
						echo "<hr>";
					}
				}
			}
			if($filedata){
				//var_dump($filenames);echo "<br>";
				$ret[0] = $ufiles;
				$filenamesJ = json_encode($filedata);
				echo "filenamesJ:" . $filenamesJ;
				echo "<hr>";
				$ret[1] = $filenamesJ;
				return $ret;
			}
			else{
				return null;
			}
		}
		else{
			return null;
		}
	}
	
	
}
