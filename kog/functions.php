<?php
// define('PATH', dirname(dirname(__FILE__)).'/');

// ini_set('date.timezone','Asia/Shanghai'); 
date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
define('PATH', dirname(dirname(__FILE__)).'/');


require_once(PATH . 'wp-blog-header.php'); 
// require_once('telegram_webhook.php');

// get_header('nonav');
error_reporting(E_ALL);
ini_set('display_errors', '1');

// test();
function test(){
	$a = 6;
	$b = 69;
	var_dump(round($a/$b,100));
	exit;
}

// 不同域名进来用不同的库，是不同的玩家
global $wpdb;
if ($_SERVER['SERVER_NAME'] == "dezhou.xys.one") {
	$wpdb->prefix  = 'dezhou_';
}
// 本地调试用
// $wpdb->prefix  = 'dezhou_';

// get_lucky_info_gen();
// // 获取 Lucky 信息
// function get_lucky_info_gen(){
// 	global $wpdb;
// 	$sql = "select * from ".$wpdb->prefix."kog_lucky";
// 	$res = $wpdb->get_results($sql);
// 	if(is_array($res)){
// 		foreach ($res as $key => $value) {
// 			$luky_info[$valu->uid]
// 		}
// 	}
// 	echo "<pre>";
// 	var_dump($res);
// 	exit;
// }

// 存储中间数据到 game_meta中
function save_game_meta($gid,$meta_value){
	global $wpdb;
	$game_info = get_game($gid);
	$game_start = $game_info->start_time;
	$res = get_game_meta_by_meta_key($gid,'tmp_chips');
	$res_section = get_game_meta_by_meta_key($gid,'game_time_zone');
	// echo "<pre>";
	// var_dump($res_section);
	// exit;
	$table = $wpdb->prefix."kog_gamemeta";
	if (!empty($res)) {
		

		$update = array(
			
			'meta_value' => $meta_value,
		);

		$where = array(
			'gid' => $gid,
			'meta_key' => "tmp_chips",
		);
		if ($wpdb->update($table,$update,$where)){
			echo "更新";
		}
	}
	else{
		$insert = array(
			'gid' => $gid,
			'meta_key' => "tmp_chips",
			'meta_value' => $meta_value,
		);
		if ($wpdb->insert($table,$insert)) {
			echo "插入";
		}
		

	}
	// 插入记录每个section 的时间
	if (empty($res_section)) {
		// 新增 第一个section的开始与结束时间
		$start_time = $game_start;
		$end_time = time();
		$section_time[] = array(
			'start_time' => $start_time,
			'end_time'	 => $end_time,
		);
		$section_time = json_encode($section_time);
		$insert_time = array(
			'gid' => $gid,
			'meta_key' => "game_time_zone",
			'meta_value' => $section_time,
		);
		$wpdb->insert($table,$insert_time);

	}else{
		//更新 增加下一个section的开始时间
		$start_time = time();
		$last_end_time = $start_time - 1000;
		$end_time = "";
		$section_time = array(
			'start_time' => $start_time,
			'end_time'	 => $end_time,
		);
		// echo "<pre>";
		// var_dump($res_section[0]['meta_value']);
		// exit;
		$res_section_timezone = json_decode($res_section[0]['meta_value'],true);
		end($res_section_timezone);
		$last_key = key($res_section_timezone); 
		$res_section_timezone[$last_key]['end_time'] = $last_end_time;
		// echo "<pre>";
		// var_dump(key($res_section_timezone));
		// exit;
		array_push($res_section_timezone, $section_time);
		$section_time_update_json = json_encode($res_section_timezone);
		$update = array(
			'meta_value' => $section_time_update_json,
		);
		$where = array(
			'gid' => $gid,
			'meta_key' => "game_time_zone",
		);
		// echo "<pre>";
		// // var_dump($res_section[0]['meta_value']);
		// var_dump($section_time_update_json);
		// exit;
		$wpdb->update($table,$update,$where);

	}

	
}

function get_game_meta_by_meta_key($gid,$meta_key){
	global $wpdb;
	$sql = "select * from ".$wpdb->prefix."kog_gamemeta where gid='".$gid."' and meta_key = '".$meta_key."' ";
	$res = $wpdb->get_results($sql,ARRAY_A);
	if (!isset($res)) {
		$res = '';
	}
	// echo "<pre>";
	// var_dump ($res);
	return $res;
}

/**
 * [update_real_chips description]
 * 批量更新 real_chips 这个字段，根据最后剩余筹码 - 初始筹码
 * 增加 real_chips 自断后执行一次即可
 * @return [type] [description]
 */
function update_real_chips(){
	global $wpdb;
	$gamedetail = get_game_by_args(array("status" => 2));
	foreach ($gamedetail as $key => $value) {
		
		$game_detail_users = get_game_detail($value->id);
		if ($game_detail_users) {
			foreach ($game_detail_users as $k => $val) {
				$real_chips = ($val->count_chips - $val->start_chips)/10;

				$table 		=	$wpdb->prefix."kog_details";

				$update 	=	array(
					'real_chips'	=> 	$real_chips,
				);
				$where 		=	array(
					'id'			=>	$val->id,
				);
				if (isset($val->id)) {
					if ($wpdb->update($table,$update,$where)){
						echo $val->id."更新".$real_chips."</br>";
					}
					else{
						echo "update 失败";
					}
				}else{
					echo "id不合法";
				}
			}
		}
		
	}
}

/**
 * [game_type_array 根据KEY值返回相应数组的value
 * @param  [type] $val [description]
 * @return [type]      [description]
 */
function game_type_array($val){
	$val = strtoupper($val);
	$game_type = array(
		'CASH' 	=> '现金局',
		'SNG'	=> '排位赛',
	);
	if (array_key_exists($val, $game_type)) {
		return $game_type[$val];
	}else{
		return "ALL";
	}
}

/**
 * 根据方向ID 获取方向的名称/描述
 * @param  [type] $position_id [description]
 * '东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)
 * @return [type]              [description]
 */
function get_position_name($position_id){
	$array_position = array(
		1 => "东(皮椅子)",
		2 => "南(黑椅子)",
		3 => "西(黑椅子)",
		4 => "北(红椅子)",
	);
	if (array_key_exists($position_id, $array_position)) {
		return $array_position[$position_id];
	}
	else {
		return $position_id;
	}
}

/**
 * 计算每个人在每个方位的表现（1~4代表东南西北）
 * @return [type] [description]
 */
function count_person_position_win($game_memo=null,$start_time=null, $end_time=null, $game_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 		=	"SELECT d.uid, d.* FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($game_memo)){
		$game_memo = str_replace("-","",$game_memo);
		$sql = $sql.' and g.memo ='.$game_memo;
	}if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}

	$res  		= 	$wpdb->get_results($sql);
	$res_uid	=  	$wpdb->get_col($sql);
	$res_uid 	=	array_unique($res_uid);
	$person_position['uid'] 	=	 $res_uid;

	if (is_array($res) && !empty($res)) {
		foreach ($res_uid as $key => $value) {
			for ($i=1; $i <=4 ; $i++) { 
				// 预先对每个方位赋值，只算4个方位 所以这里 i <= 4，
				$person_position['win'][$value][$i]=0;
				$person_position['win_rate'][$value][$i]=0;
				$person_position['win_info']['win'][$value][$i]=0;
				$person_position['win_info']['total'][$value][$i]=0;
			}
		}
		foreach ($res as $key => $value) { 
			// 针对实际支付的补丁，有时候会打折，根据数据库中 total_fact字段来判断
			$value->bonus = !empty($value->total_fact)?$value->total_fact:$value->bonus;
			$value->reward = !empty($value->total_fact)?0:$value->reward;

			$person_position['win'][$value->uid][$value->seat_position] 	= 	isset($person_position['win'][$value->uid][$value->seat_position])?$person_position['win'][$value->uid][$value->seat_position] + $value->bonus + $value->reward : $value->bonus +$value->reward;
			// ksort($person_position['win'][$value->uid]);
			$person_position_count[$value->uid][$value->seat_position] 		=	isset($person_position_count[$value->uid][$value->seat_position])?$person_position_count[$value->uid][$value->seat_position]+1:1;
			if ($value->bonus >0) {
				$peson_win_count[$value->uid][$value->seat_position] = isset($peson_win_count[$value->uid][$value->seat_position])?$peson_win_count[$value->uid][$value->seat_position]+1:1;
			}
			$peson_win_count[$value->uid][$value->seat_position]	=	isset($peson_win_count[$value->uid][$value->seat_position])?$peson_win_count[$value->uid][$value->seat_position]:0;
			$person_position['win_rate'][$value->uid][$value->seat_position] 	=	round($peson_win_count[$value->uid][$value->seat_position]/$person_position_count[$value->uid][$value->seat_position],4)*100;
			$person_position['win_info']['win'][$value->uid][$value->seat_position] 	=	$peson_win_count[$value->uid][$value->seat_position];
			$person_position['win_info']['total'][$value->uid][$value->seat_position] 	=	$person_position_count[$value->uid][$value->seat_position];
			// 获取每个方位上每人的输赢的情况，按每个方位展示一张统计图（同时显示盈亏、胜率），同时按key值对相应数组进行排序
			$person_position['each_position']['win'][$value->seat_position][$value->uid] = isset($person_position['each_position']['win'][$value->seat_position][$value->uid])?$person_position['each_position']['win'][$value->seat_position][$value->uid]+$value->bonus + $value->reward:$value->bonus + $value->reward;
			$person_position['each_position']['win_rate'][$value->seat_position][$value->uid] = $person_position['win_rate'][$value->uid][$value->seat_position];
			ksort($person_position['each_position']);
			ksort($person_position['each_position']['win']);
			ksort($person_position['each_position']['win_rate']);
			ksort($person_position['each_position']['win'][$value->seat_position]);
			ksort($person_position['each_position']['win_rate'][$value->seat_position]);
		}
	}
	return $person_position;
}

/**
 * 计算每个位置（东南西北）-1、2、3、4、5号位置赢的比例
 * 直接计算每个位置的总盈余
 * 计算谁再哪个位置输赢情况
 * @return [type] [description]
 */
