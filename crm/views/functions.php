<?php
// define('PATH', dirname(dirname(__FILE__)).'/');
// date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
ini_set('date.timezone','Asia/Shanghai'); 

define('PATH', dirname(dirname(__FILE__)).'/');


require_once(PATH . 'wp-blog-header.php'); 

// get_header('nonav');
error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * 调试区域，调用本页函数
 */
// count_person_position_win();


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
		return "全部场次";
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
function count_person_position_win($start_time=null, $end_time=null, $game_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 		=	"SELECT d.uid, d.* FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($start_time)){
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
function cont_position_winer_new($start_time=null, $end_time=null,$game_type=null,$stat_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 	=	"SELECT d.* FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($start_time)){
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
function cont_position_winer($start_time=null, $end_time=null,$game_type=null,$stat_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 	=	"SELECT d.* FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($start_time)){
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
function count_winer_rate($start_time=null, $end_time=null, $game_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 	=	"SELECT d.*,g.game_type,g.start_time,g.end_time,g.date FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($start_time)){
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
	// 
	// 获取状态是2的全部场次及详情
	$kog_games 	= 	get_game_by_args(array('status'=>2,'start_time'=>$start_time,'end_time'=>$end_time,'game_type'=>$game_type));
		// 初始化一个数组用来填写每场的数据，没参加的当场就是0
	$players = get_player();
	if (isset($kog_games)) {
		foreach ($kog_games as $key => $value) {
			foreach ($players as $k => $val) {
				$user_times_win_rate[$val->id][$value->date] = 0;
			}
		}

	}
	if (is_array($res) && !empty($res)) {
		// 计算场次
		// $game_list 		=	get_game_list();
		// $game_count 	=	count($game_list);
		
		foreach ($res as $key => $value) {
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
			$new_game_win_rate[$key][0]	= get_player($key)[0]->nickname;
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

function get_position_stat($start_time=null, $end_time=null,$game_type=null,$stat_type=null){
	$res = get_player_total_income_stat($start_time, $end_time,$game_type,$stat_type)['res'];
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

function get_best_killer($start_time=null, $end_time=null, $game_type=''){
	global $wpdb;
	$sql 	=	"SELECT killed_by FROM ".$wpdb->prefix."kog_rebuy as r 
	left join ".$wpdb->prefix."kog_games as g on g.id = r.gid where g.status <> 0";
	if(!is_null($start_time)){
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
			$ac[$key] = 0;
		}
	}
	$kill_rank 	=	array();
	// array($value['name'],array_sum($value['data']));
	foreach ($ac as $key => $value) {
		$kill_rank[] 	=	array($player_name[$key],$value);
	}
	return $kill_rank;
}
// echo "<pre>";
// var_dump (if_in_this_month("1521204268","2018","02"));
// exit;


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


function get_player_total_income_stat($start_time=null, $end_time=null,$game_type=null,$stat_type='',$timezone="all"){
	global 	$wpdb;
	$table 		=	$wpdb->prefix."kog_details";
	$sql 		=	"SELECT d.*,start_time,end_time,game_type,`date`
	FROM ".$wpdb->prefix."kog_details as d 
	left join ".$wpdb->prefix."kog_games as g 
	on g.id = d.gid where g.status =2";
	if(!is_null($start_time)){
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
		$kog_games 	= 	get_game_by_args(array('status'=>2,'start_time'=>$start_time,'end_time'=>$end_time,'game_type'=>$game_type));
		// 初始化一个数组用来填写每场的数据，没参加的当场就是0
		$players = get_player();
		if (isset($kog_games)) {
			foreach ($kog_games as $key => $value) {
				foreach ($players as $k => $val) {
					$total_income_add[$val->id][$value->date] = 0;
				}
			}

		}
		foreach ($res as $key => $value) {

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

		// var_dump ($total_income_stat['income_1']);
		// // var_dump ($total_income_stat['date_1']);
		// exit;
     foreach ($total_income_stat['income_1'] as $key => $value) {
     	$total_income_stat['income_2'][$key]['name']=$value['name'];
     	$total_income_stat['total'][] 	=	array($value['name'],array_sum($value['data']),round(array_sum($value['data'])/count($value['data']),2));
			$total_income_stat['total_uid'][$key] 	=	array($value['name'],array_sum($value['data']));//存一个对应用户id的数据，便于使用
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
		// var_dump ($total_income_stat['total']);
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
		$where .= " and meta_key = ".$meta_key;
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
		$time_y = mktime(0,0,0,1,1,$y);
		$sql 	= $sql . " and start_time >= '".$time_y."'";
	}
	$res 	= 	$wpdb->get_results($sql);
	return $res;

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

function get_game($gid = null){
	global $wpdb;
	if (!empty($gid)) {
		$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE id = ".$gid;
		$res 	= 	$wpdb->get_row($sql);
	} else {
		$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE  status <> 0 ";
		$res 	= 	$wpdb->get_results($sql);
	}	
	return 	$res;
}

function get_game_by_args($args = array()){
	global $wpdb;
	$defaults 	=	array(
		'status' 	=> 	false,
		'id' 		=>	false,
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
	// echo "<pre>";
	// var_dump ($sql);
	// exit;
	if (!empty($args['id'])) {
		// $sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE id = ".$args['id'];
		$res 	= 	$wpdb->get_row($sql);
	} else {
		// $sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE  status <> 0 ";
		$res 	= 	$wpdb->get_results($sql);
	}
	return 	$res;
}

function get_game_detail($gid){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_details WHERE gid = ".$gid;
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
	return $rid;
}

function get_player($uid = null){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_user";
	if (!empty($uid)){
		$sql = $sql." where id = '".$uid."'";
	}
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

function creat_game(){
	global $wpdb;
	$_REQUEST['bonus']  = 	isset($_REQUEST['bonus'])?$_REQUEST['bonus']:null;
	$game_data 			=	array(
		'date' 			=> 	date('Ymd',time()),
		'start_time' 	=> 	time(),
		'chips_level' 	=> 	$_REQUEST['chips_start'],
		'rebuy_rate' 	=> 	$_REQUEST['rebuy_rate'],
		'bonus' 		=>  serialize($_REQUEST['bonus']),
		'status' 		=> 	1,
		'created_by'	=>	1,
		'game_type' 	=>	$_REQUEST['game_type'],
	);
	$wpdb->insert($wpdb->prefix . 'kog_games', $game_data);
	if($gid=$wpdb->insert_id){
		creat_game_detail($wpdb->insert_id);
	}
	return $gid;
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