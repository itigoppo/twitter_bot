<?php
/**
 * login.php
 *
 * @author	HisatoS.
 * @package Pantter
 * @version 13/08/23 last update
 * @copyright http://www.nono150.com/
 */

/**
 * ログイン関連クラス
 *
 * @author HisatoS.
 * @access public
 * @package Pantter
 */

class login{

	/**
	 * コンストラクタ
	 */
	function __construct(){
	}

	/**
	 * twitter認証
	 *
	 * @access public
	 * @param bool		$regist		未登録時に登録するか
	 * @return array	エラーメッセージ、認証キー
	 */
	function twitter($regist = false){
		global $Hanauta;

		$rtn = array();
		$consumer = NULL;
		$token = array(
				"auth_flg" => false,
				"access_token" => NULL,
				"access_token_secret" => NULL
		);
		$auth_data = array(
				"db" => false,
				"user" => array()
		);
		$save_flg = false;

		$token = false;
		if(isset($Hanauta->site_info["db_prefix"])){
			// DBからトークン取得
			$save_flg = true;

			$auth_data = $this->auth();
			if(isset($auth_data["user"]["token"]) && isset($auth_data["user"]["token_secret"])){
				$token = array(
						"auth_flg" => true,
						"access_token" => $auth_data["user"]["token"],
						"access_token_secret" => $auth_data["user"]["token_secret"]
				);
			}
		}
		if(isset($Hanauta->_svars["token"]["access_token"]) && isset($Hanauta->_svars["token"]["access_token_secret"])){
			// session内トークン取得
			//if(isset($Hanauta->_svars["token"]["flg"]) && $Hanauta->_svars["token"]["flg"] == "callback"){
				$token = array(
						"auth_flg" => true,
						"access_token" => $Hanauta->_svars["token"]["access_token"],
						"access_token_secret" => $Hanauta->_svars["token"]["access_token_secret"]
				);
			//}
		}
		$consumer = $Hanauta->obj["twitter"]->twitter_auth($token);

		if($token["auth_flg"]){
			// 認証済み
			$rtn_arr = array(
					"type" => "obj",
					"consumer" => $consumer,
					"regist" => false
			);
			if($save_flg && isset($auth_data["user"]["token"]) && isset($auth_data["user"]["token_secret"])){
				$rtn_arr["user"] = $auth_data["user"];
			}

			// 未登録時は登録する
			if($save_flg && $regist && !$auth_data["db"]){
				$regist_rtn = $this->regist($token);
				$rtn_arr["error"] = $regist_rtn["error"];
				$rtn_arr["regist"] = $regist_rtn["regist"];
			}
		}else{
			// 登録用URL返却
			$rtn_arr = array(
					"type" => "url",
					"consumer" => $consumer,
					"regist" => false
			);
		}

		$rtn = $rtn_arr;
		return $rtn;
	}

	/**
	 * ユーザー認証
	 *
	 * @access public
	 * @return array	エラーメッセージor認証キー
	 */
	function auth(){
		global $Hanauta;

		$rtn = false;
		$auth = array();
		$auth_flg = false;
		$mode = false;
		if(isset($Hanauta->_gvars["login_id"])){
			$login_id = $Hanauta->_gvars["login_id"];
		}elseif(isset($Hanauta->_pvars["login_id"])){
			$login_id = $Hanauta->_pvars["login_id"];
		}else{
			$login_id = false;
		}
		$uid = $Hanauta->obj["mobile"]->get_uid();

		// 情報読み出し
		$tbl_name = $Hanauta->site_info["db_prefix"]."user";
		$fld_name = NULL;

		if(isset($Hanauta->_svars["auth"]["key"])){
			// 認証済み
			$mode = "auth";
			$where = NULL;
			$db_param = array();
		}elseif(isset($Hanauta->_pvars["submit"]) || isset($Hanauta->_pvars["Submit"]) || ($uid && $login_id)){
			// ログイン
			$mode = "login";
			$where = "id='".$login_id."'";
			$db_param = array(
					"limit" => 1
			);
		}
		if($mode){
			$db_rtn = $Hanauta->obj["read_db"]->select_db($tbl_name,$fld_name,$where,$db_param);
		}else{
			$db_rtn = false;
		}

		// 一致ID検索
		$rtn_arr = array();
		if(!$db_rtn){
			$rtn_arr["db"] = false;
		}else{
			$rtn_arr["db"] = true;
			$user_data = array();
			for($cnt1 = 0;$cnt1 < $Hanauta->obj["read_db"]->get_result($db_rtn,"rows");$cnt1++){
				$user_data = $Hanauta->obj["string"]->encode_str($Hanauta->obj["read_db"]->get_result($db_rtn,"assoc"));
				if($mode == "auth"){
					// 認証チェック
					if(isset($Hanauta->_svars["auth"]["type"]) && $Hanauta->_svars["auth"]["type"] == "uid"){
						$key = md5($user_data["id"].$user_data["uid"].$user_data["salt"]);
					}else if(isset($Hanauta->_svars["auth"]["type"]) && $Hanauta->_svars["auth"]["type"] == "pass"){
						$key = md5($user_data["id"].$user_data["pass"].$user_data["salt"]);
					}else{
						$key = NULL;
					}
					if($Hanauta->_svars["auth"]["key"] == $key){
						$auth_flg = true;
						break;
					}
				}else if($mode == "login"){
					// ログインチェック
					if(!isset($Hanauta->_pvars["login_pass"]) && $uid){
						if($user_data["uid"] == $uid){
							$auth_flg = true;
							$key = md5($user_data["id"].$user_data["uid"].$user_data["salt"]);
							$auth = array(
									"type" => "uid",
									"key" => $key
							);
							break;
						}
					}else if(isset($Hanauta->_pvars["login_pass"])){
						if($user_data["pass"] == $Hanauta->_pvars["login_pass"]){
							$auth_flg = true;
							$key = md5($user_data["id"].$user_data["pass"].$user_data["salt"]);
							$auth = array(
									"type" => "pass",
									"key" => $key
							);
							break;
						}
					}
				}
			}
		}

		if($auth_flg){
			if($mode == "login")
				$Hanauta->obj["request"]->vars2ses("auth",$auth);
			$rtn_arr["user"] = $user_data;
		}else{
			$rtn_arr["user"] = false;
		}

		$rtn = $rtn_arr;
		return $rtn;
	}