function cont_position_winer_new($game_memo=null,$start_time=null, $end_time=null,$game_type=null,$stat_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 	=	"SELECT d.* FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($game_memo)){
		$game_memo = str_replace("-","",$game_memo);
		$sql = $sql.' and g.memo ='.$game_memo;
	}if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}
	$res  	= 	$wpdb->get_results($sql);
	$i = 0;
	if(is_array($res) && !empty($res)){
		foreach ($res as $key => $value) {
			// 目前只统计4个人玩的东南西北，5人玩的数据太少，不做统计
			if ($value->seat_position > 4) {
				continue;
			}
			// 累计每个方位出现的游戏数量，单独累计到用户身上就是累计每个用户在每个方位的总游戏次数
			$position_count[$value->seat_position] 								=	isset($position_count[$value->seat_position])?$position_count[$value->seat_position]+1:1;
			$position_count['user'][$value->uid][$value->seat_position] 		=	isset($position_count['user'][$value->uid][$value->seat_position])?$position_count['user'][$value->uid][$value->seat_position]+1:1;

			// 都转成整数型
			$value->bonus 	=	intval($value->bonus);
			
			// 获胜 计算一次赢钱，用于算胜率
			if($value->bonus >0){
				$position_win[$value->seat_position]['times'] 	=	isset($position_win[$value->seat_position]['times'])?$position_win[$value->seat_position]['times']+1:1;	
			}
			// 不光累加 bonus,还得累加额外的 reward 
			// 针对实际支付的补丁，有时候会打折，根据数据库中 total_fact字段来判断
			$value->bonus = !empty($value->total_fact)?$value->total_fact:$value->bonus;
			$value->reward = !empty($value->total_fact)?0:$value->reward;

			$position_win[$value->seat_position]['bonus']	=	isset($position_win[$value->seat_position]['bonus'])?$position_win[$value->seat_position]['bonus']+ $value->bonus + $value->reward:$value->bonus+$value->reward;
			// $position_win['user'][$value->uid][$value->seat_position]	=	isset($position_win['user'][$value->uid][$value->seat_position])?$position_win['user'][$value->uid][$value->seat_position]+$value->bonus:$value->bonus;
			
			// 为了让每个位置都有值，这里对于没取到的，统一赋值0
			// 但对于[user]数组中，可能会出现某人在某个位置没有数据，所以数组中并不是1~4都有，这样的情况在前台调用并整理新数组的时候再做处理
			
			// $position_lose['user'][$value->uid][$value->seat_position]['times'] 		=	isset($position_lose['user'][$value->uid][$value->seat_position]['times'])?$position_lose['user'][$value->uid][$value->seat_position]['times']:0;			
			// 算概率，为最终数组赋值（累计盈亏总金额）
			$position_win[$value->seat_position]['times'] 	=	isset($position_win[$value->seat_position]['times'])?$position_win[$value->seat_position]['times']:0;	
			$position_win_rate['position'][$value->seat_position] 			= 	round($position_win[$value->seat_position]['times']/$position_count[$value->seat_position],4)*100;
			// $position_win_rate['user'][$value->uid][$value->seat_position] 			= 	round($position_win['user'][$value->uid][$value->seat_position]['times']/$position_count['user'][$value->uid][$value->seat_position],4)*100;
			$position_stas['win'][$value->seat_position] 		=	$position_win[$value->seat_position]['bonus'];
			ksort($position_stas['win']);
			ksort($position_win_rate['position']);
			
		}
		
		// 不排序了，直接按东南西北（1~4)的位置顺序显示
		// arsort($position_win_rate); 
		// arsort($position_lose_rate);
		// $position_stas['win'] 			= 	$position_stas['win'];
		// $position_stas['lose'] 			= 	$position_lose;
		

		$position_stas['win_rate'] 		= 	$position_win_rate['position'];
		// $position_stas['user']['win_rate'] 		= 	$position_win_rate['user'];
		
		// $position_stas['user']['win']	=	$position_win['user'];
		// $position_stas['user']['win_times']	=	$position_win_times['user'];
		
		foreach ($position_win_rate['position'] as $key => $value) {
			$position_stas['user_position']['bonus'][$key] 		= 	0;
			$position_stas['user_position']['win_rate'][$key] 	= 	0;
		}



		return $position_stas;
	}
}

/**
 * 计算每个位置（东南西北）-1、2、3、4、5号位置赢的比例
 * 计算谁再哪个位置输赢情况
 * @return [type] [description]
 */
function cont_position_winer($game_memo=null,$start_time=null, $end_time=null,$game_type=null,$stat_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 	=	"SELECT d.* FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($game_memo)){
		$game_memo = str_replace("-","",$game_memo);
		$sql = $sql.' and g.memo ='.$game_memo;
	}if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}
	$res  	= 	$wpdb->get_results($sql);
	if(is_array($res) && !empty($res)){
		foreach ($res as $key => $value) {
			if ($value->seat_position == 5) {
				continue;
			}
			$position_count[$value->seat_position] 				=	isset($position_count[$value->seat_position])?$position_count[$value->seat_position]+1:1;

			// 针对实际支付的补丁，有时候会打折，根据数据库中 total_fact字段来判断
			$value->bonus = !empty($value->total_fact)?$value->total_fact:$value->bonus;
			$value->reward = !empty($value->total_fact)?0:$value->reward;

			if ($value->bonus >0) {
				$position_win[$value->seat_position]['times'] 	=	isset($position_win[$value->seat_position]['times'])?$position_win[$value->seat_position]['times']+1:1;
				$value->bonus 	=	intval($value->bonus);
				$position_win[$value->seat_position]['bonus']	=	isset($position_win[$value->seat_position]['bonus'])?$position_win[$value->seat_position]['bonus']+$value->bonus+ $value->reward:$value->bonus+ $value->reward;

			}
			else{
				$position_lose[$value->seat_position]['times'] 	=	isset($position_lose[$value->seat_position]['times'])?$position_lose[$value->seat_position]['times']+1:1;
				$position_lose[$value->seat_position]['bonus']	=	isset($position_lose[$value->seat_position]['bonus'])?$position_lose[$value->seat_position]['bonus']+$value->bonus+ $value->reward:$value->bonus+ $value->reward;
			}
			$position_win[$value->seat_position]['bonus']		=	isset($position_win[$value->seat_position]['bonus'])?$position_win[$value->seat_position]['bonus']:0;
			$position_lose[$value->seat_position]['bonus']		=	isset($position_lose[$value->seat_position]['bonus'])?$position_lose[$value->seat_position]['bonus']:0;
			$position_win[$value->seat_position]['times'] 		=	isset($position_win[$value->seat_position]['times'])?$position_win[$value->seat_position]['times']:0;
			$position_lose[$value->seat_position]['times'] 		=	isset($position_lose[$value->seat_position]['times'])?$position_lose[$value->seat_position]['times']:0;
			

			
			

			$position_win_rate[$value->seat_position] 			= 	round($position_win[$value->seat_position]['times']/$position_count[$value->seat_position],4)*100;
			$position_lose_rate[$value->seat_position] 			= 	round($position_lose[$value->seat_position]['times']/$position_count[$value->seat_position],4)*100;
			$position_stas['win'][$value->seat_position] 		=	$position_win[$value->seat_position]['bonus'];
			$position_stas['lose'][$value->seat_position] 		=	$position_lose[$value->seat_position]['bonus'];
			ksort($position_stas['win']);
			ksort($position_stas['lose']);


		}

		ksort($position_win_rate);
		ksort($position_lose_rate);
		// $position_stas['win'] 			= 	$position_win;
		// $position_stas['lose'] 			= 	$position_lose;
		$position_stas['win_rate'] 		= 	$position_win_rate;
		$position_stas['lose_rate'] 	= 	$position_lose_rate;
		return $position_stas;
	}
}

/**
 * 或取游戏每个人的获胜/赢钱率（只要Bonus > 0 即视为获胜）——这是针对SNG的还要考虑现金的
 * 现金的是最后剩余筹码-初始筹码为正，就是获胜
 * @return [type]      [description]
 */
function count_winer_rate($game_memo=null,$start_time=null, $end_time=null, $game_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 	=	"SELECT d.*,g.game_type,g.start_time,g.end_time,g.date,g.memo FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($game_memo)){
		$game_memo = str_replace("-","",$game_memo);
		$sql = $sql.' and g.memo ='.$game_memo;
	}if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}
	$res  	= 	$wpdb->get_results($sql);
	// echo "<pre>";
	// var_dump ($res);
	// exit;
	
	// 获取状态是2的全部场次及详情
	$kog_games 	= 	get_game_by_args(array('memo'=>$game_memo,'status'=>2,'start_time'=>$start_time,'end_time'=>$end_time,'game_type'=>$game_type));
		// 初始化一个数组用来填写每场的数据，没参加的当场就是0
	// $players = get_player();
	// 获取当前统计周期内有数据的玩家
	if (is_array($res)) {
		foreach ($res as $key => $value) {
			$players_array[] = $value->uid;
		}

		if (!empty($players_array)) {
			$players_array =  array_unique($players_array);
				// echo "<pre>";
				// var_dump ($players);
				// exit;
			foreach ($players_array as $key => $value) {
				if (!empty(get_player($value)[0])){
					$players[] = get_player($value)[0];	
				}
			}
		}
	}
	

	


	if (isset($kog_games)) {
		foreach ($kog_games as $key => $value) {
			if(!empty($_REQUEST['md'])){
					$value->date = date('H:i',$value->start_time);
				}	
			foreach ($players as $k => $val) {
				// echo "<pre>";
				// var_dump ($players);
				// exit;
				$user_times_win_rate[$val->id][$value->date] = 0;
			}
		}

	}


		///////
	

	if (is_array($res) && !empty($res)) {
		// 计算场次
		// $game_list 		=	get_game_list();
		// $game_count 	=	count($game_list);
		
		foreach ($res as $key => $value) {
			if(!empty($_REQUEST['md'])){
					$value->date = date('H:i',$value->start_time);
				}
			if ($value->uid == '1') {
				// var_dump($value);
			}
			if (strtoupper($value->game_type) == 'SNG') {

				$condition = $value->bonus;
			}else{
				$condition = $value->total_fact;
				
			}

			$user_times_win_count_end[$value->uid] = isset($user_times_win_count_end[$value->uid])?$user_times_win_count_end[$value->uid]:0;
			// 计算赢了的用户及次数
			if ($condition > 0) {
				$user_times_win[$value->uid] 	= 	isset($user_times_win[$value->uid])?$user_times_win[$value->uid]+1:1;	
				// if ($value->uid == 1) {
				// 	echo "<pre>";
				// 	var_dump ($user_times_win[1]."[$value->date]:");
				// }
				$user_win_count[$value->uid] 	= isset($user_win_count[$value->uid])?$user_win_count[$value->uid]+1:1;
				$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']]	= isset($monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']])?$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']]+1:1;
				// 在相应日期计算截止到当天的胜利总场次，便于后面累加
				$user_times_win_count[$value->uid][$value->date] 	= 	$user_times_win[$value->uid];
				// $user_times_win_count[$value->uid][$value->date] 	= 	isset($user_times_win_count[$value->uid][$value->date])?$user_times_win[$value->uid]+1:$user_times_win[$value->uid];

			} else {
				$user_win_count[$value->uid] 	= isset($user_win_count[$value->uid])?$user_win_count[$value->uid]:0;
				$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']]	= isset($monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']])?$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']]:0;
				// $user_times_win_count[$value->uid][$value->date] 	=	prev($user_times_win_count[$value->uid]);
				$user_times_win_count[$value->uid][$value->date] 	=	$user_times_win_count_end[$value->uid];
			}
			$user_times_win_count_end[$value->uid] = $user_times_win_count[$value->uid][$value->date];
			
			// 计算每个用户参与的次数
			$count_gamer[$value->uid] 		=	isset($count_gamer[$value->uid])?$count_gamer[$value->uid]+1:1;
			$game_count 					=	$count_gamer[$value->uid];
			// 计算每个选手的胜率，选手获胜次数，与选手参与次数，而不是全部的game总量
			$game_win_rate[$value->uid]		= round($user_win_count[$value->uid]/$game_count,4);

			


			
			// 按日期计算截止到当天的参与的总场次
			// $user_times_game_count[$value->uid][$value->date] 	= 	isset($user_times_game_count[$value->uid][$value->date])?$user_times_game_count[$value->uid][$value->date]+1:1;
			// 累加参与总场次
			$user_times_game[$value->uid] = isset($user_times_game[$value->uid])?$user_times_game[$value->uid]+1:1;
			$user_times_game_count[$value->uid][$value->date] 	= 	isset($user_times_game_count[$value->uid][$value->date])?$user_times_game[$value->uid]+1:$user_times_game[$value->uid];

			$user_times_win_rate[$value->uid][$value->date]		=	round($user_times_win_count[$value->uid][$value->date]/$user_times_game_count[$value->uid][$value->date],4);
			// $count_gamer[$value->uid] 	=	isset($count_gamer[$value->uid])?$count_gamer[$value->uid]+1:1;
		}
		if (isset($user_times_win_rate)) {
			foreach ($user_times_win_rate as $key => $value) {
				$this_value = 0;
				foreach ($value as $k => $val) {
					if ($val != 0) {
						$this_value = $val;
					}
					// $this_value = isset($this_value)?$this_value:0;
					$user_times_win_rate[$key][$k] = $this_value;
				}
			}
		}

		// echo "<pre>";
		// var_dump ($user_times_win_rate);
		// // // var_dump ($user_times_win_count[1]);
		// // // var_dump ($user_times_game_count[1]);
		// exit;

		// 对胜率数组排序
		arsort($game_win_rate);
		foreach ($game_win_rate as $key => $value) {
			if(!empty(get_player($key)[0]->nickname)){
				$new_game_win_rate[$key][0]	= get_player($key)[0]->nickname;	
			}
			
			$new_game_win_rate[$key][1]	= $value;
			// $new_game_win_rate[$key][2]['count']	= $count_gamer[$key];
			// $new_game_win_rate[$key][2]['win']		= $user_win_count[$key];
			$new_game_win_rate[$key][2]		= $count_gamer[$key];
			$new_game_win_rate[$key][3]		= $user_win_count[$key];
			$new_game_win_rate[$key][4]		= $key;
		}

		foreach ($new_game_win_rate as $key => $value) {
			$final_game_win_rate[] = $value;
		}
		$final_game_win_rate['monthly_win'] = $monthly_income;
		$final_game_win_rate['user_times_win_rate'] = $user_times_win_rate;
		// echo "<pre>";
		// var_dump ($final_game_win_rate);
		// exit;
		return $final_game_win_rate;
	}
	else {
		return false;
	}

}

