<?php
date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
// define('PATH', dirname(dirname(__FILE__)).'/');
require_once('functions.php'); 
require_once ("kog_header.php");

// update_real_chips();
$_REQUEST['type'] = isset($_REQUEST['type'])?$_REQUEST['type']:"total";
// echo $_REQUEST['type'];
$uid = isset($_REQUEST['uid'])?$_REQUEST['uid']:null;
// $person_name = get_player($uid)[0]->nickname;
if (!empty(get_player($uid))) {
	$person_name = get_player($uid)[0]->nickname;
}

 // echo "<p>今天是：" . date("Y/m/d H:i:s") . "</p>";
 // exit;
$game_list = get_game_history_year();
// echo "<pre>";
// var_dump(get_player());
// exit;
$game=get_game("last");
if (!empty($game)) {
	$game_group = $game->memo; // 近期game 的月日
}

$game_memo = null;

//日期格式 20181001
$this_year = isset($_REQUEST['y'])?$_REQUEST['y']: date('Y');
$month_date = "0101";

if (!empty($_REQUEST['md'])) {
    // 如果指定了牌局ID (md)，则清空其他时间筛选条件
    $game_memo = $_REQUEST['md'];
    $start_time = null;
    $end_time = null;
    $this_year = substr($game_memo, 0, 4); // 从牌局ID中提取年份
} else {
    // 否则，使用默认的或指定的日期范围
    $game_memo = null;
    if (isset($_REQUEST['start_time'])){
        if ($_REQUEST['start_time'] == 'all') {
            $start_time = "20180101";
        }else{
            $start_time = $_REQUEST['start_time'];
        }
    }else{
        $start_time = $this_year.$month_date;
    }
    $end_time = isset($_REQUEST['end_time'])?$_REQUEST['end_time']:$this_year."1231";
}


// $end_time = null;
$time_zone_str = "&start_time=".$start_time."&end_time=".$end_time;
$time_zone_start = isset($_REQUEST['start_time'])?$_REQUEST['start_time']:$this_year.$month_date;
$time_zone_end = isset($_REQUEST['end_time'])?$_REQUEST['end_time']:"Now";
if (isset($_REQUEST['game_type'])&&($_REQUEST['game_type']=="all"||empty($_REQUEST['game_type']))) {
	unset($_REQUEST['game_type']);
}
$_REQUEST['stat_type'] = isset($_REQUEST['stat_type'])?$_REQUEST['stat_type']:'cahs';
$stat_type = $_REQUEST['stat_type'];
$time_zone = $time_zone_start."~".$time_zone_end;
if(isset($_REQUEST['md']) && $_REQUEST['md']!=""){
	$time_zone = $_REQUEST['md'];
}

$game_type = (isset($_REQUEST['game_type']))?$_REQUEST['game_type']:null;
$game_type_info = game_type_array($game_type);

// echo "<pre>";
// var_dump($game_memo);
// exit;
$top_10 = get_top_10(array('status'=>2,'start_time'=>$start_time,'memo'=>$game_memo,'end_time'=>$end_time,'game_type'=>$game_type,'order_type'=>'total_fact'));

