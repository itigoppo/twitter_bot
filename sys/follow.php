<?php
/**
 * follow.php
 *
 * @author	HisatoS.
 * @package Pantter
 * @version 14/01/08 last update
 * @copyright http://www.nono150.com/
 */

/**
 * フォロー関連クラス
 *
 * @author HisatoS.
 * @access public
 * @package Pantter
 */

class follow{

	/**
	 * コンストラクタ
	 */
	function __construct(){
	}

	/**
	 * フォロされているリスト取得
	 *
	 * @param string $id
	 * @param string $mode
	 * @return array
	 */
	function get_followers($id=false,$mode=false){
		global $Hanauta;
		global $login_data;

		$rtn = false;
		$rtn_arr = array();

		$next_cursor = -1;
		while($next_cursor != 0){
			$options = array(
					"count" => 200,
					"skip_status" => true,
					"include_user_entities" => false,
			);
			if($next_cursor != -1){
				$options["cursor"] = $next_cursor;
			}
			if($id){
				if($mode == "id"){
					$options["user_id"] = $id;
				}elseif($mode == "screen"){
					$options["screen_name"] = $id;
				}
			}
			$tl_data = $Hanauta->obj["twitter"]->getFollowers($login_data,$options);

			if(isset($tl_data["status"]["errors"])){
				$next_cursor = 0;
				break;
			}else{
				$next_cursor = $tl_data["status"]["next_cursor_str"];
				foreach($tl_data["status"]["users"] as $key => $val){
					$item = array(
							"name" => $val["name"],
							"screen_name" => $val["screen_name"],
					);
					$rtn_arr[] = $item;
				}
			}
		}

		$rtn = $rtn_arr;
		return $rtn;
	}
}