function get_position_stat($game_memo=null,$start_time=null, $end_time=null,$game_type=null,$stat_type=null){
	$res = get_player_total_income_stat($game_memo,$start_time, $end_time,$game_type,$stat_type)['res'];
	if (!empty($res)) {
		foreach ($res as $key => $value) {
			// 针对实际支付的补丁，有时候会打折，根据数据库中 total_fact字段来判断
			$value->bonus = !empty($value->total_fact)?$value->total_fact:$value->bonus;
			$value->reward = !empty($value->total_fact)?0:$value->reward;

			$position_stat[$value->uid]['name'] = $value->player;
			// 用来控制气泡的位置错开
			$position_stat[$value->uid]['data'][] = array(intval($value->seat_position)+(5-rand(2,8))*0.08, $value->reward + $value->bonus,abs($value->reward + $value->bonus));
		}
		foreach ($position_stat as $key => $value) {
			$position_stat_res[]=$value;
		}
		return $position_stat_res;
	}else{
		return false;
	}
}

function get_best_killer($game_memo=null,$start_time=null, $end_time=null, $game_type=''){
	global $wpdb;
	$sql 	=	"SELECT killed_by FROM ".$wpdb->prefix."kog_rebuy as r 
	left join ".$wpdb->prefix."kog_games as g on g.id = r.gid where g.status <> 0 and r.uid <>8 and r.uid <> r.killed_by";
	if(!is_null($game_memo)){
		$game_memo = str_replace("-","",$game_memo);
		$sql = $sql.' and g.memo ='.$game_memo;
	}if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}
	$rebuy 	= 	$wpdb->get_col($sql);
	$player 	=	get_player();
	$player_arr	= 	creat_single_array($player,'id','id');
	$player_name =	creat_single_array($player,'id','nickname');
	$ac 		= 	array_count_values($rebuy);
	arsort($ac);
	foreach ($player_arr as $key => $value) {

		if (!array_key_exists($value, $ac)) {
			unset($ac[$key]);
		}
	}
	$kill_rank 	=	array();
	// array($value['name'],array_sum($value['data']));
	// var_dump($ac);
	// exit;
	foreach ($ac as $key => $value) {
		$kill_rank[$key] 	=	array($player_name[$key],$value);
	}
	return $kill_rank;
}
// echo "<pre>";
// var_dump (if_in_this_month("1521204268","2018","02"));
// exit;

function get_kill_info($game_memo=null,$start_time=null, $end_time=null, $game_type=''){
	global $wpdb;
	$sql 	=	"SELECT uid, killed_by FROM ".$wpdb->prefix."kog_rebuy as r 
	left join ".$wpdb->prefix."kog_games as g on g.id = r.gid 
	left join ".$wpdb->prefix."kog_user as u on u.id = r.uid 

	where g.status <> 0 and u.is_del<>1 and r.uid <> r.killed_by";
	if(!is_null($game_memo)){
		$game_memo = str_replace("-","",$game_memo);
		$sql = $sql.' and g.memo ='.$game_memo;
	}if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}
	$kill_info 	= 	$wpdb->get_results($sql);
	$kill_info_conut = null;
	if (is_array($kill_info)) {
		foreach ($kill_info as $key => $value) {
			$kill_info_conut['killed'][$value->killed_by][$value->uid] = isset($kill_info_conut['killed'][$value->killed_by][$value->uid])?$kill_info_conut['killed'][$value->killed_by][$value->uid]+1:1;
			arsort($kill_info_conut['killed'][$value->killed_by]);

			$kill_info_conut['killed_by'][$value->uid][$value->killed_by]= isset($kill_info_conut['killed_by'][$value->uid][$value->killed_by])?$kill_info_conut['killed_by'][$value->uid][$value->killed_by]+1:1;
			arsort($kill_info_conut['killed_by'][$value->uid]);
		}
	}
	$player 	=	get_player();
	$player_arr	= 	creat_single_array($player,'id','id');
	$player_name =	creat_single_array($player,'id','nickname');
	// echo "<pre>";
	// var_dump($kill_info_conut);
	// exit();

	return $kill_info_conut;

}

/**
 * [if_in_this_month description] 判断一个时间戳是否属于某年某月
 * @param  [type] $time  [1521204268] 时间戳
 * @param  [type] $year  [2018] 2018
 * @param  [type] $month [03]
 * @return [type]        [description]
 */
function if_in_this_month($time,$year,$month){
	$ym 		= $year.$month;
	$ym_start 	= $ym."01";
	$ym_end 	= date('Ymt',strtotime($ym_start));
	$ym_start_time 	= strtotime($ym_start);
	$ym_end_time 	= strtotime($ym_end);
	if ($time <= $ym_end_time && $time>= $ym_start_time) {
		return true;
	}
	else{
		return false;
	}
}

/**
 * [get_time_ym description] 根据时间戳返回所属的年、月、日信息
 * @param  [type] $time [description]
 * @return [type]       [description]返回一维数组 y  m   d 供分别使用
 * 
 */
function get_time_ym($time){
	return array("y"=>date('Y',$time),"m"=>date('M',$time),"d"=>date('d',$time));
}