$game = get_game_by_args(array('status'=>2,'start_time'=>$start_time,'memo'=>$game_memo,'end_time'=>$end_time,'game_type'=>$game_type));
if (empty($game)) {
	$if_data 	= 0;
}
switch ($_REQUEST['type']) {
	case 'total':
	// 按应收计算
	$total_income_real	=	get_player_total_income_stat($game_memo,$start_time,$end_time,$game_type,"real_money");
	$data_total_real =	isset($total_income_real['total'])?$total_income_real['total']:"";	
	if (empty($total_income_real)) {
		
	}
	//按实际收支计算
	$total_income	=	get_player_total_income_stat($game_memo,$start_time,$end_time,$game_type,$stat_type);
	// echo "<pre>";
	// var_dump($total_income);
	// exit;

	// 每次累计收入 （按 实际收入计算）
	$total_times_add = isset($total_income['times_income_add'])?$total_income['times_income_add']:null;
	if (!empty($total_times_add)) {
		$i = 0;
		foreach ($total_times_add as $key => $value) {
			if(!empty(get_player($key)[0]->nickname)){
				$total_times_add_user[$i]['name'] = get_player($key)[0]->nickname;	
				if (isset($value)) {
					foreach ($value as $k => $val) {
						if ($val != -1) {
							$total_times_add_user[$i]['data'][] = $val;
							$total_times_add_user[$i]['date'][] = $k;
						}
						
					}
				}

				$i++;
			}
			
			
		}
	}



	// echo "<pre>";
	// // var_dump($total_income['times_income_add']);
	// var_dump($total_income);
	// // var_dump($total_times_add_user[0]['date']);
	// exit;

	if (!empty($total_income)) {
		// var_dump($total_income);
		$data_total =	$total_income['total'];
		$data 		= 	$total_income['income'];
		$date_avg	=	$total_income['avg'];
		$game_count  = 	count($total_income['date']);//一共发生了的场次（不代表每个人参加的数量）
	}
	// echo "<pre>";
	// // var_dump($total_income['times_income_add']);
	// var_dump($data_total);
	// // var_dump($total_times_add_user[0]['date']);
	// exit;
	$_REQUEST['showuser'] = isset($_REQUEST['showuser'])?$_REQUEST['showuser']:'y';
	$player  	= 	get_player();
	// 查看个人每月数据
	if(isset($uid)){
		$default_arr = array(
			"Jan"=>0,
			"Feb"=>0,
			"Mar"=>0,
			"Apr"=>0,
			"May"=>0,
			"Jun"=>0,
			"Jul"=>0,
			"Aug"=>0,
			"Sep"=>0,
			"Oct"=>0,
			"Nov"=>0,
			"Dec"=>0,
		);
		// $user_month_total = array_values($total_income['monthly_income'][$uid][$this_year]['total_date']);
		$user_month_total 	= isset($total_income['monthly_income'][$uid][$this_year]['total_date'])?$total_income['monthly_income'][$uid][$this_year]['total_date']:$default_arr;
		$user_month_times 	= isset($total_income['monthly_income'][$uid][$this_year]['total_times'])?$total_income['monthly_income'][$uid][$this_year]['total_times']:$default_arr;
		$user_month_avg 	= isset($total_income['monthly_income'][$uid][$this_year]['avg_date'])?$total_income['monthly_income'][$uid][$this_year]['avg_date']:$default_arr;
		// echo "<pre>";
		// var_dump ($default_arr);
		// var_dump ($user_month_total);
		
		$user_month_total 	= array_merge($default_arr,$user_month_total);
		$user_month_avg 	= array_merge($default_arr,$user_month_avg);
		$user_month_times 	= array_merge($default_arr,$user_month_times);
		$user_month_total 	= array_values($user_month_total);
		$user_month_avg 	= array_values($user_month_avg);
		$user_month_times 	= array_values($user_month_times);
		// $user_month_total = $default_arr+$user_month_total;
		// echo "<pre>";
		// var_dump ($user_month_avg);
		// exit;
		// var_dump ($user_month_total);
		// exit;
	}

	
	// 胜率排行
	$winer_ra 	= 	count_winer_rate($game_memo,$start_time,$end_time,$game_type);
	$monthly_win = isset($winer_ra['monthly_win'])?$winer_ra['monthly_win']:null;
	$user_times_win_rate = isset($winer_ra['user_times_win_rate'])?$winer_ra['user_times_win_rate']:null;
	if (!empty($user_times_win_rate)) {
		$i = 0;
		foreach ($user_times_win_rate as $key => $value) {
			if(!empty(get_player($key)[0]->nickname)){
				$user_times_win_rate_user[$i]['name'] = get_player($key)[0]->nickname;	
					if (isset($value)) {
						foreach ($value as $k => $val) {
							$user_times_win_rate_user[$i]['data'][] = round($val*100,2);
							$user_times_win_rate_user[$i]['date'][] = $k;
						}
					}

					$i++;
			}
			
			
		}
	}


	// echo "<pre>";
	// var_dump ($user_times_win_rate_user);
	// // var_dump ($winer_ra['user_times_win_rate']);
	// exit;
	unset($winer_ra['monthly_win']);
	unset($winer_ra['user_times_win_rate']);
	if (!empty($winer_ra)) {
		foreach ($winer_ra as $key => $value) {
			// 便于用 % 显示
			if(!empty($value[0])){
				$value[1] = $value[1]*100;
				$winer_rate[] = array(
					'gamecount'	=> $value[2],
					'name' 		=> $value[0],
					'y' 		=> $value[1],
					'wincont'	=> $value[3],
					'uid'		=> $value[4],
				);
			}
			
			
		}
	}
	if (isset($uid)) {
		
		$user_month_win 	= isset($monthly_win[$uid][$this_year]['win_count'])?$monthly_win[$uid][$this_year]['win_count']:$default_arr;
		$user_month_win 	= array_merge($default_arr,$user_month_win);
		$user_month_win 	= array_values($user_month_win);

		// echo "<pre>111";
		// var_dump ($user_month_win);
		// var_dump ($user_month_times);
		// // var_dump ($total_income['monthly_income']);
		// exit;
		foreach ($user_month_win as $key => $value) {
			if ($value!=0) {
				$user_month_win_rate[] = round($value/$user_month_times[$key]*100,2);
			}else{
				$user_month_win_rate[] = 0;
			}

		}
		// echo "<pre>";
		// var_dump ($user_month_win_rate);
		// exit;
	}


	
	break;

	// 分段统计
	case 'timezone':
		# code...
	break;

	case 'lucky':
		$lucky_info = get_lucky_info($start_time,$end_time);
	break;

	case 'killrank':
	$kill_rank 	=	get_best_killer($game_memo,$start_time,$end_time,$game_type);
	$kill_info 	=	get_kill_info($game_memo,$start_time,$end_time,$game_type);
    $kill_info_killed_by = isset($kill_info['killed_by']) ? $kill_info['killed_by'] : array();
    $kill_info = isset($kill_info['killed']) ? $kill_info['killed'] : array();
	$killed_by_tem = array();
    $kill_graph = array();
	if (is_array($kill_rank) and is_array($kill_info_killed_by)) {
		$i = 0;
		foreach ($kill_rank as $key => $value) {
			$kill_rank_player[$key]=$value[0];
			$kill_rank_player_json[]=$value[0];
			foreach ($kill_info_killed_by as $k => $val) {
				$killed_by_tem[$k]['name']=get_player($k)[0]->nickname;
				$killed_by_tem[$k]['data'][]=isset($val[$key])?$val[$key]:'';
				
			}
		}
		foreach ($kill_info_killed_by as $k => $val) {
				foreach ($val as $m => $v) {
					// 按 from to value (谁输给谁多少)的顺序组建数组，便于转json数组
					$kill_graph[] = array(get_player($k)[0]->nickname,get_player($m)[0]->nickname,$v);
				}
				
				
		}
		// echo "<pre>";
		$kill_graph_json = json_encode($kill_graph);
		// var_dump($kill_graph);
		// var_dump($kill_graph_json);
		// // var_dump($kill_info_killed_by);
		// exit;
		if (is_array($killed_by_tem)) {
			foreach ($killed_by_tem as $key => $value) {
				$killed_by[]=$value;
			}
		}

	}
	
	// 计算每个人总结推all in 被别人接的数量 （kill 以及 kill_by 都是同一个uid的）
	$players = get_player();
	if(!empty($players)){
		foreach ($players as $key => $value) {
			$killed[$value->id]['total'] = 0;
			$killed[$value->id]['win'] = 0;
			$killed[$value->id]['lose'] = 0;
			$killed[$value->id]['win_rate'] = 0;
			if (isset($kill_info[$value->id])) {
				$killed[$value->id]['total'] = array_sum($kill_info[$value->id]);
				$killed[$value->id]['win'] = array_sum($kill_info[$value->id]);
				foreach ($kill_info[$value->id] as $k => $val) {
					$killed[$value->id]['vs'][$k]['win']=$val;
					$killed[$value->id]['vs'][$k]['lose'] = 0 ;
					if (isset($kill_info[$k][$value->id])) {
						$killed[$value->id]['vs'][$k]['lose'] = $kill_info[$k][$value->id];
					}
					$killed[$value->id]['vs'][$k]['total'] = $killed[$value->id]['vs'][$k]['win'] + $killed[$value->id]['vs'][$k]['lose'];
					$killed[$value->id]['vs'][$k]['win_rate'] =100* round($killed[$value->id]['vs'][$k]['win'] / $killed[$value->id]['vs'][$k]['total'],4)."%";
					// asort($killed[$value->id][$k]['win_rate']);
				}
			}
			if (isset($kill_info_killed_by[$value->id])) {
				$killed[$value->id]['total'] = $killed[$value->id]['total'] + array_sum($kill_info_killed_by[$value->id]);
				$killed[$value->id]['lose'] = array_sum($kill_info_killed_by[$value->id]);
				foreach ($kill_info_killed_by[$value->id] as $k => $val) {
					$killed[$value->id]['vs'][$k]['lose']=$val;
					$killed[$value->id]['vs'][$k]['win'] = 0 ;
					if (isset($kill_info_killed_by[$k][$value->id])) {
						$killed[$value->id]['vs'][$k]['win'] = $kill_info_killed_by[$k][$value->id];
					}
					$killed[$value->id]['vs'][$k]['total'] = $killed[$value->id]['vs'][$k]['win'] + $killed[$value->id]['vs'][$k]['lose'];
					$killed[$value->id]['vs'][$k]['win_rate'] =100* round($killed[$value->id]['vs'][$k]['win'] / $killed[$value->id]['vs'][$k]['total'],4)."%";
					// asort($killed[$value->id][$k]['win_rate']);
				}
			}
			if($killed[$value->id]['win'] == 0){
				$killed[$value->id]['win_rate'] = "0%";
			}else{
				$killed[$value->id]['win_rate'] = 100*round($killed[$value->id]['win']/$killed[$value->id]['total'],2)."%";
			}
			// $killed[$value->id] = count($kill_info[$value->id]) + count($kill_info_killed_by[$value->id]);
		}
	}

	// $kill_rank_player_json = json_encode($kill_rank_player_json);
	// $killed_by = json_encode($killed_by);
	// echo "<pre>";
	// // var_dump($kill_rank_player);
	// // var_dump($killed);
	// $kill_graph_json = json_encode($kill_graph);
	// var_dump($kill_graph);
	// var_dump($kill_graph_json);
	// var_dump($kill_info_killed_by);
	// exit;
	break;

	case 'where':
		/**
		 * 计算每个人在每个方位的具体战果
		 */
		$person_positon = 	count_person_position_win($game_memo,$start_time,$end_time,$game_type);
		// echo "<pre>";
		// var_dump ($person_positon);
		// exit;
		if (isset($person_positon['win']) && !empty($person_positon['win'])) {
			foreach ($person_positon['win'] as $key => $value) {
				$each_position_detail_win[$key]['type'] = 'column';
				$each_position_detail_win[$key]['name'] = get_player($key)[0]->nickname;
				$each_position_detail_win[$key]['data'] = array_merge($value);
				foreach ($value as $k => $val) {
					$person_positon_win[$key][]=$val;
				}
			}

			foreach ($person_positon['win_rate'] as $key => $value) {
				foreach ($value as $k => $val) {
					// $person_positon_win_rate[$key][]=$val;
					$person_positon_win_rate[$key][]= array(
						'y' 		=> $val,
						'wincount' 	=> $person_positon['win_info']['win'][$key][$k],
						'totalcount'=> $person_positon['win_info']['total'][$key][$k],
					);
				}
			}
			foreach ($person_positon['win_info']['win'] as $key => $value) {
				foreach ($value as $k => $val) {
					$person_win[$key][] = $val;
				}
			}
			foreach ($person_positon['each_position']['win'] as $key => $value) {
				foreach ($person_positon['uid'] as $k => $val) {
					$value[$val] = isset($value[$val])?$value[$val]:0;
				}
				ksort($value);
				$each_position_win[$key]=array_merge($value);
			}
			// echo "<pre>";
			// var_dump ($each_position_win);
			// exit;
			/**
			 * 
			 * 构造二维数组即可在一列显示多个数据，详情见 #container-positon-detail 
			 * $test_array = array(
				0=> array(
					'type'=>"column",
					'name'=>"aaa",
					'data'=> array(200,300,500,100)
				),
				1=> array(
					'type'=>"column",
					'name'=>"bbb",
					'data'=> array(700,200,500,100)
				),
				2=> array(
					'type'=>"column",
					'name'=>"ccc",
					'data'=> array(300,100,500,100)
				)
				);
				$test_encode = json_encode($test_array);	
			 */
				$each_position_detail_win = array_merge($each_position_detail_win);
				foreach ($person_positon['each_position']['win_rate'] as $key => $value) {
					foreach ($person_positon['uid'] as $k => $val) {
						$value[$val] = isset($value[$val])?$value[$val]:0;
					}
					ksort($value);
					foreach ($value as $k => $val) {
						$each_position_win_rate[$key][] = $val;
					}
				}
			}

			$uid 	=	isset($_REQUEST['uid'])?$_REQUEST['uid']:1;
			$person_name = get_player($uid)[0]->nickname;
			// $person_win  = $person_positon['win_info']['win'][$uid];
			if (isset($person_win)) {
				$person_win_stat[$uid] = array(
					'wincont' 	=> $person_win[$uid],
				);
			}
			
			$_REQUEST['showuser'] = isset($_REQUEST['showuser'])?$_REQUEST['showuser']:'y';
			break;

			case 'position':
			//气球图数据
			$position_stat 	=	get_position_stat($game_memo,$start_time,$end_time,$game_type);
			// 折现柱状符合图
			// 计算每个位置东南西北（1~4号位置的输赢情况）
			$position_count_res 	=	 cont_position_winer($game_memo,$start_time,$end_time,$game_type);

			if (isset($position_count_res) && !empty($position_count_res)) {
				foreach ($position_count_res as $key => $value) {
			// $position_stat_map[$key] = $value;
					foreach ($value as $k => $val) {

						$position_stat_map[$key][]=abs($val);
					}
				}
			}
			$position_count_res_new 	=	 cont_position_winer_new($game_memo,$start_time,$end_time,$game_type);
			if (isset($position_count_res_new) && !empty($position_count_res_new)) {
				foreach ($position_count_res_new as $key => $value) {
			// $position_stat_map[$key] = $value;
					foreach ($value as $k => $val) {

						$position_stat_map_new[$key][]=intval($val);
					}
				}
			}
			break;

			default:
			$total_income	=	get_player_total_income_stat($game_memo,$start_time,$end_time,$game_type);
			$data 			= 	$total_income['income'];
			$statTime 		=	$total_income['date'];
			$rows = count($statTime);
		}
		$rows = isset($rows)?$rows:5;
		$detail_heigh = 180+$rows*30;
		$url = "kog_detail.php?gid=";

		?>


		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Big Data</title>
			<link rel="stylesheet" type="text/css" href="assets/css/style.css">
			<link rel="stylesheet" type="text/css" href="style.css">
			<script type="text/javascript" src="https://code.hcharts.cn/10.3.2/highcharts.js"></script>
			<script type="text/javascript" src="https://code.hcharts.cn/10.3.2/modules/exporting.js"></script>
			<script type="text/javascript" src="https://code.hcharts.cn/10.3.2/modules/sankey.js"></script>
			<script type="text/javascript" src="https://code.hcharts.cn/10.3.2/modules/oldie.js"></script>
			<script type="text/javascript" src="https://code.hcharts.cn/10.3.2/highcharts-more.js"></script>
			<script type="text/javascript" src="https://code.hcharts.cn/highcharts/modules/drilldown.js"></script>
			<script type="text/javascript" src="assets/js/flot/highcharts-zh_CN.js"></script>

			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.js"></script>

			<script type='text/javascript' src='https://s3.amazonaws.com/dynatable-docs-assets/js/jquery.dynatable.js'></script>
			<script src="https://code.hcharts.cn/highcharts/modules/dependency-wheel.js"></script>
			
			<script type="text/javascript">
				console.log($().jquery); // => '3.0.0'
				var $jq = jQuery.noConflict(true);
			    console.log($().jquery); // => '1.5.0'
			</script>
			<script type='text/javascript'>
				$jq(function($) {
					$('#example').dynatable();
				} );
			</script>


			<style type="text/css">
			a{
				color: #000;
			}
			.tdtxt{
				color: #777;
				font-weight: 300;
			}
			.tdtxt-weight{
				color: #777;
				font-weight: 500;
			}

			.cell1-3{
				float: left;
				width: 33%;
				text-align: center;
			}
			.row-full-table{
				float: left;
				width: 100%;
				text-align: center;
				padding-bottom: 5px;
			}
			.row-full-win{
				float: left;
				width: 100%;
				text-align: center;
				padding-bottom: 5px;
				font-size: 1.5em;

			}
			.row-full-div{
				padding: 5px;
				float: left;
				text-align: center;
				width: 100%;
			}
			.row-full-bottom{
				float: left;
				width: 100%;
				text-align: center;
				padding-bottom: 5px;
				border-bottom-style: solid;
				border-bottom-width: 1px;
				font-size: 1.5em;

			}

			.row-full-boder-bottom{
				float: left;
				width: 100%;
				text-align: center;
				padding-bottom: 5px;
				border-top-style: solid;
				border-color: #8a8a8a;
				border-width: 1px
				padding-right:5px;
				padding-left: 5px;
			}
			.cell1-5{
				float: left;
				width: 20%;
				line-height: 82px;
			}
			.cell3-5{
				float: left;
				width: 60%;
			}
			.content{
				padding-top: 20px;
			}
			.cell1-5-heigh{
				float: left;
				width: 20%;
				line-height:30px;
			}
			.cell1-5-heigh-content{
				float: left;
				width: 20%;
				line-height:30px;
				color: #999;
			}
			.cell2-5-heigh-content{
				float: left;
				width: 40%;
				line-height:30px;
				color: #999;
			}
			.div-1-2{
				float: left;
				width: 50%;
			}
			.div-1-4-title{
				float: left;
				width: 25%;
				text-align: right;
				color: #ccc;
			}
			.div-1-4-content{
				float: left;
				width: 25%;
				text-align: left;
				padding-left: 10px;
				color: #ccc;
			}
			.div-3-4-content{
				float: left;
				width: 75%;
				text-align: left;
				padding-left: 10px;
				color: #ccc;
			}
			.spantxt{
				color: #777;
				align-content: center;
				float: left;
				width: 50%;
				text-align: left;
				padding-left: 5px;
			}
			.spantxt-right{
				width: 45%;
				float: right;
				text-align: right;
			}
			.tap{
				float: left;
				width: 10px; 
				height: 24px; 
				background-color: #03c4eb;
				padding: 5px;
			}
			.select-game-type{
				max-width: 100%;
				text-align: right;
				overflow: auto;
				vertical-align: top;
				outline: none;
				border: 1px solid #e9e9e9;
				height: 24px;
				padding: 0px;
				background-color: #f5f5f5;
			}
		</style>

	</head>
	<?php
	$total_cost = 0;
	if (isset($winer_rate) && is_array($winer_rate)) {
		foreach ($winer_rate as $key => $value) {
			$order_array[$value['uid']]['uid'] = $value['uid'];
			$order_array[$value['uid']]['name'] = $value['name'];
			$order_array[$value['uid']]['gamecount'] = floatval($value['gamecount']).'/'.$game_count.'/'.$total_income['play_memo'];
			$order_array[$value['uid']]['wincont'] = $value['wincont']."(".$value['y']."%)";
			$order_array[$value['uid']]['total_income'] = $total_income['total_uid'][$value['uid']][1];
			// 支付的成本
			$order_array[$value['uid']]['game_cost_player'] = $total_income['total_uid'][$value['uid']][2];
			$order_array[$value['uid']]['total_income_pre'] = round($total_income['total_uid'][$value['uid']][1]/$value['gamecount'],2);
			$total_income_order[$value['uid']] = $order_array[$value['uid']]['total_income'];
			$total_cost += $order_array[$value['uid']]['game_cost_player'];
		}
		arsort($total_income_order);
		foreach ($total_income_order as $key => $value) {
			if ($value >= 0) {
				$user_should_win[$key]['should_get'] = $value;
				$user_should_win[$key]['get'] = $value;
			}else{
				$user_should_lose[$key] = $value;
			}
		}
		arsort($user_should_win);
		ksort($user_should_lose);

		foreach ($user_should_lose as $key => $value) {
			$lose_id = $key;
			$lose = $value;
			foreach ($user_should_win as $k => $val) {
				$lose_should = $val['should_get']+$lose;
				if ($lose_should >= 0) {
					$should_get[$lose_id][$k]['get']=abs($lose);
					$user_should_win[$k]['get']=abs($lose);
					$should_get[$lose_id][$k]['should_get']=$lose_should;
					$user_should_win[$k]['should_get'] = $lose_should;
					$lose=0;
					break;
				}else{
					$should_get[$lose_id][$k]['get']=$val['should_get'];
					$user_should_win[$k]['get']=$val['should_get'];
					$should_get[$lose_id][$k]['should_get']=0;
					$user_should_win[$k]['should_get']=0;
					$lose=$lose_should;
				}
			}
		}
		foreach ($should_get as $key => $value) {
			foreach ($value as $k => $val) {
				$pay_info_graph[]=array(get_player($key)[0]->nickname,get_player($k)[0]->nickname,$val['get']);
			}
		}
		$pay_info_graph_json = json_encode($pay_info_graph);
		}
	?>



	<?php
	$uid 								= isset($uid)?$uid:null;
	$kill_graph 								= isset($kill_graph)?$kill_graph:null;
	$pay_info_graph 								= isset($pay_info_graph)?$pay_info_graph:null;
	$killed_by 								= isset($killed_by)?$killed_by:null;
	$kill_rank_player_json 								= isset($kill_rank_player_json)?$kill_rank_player_json:null;
	$statTime 							= isset($statTime)?$statTime:null;
	$data 								= isset($data)?$data:null;
	$data_total 						= isset($data_total)?$data_total:null;
	$data_total_real 					= isset($data_total_real)?$data_total_real:null;
	$date_avg 							= isset($date_avg)?$date_avg:null;
	$kill_rank 							= isset($kill_rank)?$kill_rank:null;
	$position_stat 						= isset($position_stat)?$position_stat:null;
	$winer_rate 						= isset($winer_rate)?$winer_rate:null;
	$position_stat_map_new['win'] 		= isset($position_stat_map_new['win'])?$position_stat_map_new['win']:null;
	$position_stat_map_new['win_rate'] 	= isset($position_stat_map_new['win_rate'])?$position_stat_map_new['win_rate']:null;
	$position_stat_map['win'] 			= isset($position_stat_map['win'])?$position_stat_map['win']:null;
	$position_stat_map['lose'] 			= isset($position_stat_map['lose'])?$position_stat_map['lose']:null;
	$position_stat_map['win_rate'] 		= isset($position_stat_map['win_rate'])?$position_stat_map['win_rate']:null;
	$position_stat_map['lose_rate'] 	= isset($position_stat_map['lose_rate'])?$position_stat_map['lose_rate']:null;
	$person_positon_win_rate[$uid] 		= isset($person_positon_win_rate[$uid])?$person_positon_win_rate[$uid]:null;
	$person_positon_win[$uid] 			= isset($person_positon_win[$uid])?$person_positon_win[$uid]:null;
	$person_name 						= isset($person_name)?$person_name:null;
	$person_win[$uid] 					= isset($person_win[$uid])?$person_win[$uid]:null;
	$each_position_win 					= isset($each_position_win)?$each_position_win:null;
	$each_position_detail_win 			= isset($each_position_detail_win)?$each_position_detail_win:null;
	$user_month_total 					= isset($user_month_total)?$user_month_total:null;
	$user_month_avg 					= isset($user_month_avg)?$user_month_avg:null;
	$user_month_times 					= isset($user_month_times)?$user_month_times:null;
	$user_month_win_rate 					= isset($user_month_win_rate)?$user_month_win_rate:null;
	$total_times_add_user 					= isset($total_times_add_user)?$total_times_add_user:null;
	$user_times_win_rate_user 					= isset($user_times_win_rate_user)?$user_times_win_rate_user:null;
	$total_times_add_user[0]['date']    = isset($total_times_add_user[0]['date'])?$total_times_add_user[0]['date']:null;
	$user_times_win_rate_user[0]['date'] = isset($user_times_win_rate_user[0]['date'])?$user_times_win_rate_user[0]['date']:null;
	$user_times_win_rate_user[0]['date'] = isset($user_times_win_rate_user[0]['date'])?:null;
	?>
	<SCRIPT>

		var statTimej 		= 	<?php echo json_encode($statTime)?>;
		var killedBy 		= 	<?php echo json_encode($killed_by)?>;
		var killRankPlayerJson 		= 	<?php echo json_encode($kill_rank_player_json)?>;
		var statTestj	 	= 	<?php echo json_encode($data)?>;
		var totalTimesAddUser	 	= 	<?php echo json_encode($total_times_add_user)?>;
		var userTimesWinRateUser	 	= 	<?php echo json_encode($user_times_win_rate_user)?>;
		var totalTimesAddUserDate	 	= 	<?php echo json_encode($total_times_add_user[0]['date'])?>;
		var userTimesWinRateUserDate	 	= 	<?php echo json_encode($user_times_win_rate_user[0]['date'])?>;

		var statPlayer 		=	<?php echo json_encode($data_total)?>;
		var dataTotalReal 		=	<?php echo json_encode($data_total_real)?>;
		var date_avg 		=	<?php echo json_encode($date_avg)?>;
		var userMonthTotal 		=	<?php echo json_encode($user_month_total)?>;
		var userMonthAvg 		=	<?php echo json_encode($user_month_avg)?>;
		var userMonthMimes 		=	<?php echo json_encode($user_month_times)?>;
		var userMonthWinRate 		=	<?php echo json_encode($user_month_win_rate)?>;
		var statTimeZone	=	<?php echo json_encode($time_zone);?>;

		var killRank 		=	<?php echo json_encode($kill_rank)?>;

		var positionStat 	=	<?php echo json_encode($position_stat)?>;
		var winerRate 		=	<?php echo json_encode($winer_rate)?>;
		var positionWinNew 	=	<?php echo json_encode($position_stat_map_new['win'])?>;
		var positionWinRateNew 	=	<?php echo json_encode($position_stat_map_new['win_rate'])?>;
		var positionWin 	=	<?php echo json_encode($position_stat_map['win'])?>;
		var positionLose 	=	<?php echo json_encode($position_stat_map['lose'])?>;
		var positionWinRate =	<?php echo json_encode($position_stat_map['win_rate'])?>;
		var positionLoseRate=	<?php echo json_encode($position_stat_map['lose_rate'])?>;
		var personPositonWinRate=	<?php echo json_encode($person_positon_win_rate[$uid])?>;
		var personPositonWin 	=	<?php echo json_encode($person_positon_win[$uid])?>;
		var personName 		= 	<?php echo json_encode($person_name)?>;
		var personWin 		= 	<?php echo json_encode($person_win[$uid])?>;
		var eachPositionDetailWin 		= 	<?php echo json_encode($each_position_detail_win)?>;
		var gameTypeInfo 		= 	<?php echo json_encode($game_type_info)?>;
		var killGraph 		= 	<?php echo json_encode($kill_graph)?>;
		var payInfoGraph 		= 	<?php echo json_encode($pay_info_graph)?>;
		
		$(function (){
			if ($('#container-fuqian').length) {
				$('#container-fuqian').highcharts({
					title: {
					text: 'Pay to Info'
					},
					credits: {
						enabled: false
					},
					series: [{
						keys: ['from', 'to', 'weight'],
						data: payInfoGraph,
						type: 'dependencywheel',
						name: '支付详情',
						dataLabels: {
							color: '#333',
							textPath: {
								enabled: true,
								attributes: {
									dy: 5
								}
							},
							distance: 10
						},
						size: '95%'
					}]
				});
			}
		});
		$(function (){
			if ($('#container-hexuan').length) {
				$('#container-hexuan').highcharts({
					title: {
					text: 'All in 胜负关系'
					},
					credits: {
						enabled: false
					},
					series: [{
						keys: ['from', 'to', 'weight'],
						data: killGraph,
						type: 'dependencywheel',
						name: 'All in 输给了谁 ',
						dataLabels: {
							color: '#333',
							textPath: {
								enabled: true,
								attributes: {
									dy: 5
								}
							},
							distance: 10
						},
						size: '95%'
					}]
				});
			}
		});

		$(function () {
			if ($('#container-timesadd').length) {
				$('#container-timesadd').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						type: 'spline'
					},
					title: {
						text: ''
					},
					subtitle: {
						text: ''
					},
					xAxis: {
						categories: totalTimesAddUserDate,
						labels: {
							overflow: 'justify'
						}
					},
					yAxis: {
						title: {
							text: false
						},
					minorGridLineWidth: 0,
					gridLineWidth: 0,
					alternateGridColor: null,
					},
					tooltip: {
						valueSuffix: '元'
					},
					credits: {
						enabled: false
					},
					plotOptions: {	
						spline: {
							lineWidth: 4,
							states: {
								hover: {
									lineWidth: 5
								}
							},
							marker: {
								enabled: false
							},
						}
					},
					series: totalTimesAddUser,

					navigation: {
						menuItemStyle: {
							fontSize: '10px'
						}
					}
				});
			}
		});

		$(function () {
			if ($('#container-timeswinrateadd').length) {
				$('#container-timeswinrateadd').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						type: 'spline'
					},
					title: {
						text: ''
					},
					subtitle: {
						text: ''
					},
					xAxis: {
						categories: userTimesWinRateUserDate,
						labels: {
							overflow: 'justify'
						}
					},
					yAxis: {
						title: {
							text: false
						},
					minorGridLineWidth: 0,
					gridLineWidth: 0,
					alternateGridColor: null,
					},
					tooltip: {
						valueSuffix: '%'
					},
					credits: {
						enabled: false
					},
					plotOptions: {	
						spline: {
							lineWidth: 4,
							states: {
								hover: {
									lineWidth: 5
								}
							},
							marker: {
								enabled: false
							},
						}
					},
					series: userTimesWinRateUser,
					
					navigation: {
						menuItemStyle: {
							fontSize: '10px'
						}
					}
				});
			}
		});

		$(function () {
			if ($('#container').length) {
				$('#container').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac','#ffcc00','#ff9900'],
					chart: {
					type: 'bar'
				},
				title: {
					text: ''

				},
				subtitle: {
					text: ''
				},
				xAxis: {
					categories: statTimej,
					labels: {
					},
					stackLabels: {
						enabled: true,
						style: {
							fontWeight: 'bold',
							color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
						}
					}
				},
				yAxis: {
					title: {
						enabled: false,
						text: false
					}
				},
				legend: {
					align: 'right',
					x: 0,
					verticalAlign: 'bottom',
					y: 20,
					floating: true,
					backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
					borderColor: '#CCC',
					borderWidth: 1,
					shadow: false
				},
				tooltip: {
					formatter: function () {
						return '<b>' + this.x + '</b><br/>' +
						this.series.name + ': ' + this.y + '<br/>' +
						'总量: ' + this.point.stackTotal;
					}
				},
				plotOptions: {
					bar: {
						stacking: 'normal',
						dataLabels: {
							enabled: true,
							color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
							style: {
								textShadow: '0 0 3px black'
							}
						}
					}
				},
				pointHeigh: 30,
				credits: {
					enabled: false
				},
				series: statTestj
				});
			}
		});


		$(function () {
			if ($('#container-total').length) {
				$('#container-total').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						type: 'column'
					},
					title: {
						text: gameTypeInfo+'总收支'
					},
					subtitle: {
						text: statTimeZone
					},
					xAxis: {
						type: 'category',
						labels: {
						}
					},

					yAxis: {
						title: {
							text: '总金额'
						}
					},
					legend: {
						enabled: true
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.y:.1f}元</b><br>',
						shared: true
					},
					credits: {
						enabled: false
					},
					series: [{
						name: '实收',
						data: statPlayer,
						dataLabels: {
							enabled: true,
							color: '#FFFFFF',
							align: 'center',
							color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
							style: {
								textShadow: '0 0 1px black'
							}
						}
					},{
						name: '应收',
						data: dataTotalReal,
						dataLabels: {
							enabled: true,
							color: '#FFFFFF',
							align: 'center',
							color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
							style: {
								textShadow: '0 0 1px black'
							}
						}
					}]
				});
			}
		});

		$(function () {
			if ($('#container-total-avg').length) {
				$('#container-total-avg').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						type: 'column'
					},
					title: {
						text: gameTypeInfo+'场均收支'
					},
					subtitle: {
						text: statTimeZone
					},
					xAxis: {
						type: 'category',
						labels: {
						}
					},

					yAxis: {
						title: {
							text: '场均'
						}
					},
					legend: {
						enabled: false
					},
					tooltip: {
						pointFormat: '场均收支: <b>{point.y:.1f}元</b>'
					},
					credits: {
						enabled: false
					},
					series: [{
						name: '场均收支',
						data: date_avg,
						color: '#f7a35c',
						dataLabels: {
							enabled: true,
							color: '#FFFFFF',
							align: 'center',
							color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
							style: {
								textShadow: '0 0 1px black'
							}
						}
					}]
				});
			}
		});


		$(function () {
			if ($('#container-monthly').length) {
				$('#container-monthly').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						type: 'column'
					},
					title: {
						text: gameTypeInfo+'每月营收: '+personName
					},
					subtitle: {
						text: statTimeZone
					},
					credits: {
						enabled: false
					},
					xAxis: {
						categories: [
						'1月',
						'2月',
						'3月',
						'4月',
						'5月',
						'6月',
						'7月',
						'8月',
						'9月',
						'10月',
						'11月',
						'12月'
						],
						crosshair: true
					},
					yAxis: [{ // Primary yAxis
						
						title: {
							text: false,
						},
						opposite: true
					},{
						//min: 0,
						title: {
							text: false
						}
					}],
					tooltip: {
						headerFormat: '<span style="font-size:10px;text-align: left">{point.key}</span><table>',
						pointFormat: '<tr><td style="text-align: left;color:{series.color};padding:0">{series.name}：</td>' +
						'<td style="padding:0"><b>{point.y:.0f} </b></td></tr>',
						footerFormat: '</table>',
						shared: true,
						useHTML: true
					},
					plotOptions: {
						column: {
							colorByPoint:true
						}
					},
					series: [{
						name: "营收(元)",
						type: 'column',
						data: userMonthTotal,
					},{
						name: "场均(元/场)",
						type: 'spline',
						data: userMonthAvg,
					},{
						name: "胜率(%)",
						type: 'spline',
						data: userMonthWinRate,
					}]
				});
			}
		});




		$(function () {
			if ($('#container-winer').length) {
				$('#container-winer').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						type: 'column'
					},
					title: {
						text: gameTypeInfo+'胜率'
					},
					subtitle: {
						text: statTimeZone
					},
					xAxis: {
						type: 'category', // 只有type 为 category 时，才会从series 对应的date数组中取 key 是 name 的值作为坐标名称
						labels: {
						}
					},
					yAxis: {
						title: {
							text: '胜率'
						}
					},
					legend: {
						enabled: false
					},
					tooltip: {
						pointFormat: '胜率: <b>{point.y:.2f}%</b>'
					},

					tooltip: {
						formatter: function () {
							console.log(this.point);
							return '<b>'+this.point['name'] + '</b><br/>' +
							this.series.name + ': ' + this.y+'%<br/>' + 
							this.point.gamecount + '场胜' + this.point.wincont +'场'
							;
						}
					},
					credits: {
						enabled: false
					},
					series: [{
						name: '胜率',
						data: winerRate,
						color: '#8085e8',
						rateLabels: '4',
						dataLabels: {
							enabled: true,
							color: '#FFFFFF',
							align: 'center',
							format: '{point.y:.2f}%', // one decimal
							color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
							style: {
								textShadow: '0 0 1px black'
							}
						}
					}]
				});
			}
		});

		$(function () {
			if ($('#container-kill').length) {
				$('#container-kill').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						type: 'column'
					},
					title: {
						text:''
					},
					subtitle: {
						text: ''
					},
					xAxis: {
						type: 'category',
						labels: {
						}
					},
					yAxis: {
						title: {
							text: '清台次数'
						}
					},
					legend: {
						enabled: false
					},
					tooltip: {
						pointFormat: '总次数: <b>{point.y:.1f}次</b>'
					},
					credits: {
						enabled: false
					},
					series: [{
						name: '总次数',
						data: killRank,
						dataLabels: {
							enabled: true,
							color: '#FFFFFF',
							align: 'center',
							color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
							style: {
								textShadow: '0 0 1px black'
							}
						}
					}]
				});
			}
		});

		$(function () {
			if ($('#container-pie').length) {
				$('#container-pie').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					title: {
						text: gameTypeInfo+'盈利比例'
					},
					tooltip: {
						headerFormat: '{series.name}<br>',
						pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b>'
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								format: '<b>{point.name}</b>: {point.percentage:.1f} %',
								style: {
									color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
								}
							}
						}
					},
					credits: {
						enabled: false
					},
					series: [{
						type: 'pie',
						name: '盈利占比',
						data: statPlayer
					}]
				});
			}
		});

		$(function () {
			if ($('#container-person-positon').length) {
				$('#container-person-positon').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						zoomType: 'xy'
					},
					title: {
						text: '-'+personName+'-在哪儿赢得多'
					},
					subtitle: {
						text: ''
					},
				    xAxis: {
					    	type: 'category',
				    },
				    xAxis: {
				    	max: 3,
				    	min: 0,
				    	categories: ['东', '南', '西' ,'北']
				    },
				    yAxis: [
				    { // Secondary yAxis
				    	labels: {
				    		format: '{value}%',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	},
				    	title: {
				    		text: '胜率',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	},
				    	opposite: true
				    }, 
				    { // Primary yAxis
				    	title: {
				    		text: '奖金',
				    		style: {
				    			color: Highcharts.getOptions().colors[0]
				    		}
				    	},
				    	labels: {
				    		format: '￥{value}',
				    		style: {
				    			color: Highcharts.getOptions().colors[0]
				    		}
				    	}
				    }],
				    tooltip: {
				    	shared: true,
				    	formatter: function () {
							return '赢取奖金: ' + this.points["0"].y+'<br/>'+
							'胜率: ' + Math.round(this.points["1"].y)+'%<br/>'+
							'胜: ' + this.points["1"].point.wincount +'场<br/>'+
							'共: ' + this.points["1"].point.totalcount +'场<br/>'+
							'场均: ' + Math.round(this.points["0"].y/this.points["1"].point.totalcount) + '元/场'
							;
						}

				    },
				    credits: {
						enabled: false //去除水印
					},
					legend: {
						layout: 'vertical',
						align: 'left',
						x: 10,
						verticalAlign: 'top',
						y: 0,
						floating: true,
						backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
					},
					plotOptions: {
						series: {
							threshold: -10
						}
					},
					series: [{
						name: '赢得奖金',
						color:'#f3d64e',
						type: 'column',
						zones: [{
							value: 0,
							color: '#ff0080'
						}],
						yAxis: 1,
						data: personPositonWin,
						tooltip: {
							valueSuffix: ' mm'
						}
					}, {
						name: '赢钱概率',
						color:'#555555',
						type: 'spline',
						data: personPositonWinRate,
						tooltip: {
							valueSuffix: '°C'
						}
					}]
				})
			}
		});


		$(function () {
			if ($('#container-positon-detail').length) {
				$('#container-positon-detail').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						zoomType: 'xy'
					},
					title: {
						text: '每人在每个方向的汇总'
					},
					subtitle: {
						text: ''
					},
				    xAxis: {
				    	type: 'category',
				    },
				    xAxis: {
				    	max: 3,
				    	min: 0,
				    	categories: ['东', '南', '西' ,'北']
				    },
				    yAxis: [
				    { // Secondary yAxis
				    	title: {
				    		text: '奖金',
				    		style: {
				    			color: Highcharts.getOptions().colors[0]
				    		}
				    	},
				    	labels: {
				    		format: '{value}',
				    		style: {
				    			color: Highcharts.getOptions().colors[0]
				    		}
				    	},
				    }],
				    tooltip: {
				    	shared: true,
				    	formatter: function () {
				    		console.log(this.points);

							return this.points["0"].series.userOptions.name+': ' + this.points["0"].y+'<br/>'+
							this.points["1"].series.userOptions.name+': ' + this.points["1"].y+'<br/>'+
							this.points["2"].series.userOptions.name+': ' + this.points["2"].y+'<br/>'+
							this.points["3"].series.userOptions.name+': ' + this.points["3"].y+'<br/>'+
							this.points["4"].series.userOptions.name+': ' + this.points["4"].y
							;
						}

				    },
				    credits: {
						enabled: false //去除水印
					},
					legend: {
						layout: 'vertical',
						align: 'left',
						x: 10,
						verticalAlign: 'top',
						y: 0,
						floating: true,
						backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
					},
					plotOptions: {
						series: {
							threshold: -10
						}
					},
					series: eachPositionDetailWin
				})
			}
		});



		$(function () {
			if ($('#container-position-rate-new').length) {
				$('#container-position-rate-new').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						zoomType: 'xy'
					},
					credits: {
						enabled: false //去除水印
					},
					title: {
						text: ''
					},
					subtitle: {
						text: ''
					},
				    xAxis: {
				    	type: 'category',
				    	labels: {
						}
					},
					xAxis: {
						max: 3,
						min: 0,
						categories: ['东', '南', '西' ,'北']
					},

				    yAxis: [{ // Primary yAxis
				    	labels: {
				    		format: '{value}%',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	},
				    	title: {
				    		text: '胜率',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	}
				    }, { // Secondary yAxis
				    	title: {
				    		text: '奖金',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	},
				    	labels: {
				    		format: '￥{value}',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	},
				    	opposite: true
				    }],
				    tooltip: {
				    	shared: true,
				    	formatter: function () {
				    		console.log(this.points["0"].y);

							return '赢取奖金: ' + this.points["0"].y+'<br/>'+
							'胜率: ' + this.points["1"].y+'%';
						}

				    },

				    legend: {
				    	layout: 'vertical',
				    	align: 'left',
				    	x: 10,
				    	verticalAlign: 'top',
				    	y: 0,
				    	floating: true,
				    	backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
				    },
				    series: [{
				    	name: '赢得奖金',
				    	type: 'column',
				    	yAxis: 1,
				    	data: positionWinNew,
				    	tooltip: {
				    		valueSuffix: ' mm'
				    	}
				    }, {
				    	name: '胜率',
				    	type: 'spline',
				    	data: positionWinRateNew,
				    	tooltip: {
				    		valueSuffix: '°C'
				    	}
				    }]
				})
			}
		});

		$(function () {
			if ($('#container-position-rate').length) {
				$('#container-position-rate').highcharts({
					
					chart: {
						zoomType: 'xy'
					},
					title: {
						text: ''
					},
					subtitle: {
						text: ''
					},
				    xAxis: {
				    	type: 'category',
				    	labels: {
						}
					},
					xAxis: {
						max: 3,
						min: 0,
						categories: ['东', '南', '西' ,'北']
					},

				    yAxis: [{ // Primary yAxis
				    	labels: {
				    		format: '{value}%',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	},
				    	title: {
				    		text: '胜率',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	}
				    }, { // Secondary yAxis
				    	title: {
				    		text: '奖金',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	},
				    	labels: {
				    		format: '￥{value}',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	},
				    	opposite: true
				    }],
				    tooltip: {
				    	shared: true,
				    	formatter: function () {
				    		console.log(this.points["0"].y);

							return '赢取奖金: ' + this.points["0"].y+'<br/>'+
							'胜率: ' + this.points["1"].y+'%';
						}

				    },
				    credits: {
						enabled: false //去除水印
					},
				    legend: {
				    	layout: 'vertical',
				    	align: 'left',
				    	x: 10,
				    	verticalAlign: 'top',
				    	y: 0,
				    	floating: true,
				    	backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
				    },
				    series: [{
				    	name: '赢得奖金',
				    	color:'#536bdb',
				    	type: 'column',
				    	yAxis: 1,
				    	data: positionWin,
				    	tooltip: {
				    		valueSuffix: ' mm'
				    	}
				    }, {
				    	name: '赢钱概率',
				    	color:'#1cc7ea',
				    	type: 'spline',
				    	data: positionWinRate,
				    	tooltip: {
				    		valueSuffix: '°C'
				    	}
				    }]
				})
			}
		});


		$(function () {
			if ($('#container-position-lose-rate').length) {
				$('#container-position-lose-rate').highcharts({
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
					chart: {
						zoomType: 'xy'
					},
					title: {
						text:''
					},
					subtitle: {
						text: ''
					},

					credits: {
						enabled: false //去除水印
					},

					xAxis: {
						type: 'category',
					},
					xAxis: {
						max: 3,
						min: 0,
						categories: ['东', '南', '西' ,'北']
					},

				    yAxis: [{ // Primary yAxis
				    	labels: {
				    		format: '{value}%',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	},
				    	title: {
				    		text: '输钱概率',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	}
				    }, { // Secondary yAxis
				    	title: {
				    		text: '输钱',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	},
				    	labels: {
				    		format: '￥{value}',
				    		style: {
				    			color: Highcharts.getOptions().colors[1]
				    		}
				    	},
				    	opposite: true
				    }],
				    tooltip: {
				    	shared: true,
				    	formatter: function () {
				    		console.log(this.points["0"].y);

							return '输的金额: ' + this.points["0"].y+'<br/>'+
							'输钱概率: ' + this.points["1"].y+'%';
						}

				    },

				    legend: {
				    	layout: 'vertical',
				    	align: 'left',
				    	x: 10,
				    	verticalAlign: 'top',
				    	y: 0,
				    	floating: true,
				    	backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
				    },
				    series: [{
				    	name: '输的金额',
				    	color:'#f22f52',
				    	type: 'column',
				    	yAxis: 1,
				    	data: positionLose,
				    	tooltip: {
				    		valueSuffix: ' mm'
				    	}
				    }, {
				    	name: '输钱概率',
				    	color:'#f09aac',
				    	type: 'spline',
				    	data: positionLoseRate,
				    	tooltip: {
				    		valueSuffix: '°C'
				    	}
				    }]
				})
			}
		});
		
		$(function(){
			if ($('#container-duidie').length) {
				var chart = Highcharts.chart('container-duidie', {
					colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
				chart: {
					type: 'column'
				},
				title: {
					text: '清台榜'
				},
				xAxis: {
					categories: killRankPlayerJson
				},
				yAxis: {
					min: 0,
					title: {
						text: '清台总量'
					},
					stackLabels: {  // 堆叠数据标签
						enabled: true,
						style: {
							fontWeight: 'bold',
							color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
						}
					}
				},
				legend: {
					align: 'right',
					x: -30,
					verticalAlign: 'top',
					y: 25,
					floating: true,
					backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
					borderColor: '#CCC',
					borderWidth: 1,
					shadow: false
				},
				tooltip: {
					formatter: function () {
						return  this.series.name + ': ' + this.y ;
					}
				},
				plotOptions: {
					column: {
						stacking: 'normal',
						dataLabels: {
							enabled: true,
							color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
							style: {
								textOutline: '1px 1px black'
							}
						}
					}
				},
				series: killedBy
				});
			}
		});
	</SCRIPT>