	/**
	 * ユーザー登録
	 *
	 * @access public
	 * @param array		$token		token情報
	 * @return array	エラーメッセージ
	 */
	function regist($token){
		global $Hanauta;

		$rtn = false;
		$rtn_arr = array(
				"regist" => false
		);
		$now_date = gmdate("Y-m-d H:i:s",time() + $Hanauta->site_info["time_zone"]);

		// エラーチェック
		$chk_arr = array(
				"login_id" => array(
						array(
								"rule" => "len",
								"msg" => mb_ereg_replace("##MSG##","ID",$Hanauta->error["E0012"]),
								"param" => array(
										"max" => 20,
										"min" => 3
								)
						),
						array(
								"rule" => "han_mix",
								"msg" => mb_ereg_replace("##MSG##","ID",$Hanauta->error["E0007"])
						),
						array(
								"rule" => "noempty",
								"msg" => mb_ereg_replace("##MSG##","ID",$Hanauta->error["E0001"])
						),
				),
				"login_pass" => array(
						array(
								"rule" => "len",
								"msg" => mb_ereg_replace("##MSG##","パスワード",$Hanauta->error["E0012"]),
								"param" => array(
										"max" => 20,
										"min" => 3
								)
						),
						array(
								"rule" => "han_mix",
								"msg" => mb_ereg_replace("##MSG##","パスワード",$Hanauta->error["E0007"])
						),
						array(
								"rule" => "noempty",
								"msg" => mb_ereg_replace("##MSG##","パスワード",$Hanauta->error["E0001"])
						),
				),
				"keyword" => array(
						array(
								"rule" => "same",
								"msg" => $Hanauta->obj["twitter"]->regist_ng,
								"param" => array(
										"word" => $Hanauta->obj["twitter"]->regist_word
								)
						),
						array(
								"rule" => "noempty",
								"msg" => mb_ereg_replace("##MSG##","キーワード",$Hanauta->error["E0001"])
						),
				),
		);
		$error = $Hanauta->obj["validate"]->error_msg($chk_arr);

		// ID重複チェック
		$tbl_name = $Hanauta->site_info["db_prefix"]."user";
		if(!$error["error"]){
			$fld_name = "id";
			$where = "id='".$Hanauta->_pvars["login_id"]."'";
			$db_param = array();
			$db_rtn = $Hanauta->obj["read_db"]->select_db($tbl_name,$fld_name,$where,$db_param);
			if($db_rtn){
				$error["error"] = true;
				$error["login_id"] = "ご指定のIDはすでに登録されています。";
			}
		}
		$rtn_arr["error"] = $error;

		if(!$error["error"]){
			$rand_key = $Hanauta->obj["string"]->randam_str(20,"Mix");
			$sql = "insert into ".$tbl_name."(id,pass,salt,token,token_secret,ip,regist_date) values("."'".$Hanauta->_pvars["login_id"]."',"."'".$Hanauta->_pvars["login_pass"]."',"."'".$rand_key."',"."'".$token["access_token"]."',"."'".$token["access_token_secret"]."',"."'".$Hanauta->_srvars["REMOTE_ADDR"]."',"."'".$now_date."'".")";
			$result = $Hanauta->obj["read_db"]->send_query($sql,1);
			if($result){
				$rtn_arr["regist"] = true;
			}
		}

		$rtn = $rtn_arr;
		return $rtn;
	}

	/**
	 * 最終ログイン時間更新
	 *
	 * @access public
	 * @param string	$id			ユーザーID
	 * @return array	エラーメッセージ
	 */
	function set_last_login($id){
		global $Hanauta;

		$rtn = true;
		$now_date = gmdate("Y-m-d H:i:s",time() + $Hanauta->site_info["time_zone"]);

		$tbl_name = $Hanauta->site_info["db_prefix"]."user";
		$sql = "update ".$tbl_name." set login_date='".$now_date."' where id='".$id."'";

		$result = $Hanauta->obj["read_db"]->send_query($sql,1);
		if($result){
			$rtn = false;
		}else{
			$rtn = true;
		}

		return $rtn;
	}
}