function get_player_total_income_stat($game_memo=null,$start_time=null, $end_time=null,$game_type=null,$stat_type='',$timezone="all"){
	global 	$wpdb;
	$table 		=	$wpdb->prefix."kog_details";
	$sql 		=	"SELECT d.*,start_time,end_time,game_type,`date`,`memo`
	FROM ".$wpdb->prefix."kog_details as d 
	left join ".$wpdb->prefix."kog_games as g 
	on g.id = d.gid 
	left join ".$wpdb->prefix."kog_user as u 
	on d.uid = u.id
	where g.status =2 and u.is_del <> 1
	";
	if(!is_null($game_memo)){
		$game_memo = str_replace("-","",$game_memo);
		$sql = $sql.' and g.memo ='.$game_memo;
	}if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}
	$res 		= 	$wpdb->get_results($sql);

	$monthly_income = array();
	if (!empty($res)) {

		// 获取状态是2的全部场次及详情
		$kog_games 	= 	get_game_by_args(array('memo'=>$game_memo,'status'=>2,'start_time'=>$start_time,'end_time'=>$end_time,'game_type'=>$game_type));
		// 初始化一个数组用来填写每场的数据，没参加的当场就是0
		// $players = get_player();

		// 获取当前统计周期内有数据的玩家
		foreach ($res as $key => $value) {
			$players_array[] = $value->uid;
			$play_memo[] = $value->memo;
		}
		$play_memo = array_unique($play_memo);
		$players_array =  array_unique($players_array);

		foreach ($players_array as $key => $value) {
			if(!empty(get_player($value)[0])){
				$players[] = get_player($value)[0];	
			}
			
	
		}
		
		// echo "<pre>";
		// var_dump($sql);
		// // var_dump($players_array);
		// exit;
		if (isset($kog_games)) {
			foreach ($kog_games as $key => $value) {
				// 如果有精确日期，就获取当天以创建时间为单位的统计数据
				if(!empty($_REQUEST['md'])){
					$value->date = date('H:i',$value->start_time);
				}
				foreach ($players as $k => $val) {
					$total_income_add[$val->id][$value->date] = 0;
				}
			}

		}
		foreach ($res as $key => $value) {
			// 如果有精确日期，就获取当天以创建时间为单位的统计数据
			if(!empty($_REQUEST['md'])){
					$value->date = date('H:i',$value->start_time);
				}
			// 默认统计的是现金，如果是筹码，则需要用筹码的字段”real_chips"代替 total_fact
			if ($stat_type == "chips") {
				$value->total_fact = !is_null($value->real_chips)?$value->real_chips:$value->total_fact;
			}
			if ($stat_type == 'real_money'){
				if ($value->game_type == "SNG") {
					$value->total_fact = !is_null($value->total_should)?$value->total_should:$value->total_fact;
				}elseif ($value->game_type == "CASH") {
					$value->total_fact = !is_null($value->total_should)?$value->real_chips:$value->real_chips;
					# code...
				}
				
			}
			// 针对实际支付的补丁，有时候会打折，根据数据库中 total_fact字段来判断
			$value->bonus = !empty($value->total_fact)?$value->total_fact:$value->bonus;
			$value->reward = !empty($value->total_fact)?0:$value->reward;

			$total_income_stat['income_1'][$value->uid]['name'] =	$value->player;
			$total_income_stat['income_1'][$value->uid]['data'][$value->gid] = $value->bonus + $value->reward;
			$total_income_stat['date_1'][$value->gid]	= date('m-d',$value->start_time);
			$total_income_stat['name'][$value->gid][$value->uid]	= $value->player;

			// $total_count = isset($total_count)?$value->bonus + $value->reward:0;
			$total_count = $value->bonus + $value->reward;
			$user_total_count[$value->id] = isset($user_total_count[$value->id])?$user_total_count[$value->id]+$total_count:$total_count;


			$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_date'][get_time_ym($value->start_time)['m']]	= isset($monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_date'][get_time_ym($value->start_time)['m']])?$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_date'][get_time_ym($value->start_time)['m']]+$value->bonus + $value->reward:$value->bonus + $value->reward;
			
			$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_times'][get_time_ym($value->start_time)['m']]	= isset($monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_times'][get_time_ym($value->start_time)['m']])?$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_times'][get_time_ym($value->start_time)['m']]+1:1;
			$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['avg_date'][get_time_ym($value->start_time)['m']]	= round($monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_date'][get_time_ym($value->start_time)['m']]/$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_times'][get_time_ym($value->start_time)['m']],2);


			// 每次累加的总和及明细
			$total_income_everytimes[$value->uid]['total'] = isset($total_income_everytimes[$value->uid]['total'])?$total_income_everytimes[$value->uid]['total']+ $total_count:$total_count;
			$total_income_everytimes[$value->uid][$value->date]= isset($total_income_everytimes[$value->uid][$value->date])?$total_income_everytimes[$value->uid][$value->date] + $total_count:$total_count;
			
			// 每次累积的总和，用来真正画图用的
			$total_income_add[$value->uid][$value->date] = $total_income_everytimes[$value->uid]['total'];



			

		}
		if (isset($total_income_add)) {
			foreach ($total_income_add as $key => $value) {
				$this_value = 0;
				foreach ($value as $k => $val) {
					if ($val != 0) {
						$this_value = $val;
					}
					// $this_value = isset($this_value)?$this_value:0;
					$total_income_add[$key][$k] = $this_value;
				}
			}
		}
		// echo "<pre>";
		// var_dump ($total_income_add);
		// exit;
		// echo "<pre>";
		// var_dump($total_income_add);
		// var_dump(array_sum($total_income_everytimes[1]));
		// var_dump($total_income_add);
		// var_dump ($monthly_income[1][2018]["total_date"]);
		// var_dump (array_sum($monthly_income[1][2018]["total_date"]));
		// exit;
		// $kog_games 	= 	get_game();
		// echo "<pre>";
		// // var_dump ($res);
		// var_dump ($monthly_income);
		// exit;

		if (!empty($kog_games)) {
			foreach ($kog_games as $key => $value) {
				foreach ($total_income_stat['income_1'] as $k => $val) {
					if (!isset($val['data'][$value->id])) {
						$total_income_stat['income_1'][$k]['data'][$value->id] = 0;
					}
					// 排序一下，否则新人的数据会出现在第一次游戏数据中
					ksort($total_income_stat['income_1'][$k]['data']);
				}

			}
		}

		// 前台图表显示数据的data的数组游标是要从0开始的自然游标，所以重新整理数据
		foreach ($total_income_stat['date_1'] as $key => $value) {
			$total_income_stat['date'][] 	=	$value;
		}

		/**
		 * $b = array(
             array('name'=>'北京', 'y'=>20.2),
             array('name'=>'上海', 'y'=>9.6),
             array('name'=>'武汉', 'y'=>16.6),
     );
     $data = json_encode($b);
     echo($data);
		 */
		// echo "<pre>";
		// var_dump($res);
     	
     	// 获取当前场次游戏成本--20230615 添加
		$game_cost_memo = get_cost($game_memo);
		$game_cost_player = [];
		if (is_array($game_cost_memo)) {
			foreach ($play_memo as $key => $value) {
				// echo $value;
				if(!empty($game_cost_memo[$value]) ){
					foreach ($game_cost_memo[$value] as $k => $val) {
						if (!isset($game_cost_player[$k])) {
							$game_cost_player[$k]=$val;
						} else {
							$game_cost_player[$k] += $val;
						}
					}
				}
				
			}
		}
		// echo "<pre>";
		// // echo $sql;
		// // // var_dump ($game_cost_player);
		// // var_dump ($play_memo);
		// var_dump ($game_cost_memo);
		// var_dump ($game_cost_player);
		// var_dump ($total_income_stat['income_1']);
		// exit;
     	foreach ($total_income_stat['income_1'] as $key => $value) {
     		// $game_cost_player = isset($game_cost_arr)?$game_cost_arr->$key:0;
     		$game_cost_player[$key] = isset($game_cost_player[$key])?$game_cost_player[$key]:0;
	     	$total_income_stat['income_2'][$key]['name']=$value['name'];
	     	$total_income_stat['total'][] 	=	array($value['name'],array_sum($value['data']),round(array_sum($value['data'])/count($value['data']),2));
			$total_income_stat['total_uid'][$key] 	=	array($value['name'],array_sum($value['data']),$game_cost_player[$key]);//存一个对应用户id的数据，便于使用
			foreach ($value['data'] as $k => $val) {
				$total_income_stat['income_2'][$key]['data'][]=$val;
			}

		}
		foreach ($total_income_stat['income_2'] as $key => $value) {
			$total_income_stat['income'][] 	= 	$value;
		}
		// echo "<pre>";
		// var_dump($total_income_stat['income_1']);
		// var_dump (array_sum($total_income_stat['income_1'][1]['data']));
		// var_dump ($total_income_stat['total_uid']);
		// exit;
		// 对总数的二维数组排序
		foreach ($total_income_stat['total'] as $key => $value) {
			$sort_total[]= $value[1];
		}
		array_multisort($sort_total,SORT_DESC,$total_income_stat['total']);

		foreach ($total_income_stat['total'] as $key => $value) {
			$total_income_stat['avg'][] = array($value['0'], $value['2']);
		}

		unset($total_income_stat['date_1']);
		unset($total_income_stat['income_1']);
		unset($total_income_stat['income_2']);
		unset($total_income_stat['name']);
		$total_income_stat['res'] 	=	$res;

		
		// echo "<pre>";
		// var_dump ($total_income_stat['avg']);
		// exit;

		$total_income_stat['res_total'] 	=	$total_income_stat['total'];
		$total_income_stat['monthly_income'] 	=	$monthly_income;
		$total_income_stat['times_income_add'] 	=	$total_income_add;
		$total_income_stat['play_memo'] 	=	count($play_memo);
		// echo "<pre>";
		// var_dump ($total_income_stat['times_income_add']);
		// exit;
		return 	$total_income_stat;
		// return 	$res;
	} else {
		return 	false;
	}
}


















function get_honor_title_arr(){
	$honor_title = array(
		'best_killer' 	=> 	'最佳清台',
		'mvp' 			=>	'MVP',
		'over_god'		=>	'超神',
		'dark_priest'	=>	'最佳暗牧',
		'priest'		=>	'有容奶大',
	);

	return $honor_title;

}

function get_gamemeta($gid,$meta_key=null,$arr='OBJECT'){
	global $wpdb;
	$table 		=	$wpdb->prefix."kog_gamemeta";
	$where 		=	" where gid =".$gid;
	if (!empty($meta_key)) {
		$where .= " and meta_key = "."'".$meta_key."'";
	}
	$sql = "SELECT * from ".$table.$where;
	$cur = $wpdb->get_results( $sql,$arr);
	return $cur; 	
}

function insert_gamemeta($gid,$meta_key,$meta_value){
	global $wpdb;
	$table 		=	$wpdb->prefix."kog_gamemeta";
	$insert 	= 	array(
		'gid' 			=> 	$gid,
		'meta_key' 		=> 	$meta_key,
		'meta_value' 	=> 	$meta_value,
	);
	$wpdb->insert($table,$insert);
	return $wpdb->insert_id;
}

// 如果有就更新，没有就插入
function update_gamemeta($gid,$meta_key,$meta_value){
	global $wpdb;
	$table 		=	$wpdb->prefix."kog_gamemeta";

	$update 	=	array(
		'meta_value'	=> 	$meta_value,
	);
	$where 		=	array(
		'gid'			=>	$gid,
		'meta_key'		=>	$meta_key,
	);
	if (empty($meta_value)) {
		$wpdb->delete($table,$where);
		return;
	}
	$cur = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table WHERE gid = %d AND meta_key = %s", $gid, $meta_key) );

	if ($cur) {
		$res_update =	$wpdb->update($table,$update,$where);
		return $cur->id;
	}
	if (!$cur) {
		$insert 	= 	array(
			'gid' 			=> 	$gid,
			'meta_key' 		=> 	$meta_key,
			'meta_value' 	=> 	$meta_value,
		);
		$wpdb->insert($table,$insert);
		return $wpdb->insert_id;
	}
}

function get_game_status($status){
	$array 	= array(
		'0'	=> '已删除',
		'1'	=> '进行中',
		'2'	=> '已结束',
	);
}

function get_game_list($y=null){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE status <> 0";
	if (!is_null($y)){
		if ($y=="all") {
			$y = 2018;
		}
		$time_y = mktime(0,0,0,1,1,$y);
		$sql 	= $sql . " and start_time >= '".$time_y."' ";
	}
	$sql = $sql . " ORDER BY start_time desc";
	$res 	= 	$wpdb->get_results($sql);
	// echo $sql;
	// exit;
	return $res;

}

function get_luck_by_gid($gid){
	global $wpdb;
	$sql = "select * from ".$wpdb->prefix."kog_lucky where gid = '".$gid."'";
	$res 	= 	$wpdb->get_results($sql);
	return $res;
}


function get_game_memo_list($y=null,$memo=null,$end_y=null){
	global $wpdb;
	$end_y = isset($_REQUEST['end_y'])?$_REQUEST['end_y']:null;
	$sql 	=	"SELECT *,end_time - start_time  AS duration FROM ".$wpdb->prefix."kog_games WHERE status <> 0";
	if (!is_null($y)){
		if ($y=="all") {
			$y = 2018;
		}
		$time_y = mktime(0,0,0,1,1,$y);
		if(is_null($end_y)){
			$time_y_end = mktime(23,59,59,12,31,$y);	
		}
		if(isset($time_y_end)){
			$sql 	= $sql . " and start_time >= '".$time_y."' and end_time <= '".$time_y_end."'";	
		}else{
			$sql 	= $sql . " and start_time >= '".$time_y."'";
		}
		
	}
	if (!is_null($memo)) {
		$sql = $sql. " and memo = '".$memo."'";
	}
	// if(!is_null($gid)){
	// 	$sql = $sql. " and id = '".$gid."'";
	// }
	// echo "<pre>";
	// var_dump ($end_y);
	// exit;
	$sql = $sql . " ORDER BY start_time asc";
	$res 	= 	$wpdb->get_results($sql);
	// var_dump ($res);
	if (!empty($res)) {
		$res = get_game_list_bymemo($res);
		// 根据memo 核算此memo下的合计
		if (is_array($res['memo_gids'])) {
			foreach ($res['memo_gids'] as $key => $value) {
				// sql中取两位小数否则合计有问题
				$sql = "SELECT player , truncate(sum(total_fact),2) as total_fact from ".$wpdb->prefix."kog_details where gid IN ('".implode("','",$value)."') GROUP BY uid ORDER BY total_fact desc";
				$res['memo_total']['player_info'][$key] = $wpdb->get_results($sql);		
				// echo $sql;
				// exit;	
						
			}
		}
		
		// var_dump ($res);
		// // var_dump ($res['memo_total']['player_info']);
		// exit;
		return $res;
	}else{
		return false;
	}
	
}


