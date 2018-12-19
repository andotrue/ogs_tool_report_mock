<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ImageMst extends Model
{
	protected $dates = ['open_date','close_date'];
	
	public function getOpenDateAttribute($value)
	{
		return Carbon::parse($value)->format('Y/m/d H:i:s');
	}
	public function setOpenDateAttribute($value)
	{
		$this->attributes['open_date'] = Carbon::parse($value)->format('Y/m/d H:i:s');
		//$this->attributes['open_date'] = $value;
	}
	
	public function getCloseDateAttribute($value)
	{
		
		if(is_null($value)){
			return "";
		}else{
			return Carbon::parse($value)->format('Y/m/d H:i:s');
		}
	}
	public function setCloseDateAttribute($value)
	{
		if(is_null($value)){
			$this->attributes['close_date'] = null;
		}else{
			$this->attributes['close_date'] = Carbon::parse($value)->format('Y/m/d H:i:s');
		}
	}
}
