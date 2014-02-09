<?php
/**
 * 11510_.php
 *
 * @author	HisatoS.
 * @package twitter_bot
 * @version 14/01/05 last update
 * @copyright http://www.nono150.com/
 */

// 設定ファイル
require_once("./cronset.php");
require_once("./config.php");
$bot_id = "11510_";
// apiディレクトリ別設定
define("DIR_API",constant("DIR_CNF")."api/".$bot_id."/");

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

// Twitter認証
$Hanauta->_svars["token"]["access_token"] = "40831111-rv5rvopAyajZg9zlXqpyPmvA0u7BtXSmvbrcwLS0n";
$Hanauta->_svars["token"]["access_token_secret"] = "eNY89IBcfE66DeVnPWhbPpcKsrv1Y3rNHo46WjHdBM";
// panda_api
//$Hanauta->_svars["token"]["access_token"] = "115958635-Nu7SqE7hlV2aXm2TFx9OpxXrLUdnf5AdBx9W22eJ";
//$Hanauta->_svars["token"]["access_token_secret"] = "BXwxm73uTclw4JBsJz3kNvhBphW5wtEo3OgTsxbLrM";
$login_data = $Hanauta->obj_ext["login"]->twitter();

$posts = $Hanauta->obj_ext["pandbot"]->setPosts_11510();
$post_flg = $Hanauta->obj_ext["pandbot"]->updatePosts($posts);


$Hanauta->obj["ponpon"]->pr($posts);
//$Hanauta->obj["ponpon"]->pr($Hanauta);

/**
 *	テンプレート
 */
// テンプレート用変数設定


// 処理時間計測終了
$Hanauta->obj["benchmark"]->end();
$smarty->assign("cpus",$Hanauta->obj["benchmark"]->score);

// テンプレート出力
//$smarty->display($tmp_file);