function get_game_list_bymemo($game_list){
	// var_dump ($game_list);
	// exit;
	foreach ($game_list as $key => $value) {
		$game_memo['memo_games'][$value->memo][]=$value; 
		$game_memo['memo_gids'][$value->memo][]=$value->id;
		if ($value->duration <0) {
			$value->duration =0;
		}
		if ($value->duration >= 0 ) {
				$game_memo['memo_total']['duration'][$value->memo] = isset($game_memo['memo_total']['duration'][$value->memo])?$game_memo['memo_total']['duration'][$value->memo]+$value->duration:$value->duration;
				$game_memo['memo_total']['count_games'][$value->memo] = count($game_memo['memo_gids'][$value->memo]);	
				
			}
	}
	// var_dump ($game_memo);
	if (isset($game_memo)) {
		return $game_memo;
	}else{
		return false;
	}
	
}

function hmtl_if_negative($value){
	if ($value<0) {
		return "<span class='red-text'>".$value."</span>";
	} else {
		return "<span>".$value."</span>";
	}
}

function update_some_table($table,$update,$where){
	global $wpdb;
	$table = $wpdb->prefix.$table;
	return $wpdb->update($table,$update,$where);
}

function update_game_detail_rank($rank_data){
	global $wpdb;

	$table 		= $wpdb->prefix."kog_details";
	$update 	= array(
		'end_chips'		=>	$rank_data['end_chips'],
		'rank'			=>	$rank_data['rank'],
		'bonus'			=>	$rank_data['bonus'],
		'count_chips'	=>	$rank_data['count_chips'],
		'reward'		=>	isset($rank_data['reward'])?$rank_data['reward']:0,
	);

	$where 		= array(
		'uid' 	=> $rank_data['uid'],
		'gid' 	=> $rank_data['gid'],
	);
	return $wpdb->update($table,$update,$where);
}

function get_game_history_year(){
	global $wpdb;
	$sql = "select DISTINCT ( DATE_FORMAT (FROM_UNIXTIME (start_time),'%Y'))as start_y  from ".$wpdb->prefix."kog_games where status=2  ORDER BY start_y DESC";
	// echo $sql;
	// exit;
	$res_years = $wpdb->get_col($sql);
	return $res_years;
}

function test_js($value){

	$game_type = isset($_REQUEST['game_type'])?$_REQUEST['game_type']:null;
	$start_year = isset($_REQUEST['start_year'])?$_REQUEST['start_year']:null;
	$start_y = isset($_REQUEST['y'])?$_REQUEST['y']:null;
	if ($value == $game_type) {
		return "selected='selected'";
	}elseif ($value == $start_year){
		return "selected='selected'";
	}elseif ($value == $start_y){
		return "selected='selected'";
	}
	else{
		return false;
	}
}


// 记录游戏成本
function record_cost($memo,$cost,$pay_info){
	if (empty($memo)) {
		return false;
	}
	global $wpdb;
	$insert_cost = array(
				'memo' => $memo,
				'cost' => $cost,
				'pay_info' => json_encode($pay_info)
			);
	// $result = $wpdb->insert($wpdb->prefix . 'kog_gamegroup', $insert_cost);
	$result = $wpdb->replace($wpdb->prefix . 'kog_gamegroup', $insert_cost);
;
	return true;
}

// 获取游戏成本
function get_cost($memo=null,$player_id=null,$total=null){
	global $wpdb;
	$table = $wpdb->prefix . 'kog_gamegroup';
	$sql = "select * from ".$table." where 1=1";
	if (!empty($memo)) {
		$sql .=" and memo = '".$memo."' ";
	}
	$res = $wpdb->get_results($sql);
	
	if(!$res){
		return false;
	}
	foreach ($res as $key => $value) {
		$res_cost[$value->memo]=json_decode($value->pay_info);
	}
	// $res[0]->pay_info = json_decode($res[0]->pay_info);
	// echo "<pre>";
	// var_dump($res);
	// exit;
	// 直接获取某memo的成本
	if (!empty($total)) {
		// echo $res[0]->cost;
		// exit;
		return $res[0]->cost;
	}

	if (!empty($player_id)) {
		$res_cost = $res[0]->pay_info->$player_id;
		// $res = $res->$player_id;
	}

	return $res_cost;
}

function get_game($gid = null){
	global $wpdb;
	if (!empty($gid)) {
		if($gid =='last'){
			$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE status = 2 order by id desc";
			$res 	= 	$wpdb->get_row($sql);
		} else{
			$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE id = ".$gid;
			$res 	= 	$wpdb->get_row($sql);
		}
		
	} else {
		$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE  status <> 0 ";
		$res 	= 	$wpdb->get_results($sql);
	}	
	return 	$res;
}

// Top 10
function get_top_10($args = array()){
	global $wpdb;
	$defaults 	=	array(
		'status' 	=> 	false,
		'id' 		=>	false,
		'memo' 		=>	false,
		'start_time' 		=>	false,
		'end_time' 		=>	false,
		'game_type' 		=>	false,
		'order_type' 		=>	false,
	);
	$args = wp_parse_args($args , $defaults);
	$sql = "SELECT
			-- gid,total_fact
			*
			FROM
			".$wpdb->prefix."kog_games
			JOIN ".$wpdb->prefix."kog_details
			ON ".$wpdb->prefix."kog_games.id = ".$wpdb->prefix."kog_details.gid
			where 1=1 ";
			if (!empty($args['status'])) {
				$sql .= " and status = '".$args['status']."'";
			} else{
				$sql .= " and status <> 0 ";
			}
			
			if (!empty($args['id'])) {
				$sql .= " and id = '".$args['id']."'";
			}
			if(!empty($args['memo'])){
				$args['memo'] = str_replace("-","",$args['memo']);
				$sql = $sql.' and memo ='.$args['memo'];
			}
			if(!empty($args['start_time'])){
				$args['start_time'] = str_replace("-","",$args['start_time']);
				$sql = $sql.' and date>='.$args['start_time'];
			}
			if(!empty($args['end_time'])){
				$args['end_time'] = str_replace("-","",$args['end_time']);
				$sql = $sql.' and date<='.$args['end_time'];
			}
			if(!empty($args['game_type'])){
				$sql = $sql.' and game_type like '."'".$args['game_type']."'";
			}
			if(!empty($args['order_type'])){
				//
				if ($args['order_type'] == 'total_fact') {
					$sql .=" ORDER BY ".$args['order_type']." desc limit 10";
				}
			}
			if (!empty($args['id'])) {
				$res 	= 	$wpdb->get_row($sql);
			} else {
				$res 	= 	$wpdb->get_results($sql);
			}
			foreach ($res as $key => $value) {
				$res_arr['key'][]=$value->gid;
				$res_arr['value'][]=$value->total_fact;
			}
			// echo "<pre>";
			// var_dump($res);
			// exit;
			return $res;
}

function get_game_by_args($args = array()){
	global $wpdb;
	$defaults 	=	array(
		'status' 	=> 	false,
		'id' 		=>	false,
		'memo' 		=>	false,
		'start_time' 		=>	false,
		'end_time' 		=>	false,
		'game_type' 		=>	false,
	);

	$args = wp_parse_args($args , $defaults);

	$sql = "SELECT * FROM ".$wpdb->prefix."kog_games where 1=1 ";

	if (!empty($args['status'])) {
		$sql .= " and status = '".$args['status']."'";
	} else{
		$sql .= " and status <> 0 ";
	}
	
	if (!empty($args['id'])) {
		$sql .= " and id = '".$args['id']."'";
	}
	if(!empty($args['memo'])){
		$args['memo'] = str_replace("-","",$args['memo']);
		$sql = $sql.' and memo ='.$args['memo'];
	}
	if(!empty($args['start_time'])){
		$args['start_time'] = str_replace("-","",$args['start_time']);
		$sql = $sql.' and date>='.$args['start_time'];
	}
	if(!empty($args['end_time'])){
		$args['end_time'] = str_replace("-","",$args['end_time']);
		$sql = $sql.' and date<='.$args['end_time'];
	}
	if(!empty($args['game_type'])){
		$sql = $sql.' and game_type like '."'".$args['game_type']."'";
	}
	if (!empty($args['id'])) {
		// $sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE id = ".$args['id'];
		$res 	= 	$wpdb->get_row($sql);
	} else {
		// $sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE  status <> 0 ";
		$res 	= 	$wpdb->get_results($sql);
	}
	// echo "<pre>";
	// var_dump ($res);
	// exit;
	return 	$res;
}

function get_memo_winer($memo){
	// global 
}

function get_game_detail($gid,$orderby=null){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_details WHERE gid = ".$gid;
	if ($orderby != null) {
		$sql = $sql." order by ".$orderby." desc";
	}
	$res 	= 	$wpdb->get_results($sql);
	return 	$res;
}

function get_rebuy_info($gid,$uid=null){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_rebuy where gid = ".$gid;
	if(!empty($uid)){
		$sql = $sql . " and uid = ".$uid;
	}
	$res 	= 	$wpdb->get_results($sql);
	return $res;
}

function get_game_player($gid){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_details where gid = ".$gid." order by `rank`,`seat_position`";
	$res 	= 	$wpdb->get_results($sql);
	if (!empty($res)) {
		return $res;
	}else{
		return false;
	}
}

/**
 * 根据action gourp id (来自 gamemeta 表，就是插入的 meta_id)
 * @param  [type] $action_group_id [description]
 * @return [type]                  [description]
 */
function creat_action_info($action_group_id){
	global $wpdb;
	$table 	=	$wpdb->prefix.'kog_action';
	$date 	=	array(
		'gid' 			=> 	$_REQUEST['gid'],
		'action_group' 	=>	$action_group_id,
		'created_at' 	=>	time(),	
	);
	$allin 	=	array_merge($date, array('action' => 'allin','uid'=>$_REQUEST['allin_player'],'chips'=>$_REQUEST['chips']));
	$wpdb->insert($table,$allin);
	$win	=	array_merge($date, array('action' => 'win','uid'=>$_REQUEST['winner'],'chips'=>$_REQUEST['win_chips']));
	$wpdb->insert($table,$win);
	foreach ($_REQUEST['user_action'] as $key => $value) {
		$action_date 	= 	array_merge($date,array('action' => $value,'uid'=>$key));
		$wpdb->insert($table,$action_date);
	}
}

// LUCKY 对应的 Bonus

function get_lucky_bonus($key = null){
	$lucky_bonus =  array(
		"four" => "金刚",
		"straight flush" => "同花顺",
		"royal straight flush" =>"皇家同花顺",
		
	);
	if($key != null){
		return $lucky_bonus[$key];	
	}else{
		return $lucky_bonus;
	}
	
}

