<?php
//var_dump($_SERVER);exit;
$siteType = (preg_match('/^\/tool*/', $_SERVER['REQUEST_URI']))? "tool" : "front";
//echo $siteType;

return [
	'domain' => $_SERVER["SERVER_NAME"],
	'siteType' => $siteType,
	'toolUserRole' => array("admin"=>"システム管理者","store"=>"施設管理者","user"=>"テナント"),
	'openFlg' => array("0"=>"未公開","1"=>"公開"),
	'imageCategory' => array("1"=>"メインバナー"),
	'linkNewWindow' => array("1"=>"作成する","2"=>"作成しない"),
];
