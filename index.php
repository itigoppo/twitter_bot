<?php
/**
 * index.php
 *
 * @author	HisatoS.
 * @package MorningPorker
 * @version 11/12/06 last update
 * @copyright http://www.nono150.com/
 */

// 設定ファイル
require_once("./config.php");

// 共通処理ファイル
require_once("./common.php");

/**
 * 変数設定
 */
// テンプレートファイル名
$tmp_file = "index.tpl";

// ページ用各変数初期化
$login_data = false;

/**
 * 処理開始
 */
// ヘッダ出力
$heder_xml = constant("CONTENT_TYPE_XML");
$heder_html = constant("CONTENT_TYPE_HTML");
if($mode == "rss") header($heder_xml);
else header($heder_html);

// セッション削除
$Hanauta->obj["request"]->del_ses($Hanauta->_svars,array("auth"));
if(isset($Hanauta->_gvars["logout"])){
	$Hanauta->obj["request"]->del_ses($Hanauta->_svars,array());
}

//$Hanauta->obj["ponpon"]->pr($Hanauta);

/**
 *	テンプレート
 */
// テンプレート用変数設定


// 処理時間計測終了
$Hanauta->obj["benchmark"]->end();
$smarty->assign("cpus",$Hanauta->obj["benchmark"]->score);

// テンプレート出力
$smarty->display($tmp_file);

// 解析タグ
if($Hanauta->site_info["server"] != NULL){
	include_once("/home/itigoppo/www/cgi/ana/lunasys/analyzer/write.php");
}
?>