// 获取指定时间后的 lucky 详情
function get_lucky_info($start_time){
	global $wpdb;
	$start_time = strtotime($start_time);
	// $sql = "select * from  ".$wpdb->prefix ."kog_lucky where `created_at`>= ".$start_time;
	// 只去game status <> 0 的
	$sql = "SELECT
	".$wpdb->prefix ."kog_games.`status`, 
	".$wpdb->prefix ."kog_games.`rebuy_rate`/".$wpdb->prefix ."kog_games.`chips_level` * ".$wpdb->prefix."kog_lucky.`bonus`as money, 
	".$wpdb->prefix ."kog_lucky.*, 
	".$wpdb->prefix ."kog_games.id
	FROM
	".$wpdb->prefix ."kog_games
	INNER JOIN
	".$wpdb->prefix ."kog_lucky
	ON 
		".$wpdb->prefix ."kog_games.id = ".$wpdb->prefix ."kog_lucky.gid
	WHERE
	".$wpdb->prefix ."kog_games.`status` <> 0 
	AND ".$wpdb->prefix ."kog_lucky.created_at>= ".$start_time;

	$res = $wpdb->get_results($sql);
	$lucky_type = get_lucky_bonus();

	// 在sql中按总计次数排序

	$sql_order = "select `uid`, count(uid)as times from ".$wpdb->prefix ."kog_lucky group by `uid` ORDER BY times desc";
	$res_order = $wpdb->get_col($sql_order);

	if (is_array($res)) {
		foreach ($res as $key => $value) {
			foreach ($lucky_type as $k => $val) {
				
			
				if ($value->lucky_type == $k) {
					// 总计，以及各个类型的次数、金额
					$info[$value->uid]['total']['times'] = isset($info[$value->uid]['total']['times'])?$info[$value->uid]['total']['times']+1:0;
					$info[$value->uid]['total']['bonus']= isset($info[$value->uid]['total']['bonus'])?$info[$value->uid]['total']['bonus']+$value->bonus:0;
					$info[$value->uid]['total']['money']= isset($info[$value->uid]['total']['money'])?$info[$value->uid]['total']['money']+$value->money:0;
					$info[$value->uid][$k]['times'] = isset($info[$value->uid][$k]['times'])?$info[$value->uid][$k]['times']+1:0;
					$info[$value->uid][$k]['bonus']= isset($info[$value->uid][$k]['bonus'])?$info[$value->uid][$k]['bonus']+$value->bonus:0;
					$info[$value->uid][$k]['money']= isset($info[$value->uid][$k]['money'])?$info[$value->uid][$k]['money']+$value->money:0;
				}else{
					$info[$value->uid]['total']['times'] = isset($info[$value->uid]['total']['times'])?$info[$value->uid]['total']['times']:0;
					$info[$value->uid]['total']['bonus']= isset($info[$value->uid]['total']['bonus'])?$info[$value->uid]['total']['bonus']:0;
					$info[$value->uid]['total']['money']= isset($info[$value->uid]['total']['money'])?$info[$value->uid]['total']['money']:0;
					$info[$value->uid][$k]['times'] = isset($info[$value->uid][$k]['times'])?$info[$value->uid][$k]['times']:0;
					$info[$value->uid][$k]['bonus']= isset($info[$value->uid][$k]['bonus'])?$info[$value->uid][$k]['bonus']:0;
					$info[$value->uid][$k]['money']= isset($info[$value->uid][$k]['money'])?$info[$value->uid][$k]['money']:0;
				}
			}
		}
		foreach ($res_order as $key => $value) {
			if(isset($info[$value])){
				$info_order[$value] = $info[$value];	
			}
		}
	}
	// echo "<pre>";
	// var_dump($sql);
	// exit;
	return $info_order;
}


function creat_lucky_info($uid){
	global $wpdb;
	$lucky_bonus =  array(
		"straight flush" => "",
		"royal straight flush" =>"300",
		"four" => "100",
	);

	$lucky_date			=	array(
		'uid' 			=> 	$_REQUEST['uid'],
		'gid' 			=> 	$_REQUEST['gid'],
		'lucky_type' 	=> 	$_REQUEST['lucky_type'],
		'bonus'			=> 	$_REQUEST['bonus'],
		'cards' 		=>  $_REQUEST['cards'],
		'dealar' 		=>  $_REQUEST['dealar'],
		'player' 		=>  $_REQUEST['player'],
		'created_at' 	=>  time(),
	);

	$wpdb->insert($wpdb->prefix . 'kog_lucky', $lucky_date);
	$rid=$wpdb->insert_id;
	// $gamemeta_rebuy_date	=	array(
	// 	'gid' 			=> 	$_REQUEST['gid'],
	// 	'meta_key'		=>	'rebuy_id',
	// 	'meta_value'	=>	$rid,
	// );
	// $wpdb->insert($wpdb->prefix . 'kog_gamemeta', $gamemeta_rebuy_date);
	return $rid;
}

function creat_rebuy_info($uid){
	global $wpdb;

	$rebuy_date			=	array(
		'uid' 			=> 	$_REQUEST['uid'],
		'gid' 			=> 	$_REQUEST['gid'],
		'rebuy' 		=> 	$_REQUEST['chips'],
		'paied'		 	=> 	$_REQUEST['paied'],
		'killed_by' 	=>  $_REQUEST['killed_by'],
		'created_at' 	=>  time(),
	);

	$wpdb->insert($wpdb->prefix . 'kog_rebuy', $rebuy_date);
	$rid=$wpdb->insert_id;
	// $gamemeta_rebuy_date	=	array(
	// 	'gid' 			=> 	$_REQUEST['gid'],
	// 	'meta_key'		=>	'rebuy_id',
	// 	'meta_value'	=>	$rid,
	// );
	// $wpdb->insert($wpdb->prefix . 'kog_gamemeta', $gamemeta_rebuy_date);
	return $rid;
}

function del_rebuy_info($rebuy_id){
	global $wpdb;
	$table 		=	$wpdb->prefix . 'kog_rebuy';

	
	$where 		=	array(
		'id'			=>	$rebuy_id
	);
	$wpdb->delete($table,$where);
}

function creat_player($players=array()){
	global $wpdb;
	foreach ($players as $key => $value) {
		if (!empty($value)) {
			$insert_player = array(
				'nickname' => $value,
			);
			$wpdb->insert($wpdb->prefix . 'kog_user', $insert_player);
		}
	}
	return true;
}

function get_player($uid = null){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_user where `is_del` <> '1'";
	if (!empty($uid)){
		$sql = $sql." and id = '".$uid."'";
	}
	$res 	=	$wpdb->get_results($sql);
	// echo "<pre>";
	// var_dump($res);
	// exit;
	return 	$res;
}

function get_player_id_arr($arr_id){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_user where id in (".$arr_id.")";
	$res 	=	$wpdb->get_results($sql);
	return 	$res;
}
/**
 * 将一个二维数组中某两个值拿出来组成一个一维数组，并且新数组的key 是二维数组中的某个value, 
 * @param  [type] $array [description]
 * @param  [type] $key   [作为新数组key, 是原数组的某一个key],一般是id
 * @param  [type] $value [作为新数组的Value，是原数组的某一个key],一般是id 对应的某个值。如id=5, name=abc
 * @return [type]        [description]
 */
function creat_single_array($array,$key,$value){
	if (empty($array)) {
		return false;
	} else {
		foreach ($array as $k => $val) {
			if (is_object($val)) {
				$single_array[$val->$key] = $val->$value;
			}
			else if (is_array($val)) {
				$single_array[$val->$key] = $val[$value];
			}
			else {
				return false;
			}
		}
		// $single_array 	= array_unique($single_array);
		return $single_array;
	}
}
// get_last_game_memo();
// 获取最近一场game
function get_last_game_memo(){
	global $wpdb;
	$sql = "select memo from ".$wpdb->prefix."kog_games  order by id DESC limit 1";
	$last_game_memo = $wpdb->get_var($sql);
}

// 根据gid获取gamememo
function get_game_memo_byid($gid){
	global $wpdb;
	$sql = "select memo from ".$wpdb->prefix."kog_games where id ='".$gid."' order by id DESC limit 1";
	$game_memo = $wpdb->get_var($sql);
	return $game_memo;
}


function creat_game(){
	global $wpdb;
	$_REQUEST['bonus']  = 	isset($_REQUEST['bonus'])?$_REQUEST['bonus']:null;
	$game_data 			=	array(
		'date' 			=> 	date('Ymd',time()),
		'memo' 			=> 	date('Ymd',time()), // 用来标识 是同一个“大场次”的game通过Clone创建的,为了避免重复，此处加上“年”。
		'start_time' 	=> 	time(),
		'chips_level' 	=> 	$_REQUEST['chips_start'],
		'rebuy_rate' 	=> 	$_REQUEST['rebuy_rate'],
		'bonus' 		=>  serialize($_REQUEST['bonus']),
		'status' 		=> 	1,
		'created_by'	=>	1,
		'game_type' 	=>	$_REQUEST['game_type']
	);
	$wpdb->insert($wpdb->prefix . 'kog_games', $game_data);
	$meta_date = array(
		'small_blind' 	=>	$_REQUEST['small_blind'],
		'big_blind' 	=>	$_REQUEST['big_blind'],
		'straddle' 		=>	$_REQUEST['straddle'],
		'blind_level' 	=>	"1" // 初始盲注级别是1，即：原始盲注
	);
	if($gid=$wpdb->insert_id){
		creat_game_detail($wpdb->insert_id);
		// 插入盲注级别到 meta表中，用来自动检测
		insert_game_meta_by_arr($gid,$meta_date);
	}
	return $gid;
}

function send_message($tel_text=null){
	$bot_key = "bot5174575036:AAEwt_eSARtn3rJl6n1Vg5DO5Z0lLZQ1msU";
	$chat_id = "-1001681233477";
	// 本地调试用
	// $chat_id = "652145690";
	$tel_bot = "https://api.telegram.org/".$bot_key."/sendMessage?chat_id=".$chat_id."&text=";
	$url = $tel_bot.$tel_text;
	curl_file_get_contents($url);
	// apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => $tel_text));
}

