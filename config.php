<?php
/**
 * config.php
 *
 * @author	HisatoS.
 * @package Hanauta
 * @version 13/08/13 last update
 * @copyright http://www.nono150.com/
 */

/**
 *	エラー表示
 */
error_reporting(E_ALL);
ini_set("display_errors",true);
if(error_reporting() > 6143) error_reporting(E_ALL ^ E_DEPRECATED);

/**
 *	セッション表示
 */
ini_set("session.name","HanautaSID");
ini_set("session.use_trans_sid",true);

/**
 *	PEARパス設定
 */
//ini_set("include_path", ".:/home/itigoppo/pear/php");
ini_set("include_path", ".:/usr/lib/php");

///
/**
 *	全体設定
 */
// ルートパス
//define("DIR_ROOT","/home/itigoppo/www/");
define("DIR_ROOT","/Users/itigoppo/Sites/hanauta/src/");
//define("DIR_ROOT",$_SERVER["DOCUMENT_ROOT"]."/twitter/");

// ライブラリディレクトリ
define("DIR_LIB", constant("DIR_ROOT")."libs/");

/**
 * システム設定
 */
// プロジェクトディレクトリ
define("DIR_PRJ", dirname(__FILE__)."/");

// システムディレクトリ
define("DIR_SYS", constant("DIR_PRJ")."sys/");

// 各種設定ファイルディレクトリ
define("DIR_CNF",constant("DIR_PRJ")."conf/");

// プロジェクト設定ファイル
define("INI_SYS", constant("DIR_CNF")."sys.ini");

// 各API系設定ファイルディレクトリ
//define("DIR_API",constant("DIR_CNF")."api/");

// エラーログ格納ディレクトリ
define("D_DIR_ERRLOG", constant("DIR_PRJ")."tmp/error/");

/**
 *	フレームワーク設定
 */
// フレームワークディレクトリ
define("DIR_FW", constant("DIR_LIB")."hanauta/");

// フレームワーク設定ファイル
define("INI_FW",constant("DIR_CNF")."fw.ini");

/**
 *	Smarty設定
 */
// Smartyディレクトリ
define("DIR_SMARTY", constant("DIR_LIB")."smarty/");

// テンプレートディレクトリ
define("DIR_SMARTY_TMPL",constant("DIR_PRJ")."templates");

// コンパイルディレクトリ
define("DIR_SMARTY_COMPILE",constant("DIR_PRJ")."tmp/templates_c");

// キャッシュディレクトリ
define("DIR_SMARTY_CACHE",constant("DIR_PRJ")."tmp/cache");

?>