<script type="text/javascript">
	function statusChange(value) {
		if (location.href.indexOf('?') == -1) {
			window.location.href = location.href + "?status=" + value;
		} else {
			var prefix = location.href.split('&game_type')[0];
			var url = location.href
			if (value == -1) {
				window.location.href = prefix;
			} else {
				window.location.href = prefix + "&game_type=" + value;
			}
		}
	}
	$(document).ready(function() {
		var value = location.href.split('=')[1];
		var select = $("#gameType");
		
	});	
	
	function startYearChange(value) {
		if (location.href.indexOf('?') == -1) {
			window.location.href = location.href + "?status=" + value;
		} else {
			var prefix = location.href.split('&y')[0];
			var url = location.href
			if (value == -1) {
				window.location.href = prefix;
			} else {
				if (value == 'all'){
					window.location.href = prefix + "&y=2018&start_time=20180101";
				}else{
					window.location.href = prefix + "&y=" + value + "&start_time=" + value + "0101&end_time=" + value + "1231";
					
				}
			}
		}
	}
	$(document).ready(function() {
		var value = location.href.split('=')[1];
		var select = $("#gameType");
		
	});	

</script>

<body>
	<div class="container-fluid mt--6">
      	<div class="row justify-content-center">
      		<div class="col-lg-12 card-wrapper ct-example">
      			    <div class="row row-example" style="padding-bottom: 20px;padding-left: 10px; padding-right: 10px;">
						<?php $md_link = isset($_REQUEST['md']) ? '&md=' . htmlspecialchars($_REQUEST['md']) : ''; ?>
						<div class="col-3 col-md-2"><a style="color: #ffffff" href="?type=total&y=<?php _e($this_year)?><?php echo $md_link; ?>">Overview</a></div>
						<div class="col-3 col-md-2"><a style="color: #ffffff" href="?type=detail&y=<?php _e($this_year)?><?phpecho $md_link; ?>">Details</a></div>
						<div class="col-3 col-md-2"><a style="color: #ffffff" href="?type=killrank&y=<?php _e($this_year)?><?php echo $md_link; ?>">Killer</a></div>
						<div class="col-3 col-md-2"><a style="color: #ffffff" href="?type=lucky&y=<?php _e($this_year)?><?php echo $md_link; ?>">Lucky</a></div>
						<div class="col-3 col-md-2"><a style="color: #ffffff" href="?type=position&y=<?php _e($this_year)?><?php echo $md_link; ?>">Position</a></div>
					</div>
      			
		
		<?php if(!isset($if_data)):?>
			
				<?php if($_REQUEST['type'] == "total" && !empty($winer_rate)):?>
					
				<div class="card">
		            <div class="card-header">
			              <div class="row align-items-center">
				                <div class="col-7">
				                  <h5 class="h3 mb-0"><?php _e($time_zone.":".game_type_array($game_type))?> &nbsp 总成本：<?php _e(round($total_cost,0))?>	</h5>
				                </div>
				                 <div class="col-5 text-right">
				                  <select class="form-control" id="start_year" name="start_year" onchange="startYearChange(this.value)">
				                  	<option select="selected" value=<?php _e($this_year)?>>今年</option>
									<?php foreach ($game_list as $key => $value): ?>
										<option <?php _e(test_js($value))?> value=<?php _e($value)?> ><?php _e($value)?></option>
									<?php endforeach ?>	
									<option <?php _e(test_js('all'))?> value="all"></option>		                    
				                    </select>

				                </div>
				              </div>
		            </div>

		            <div class="table-responsive">
				        <table id="stattable" class="table align-items-center table-flush table-striped">
				            <thead class="thead-light">
					              <tr>
				               			<th>Player</th>
										<th>参与/总局数/总次数</th>
										<th>Win&Rate</th>
										<th>Income-Cost(￥)</th>
										<th>Income.avg(￥)</th>
					              </tr>
				            </thead>
				            <tbody>
								<?php foreach($total_income_order as $key => $value):?>
									
									<tr>
										<td class="tdtxt-weight"><?php _e($order_array[$key]['name'])?></td>
										<td class="tdtxt"><?php _e($order_array[$key]['gamecount'])?></td>
										<td class="tdtxt"><?php _e($order_array[$key]['wincont'])?></td>
										<?php if($order_array[$key]['game_cost_player'] >0):?>
										<td class="tdtxt"><?php _e($order_array[$key]['total_income'])?>-<?php _e($order_array[$key]['game_cost_player'])?> =<?php _e($order_array[$key]['total_income']-$order_array[$key]['game_cost_player'])?></td>
										<?php else:?>
										<td class="tdtxt"><?php _e($order_array[$key]['total_income'])?></td>
										<?php endif?>
										<td class="tdtxt"><?php _e($order_array[$key]['total_income_pre'])?></td>
										
										
									</tr>
									
								<?php endforeach?>
							</tbody>
			        	</table>
			    	</div>
			    </div>	    
			    <?php if(isset($game_memo)):?>
			    <div class="row row-example">
					<div class="col text-center">
						
							<button style="margin-bottom: 30px;" class="btn btn-sm btn-primary" id="openDialog">Add Cost</button>
					</div>
				</div>
				<?php endif?>
				
				<?php endif?>

				<?php if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'total'):?>
				<div class="card">
					<div class="card-header">
				             <h3 class="mb-0">Total Incom</h3>
				    </div>
				    <div class="card-body " style="padding-left: 0;padding-right: 0">
				        <div id="container-timesadd" style="float: left; width: 100%;height:400px">
				        </div>
				    </div>
			    </div>
			    <div class="card" >
					<div class="card-header">
				             <h3 class="mb-0"><?php _e($game_type_info)?>支付关系</h3>
				    </div>
				    <div class="card-body " style="padding-left: 0;padding-right: 0">
				        <div id="container-fuqian"></div>
				    </div>
		   		</div>
			    <div class="card">
					<div class="card-header">
				             <h3 class="mb-0">Total Win Rate</h3>
				    </div>
				    <div class="card-body " style="padding-left: 0;padding-right: 0">
				        <div id="container-timeswinrateadd" style="float: left; width: 100%;height:400px">
				        </div>
				    </div>
			    </div>

			    <div class="card" style="display: none">
					<div class="card-header">
				             <h3 class="mb-0">现金局总收支</h3>
				    </div>
				    <div class="card-body " style="padding-left: 0;padding-right: 0">
				        <div id="container-total" style="float: left; width: 100%;height:400px;">
				        </div>
				    </div>
			    </div>

			    <div class="card" style="display: none">
					<div class="card-header">
				             <h3 class="mb-0">累计胜率</h3>
				    </div>
				    <div class="card-body " style="padding-left: 0;padding-right: 0">
				        <div id="container-total-avg" style="float: left; width: 100%; min-width:300px;height:400px;">
				        </div>
				    </div>
			    </div>
	
				<div id="container-winer" style="float: left; width: 100%; min-width:300px;height:300px;display: none"></div>
				
				
				<div class="card" style="display: ">
					<div class="card-header">
				             <h3 class="mb-0">Profit Margin</h3>
				    </div>
				    <div class="card-body " style="padding-left: 0;padding-right: 0">
				        <div id="container-pie" style="float: left; width: 100%; min-width:300px;height:300px"></div>
				    </div>
			    </div>
				<div style="min-width:300px;text-align: center;float: left;width: 100%;color: #555555;display: none"> 
					看看你的每月统计<br>
					↓&nbsp&nbsp↓&nbsp&nbsp↓&nbsp&nbsp↓&nbsp&nbsp<br>
					<?php foreach($player as $key => $value):?>
						<a style="/*color: #555555*/" href="?type=total&uid=<?php _e($value->id)?>&game_type=<?php _e($game_type)?>"><?php _e($value->nickname)?></a> &nbsp
					<?php endforeach?>
					<?php if($_REQUEST['showuser']!='no'):?>
						<div id="container-monthly" style="float: left; width: 100%; min-width:300px;height:300px"></div>
					<?php endif?>
				</div>


				<?php elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'killrank'):?>


				<div class="card" >
					<div class="card-header">
				             <h3 class="mb-0"><?php _e($game_type_info)?>清台榜</h3>
				    </div>
				    <div class="card-body " style="padding-left: 0;padding-right: 0">
				        <div id="container-duidie" style="min-width:300px"></div>
				    </div>
		   		</div>
		   		<div class="card" >
					<div class="card-header">
				             <h3 class="mb-0"><?php _e($game_type_info)?>胜负关系</h3>
				    </div>
				    <div class="card-body " style="padding-left: 0;padding-right: 0">
				        <div id="container-hexuan"></div>
				    </div>
		   		</div>

		   		<div class="row card-wrapper">
		   		
			   		<div class="col-lg-6">
			   			<div class="card" style="padding-left: 2px;padding-right: 2px;">
				   			<div class="card-header">
						             <h3 class="mb-0">All In 胜率</h3>
						    </div>
				   			<?php foreach($killed as $key => $value):?>
				   			<!-- Card body -->
			           	 	<div class="table-responsive">
				            	<div class="card-header">
					              <h5 class="mb-0"><?php _e(get_player($key)[0]->nickname)?>:<?php _e($value['win_rate'])?>&nbsp(Win:<?php _e($value['win'])?> / Total:<?php _e($value['total'])?>)</h5>
					            </div>
						        <table id="stattable" class="table align-items-center table-flush table-striped">
						            <thead class="thead-light">
							              <tr>
						               			<th>VS 玩家</th>
												<th>Win / Total</th>
												<th>胜率</th>
							              </tr>
						            </thead>
						            <tbody>
											<?php if(isset($value['vs'])):?>
												<?php foreach($value['vs'] as $k => $val):?>
												<tr>
													<td class="tdtxt-weight"><?php _e(get_player($k)[0]->nickname)?></td>
													<td class="tdtxt-weight"><?php _e($val['win'])?>/<?php _e($val['total'])?></td>
													<td class="tdtxt-weight"><?php _e($val['win_rate'])?></td>
												</tr>
												<?php endforeach?>
											<?php endif?>
									</tbody>
					        	</table>
				    		</div>
							<?php endforeach?>
						</div>
					</div>
					

		   			<?php if(isset($kill_info)):?>
			   		<div class="col-lg-6">
				   		<div class="card" style="padding-left: 2px;padding-right: 2px;">
				   			<div class="card-header">
						             <h3 class="mb-0">接 All In 赢了</h3>
						    </div>
					   		<?php foreach($kill_info as $key => $value):?>
					   		<!-- Card body -->
				            <div class="table-responsive">
				            	<div class="card-header">
					              <h5 class="mb-0"><?php _e(get_player($key)[0]->nickname)?>(<?php _e(array_sum($value))?>次)</h5>
					            </div>
						        <table id="stattable" class="table align-items-center table-flush table-striped">
						            <thead class="thead-light">
							              <tr>
						               			<th>玩家</th>
												<th>被清台（次）</th>
												<th>占比</th>
							              </tr>
						            </thead>
						            <tbody>
											
												<?php foreach($value as $k => $val):?>
												<tr>
													<td class="tdtxt-weight"><?php _e(get_player($k)[0]->nickname)?></td>
													<td class="tdtxt-weight"><?php _e($val)?></td>
													<td class="tdtxt-weight"><?php _e(round($val/array_sum($value)*100,2))?>%</td>
												</tr>
												<?php endforeach?>
												
											
									</tbody>
					        	</table>
					    	</div>
							<?php endforeach?>
						</div>
					</div>
					<?php endif?>
					<?php if (isset($kill_info_killed_by)):?>
					<div class="col-lg-6">
						<div class="card" style="padding-left: 2px;padding-right: 2px;">
			   			<div class="card-header">
					             <h3 class="mb-0">推 All In 输了</h3>
					    </div>
						<?php foreach($kill_info_killed_by as $key => $value):?>
			   			<!-- Card body -->
			            <div class="table-responsive">
			            	<div class="card-header">
				              <h5 class="mb-0"><?php _e(get_player($key)[0]->nickname)?>:被清台(<?php _e(array_sum($value))?>次)</h5>
				            </div>
					        <table id="stattable" class="table align-items-center table-flush table-striped">
					            <thead class="thead-light">
						              <tr>
					               			<th>玩家</th>
											<th>清台</th>
											<th>占比</th>
						              </tr>
					            </thead>
					            <tbody>
										
											<?php foreach($value as $k => $val):?>
											<tr>
												<td class="tdtxt-weight"><?php _e(get_player($k)[0]->nickname)?></td>
												<td class="tdtxt-weight"><?php _e($val)?></td>
												<td class="tdtxt-weight"><?php _e(round($val/array_sum($value)*100,2))?>%</td>
											</tr>
											<?php endforeach?>
								</tbody>
				        	</table>
				    	</div>
						<?php endforeach?>
						</div>
					</div>
				</div>
				<?php endif?>
				<?php elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'lucky'):?>


				
		   		<div class="row card-wrapper">
		   		<div class="col-lg-12">
		   		<div class="card" style="padding-left: 2px;padding-right: 2px;">
		   			<div class="card-header">
				             <h3 class="mb-0">Lucky Star</h3>
				    </div>
		   		
		   		<!-- Card body -->
		            <div class="table-responsive">
		            	
				        <table id="stattable" class="table align-items-center table-flush table-striped">
				            <thead class="thead-light">
					              <tr>
				               			<th>玩家</th>
										<th>总计</th>
										<th>皇家同花顺</th>
										<th>同花顺</th>
										<th>金刚</th>
					              </tr>
				            </thead>
				            <tbody>
						
							<?php foreach($lucky_info as $k => $val):?>
							<tr>
								<td class="tdtxt-weight"><?php _e(get_player($k)[0]->nickname)?></td>
								
								<td class="tdtxt-weight">
								<?php if($val['total']['times'] <>0):?>
									<?php _e($val['total']['times']."次 (￥".$val['total']['money'].")")?>
								<?php else:?>
									---
								<?php endif?>
									</td>
								
								<td class="tdtxt-weight">
								<?php if($val['royal straight flush']['times'] <> 0):?>
									<?php _e($val['royal straight flush']['times']."次 (￥".$val['royal straight flush']['money'].")")?>
								<?php else:?>
									---
								<?php endif?>
									</td>
								
								<td class="tdtxt-weight">
								<?php if($val['straight flush']['times'] <> 0):?>
									<?php _e($val['straight flush']['times']."次 (￥".$val['straight flush']['money'].")")?>
								<?php else:?>
									---
								<?php endif?>
									</td>
								<td class="tdtxt-weight">
								<?php if($val['four']['times'] <> 0):?>
									<?php _e($val['four']['times']."次 (￥".$val['four']['money'].")")?>
								<?php else:?>
									---
								<?php endif?>
								</td>
								
							</tr>
							<?php endforeach?>
										
									
							</tbody>
			        	</table>
			    	</div>
					
					</div>
					</div>
					
					</div>
					</div>
					</div>
				<?php elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'position'):?>
				<div class="card" >
					<div class="card-header">
				             <h3 class="mb-0"><?php _e($game_type_info)?>每个方向总计</h3>
				    </div>
				    <div id="container-position-rate-new" style="height:300px;">
				    </div>
		   		</div>

		   		<div class="card" >
					<div class="card-header">
				             <h3 class="mb-0"><?php _e($game_type_info)?>哪儿赢得多</h3>
				    </div>
				    <div id="container-position-rate" style="height:300px;"></div>
		   		</div>

		   		<div class="card" >
					<div class="card-header">
				             <h3 class="mb-0"><?php _e($game_type_info)?>哪儿输得多</h3>
				    </div>
				    <div id="container-position-lose-rate" style="height:300px;"></div>
		   		</div>

		   		<div class="card" style="display: none;" >
					<div class="card-header">
				             <h3 class="mb-0">方位总盈亏</h3>
				    </div>
				    <div  id="container-bubble" style=" height:500px;"></div>
		   		</div>
							

					
							
							
				<?php elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'where'):?>
								<!-- <div style="min-width:100px;text-align: center; padding-top: 10px;float: left;width: 100%;float: left;color: #555555" id="container-test"></div> -->
				<div class="card" >
					<div class="card-header">
				             <h3 class="mb-0">累计胜率</h3>
				    </div>
				   	<div id="container-positon-detail" style=";height:300px;margin-top: 50px;padding-top: 20px"></div>
		   		</div>

		   		<div class="card" >
					<div class="card-header">
				             <h3 class="mb-0">累计胜率</h3>
				    </div>
				   	<div style="text-align: center; padding-top: 80px;float: left;width: 100%;color: #555555"> 
						看看你的风水<br>
						↓&nbsp&nbsp↓&nbsp&nbsp↓&nbsp&nbsp↓&nbsp&nbsp<br>
						<?php foreach($person_positon['uid'] as $key => $value):?>
							<a style="/*color: #555555*/" href="?type=where&uid=<?php _e($value)?>&game_type=<?php _e($game_type)?>&y=<?php _e($_REQUEST['y'])?>"><?php _e(get_player($value)[0]->nickname)?></a> &nbsp
						<?php endforeach?>
						<?php if($_REQUEST['showuser']!='no'):?>
							<div id="container-person-positon" style=";height:500px;padding-top: 20px"></div>
						<?php endif?>
					</div>
		   		</div>
								
								
				<?php else:?>

				<div class="card" >
					<div class="card-header">
				             <h3 class="mb-0">每次收支情况</h3>
				    </div>
				   	<div id="container" style="height: <?php _e($detail_heigh)?>px" ></div>
		   		</div>
									
				<?php endif?>
				
		<?php endif?>
				<?php if(isset($if_data)):?>
				<div class="row-full-div">
					<div class="card-header">
			              <div class="row align-items-center">
				                <div class="col-7">
				                  <!-- Title -->
				                  <h5 class="h3 mb-0">暂无数据，请选择其他时间段或游戏类型</h5>
				                </div>
				                <div class="col-5 text-right">
				                  <select class="form-control" id="gameType" name="gameType" onchange="statusChange(this.value)">
				                     <option <?php _e(test_js("all"))?> value="all">现金 OR 排位?</option>
											<option <?php _e(test_js("cash"))?> value="cash">现金局</option>
											<option <?php _e(test_js("sng"))?> value="sng">排位赛</option>
											<option <?php _e(test_js("all"))?> value="all"></option>
				                    </select>		                  
				                </div>
			              </div>
		            </div>
				</div>
					
				</div>
				<?php endif?>
				<div class="row row-example">
					<div class="col text-center">
						<a class="btn btn-primary btn-sm" href="?type=<?php _e($_REQUEST['type'])?>&y=2018&game_type=<?php _e($game_type)?>&end_time=<?php _e(date("Ymd",time()))?>">全部数据</a>
					</div>
					<div class="col text-center">
						<a class="btn btn-primary btn-sm" href="?type=<?php _e($_REQUEST['type'])?>&y=<?php _e(date('Y'))?>&game_type=<?php _e($game_type)?>">今年数据</a>

					</div><div class="col text-center">
						<a class="btn btn-primary btn-sm" href="?type=<?php _e($_REQUEST['type'])?>&y=<?php _e(date('Y'))?>&game_type=<?php _e($game_type)?>&md=<?php _e($game_group)?>">最近一场</a>

					</div>

					
					
				</div>
				<div class="row row-example">
					&nbsp;
				</div>
			</div>
        </div>
    </div>
<script>
$(document).ready(function() {
  $('#openDialog').click(function() {
    Swal.fire({
      title: '输入成本',
      input: 'number',
      inputAttributes: {
        min: 0
      },
      showCancelButton: true,
      confirmButtonText: '提交',
      cancelButtonText: '取消',
      showLoaderOnConfirm: true,
      preConfirm: (value) => {
        if (value === '') {
          Swal.showValidationMessage('请输入一个数字');
        }
        return value;
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if (result.value) {
        let cost = result.value;
        let memo = "<?php echo $game_memo; ?>";
        $.ajax({
          url: 'kog_addcost.php',
          type: 'POST',
          data: { cost: cost, memo: memo },
          success: function(response) {
           	Swal.fire('成功', response, 'success').then(function() {
     				 	window.location.href = 'stat.php?type=total&md='+memo;
   					 });
          },
          error: function(xhr, status, error) {
            Swal.fire('错误', '处理请求时发生错误', 'error');
          }
        });
      }
      
    });
  });
});
</script>
</body>
</html>