// 自动检测是否强抓、升盲 通过 check_game.php 页面调用，用于 crontab 5分钟定时
function check_upgrade_by_time($host=null){
	// 找到正在进行中的游戏
	global $wpdb;
	$bot_key = "bot5174575036:AAEwt_eSARtn3rJl6n1Vg5DO5Z0lLZQ1msU";
	$chat_id = "-1001681233477";
	// 本地调试用
	// $chat_id = "652145690";
	$tel_bot = "https://api.telegram.org/".$bot_key."/sendMessage?chat_id=".$chat_id."&text=";
	$ongoing = get_game_by_args(array("status" => 1));//只获取status = 1 的数据，既进行中的数据。

	if ($ongoing == null) {
		// echo "没有进行中游戏...";
		$tel_text = "没有进行中游戏";
		$url = $tel_bot.$tel_text;
			// echo $tel_bot."<br>";

			// 以下两行用来调试消息发送用，调试完毕后需注销.下面两行一样，都会执行，用一个即可
			// curl_file_get_contents($url);
			// apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => $tel_text));
			
		return false;
	}else{
		// echo "<pre>";
		// var_dump($ongoing);
		// exit;
		foreach ($ongoing as $key => $value) {
			$button_1 ="";
			$button_2 = "查看游戏";
			$callback = "";
			$callback_2 = "end_game";
			// 定时5分钟一次，最多延误5分钟，所以判断是把误差加进去
			$delta = 5*60;
			$duration = time()+ $delta - $value->start_time ;
			if ($duration < 0) {
				$tel_text = "开始时间异常，Duration 小于0";
			}elseif($duration >= 0 && $duration < 3600) {
				$tel_text = "正常进行中，无需处理";
				// 直接 return不做消息输出，仅作调试用
				return true;
			}elseif($duration >= 3600 && $duration < 7200) {
				// 检查当前是否已经开始强抓？
				$straddle = get_game_meta_by_meta_key($value->id,'straddle')['0']['meta_value'];
				if ($straddle != 0) {
					// 已经开始强抓，无需任何处理，直接跳出此时间检验
				}else{
					// 已开始1小时，还没到2小时,并且尚未开始强抓，需要消息提醒
					$n_min_to_3b = intval((3600 - ($duration - $delta))/60);
					if ($n_min_to_3b<0) {
						$n_min_to_3b = abs($n_min_to_3b);
						$n_min_to_3b_str = "已超过1小时 $n_min_to_3b 分钟";
					}elseif ($n_min_to_3b > 0){
						$n_min_to_3b_str = abs($n_min_to_3b)."分钟后到1小时。";
					}else{
						$n_min_to_3b_str = "刚好到达1小时";
					}
					$tel_text = "GID: ".$value->id."
					本场游戏于".date("H:i",$value->start_time)."开始
					".$n_min_to_3b_str."是否开始强抓？";
					$button_1 = "立刻强抓";
					$callback = ["a"=>"straddle","gid"=>$value->id];
				}
			}
			else{
				// 大于等于2小时，先检查当前盲注级别
				// echo "<pre>";
				// echo $value->id;
				// var_dump(get_game_meta_by_meta_key($value->id,'blind_level'));
				// exit;
				$blind_level = get_game_meta_by_meta_key($value->id,'blind_level')['0']['meta_value'];
				$small_blind = get_game_meta_by_meta_key($value->id,'small_blind')['0']['meta_value'];
				$big_blind = get_game_meta_by_meta_key($value->id,'big_blind')['0']['meta_value'];
				$straddle = get_game_meta_by_meta_key($value->id,'straddle')['0']['meta_value'];
				// n>1后，n小时升盲n倍（初始n是1），所以当
				// $check_time_pre = $n * 3600;
				// $check_time_last =  ($n+1) * 3600
				// 距离开始多少时间了？
				$duration_h = intval(($duration/3600));
				// 几分钟后到达 n 小时
				// echo $duration;
				// echo "<br>";
				// echo $blind_level;
				// exit;
				$n_min_to_blindplus = intval((($duration_h)-(($duration-$delta)/3600))*60);
				if ($n_min_to_blindplus >0) {
					$n_min_to_blindplus_str = $n_min_to_blindplus."分钟后到达 ".($duration_h)." 小时";
				}elseif($n_min_to_blindplus <0){
					$n_min_to_blindplus = abs($n_min_to_blindplus);
					$n_min_to_blindplus_str = "已超过 $duration_h 小时 $n_min_to_blindplus 分钟";
				}else{
					$n_min_to_blindplus_str = "刚好到达 $duration_h 小时";
				}
				if (($duration_h) > $blind_level) {
					// 当前用时与盲注级别不匹配，提示升盲.初始默认盲注级别是1
					$blind_times = pow(2,($blind_level-1));
					$blind_times_to = pow(2,($blind_level));
					$small_blind = $small_blind*$blind_times;
					$big_blind = $big_blind*$blind_times;
					$straddle = $straddle*$blind_times;
					$tel_text = "GID: ".$value->id."
					本场游戏在".date("H:i",$value->start_time)."开始
					已进行 $duration_h 个多小时
					$n_min_to_blindplus_str
					当前盲注级别: $blind_level (建议级别: $duration_h)
					盲注级别将改变
					SB: $small_blind -> ".$small_blind*$blind_times_to."
					BB: $big_blind -> ".$big_blind*$blind_times_to."
					Straddle: $straddle -> ".$straddle*$blind_times_to."
					即将到达升盲时间，是否开始升盲？";
					$button_1 = "立刻升盲";
					$callback = array('a'=>'blind_up','gid'=>$value->id);
				}else{
					// 盲注级别与当前时间相符，无需处理
					// echo "盲注级别与当前时间相符，无需处理";
					$tel_text = null;
				}
			}
			// 正式环境需取消注释
			$callback_url = "https://".$host."/kog_detail.php?page_name=Have%20Fun%20!&gid=".$value->id;
			// 本地测试环境用
			// $callback_url = "https://allin.xys.one/kog_detail.php?page_name=Have%20Fun%20!&gid=585";
			// echo $callback_url;
			// exit;
			if (isset($tel_text) && $tel_text != null) {
				$callback = json_encode($callback);
				$inlineKeyboard = [
				    'inline_keyboard' => [
				        [
				            ['text' => $button_1, 'callback_data' => $callback],
				            ['text' => '查看游戏',  'url' =>  $callback_url]
				        ]
				    ]
				];
				$encodedKeyboard = json_encode($inlineKeyboard);
				$response =apiRequestJson("sendMessage", [
				    'chat_id' => $chat_id,
				    'text' => $tel_text,
				    'reply_markup' => $encodedKeyboard
				]);

				if (!$response) {
				    // 输出错误信息进行调试
				    echo ("Failed to send message: " . json_encode($response));
				    // 根据需要进行错误处理
				}
			}
			
		}
		return true;
	}
}

// telegram bot 相关函数
ini_set('error_reporting', E_ALL);

// file_get_contents("https://api.telegram.org/bot5174575036:AAEwt_eSARtn3rJl6n1Vg5DO5Z0lLZQ1msU/sendMessage?chat_id=-1001681233477&text=Hello world again!");

// 设置 webhook
// https://api.telegram.org/bot5174575036:AAEwt_eSARtn3rJl6n1Vg5DO5Z0lLZQ1msU/getWebhookInfo
// https://api.telegram.org/bot5174575036:AAEwt_eSARtn3rJl6n1Vg5DO5Z0lLZQ1msU/getupdates
// https://api.telegram.org/bot5174575036:AAEwt_eSARtn3rJl6n1Vg5DO5Z0lLZQ1msU/setWebhook?url=https://bot.xys.one/telegram_webhook.php
// $bot_key = "bot5174575036:AAEwt_eSARtn3rJl6n1Vg5DO5Z0lLZQ1msU";

// $chat_id = "-1001681233477";
// $tel_bot = "https://api.telegram.org/".$bot_key."/sendMessage?chat_id=".$chat_id."&text=";
define('BOT_TOKEN', '5174575036:AAEwt_eSARtn3rJl6n1Vg5DO5Z0lLZQ1msU');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

// check_upgrade_by_time();

function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $payload = json_encode($parameters);
  header('Content-Type: application/json');
  header('Content-Length: '.strlen($payload));
  echo $payload;

  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successful: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

// 发送消息给 企业微信_BOT
// webhook https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=d3a80b49-c08d-4ac8-a5ee-6a64ab8e4a54


// 发送消息向Telegram_BOT
function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POST, true);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}


// 根据回复内容确定如何回调 2022.08.03
function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];

    if (strpos($text, "/start") === 0) {
      apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello', 'reply_markup' => array(
        'keyboard' => array(array('Hello', 'Hi')),
        'one_time_keyboard' => true,
        'resize_keyboard' => true)));
    } else if (strpos($text, "/keyboard") === 0) {
    	$keyboard = [
			    'inline_keyboard' => [
			        [
			            ['text' => 'forward me to groups', 'callback_data' => 'someString'],
			            ['text' => 'forward me to groups2', 'callback_data' => 'someString2']
			        ]
			    ]
			];
			$encodedKeyboard = json_encode($keyboard);


    	apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello', 'reply_markup' => $keyboard));
    } else if (strpos($text, "/check") === 0) {
    	$callback = array('a'=>'count_time');
    	$callback = json_encode($callback);
    	$keyboard = [
			    'inline_keyboard' => [
			        [
			            ['text' => '开始计时', 'callback_data' => $callback],
			            // ['text' => 'forward me to groups2', 'callback_data' => 'someString2']
			        ]
			    ]
			];
			$encodedKeyboard = json_encode($keyboard);


    	apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => "游戏开始，是否开始计时？", 'reply_markup' => $keyboard));
    } else if (strpos($text, "/code") === 0){
    	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $message));
    } else if ($text === "Hello" || $text === "Hi") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Nice to meet you'));
    } else if (strpos($text, "/stop") === 0) {
      // stop now
    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => 'Cool'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
  }
}

// 对回调内容进行处理
function processCallback($callback){
	
  $message = $callback['message'];
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  $data = json_decode($callback['data'],true);
  $action = $data['a'];
  $gid = $data['gid'];
  $gid_status = get_game($gid)->status;

  // $gid_status = $data['status'];
  $text = "";
  if ($gid_status == 0) {
  	// 游戏已删除，不可进行任何操作，直接返回
  	$text = "Gid:".$gid." 已被删除，无法进行任何操作";
  	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $text));
  	return;
  }
  if ($gid_status == 2) {
  	// 游戏已结束，不可进行任何操作，直接返回
  	$text = "Gid:".$gid." 已结束，无法进行任何操作";
  	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $text));
  	return;
  }
	// apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $data));
  if ($action == 'straddle') {
  	// 开始强抓
  	$text = $gid;
  	update_gamemeta($gid,'straddle',"20");
		if(update_gamemeta($gid,'straddle_time',time())){
			$blind_up_next = date("H:i",(time()+3600));
			$text = "Gid:".$gid."「开始强抓」
			Straddle: 20
			下次升盲时间: $blind_up_next";

		}
  } else if($action == "blind_up"){
  	// 升盲
  	// 反馈当前盲注级别、历时时间、应该升盲倍数
  	$blind_level = isset(get_game_meta_by_meta_key($gid,'blind_level')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'blind_level')['0']['meta_value']:"1";
  	$small_blind = isset(get_game_meta_by_meta_key($gid,'small_blind')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'small_blind')['0']['meta_value']:"5";
		$big_blind = isset(get_game_meta_by_meta_key($gid,'big_blind')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'big_blind')['0']['meta_value']:"10";
		$straddle = isset(get_game_meta_by_meta_key($gid,'straddle')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'straddle')['0']['meta_value']:"0";
  	$blind_level_to = $blind_level+1;
		$blind_levle_n = "blind_level_".($blind_level_to);
		update_gamemeta($gid,'blind_level',$blind_level_to);
		$blind_times = pow(2,($blind_level_to));
		$small_blind = $small_blind * $blind_times ;
		$big_blind = $big_blind* $blind_times;
		$straddle = $straddle* $blind_times;
		$blind_up_next = date("H:i",(time()+3600));
		if(update_gamemeta($gid,$blind_levle_n,time())){
			$text = "Gid:".$gid."「开始升盲」
			盲注级别: $blind_level -> $blind_level_to
			SB: $small_blind
			BB: $big_blind
			Straddle: $straddle
			下次升盲时间: $blind_up_next
			";

		}
		// $text = "当前盲注级别: $blind_level";
  	// 再接受确定的升盲倍数
  	// 调用已有方法，写入升盲倍数、升盲时间
  } else if($action == "count_time"){
  	$text = "开始计时，3600秒后check";
  	// apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $text));
  	$i = 1;
  	// 先检查一次
  	$check_res = check_upgrade_by_time();
  	// do {
  	// 	$check_res = check_upgrade_by_time();
  	// 	$i++;
  	// 	$text = "第 $i 次检查，检查结果为 $check_res,将在 60 S之后再次检查";
  	// 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $text));
  	// 	sleep(60);
  	// } 
  	while ($check_res === false){
  		
  		// $text = "第 $i 次检查，检查结果为 $check_res,将在 33 S之后再次检查";
  		// apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $text));
  		sleep(33);
  		$check_res = check_upgrade_by_time();
  		$i++;
  	}
  	$text = "检查完毕，退出检查";
  	// exit;
  }
  apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $text));
}





