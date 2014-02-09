<?php
/**
 * pandbot.php
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

class pandbot{

	/**
	 * コンストラクタ
	 */
	function __construct(){
	}

	function updatePosts($posts){
		global $Hanauta;
		global $login_data;

		$rtn = false;

		foreach($posts as $key => $val){
			$options = array(
					"status" => $val["text"],
			);
			if(isset($val["res"])) $options["in_reply_to_status_id"] = $val["res"];
			$tl_data = $Hanauta->obj["twitter"]->updateStatus($login_data,$options);
		}

		return $rtn;
	}

	/**
	 * 発言リスト取得
	 * @param string $file
	 * @return array
	 */
	function getPostList($file){
		global $Hanauta;

		$rtn = false;
		$lines = file("./text/".$file.".log");
		$now_day = $Hanauta->time_info["mon"].$Hanauta->time_info["day"];
		$now_time = $Hanauta->time_info["time_h"].$Hanauta->time_info["time_i"];

		$rtn_arr = array();
		foreach($lines as $key => $val){
			$val = trim($val);
			list($mode,$time,$text) = explode("<>",$val);
			if($time == "-"){
				$rtn_arr[$mode][] = $text;
			}else{
				if($mode == "event_day"){
					// 日付イベント
					if($now_day == $time){
						$rtn_arr["normal"][] = $text;
					}
				}elseif($mode == "event_time"){
					// 時間イベント
					$times = explode("-",$time);
					if($now_time >= $times[0] && $now_time < $times[1]){
						$rtn_arr["time"][] = $text;
					}
				}
			}
		}

		$rtn = $rtn_arr;
		return $rtn;
	}

	/**
	 * botアカウントリスト
	 * @return array
	 */
	function getBotIds(){
		$rtn = false;
		$rtn_arr = array();
		$lines = file("./text/bot_list.log");
		foreach($lines as $key => $val){
			$val = trim($val);
			$rtn_arr[] = $val;
		}
		$rtn = $rtn_arr;
		return $rtn;
	}

	/**
	 * ご本人告知bot
	 * @return boolean
	 */
	function setPosts_itigoppo(){
		global $Hanauta;
		global $login_data;
		global $bot_id;
		$rtn = false;

		$now_day = $Hanauta->time_info["mon"].$Hanauta->time_info["day"];
		$now_time = $Hanauta->time_info["time_h"].$Hanauta->time_info["time_i"];
		$now_week = $Hanauta->time_info["week"];
		$today = $Hanauta->time_info["year"]."-".$Hanauta->time_info["mon"]."-".$Hanauta->time_info["day"];
		$yesterday = date("Y-m-d",strtotime("-1day",strtotime($today)));

		// 発言リスト
		$post_list = $this->getPostList($bot_id);

		$posts = array();
		$post_flg = false;


		// 通常ポスト
		$post = NULL;
		if(!$post_flg){
			if(rand(0,5) < 4){
				exit;
			}
			$rand_cnt = rand(0,(count($post_list["normal"])-1));
			$post = $post_list["normal"][$rand_cnt];
			$posts[] = array("text"=>$post);
			$post_flg = true;
		}

		$rtn = $posts;
		return $rtn;
	}

	/**
	 * いいぱんだ用
	 * @return boolean
	 */
	function setPosts_11panda(){
		global $Hanauta;
		global $login_data;
		global $bot_id;
		$rtn = false;

		$reply_limit_cnt = 2;
		$now_day = $Hanauta->time_info["mon"].$Hanauta->time_info["day"];
		$now_time = $Hanauta->time_info["time_h"].$Hanauta->time_info["time_i"];
		$now_week = $Hanauta->time_info["week"];
		$today = $Hanauta->time_info["year"]."-".$Hanauta->time_info["mon"]."-".$Hanauta->time_info["day"];
		$yesterday = date("Y-m-d",strtotime("-1day",strtotime($today)));

		$tb_name = $Hanauta->site_info["db_prefix"]."at11panda";

		// 発言リスト
		$post_list = $this->getPostList($bot_id);
		// botリスト
		$bot_list = $this->getBotIds();

		$posts = array();
		$post_flg = false;
		$res_flg = false;

		// 起床就寝
		$post = NULL;
		if($now_week != "0" && $now_week != "6"){
			// 平日睡眠停止
			if($now_time >= "0245" && $now_time < "0915"){
				exit;
			}

			if($now_time >= "0915" && $now_time < "0930"){
				$post = "おはぱんだー！";
			}
			if($now_time >= "1215" && $now_time < "1230"){
				$post = "現実逃避。";
			}
			if($now_time >= "1300" && $now_time < "1315"){
				$post = "もぞり。昼の部ー。";
			}
			if($now_time >= "1930" && $now_time < "1945"){
				$post = "脱走っ！とぅ！";
			}
			if($now_time >= "2100" && $now_time < "2115"){
				$post = "おうち。";
			}
			if($now_time >= "0215" && $now_time < "0230"){
				$post = "おふとん。おやすみぱんだ！";
			}
		}elseif($now_week == "7"){
			// 土曜睡眠停止
			if($now_time >= "0245" && $now_time < "1230"){
				exit;
			}

			if($now_time >= "1230" && $now_time < "1245"){
				$post = "おは、、、おそようございまぱんだ。";
			}
			if($now_time >= "0215" && $now_time < "0230"){
				$post = "おふとん。おやすみぱんだ！";
			}
		}else{
			// 日曜睡眠停止
			if($now_time >= "0315" && $now_time < "1230"){
				exit;
			}

			if($now_time >= "1230" && $now_time < "1245"){
				$post = "おは、、、おそようございまぱんだ。";
			}
			if($now_time >= "0245" && $now_time < "0300"){
				$post = "はっ。寝ないと。おやすみぱんだ。";
			}
		}
		if(!$post_flg && $post){
			$posts[] = array("text"=>$post);
			$post_flg = true;
		}

		// 誕生日
		if($now_time >= "0000" && $now_time < "0015"){
			$bd_arr = file("./text/bd_".$bot_id.".log");
			foreach($bd_arr as $key => $val){
				$post = NULL;
				$val = trim($val);
				list($birth,$id,$name) = explode("<>",$val);
				if($now_day == $birth){
					$post = "@".$id." ぱんぱかぱーん！".$name."誕生日おめぱんだー！って言っとけって @itigoppo がいってた！";

					if($post){
						$posts[] = array(
								"text" => $post
						);
						$post_flg = true;
					}
				}
			}
		}

		// かわいいカウント
		$cute_cnt = 0;
		$fldname = "count(status_id)";
		$where = "text REGEXP '(かわいい|カワイイ|可愛い)' and push_flg=1";
		$db_param = array();
		$db_rtn = $Hanauta->obj["read_db"]->select_db($tb_name,$fldname,$where,$db_param);
		if($db_rtn){
			$entry = $Hanauta->obj["string"]->encode_str($Hanauta->obj["read_db"]->get_result($db_rtn,"assoc"));
			$cute_cnt = $entry["count(status_id)"];
		}

		// リプライ反応周り
		// リプライ保存
		$this->getRepLists($tb_name);


		// その他リプライ
		if(!$post_flg){
			// データ取得
			$fldname = "status_id,name,screen_name,text";
			$where = "push_flg=0";
			$db_param = array("orderby"=>"regist_date asc","offset"=>0,"limit"=>1);
			$db_rtn = $Hanauta->obj["read_db"]->select_db($tb_name,$fldname,$where,$db_param);
			if($db_rtn){
				$post = NULL;
				$entry = $Hanauta->obj["string"]->encode_str($Hanauta->obj["read_db"]->get_result($db_rtn,"assoc"));
				// 複数＠数
				$at_cnt = preg_match_all("/@/",$entry["text"],$at_matches);
				// RT数
				$rt_cnt = preg_match_all("/RT @/",$entry["text"],$rt_matches);
				if(preg_match("/おやすみ|オヤスミ|おやす|おはよう|おは|オハヨウ|ただいま|タダイマ/",$entry["text"]) || (preg_match("/あり|ありがとう/",$entry["text"]) && $at_cnt != 0) || $rt_cnt != 0){
					// 挨拶＠、複数＠でのありがとう、RTはスルー
				}elseif(preg_match("/おやすみ|オヤスミ|おやす/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_sleep"])-1));
					$post = $post_list["at_sleep"][$rand_cnt];
				}elseif(preg_match("/おはよう|おは|オハヨウ/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_morning"])-1));
					$post = $post_list["at_morning"][$rand_cnt];
				}elseif(preg_match("/おかえり|おかー/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_home"])-1));
					$post = $post_list["at_home"][$rand_cnt];
				}elseif(preg_match("/お疲れ|お疲れ様|おつかれ/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_otsu"])-1));
					$post = $post_list["at_otsu"][$rand_cnt];
				}elseif(preg_match("/おめ|おめでとう/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_thx"])-1));
					$post = $post_list["at_thx"][$rand_cnt];
				}elseif(preg_match("/わしゃ|なでなで|ナデナデ/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_wasya"])-1));
					$post = $post_list["at_wasya"][$rand_cnt];
				}elseif(preg_match("/ｼｬｰ|シャー|がぉー|ガォー|ｶﾞｫｰ|がおー|ガオー|がぶ|ガブ/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_ikaku"])-1));
					$post = $post_list["at_ikaku"][$rand_cnt];
				}elseif(preg_match("/ぎゅ/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_gyu"])-1));
					$post = $post_list["at_gyu"][$rand_cnt];
				}elseif(preg_match("/なでて/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_nadete"])-1));
					$post = $post_list["at_nadete"][$rand_cnt];
				}elseif(preg_match("/なに|何|用/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_what"])-1));
					$post = $post_list["at_what"][$rand_cnt];
				}elseif(preg_match("/かわいい|可愛い|カワイイ/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_cute"])-1));
					$post = $post_list["at_cute"][$rand_cnt];
					$cute_cnt++;
				}elseif(preg_match("/いらない|要らない|いらん|いらぬ/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_no"])-1));
					$post = $post_list["at_no"][$rand_cnt];
				}elseif(preg_match("/本体|ひさと|主人|ひさにゃ/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_main"])-1));
					$post = $post_list["at_main"][$rand_cnt];
				}

				if($post){
					$post = mb_ereg_replace("##ID##",$entry["screen_name"],$post);
					$post = mb_ereg_replace("##NAME##",$entry["name"],$post);
					$post = mb_ereg_replace("##COUNT##",$cute_cnt,$post);
					$posts[] = array("text"=>$post,"res"=>$entry["status_id"]);
					$post_flg = true;
					$res_flg = true;
				}
				// フラグ更新
				$sql = "update ".$tb_name." set push_flg='1' where status_id='".$entry["status_id"]."'";
				$result = $Hanauta->obj["read_db"]->send_query($sql,1);
				if(!$result) $error_flg = true;
			}
		}


		// TLに反応
		if(!$post_flg){
			$options = array();
			$options = array(
					"count" => 20,
					"trim_user" => false,
					"exclude_replies" => true,
					"contributor_details" => true,
					"include_rts" => true
			);
			$tl_data = $Hanauta->obj["twitter"]->getHomeTimeline($login_data,$options);
			foreach($tl_data["status"] as $key => $val){
				$post = NULL;

				// 土日は通常ポストのみ
				if($now_week == "0" || $now_week == "6"){
					if($now_time >= "0230" && $now_time < "0515"){
						break;
					}
				}
				// 本人は無視
				if($val["user"]["screen_name"] == $bot_id){
					continue;
				}
				// いざ
				if(!preg_match("/@/",$val["text"]) && preg_match("/おやすみ|おやす|オヤスミ|寝る|ねる|おふとん/",$val["text"])){
					$rand_cnt = rand(0,(count($post_list["tl_sleep"])-1));
					$post = $post_list["tl_sleep"][$rand_cnt];
				}elseif(!preg_match("/@/",$val["text"]) && preg_match("/おはよう|オハヨウ|おは|出社|しゅっしゃ|ｶｯ|起き|がばっ|むくり|ｶﾞﾊﾞｯ/",$val["text"])){
					$rand_cnt = rand(0,(count($post_list["tl_morning"])-1));
					$post = $post_list["tl_morning"][$rand_cnt];
				}elseif(!preg_match("/@/",$val["text"]) && preg_match("/おうち|ただいま|タダイマ|帰って|帰宅/",$val["text"])){
					$rand_cnt = rand(0,(count($post_list["tl_home"])-1));
					$post = $post_list["tl_home"][$rand_cnt];
				}elseif(!preg_match("/@/",$val["text"]) && preg_match("/さむい|寒い/",$val["text"])){
					$rand_cnt = rand(0,(count($post_list["tl_cold"])-1));
					$post = $post_list["tl_cold"][$rand_cnt];
				}

				if($post){
					$post = mb_ereg_replace("##ID##",$val["user"]["screen_name"],$post);
					$post = mb_ereg_replace("##NAME##",$val["user"]["name"],$post);
					$posts[] = array("text"=>$post,"res"=>$val["id_str"]);
					$post_flg = true;
					break;
				}
			}
		}

		// 時間イベント
		$post = NULL;
		if(!$post_flg && isset($post_list["time"])){
			$rand_cnt = rand(0,(count($post_list["time"])-1));
			$post = $post_list["time"][$rand_cnt];
			if($post){
				$posts[] = array("text"=>$post);
				$post_flg = true;
			}
		}

		// 通常ポスト
		$post = NULL;
		if(!$post_flg || ($post_flg && $res_flg)){
			$normal_flg = false;
			$followers = $Hanauta->obj_ext["follow"]->get_followers();

			$screen_name = NULL;
			$name = NULL;
			if(count($followers) > 0){
				$rand_cnt = rand(0,(count($followers)-1));
				$screen_name = $followers[$rand_cnt]["screen_name"];
				$name = $followers[$rand_cnt]["name"];
			}

			while(!$normal_flg){
				$rand_cnt = rand(0,(count($post_list["normal"])-1));
				$post = $post_list["normal"][$rand_cnt];

				if(count($followers) == 0 && preg_match("/##/",$post)){
				}else{

					// 愛してない感じ←
					if(preg_match("/さん、だーいすきっ！/",$post) || preg_match("/あいするひとーは/",$post)){
						if($screen_name == "kuroyuma" || $screen_name == "sayama_yuki" || $screen_name == "lyricalstep365"){
							$name = "ほとりー";
						}
					}

					$post = mb_ereg_replace("##ID##",$screen_name,$post);
					$post = mb_ereg_replace("##NAME##",$name,$post);
					$posts[] = array("text"=>$post);
					$post_flg = true;
					$normal_flg = true;
				}
			}
		}

		if($now_week != "0" && $now_week != "6"){
			if($now_time >= "1945" && $now_time < "2100"){
				foreach($posts as $key => $val){
					$val["text"] .= " [PanT!]";
					$posts[$key] = $val;
				}
			}
		}


		$rtn = $posts;
		return $rtn;
	}

	/**
	 * イイゴドーさん用
	 * @return boolean
	 */
	function setPosts_11510(){
		global $Hanauta;
		global $login_data;
		global $bot_id;
		$rtn = false;

		$reply_limit_cnt = 2;
		$now_day = $Hanauta->time_info["mon"].$Hanauta->time_info["day"];
		$now_time = $Hanauta->time_info["time_h"].$Hanauta->time_info["time_i"];
		$now_week = $Hanauta->time_info["week"];
		$today = $Hanauta->time_info["year"]."-".$Hanauta->time_info["mon"]."-".$Hanauta->time_info["day"];
		$yesterday = date("Y-m-d",strtotime("-1day",strtotime($today)));

		$tb_name = $Hanauta->site_info["db_prefix"]."at11510";

		// 発言リスト
		$post_list = $this->getPostList($bot_id);
		// botリスト
		$bot_list = $this->getBotIds();

		$posts = array();
		$post_flg = false;
		$res_flg = false;

		// 起床就寝
		$post = NULL;
		if($now_week != "0" && $now_week != "6"){
			// 睡眠停止
			if($now_time >= "0230" && $now_time < "0515"){
				exit;
			}
			if($now_time >= "0515" && $now_time < "0530"){
				$post = "クッ…！目覚めちまったぜ。";
			}
			if($now_time >= "0215" && $now_time < "0230"){
				$post = "……おっと。布団が、俺を呼んでるぜ。";
			}

			if(!$post_flg && $post){
				$posts[] = array(
						"text" => $post
				);
				$post_flg = true;
			}
		}

		// 誕生日
		if($now_time >= "0000" && $now_time < "0015"){
			$bd_arr = file("./text/bd_".$bot_id.".log");
			foreach($bd_arr as $key => $val){
				$post = NULL;
				$val = trim($val);
				list($birth,$id,$name) = explode("<>",$val);
				if($now_day == $birth){
					$post = "@".$id." …なあ".$name."さんよォ。コーヒーのプレゼントなんてどうだい？今日誕生日だろ？コネコちゃん。";

					if($post){
						$posts[] = array(
								"text" => $post
						);
						$post_flg = true;
					}
				}
			}
			if($now_day == "0510"){
				$post = "クッ…。オレの日だぜ。祝ってもいいんだぜコネコちゃん！";
				$posts[] = array(
						"text" => $post
				);
				$post_flg = true;
			}
		}

		// 今日もらったコーヒー数
		$coffee_cnt = 0;
		$fldname = "status_id,name,screen_name,text";
		$where = "push_flg=1 and regist_date between '".$today." 00:00:00' and '".$today." 23:59:59'";
		$db_param = array(
				"orderby" => "regist_date asc"
		);
		$db_rtn = $Hanauta->obj["read_db"]->select_db($tb_name,$fldname,$where,$db_param);
		if($db_rtn){
			$entry = array();
			for($cnt1 = 0;$cnt1 < $Hanauta->obj["read_db"]->get_result($db_rtn,"rows");$cnt1++){
				$entry = $Hanauta->obj["string"]->encode_str($Hanauta->obj["read_db"]->get_result($db_rtn,"assoc"));
				if(preg_match("/(っ|つ).*(珈琲|コーヒー|こーひー)/",$entry["text"])){
					$coffee_cnt++;
				}
			}
		}
		// 昨日の＠トップを取得
		$love_id = "_naruhodou_bot";
		$love_name = "まるほどう";
		$fldname = "screen_name,name,count(status_id)";
		$where = "push_flg=1 and regist_date between '".$yesterday." 00:00:00' and '".$yesterday." 23:59:59'";
		$db_param = array(
				"groupby" => "screen_name asc",
				"offset" => 0,
				"limit" => 1
		);
		$db_rtn = $Hanauta->obj["read_db"]->select_db($tb_name,$fldname,$where,$db_param);
		if($db_rtn){
			$entry = $Hanauta->obj["string"]->encode_str($Hanauta->obj["read_db"]->get_result($db_rtn,"assoc"));
			$love_id = $entry["screen_name"];
			$love_name = $entry["name"];
		}

		// リプライ反応周り
		// リプライ保存
		$this->getRepLists($tb_name);

		// おみくじ
		if(!$post_flg && isset($post_list["at_mikuji"])){
			// データ取得
			$fldname = "status_id,name,screen_name,text";
			$where = "push_flg=0 and text like '@11510_ ごどみくじ%'";
			$db_param = array(
					"orderby" => "regist_date asc"
			);
			$db_rtn = $Hanauta->obj["read_db"]->select_db($tb_name,$fldname,$where,$db_param);
			if($db_rtn){
				$entry = array();
				for($cnt1 = 0;$cnt1 < $Hanauta->obj["read_db"]->get_result($db_rtn,"rows");$cnt1++){
					$error_flg = false;
					$entry = $Hanauta->obj["string"]->encode_str($Hanauta->obj["read_db"]->get_result($db_rtn,"assoc"));

					$rand_cnt = rand(0,(count($post_list["at_mikuji"]) - 1));
					$post = $post_list["at_mikuji"][$rand_cnt];
					$post = mb_ereg_replace("##ID##",$entry["screen_name"],$post);
					$post = mb_ereg_replace("##NAME##",$entry["name"],$post);
					if($post){
						$posts[] = array(
								"text" => $post,
								"res" => $entry["status_id"]
						);
						$post_flg = true;
						$res_flg = true;
						// フラグ更新
						$sql = "update ".$tb_name." set push_flg='1' where status_id='".$entry["status_id"]."'";
						$result = $Hanauta->obj["read_db"]->send_query($sql,1);
						if(!$result){
							$error_flg = true;
						}
					}
				}
			}
		}
		// その他リプライ
		if(!$post_flg || ($post_flg && $res_flg)){
			// データ取得
			$fldname = "status_id,name,screen_name,text";
			$where = "push_flg=0";
			$db_param = array("orderby"=>"regist_date asc");
			$db_rtn = $Hanauta->obj["read_db"]->select_db($tb_name,$fldname,$where,$db_param);
			if($db_rtn){
				$post = NULL;
				$entry = $Hanauta->obj["string"]->encode_str($Hanauta->obj["read_db"]->get_result($db_rtn,"assoc"));
				// 複数＠数
				$at_cnt = preg_match_all("/@/",$entry["text"],$at_matches);
				// RT数
				$rt_cnt = preg_match_all("/RT @/",$entry["text"],$rt_matches);
				// bot判定
				if(in_array($entry["screen_name"],$bot_list) || preg_match("/bot$|\_bot/i",$entry["screen_name"])){
					$bot_flg = true;
				}else{
					$bot_flg = false;
				}
				// botとの連続＠数を取得
				if($bot_flg){
					$reply_cnt_file = constant("DIR_PRJ")."tmp/bot/".$bot_id."/at_cnt_".$entry["screen_name"].".log";
					if(file_exists($reply_cnt_file)){
						$reply_cnt = (int)file_get_contents($reply_cnt_file);
					}else{
						touch($reply_cnt_file);
						chmod($reply_cnt_file, 0666);
						$reply_cnt = 0;
					}
				}

				if(preg_match("/おやすみ|オヤスミ|おやす|おはよう|おは|オハヨウ|ただいま|タダイマ/",$entry["text"]) || (preg_match("/あり|ありがとう/",$entry["text"]) && $at_cnt != 0) || $rt_cnt != 0){
					// 挨拶＠、複数＠でのありがとう、RTはスルー
				}elseif(preg_match("/(っ|つ).*(珈琲|コーヒー|こーひー)/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_coffee"])-1));
					$post = $post_list["at_coffee"][$rand_cnt];
					$coffee_cnt++;
				}elseif(preg_match("/(珈琲|コーヒー|こーひー).*(ちょうだい|頂戴|ください|下さい)/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_please"])-1));
					$post = $post_list["at_please"][$rand_cnt];
				}elseif(preg_match("/紅茶/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_tea"])-1));
					$post = $post_list["at_tea"][$rand_cnt];
				}elseif(preg_match("/マスク|ますく/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_mask"])-1));
					$post = $post_list["at_mask"][$rand_cnt];
				}elseif(preg_match("/かわいい|可愛い|カワイイ/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_cute"])-1));
					$post = $post_list["at_cute"][$rand_cnt];
				}elseif(preg_match("/(電話番号).*(おしえ|何|なに|教え)/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_tel"])-1));
					$post = $post_list["at_tel"][$rand_cnt];
				}elseif(preg_match("/鎖骨|さこつ|サコツ/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_352"])-1));
					$post = $post_list["at_352"][$rand_cnt];
				}elseif(preg_match("/もじもじ/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_moji"])-1));
					$post = $post_list["at_moji"][$rand_cnt];
				}elseif(preg_match("/((っ|つ).*(チョコ|ちょこ))|((チョコ|ちょこ).*(あげる|上げる))/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_vd2"])-1));
					$post = $post_list["at_vd2"][$rand_cnt];
				}elseif(preg_match("/チョコ|ちょこ|バレンタイン/",$entry["text"])){
					$rand_cnt = rand(0,(count($post_list["at_vd"])-1));
					$post = $post_list["at_vd"][$rand_cnt];
				}else{
					if($bot_flg && $reply_cnt >= $reply_limit_cnt){
						// botとの連続＠規制
						unlink($reply_cnt_file);
					}else{
						$rand_cnt = rand(0,(count($post_list["at_random"])-1));
						$post = $post_list["at_random"][$rand_cnt];
					}
				}

				if($post){
					$post = mb_ereg_replace("##ID##",$entry["screen_name"],$post);
					$post = mb_ereg_replace("##NAME##",$entry["name"],$post);
					$post = mb_ereg_replace("##COUNT##",$coffee_cnt,$post);
					$posts[] = array("text"=>$post,"res"=>$entry["status_id"]);
					$post_flg = true;
					$res_flg = true;
					if($bot_flg){
						$reply_cnt++;
						$fp = fopen($reply_cnt_file,"w");
						fputs($fp,$reply_cnt);
						fclose($fp);
					}
				}
				// フラグ更新
				$sql = "update ".$tb_name." set push_flg='1' where status_id='".$entry["status_id"]."'";
				print $sql."<br />";
				$result = $Hanauta->obj["read_db"]->send_query($sql,1);
				if(!$result){
					$error_flg = true;
				}
			}
		}

		// TLに反応
		if(!$post_flg){
			$options = array();
			$options = array(
					"count" => 20,
					"trim_user" => false,
					"exclude_replies" => true,
					"contributor_details" => true,
					"include_rts" => true
			);
			$tl_data = $Hanauta->obj["twitter"]->getHomeTimeline($login_data,$options);
			foreach($tl_data["status"] as $key => $val){
				$post = NULL;

				// 土日は通常ポストのみ
				if($now_week == "0" || $now_week == "6"){
					if($now_time >= "0230" && $now_time < "0515"){
						break;
					}
				}
				// 本人は無視
				if($val["user"]["screen_name"] == $bot_id){
					continue;
				}
				// いざ
				if(preg_match("/珈琲|コーヒー|こーひー/",$val["text"])){
					$rand_cnt = rand(0,(count($post_list["tl_coffee"])-1));
					$post = $post_list["tl_coffee"][$rand_cnt];
				}elseif(preg_match("/マスク|ますく/",$val["text"])){
					$rand_cnt = rand(0,(count($post_list["tl_mask"])-1));
					$post = $post_list["tl_mask"][$rand_cnt];
				}elseif(!preg_match("/@/",$val["text"]) && preg_match("/おやすみ|おやす|オヤスミ|寝る|ねる|おふとん/",$val["text"])){
					$rand_cnt = rand(0,(count($post_list["tl_sleep"])-1));
					$post = $post_list["tl_sleep"][$rand_cnt];
				}elseif(!preg_match("/@/",$val["text"]) && preg_match("/おはよう|オハヨウ|おは|出社|しゅっしゃ|ｶｯ|起き|がばっ|むくり|ｶﾞﾊﾞｯ/",$val["text"])){
					$rand_cnt = rand(0,(count($post_list["tl_morning"])-1));
					$post = $post_list["tl_morning"][$rand_cnt];
				}elseif(!preg_match("/@/",$val["text"]) && preg_match("/おうち|ただいま|タダイマ|帰って|帰宅/",$val["text"])){
					$rand_cnt = rand(0,(count($post_list["tl_home"])-1));
					$post = $post_list["tl_home"][$rand_cnt];
				}elseif(preg_match("/まるほどう/",$val["text"]) || (!preg_match("/^@".$love_id."/",$val["text"]) && preg_match("/@".$love_id."|".$love_name."/",$val["text"]))){
					if(rand(0,1000) > 400){
						continue;
					}
					$rand_cnt = rand(0,(count($post_list["tl_love"])-1));
					$post = $post_list["tl_love"][$rand_cnt];
				}elseif(!preg_match("/@/",$val["text"]) && preg_match("/さむい|寒い/",$val["text"])){
					$rand_cnt = rand(0,(count($post_list["tl_cold"])-1));
					$post = $post_list["tl_cold"][$rand_cnt];
				}

				if($post){
					$post = mb_ereg_replace("##ID##",$val["user"]["screen_name"],$post);
					$post = mb_ereg_replace("##NAME##",$val["user"]["name"],$post);
					$posts[] = array("text"=>$post,"res"=>$val["id_str"]);
					$post_flg = true;
					break;
				}
			}
		}

		// 時間イベント
		$post = NULL;
		if(!$post_flg && isset($post_list["time"])){
			$rand_cnt = rand(0,(count($post_list["time"])-1));
			$post = $post_list["time"][$rand_cnt];
			if($post){
				$posts[] = array("text"=>$post);
				$post_flg = true;
			}
		}

		// 通常ポスト
		$post = NULL;
		if(!$post_flg || ($post_flg && $res_flg)){
			$normal_flg = false;
			$followers = $Hanauta->obj_ext["follow"]->get_followers();

			$screen_name = NULL;
			$name = NULL;
			if(count($followers) > 0){
				$rand_cnt = rand(0,(count($followers)-1));
				$screen_name = $followers[$rand_cnt]["screen_name"];
				$name = $followers[$rand_cnt]["name"];
			}

			while(!$normal_flg){
				$rand_cnt = rand(0,(count($post_list["normal"])-1));
				$post = $post_list["normal"][$rand_cnt];

				if(count($followers) == 0 && preg_match("/##/",$post)){
				}else{
					$post = mb_ereg_replace("##ID##",$screen_name,$post);
					$post = mb_ereg_replace("##NAME##",$name,$post);
					$posts[] = array("text"=>$post);
					$post_flg = true;
					$normal_flg = true;
				}
			}
		}

		//$Hanauta->obj["ponpon"]->pr($tl_data);

		$rtn = $posts;
		return $rtn;
	}

	/**
	 * リプライ保存
	 * @param unknown $tb_name
	 * @return boolean
	 */
	function getRepLists($tb_name){
		global $Hanauta;
		global $login_data;
		$rtn = false;
		$error_flg = false;

		// 取得済みの最新のpostid取得
		$last_id = false;
		$fldname = "status_id";
		$where = NULL;
		$db_param = array(
				"orderby" => "regist_date desc",
				"offset" => 0,
				"limit" => 1
		);
		$db_rtn = $Hanauta->obj["read_db"]->select_db($tb_name,$fldname,$where,$db_param);
		if($db_rtn){
			$entry = $Hanauta->obj["string"]->encode_str($Hanauta->obj["read_db"]->get_result($db_rtn,"assoc"));
			$last_id = $entry["status_id"];
		}

		// リプライ
		$options = array(
				"count" => 0,
				"trim_user" => false,
				"contributor_details" => false,
				"include_entities" => false
		);
		if($last_id){
			$options["since_id"] = $last_id;
		}
		$tl_data = $Hanauta->obj["twitter"]->getMentions($login_data,$options);

		foreach($tl_data["status"] as $key => $val){
			// とりあえずDBに保存
			// 重複チェック
			$fldname = "status_id";
			$where = "status_id='".$val["id_str"]."'";
			$db_param = array();
			$db_rtn = $Hanauta->obj["read_db"]->select_db($tb_name,$fldname,$where,$db_param);
			if($db_rtn){
				break;
			}

			// 登録
			$sql = "insert into ".$tb_name."(status_id,name,screen_name,text,regist_date) values("
					."'".$val["id_str"]."',"
					."'".$val["user"]["name"]."',"
					."'".$val["user"]["screen_name"]."',"
					."'".$val["text"]."',"
					."'".$Hanauta->obj["twitter"]->format_date($val["created_at"])."'".")";
			$result = $Hanauta->obj["read_db"]->send_query($sql,1);
			if(!$result){
				$error_flg = true;
			}
		}
		$rtn = $error_flg;
		return $rtn;
	}

}