function curl_file_get_contents($durl){  
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $durl);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回    
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回    
    $r = curl_exec($ch);  
    curl_close($ch);  
    return $r;  
}  

// get_blind_start("592");
function get_blind_start($gid){
	$small_blind = get_game_meta_by_meta_key($gid,'small_blind')['0']['meta_value'];
	// echo "<pre>";
	// var_dump ($small_blind);
	// exit;
	$big_blind = get_game_meta_by_meta_key($gid,'big_blind')['0']['meta_value'];
	$straddle = 0;
	$blind_level = 1;
	$meta_date = array(
		'small_blind' => $small_blind,
		'big_blind' => $big_blind,
		'straddle' => $straddle,
		'blind_level' => $blind_level,
	);
	return $meta_date;
}
function set_straddle($gid,$straddle){

}

function insert_game_meta_by_arr($gid,$game_meta_data){
	global $wpdb;
	$sql = "insert into ".$wpdb->prefix."kog_gamemeta(gid,meta_key,meta_value) values 
	('".$gid."','small_blind','".$game_meta_data['small_blind']."'),
	('".$gid."','big_blind','".$game_meta_data['big_blind']."'),
	('".$gid."','straddle','".$game_meta_data['straddle']."'),
	('".$gid."','blind_level','".$game_meta_data['blind_level']."')
	";
	$wpdb->query($sql);
}

function clone_game($gid){
	global $wpdb;
	$source_game = get_game($gid);
	// 插入一条重复的
	$sql_game = "insert into ".$wpdb->prefix . 'kog_games'." (date,start_time,chips_level,rebuy_rate,bonus,status,created_by,game_type,memo) 
		select ".date('Ymd',time())." as date,".time()." as start_time,chips_level,rebuy_rate,bonus,1 as status,created_by,game_type,memo from ".$wpdb->prefix . 'kog_games'." where id =  ".$gid;

	$wpdb->query($sql_game);
	$new_gid = $wpdb->insert_id;
	$sql_game_detail = "insert into ".$wpdb->prefix . 'kog_details'." (gid,player,uid,start_chips,seat_position) 
		select ".$new_gid." as gid, player,uid,start_chips,seat_position from ".$wpdb->prefix . 'kog_details'." where gid =  ".$gid;
	$wpdb->query($sql_game_detail);
	$meta_date = get_blind_start($gid);
	insert_game_meta_by_arr($new_gid,$meta_date);
	return $new_gid;
}


function creat_game_detail($gid){
	global $wpdb;
	$player_arr 	=	get_player();
	$player 		=	creat_single_array($player_arr,'id','nickname');
	foreach ($_REQUEST['position'] as $key => $value) {
		if (!empty($value)) {
			$game_data 			=	array(
				'gid' 			=>	$gid,
				'uid' 			=>	$value,
				'player'		=>	$player[$value],
				'start_chips' 	=> 	$_REQUEST['chips_start'],
				'seat_position' => 	$key
			);
			$wpdb->insert($wpdb->prefix . 'kog_details', $game_data);
		}
	}
	return true;
}

/**
 * Prints a HTML input field
 *
 * @param  array   the args
 *
 * @return void
 */
function erp_html_form_input( $args = array() ) {
	$defaults = array(
		'placeholder'   => '',
		'required'      => false,
		'type'          => 'text',
		'class'         => '',
		'tag'           => '',
		'wrapper_class' => '',
		'label'         => '',
		'name'          => '',
		'id'            => '',
		'value'         => '',
		'help'          => '',
		'addon'         => '',
		'readonly'      => '',
		'addon_pos'     => 'before',
		'custom_attr'   => array(),
		'options'       => array(),
		'style'         => '',
		'function'      => '',
	);

	$field    = wp_parse_args( $args, $defaults );
	$field_id = empty( $field['id'] ) ? $field['name'] : $field['id'];

	$field_attributes = array_merge( array(
		'name'        => $field['name'],
		'id'          => $field_id,
		'class'       => $field['class'],
		'placeholder' => $field['placeholder'],
	), $field['custom_attr'] );

	if ( $field['required'] ) {
		$field_attributes['required'] = 'required';
	}

	$custom_attributes = erp_html_form_custom_attr( $field_attributes );

    // open tag
	if ( ! empty( $field['tag'] ) ) {
		echo '<' . $field['tag'] . ' class="erp-form-field ' . esc_attr( $field['name'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '">';
	}

	if ( ! empty( $field['style'])) {
		$field['style'] = "style='".$field['style']."'";
	}

	if ( ! empty( $field['label'] ) ) {
		erp_html_form_label( $field['label'], $field_id, $field['required'] );
	}

	if ( ! empty( $field['addon'] ) ) {
		echo '<div class="input-group">';

		if ( $field['addon_pos'] == 'before' ) {
			echo '<span class="input-group-addon">' . $field['addon'] . '</span>';
		}
	}

	switch ( $field['type'] ) {
		case 'text':
		case 'email':
		case 'number':
		case 'hidden':
		case 'url':
		echo '<input  ' . $field['style'] . ' ' . $field['readonly'] . ' type="' . $field['type'] . '" value="' . esc_attr( $field['value'] ) . '" ' . implode( ' ', $custom_attributes ) . ' ' . $field['function'] . ' />';
		break;

		case 'select':
		if ( $field['options'] ) {
			echo '<select ' . implode( ' ', $custom_attributes ) . '>';
			foreach ($field['options'] as $key => $value) {
				printf( "<option value='%s'%s>%s</option>\n", $key, selected( $field['value'], $key, false ), $value );
			}
			echo '</select>';
		}
		break;

		case 'textarea':
		echo '<textarea ' . $field['style'] . '  ' . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $field['value'] ) . '</textarea>';
		break;

		case 'wysiwyg':
		$editor_args = [
			'editor_class'  => $field['class'],
			'textarea_rows' => isset( $field['custom_attr']['rows'] ) ? $field['custom_attr']['rows'] : 10,
			'media_buttons' => isset( $field['custom_attr']['media'] ) ? $field['custom_attr']['media'] : false,
			'teeny'         => isset( $field['custom_attr']['teeny'] ) ? $field['custom_attr']['teeny'] : true,

		];

		wp_editor( $field['value'], $field['name'], $editor_args );
		break;

		case 'checkbox':
            //echo '<input type="hidden" value="off" name="' . $field['name'] . '" />';
		echo '<span class="checkbox">';
		echo '<label for="' . esc_attr( $field_attributes['id'] ) . '">';
		echo '<input type="checkbox" '.checked( $field['value'], 'on', false ).' value="on" ' . implode( ' ', $custom_attributes ) . ' />';
		echo wp_kses_post( $field['help'] );
		echo '</label>';
		echo '</span>';
		break;

		case 'multicheckbox':

		echo '<span class="checkbox">';
		unset( $custom_attributes['id'] );

		foreach ( $field['options'] as $key => $value ) {
			echo '<label for="' . esc_attr( $field_attributes['id'] ) . '-' . $key .'">';
			if ( ! empty( $field['value'] ) ) {
				if ( is_array( $field['value'] ) ) {
					$checked = in_array( $key, $field['value'] ) ? 'checked' : '';
				} else if ( is_string( $field['value'] ) ) {
					$checked = in_array( $key, explode(',', $field['value'] ) ) ? 'checked' : '';
				} else {
					$checked = '';
				}
			} else {
				$checked = '';
			}

			echo '<input type="checkbox" '. $checked .' id="' . esc_attr( $field_attributes['id'] ) . '-' . $key . '" value="'.$key.'" ' . implode( ' ', $custom_attributes ) . ' />';
			echo '<span class="checkbox-value">' . wp_kses_post( $value ) . '</span>';
			echo '</label>';
		}
		echo '</span>';
		break;

		case 'radio':
		if ( $field['options'] ) {
			foreach ( $field['options'] as $key => $value) {
				echo '<input type="radio" '.checked( $field['value'], $key, false ).' value="'.$key.'" ' . implode( ' ', $custom_attributes ) . ' />'. $value . '&nbsp;';
			}
		}
		break;
		case 'radio_group':
		if ( $field['options'] ) {
			foreach ( $field['options'] as $key => $value) {
				echo '<div style="float:left;width:100px;font-size:0.9em"><input type="radio" '.checked( $field['value'], $key, false ). $field['function'].$key .'" value="'.$key.'" ' . implode( ' ', $custom_attributes ) . '  />'. $value . '&nbsp;</div>';
			}
		}
		break;


        // 增加一个label的类型，便于用这个直接显示结果。
		case 'span':
		erp_html_form_label($field['value']);
		break;

		default:
            # code...
		break;
	}

	if ( ! empty( $field['addon'] ) ) {

		if ( $field['addon_pos'] == 'after' ) {
			echo '<span class="input-group-addon">' . $field['addon'] . '</span>';
		}

		echo '</div>';
	}

	if ( $field['type'] != 'checkbox' ) {
		erp_html_form_help( $field['help'] );
	}

    // closing tag
	if ( ! empty( $field['tag'] ) ) {
		echo '</' . $field['tag'] . '>';
	}
} 

/**
 * Handles an elements custom attribute
 *
 * @param  array   attributes as key/value pair
 *
 * @return array
 */
function erp_html_form_custom_attr( $attr = array(), $other_attr = array() ) {
	$custom_attributes = array();

	if ( ! empty( $attr ) && is_array( $attr ) ) {
		foreach ( $attr as $attribute => $value ) {
			if ( $value != '' ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}
	}

	if ( ! empty( $other_attr ) && is_array( $other_attr ) ) {
		foreach ( $attr as $attribute => $value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
		}
	}

	return $custom_attributes;
}

/**
 * Prints a label attribute
 *
 * @param  string  label vlaue
 * @param  string  id field for label
 *
 * @return void
 */
function erp_html_form_label( $label, $field_id = '', $required = false ) {
	$req = $required ? ' <span class="required">*</span>' : '';
	echo '<label for="' . esc_attr( $field_id ) . '">' . wp_kses_post( $label ) . $req . '</label>';
}

/**
 * Prints help text
 *
 * @param  string  the description
 *
 * @return void
 */
function erp_html_form_help( $value = '' ) {
	if ( ! empty( $value ) ) {
		echo '<span class="description">' . wp_kses_post( $value ) . '</span>';
	}
}


?>	