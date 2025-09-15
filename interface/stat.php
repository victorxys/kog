<?php

require_once('functions.php'); 

// update_real_chips();
$_REQUEST['type'] = isset($_REQUEST['type'])?$_REQUEST['type']:"detail";

$uid = isset($_REQUEST['uid'])?$_REQUEST['uid']:null;
$person_name = get_player($uid)[0]->nickname;

//日期格式 20181001
$this_year = isset($_REQUEST['y'])?$_REQUEST['y']: date('Y');
// $this_year = "2018";
$start_time = isset($_REQUEST['start_time'])?$_REQUEST['start_time']:$this_year."0101";

// 几乎用不到结束日期，暂时注释掉，只有选看某一年后某一段时间的数据时，才用到技术时间。
// $end_time = isset($_REQUEST['end_time'])?$_REQUEST['end_time']:$this_year."1231";
$end_time = null;

$time_zone_start = isset($_REQUEST['start_time'])?$_REQUEST['start_time']:$this_year;
$time_zone_end = isset($_REQUEST['end_time'])?$_REQUEST['end_time']:"至今";
if (isset($_REQUEST['game_type'])&&($_REQUEST['game_type']=="all"||empty($_REQUEST['game_type']))) {
	unset($_REQUEST['game_type']);
}
$_REQUEST['stat_type'] = isset($_REQUEST['stat_type'])?$_REQUEST['stat_type']:'cahs';
$stat_type = $_REQUEST['stat_type'];
$time_zone = $time_zone_start."~".$time_zone_end;
$game_type = (isset($_REQUEST['game_type']))?$_REQUEST['game_type']:null;
$game_type_info = game_type_array($game_type);


$game = get_game_by_args(array('status'=>2,'start_time'=>$start_time,'end_time'=>$end_time,'game_type'=>$game_type));
if (empty($game)) {
	$if_data 	= 0;
}
switch ($_REQUEST['type']) {
	case 'total':
	// 按应收计算
	$total_income_real	=	get_player_total_income_stat($start_time,$end_time,$game_type,"real_money");
	$data_total_real =	$total_income_real['total'];	
	if (empty($total_income_real)) {
		
	}
	//按实际收支计算
	$total_income	=	get_player_total_income_stat($start_time,$end_time,$game_type,$stat_type);

	// 每次累计收入 （按 实际收入计算）
	$total_times_add = $total_income['times_income_add'];
	if (isset($total_times_add)) {
		$i = 0;
		foreach ($total_times_add as $key => $value) {
			$total_times_add_user[$i]['name'] = get_player($key)[0]->nickname;
			if (isset($value)) {
				foreach ($value as $k => $val) {
					$total_times_add_user[$i]['data'][] = $val;
					$total_times_add_user[$i]['date'][] = $k;
				}
			}

			$i++;
		}
	}
	// echo "<pre>";
	// var_dump ($total_times_add_user);
	// exit;

	$data_total =	$total_income['total'];
	$data 		= 	$total_income['income'];
	$date_avg	=	$total_income['avg'];
	$game_count  = 	count($total_income['date']);//一共发生了的场次（不代表每个人参加的数量）
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
	$winer_ra 	= 	count_winer_rate($start_time,$end_time,$game_type);
	$monthly_win = $winer_ra['monthly_win'];
	$user_times_win_rate = $winer_ra['user_times_win_rate'];
	if (isset($user_times_win_rate)) {
		$i = 0;
		foreach ($user_times_win_rate as $key => $value) {
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


	// echo "<pre>";
	// var_dump ($user_times_win_rate_user);
	// // var_dump ($winer_ra['user_times_win_rate']);
	// exit;
	unset($winer_ra['monthly_win']);
	unset($winer_ra['user_times_win_rate']);
	if (!empty($winer_ra)) {
		foreach ($winer_ra as $key => $value) {
			// 便于用 % 显示

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
	// echo "<pre>";
	// var_dump ($user_month_win_rate);
	// exit;
	
	break;

	// 分段统计
	case 'timezone':
		# code...
	break;

	case 'killrank':
	$kill_rank 	=	get_best_killer($start_time,$end_time,$game_type);
	break;

	case 'where':
		/**
		 * 计算每个人在每个方位的具体战果
		 */
		$person_positon = 	count_person_position_win($start_time,$end_time,$game_type);
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
			$position_stat 	=	get_position_stat($start_time,$end_time,$game_type);
			// 折现柱状符合图
			// 计算每个位置东南西北（1~4号位置的输赢情况）
			$position_count_res 	=	 cont_position_winer($start_time,$end_time,$game_type);

			if (isset($position_count_res) && !empty($position_count_res)) {
				foreach ($position_count_res as $key => $value) {
			// $position_stat_map[$key] = $value;
					foreach ($value as $k => $val) {

						$position_stat_map[$key][]=abs($val);
					}
				}
			}
			$position_count_res_new 	=	 cont_position_winer_new($start_time,$end_time,$game_type);
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
			$total_income	=	get_player_total_income_stat($start_time,$end_time,$game_type);
			// $data_total =	json_encode($total_income['total']);
			$data 			= 	$total_income['income'];
			$statTime 		=	$total_income['date'];
			// echo "<pre>";
			// var_dump ($data);
			// var_dump ($statTime);
			// exit;
			break;
		}
		$url = "kog_detail.php?gid=";
		function test_js($value){

			$game_type = isset($_REQUEST['game_type'])?$_REQUEST['game_type']:null;
			if ($value == $game_type) {
				return "selected='selected'";
			}
			else{
				return false;
			}
		}
		?>


		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Big Data</title>
			<link rel="stylesheet" type="text/css" href="assets/css/style.css">
			<!-- <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"> -->
			<link rel="stylesheet" type="text/css" href="style.css">
			<script type="text/javascript" src="assets/js/flot/jquery-1.8.3.min.js"></script>
			<script type="text/javascript" src="assets/js/flot/highcharts.js"></script>
			<script type="text/javascript" src="assets/js/flot/exporting.js"></script>
			<script type="text/javascript" src="assets/js/flot/highcharts-more.js"></script>
			<script type="text/javascript" src="assets/js/flot/highcharts-zh_CN.js"></script>

			<!-- 表格显示 -->
			

			<!--  jQuery v3.0.0-beta1 -->
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.js"></script>

			<!-- JS Pluging -->
			<script type='text/javascript' src='https://s3.amazonaws.com/dynatable-docs-assets/js/jquery.dynatable.js'></script>

			
			<!-- 表格显示 -->
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
	$uid 								= isset($uid)?$uid:null;
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
	?>
	<SCRIPT>

		var statTimej 		= 	<?php echo json_encode($statTime)?>;
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

		
		$(function () {
			$('#container-timesadd').highcharts({
				chart: {
					type: 'spline'
				},
				title: {
					text: '累计营收'
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
				// min: false,
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
					// pointInterval: 3600000, // one hour
					// pointStart: Date.UTC(2009, 9, 6, 0, 0, 0)
				}
			},
			series: totalTimesAddUser,
			navigation: {
				menuItemStyle: {
					fontSize: '10px'
				}
			}
		});
		});

		$(function () {
			$('#container-timeswinrateadd').highcharts({
				chart: {
					type: 'spline'
				},
				title: {
					text: '累计胜率'
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
				// min: false,
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
					// pointInterval: 3600000, // one hour
					// pointStart: Date.UTC(2009, 9, 6, 0, 0, 0)
				}
			},
			series: userTimesWinRateUser,
			
			navigation: {
				menuItemStyle: {
					fontSize: '10px'
				}
			}
		});
		});

		$(function () {
			$('#container').highcharts({
				chart: {
				// type: 'column'
				type: 'bar'
			},
			title: {
				text: '每次收支情况'

			},
			subtitle: {
				text: '数据截止到最近一次'
			},
			xAxis: {
				// categories: ['1','2','3','4','5','6','7']
				categories: statTimej,
				labels: {
					// rotation: -45,
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
				// min: -1000,
				title: {
					enabled: false,
					text: false
				}
			},
			legend: {
				align: 'right',
				x: 0,
				verticalAlign: 'top',
				y: 20,
				floating: true,
				backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
				borderColor: '#CCC',
				borderWidth: 1,
				shadow: false
			},
			tooltip: {
				formatter: function () {
					// console.log(this.point);
					return '<b>' + this.x + '</b><br/>' +
					this.series.name + ': ' + this.y + '<br/>' +
					'总量: ' + this.point.stackTotal;
				}
			},
			plotOptions: {
				// bar 对应的是 chart 的 type,
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
		});


		$(function () {
			$('#container-total').highcharts({
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
						// rotation: -45,
						// style: {
						// 	fontSize: '13px',
						// 	fontFamily: 'Verdana, sans-serif'
						// }
					}
				},

				yAxis: {
					// min: 0,
					title: {
						text: '总金额'
					}
				},
				legend: {
					enabled: true
				},
				tooltip: {
					// formatter: function () {
					// 	console.log(this.point);
					// 	return '<b>' + this.x + '</b><br/>' +
					// 	this.series.name + ': ' + this.y + '<br/>' +
					// 	'总量: ' + this.point.stackTotal;
					// }
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
						// rotation: -90,
						color: '#FFFFFF',
						align: 'center',
		                // format: '{point.y:.1f}', // one decimal
		                // y: 10, // 10 pixels down from the top
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
						// rotation: -90,
						color: '#FFFFFF',
						align: 'center',
		                // format: '{point.y:.1f}', // one decimal
		                // y: 10, // 10 pixels down from the top
		                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
		                style: {
		                	textShadow: '0 0 1px black'
		                }
		            }
		        }]
		    });
		});

		$(function () {
			$('#container-total-avg').highcharts({
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
						// rotation: -45,
						// style: {
						// 	fontSize: '13px',
						// 	fontFamily: 'Verdana, sans-serif'
						// }
					}
				},

				yAxis: {
					// min: 0,
					title: {
						text: '场均'
					}
				},
				legend: {
					enabled: false
				},
				tooltip: {
					// formatter: function () {
					// 	console.log(this.point);
					// 	return '<b>' + this.x + '</b><br/>' +
					// 	this.series.name + ': ' + this.y + '<br/>' +
					// 	'总量: ' + this.point.stackTotal;
					// }
					pointFormat: '场均收支: <b>{point.y:.1f}元</b>'
				},
				credits: {
					enabled: false
				},
				series: [{
					name: '场均收支',
					data: date_avg,
					color: '#f7a35c',
					// colorByPoint:true,  //或者直接写在这里
					// 	colors: ['#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9', 
    				// '#f15c80', '#e4d354', '#8085e8', '#8d4653', '#91e8e1'],
    				dataLabels: {
    					enabled: true,
						// rotation: -90,
						color: '#FFFFFF',
						align: 'center',
		                // format: '{point.y:.1f}', // one decimal
		                // y: 10, // 10 pixels down from the top
		                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
		                style: {
		                	textShadow: '0 0 1px black'
		                }
		            }
		        }]
		    });
		});


		$(function () {
			$('#container-monthly').highcharts({
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
				// 	formatter: function () {
		  //   		console.log(this.points);

				// 	// 此处需要在原data 数组中构造相应数组结构，图标中的数据key值是“y“即可，其他的key值不限，才能在point 中显示相应的参数
				// 	return '营收: ' + this.points["0"].y+'<br/>'+
				// 	'场均: ' + Math.round(this.points["1"].y)+'元/场<br/>'+
				// 	'胜: ' + this.points["1"].point.wincount +'场<br/>'+
				// 	'共: ' + this.points["1"].point.totalcount +'场<br/>'+
				// 	'场均: ' + Math.round(this.points["0"].y/this.points["1"].point.totalcount) + '元/场'
				// 	;
				// },
					// console.log(this.points);
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
					// name: personName,
					name: "营收(元)",
					type: 'column',
					// colorByPoint:true,  //或者直接写在这里
					data: userMonthTotal,
				},{
					// name: personName,
					name: "场均(元/场)",
					type: 'spline',
					// colorByPoint:true,  //或者直接写在这里
					data: userMonthAvg,
				},{
					// name: personName,
					name: "胜率(%)",
					type: 'spline',
					// colorByPoint:true,  //或者直接写在这里
					data: userMonthWinRate,
				}
				// ,{
				// 	// name: personName,
				// 	name: "次数",
				// 	type: 'spline',
				// 	colorByPoint:true,  //或者直接写在这里
				// 	data: userMonthMimes,
				// 	format: '{point.y:.2f}%', // one decimal
				// }
				]
			});
		});




		$(function () {
			$('#container-winer').highcharts({
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
					// rotation: -45,
					// style: {
					// 	fontSize: '13px',
					// 	fontFamily: 'Verdana, sans-serif'
					// }
				}
			},
			yAxis: {
				// min: 0,
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
					// 此处需要在原data 数组中构造相应数组结构，才能在point 中显示相应的参数
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
				// data: [{
				// 	name:winerRate[0],
				// 	y:winerRate[1],
				// 	countgame:winerRate[2],
				// }],
				color: '#8085e8',
				rateLabels: '4',
				dataLabels: {
					enabled: true,
					// rotation: -90,
					color: '#FFFFFF',
					align: 'center',
                format: '{point.y:.2f}%', // one decimal
                // y: 10, // 10 pixels down from the top
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                style: {
                	textShadow: '0 0 1px black'
                }
            }
        }]
    });
		});

		$(function () {
			$('#container-kill').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: gameTypeInfo+'清台榜'
				},
				subtitle: {
					text: ''
				},
				xAxis: {
					type: 'category',
					labels: {
					// rotation: -45,
					// style: {
					// 	fontSize: '13px',
					// 	fontFamily: 'Verdana, sans-serif'
					// }
				}
			},
			yAxis: {
				// min: 0,
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
					// rotation: -90,
					color: '#FFFFFF',
					align: 'center',
                // format: '{point.y:.1f}', // one decimal
                // y: 10, // 10 pixels down from the top
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                style: {
                	textShadow: '0 0 1px black'
                }
            }
        }]
    });
		});

		$(function () {
			$('#container-pie').highcharts({
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
		});

		$(function () {
			$('#container-person-positon').highcharts({
				chart: {
					zoomType: 'xy'
				},
				title: {
					text: '-'+personName+'-在哪儿赢得多'
				},
				subtitle: {
					text: '数据来源: KOG'
				},
		    // xAxis: [{
		    //     categories: ['0','东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)'],
		    //     crosshair: true
		    // }],
		    xAxis: {
		    	type: 'category',
		    },
		    xAxis: {
		    	max: 3,
		    	min: 0,
		    	categories: ['东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)']
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
		    		// console.log(this.points);

					// 此处需要在原data 数组中构造相应数组结构，图标中的数据key值是“y“即可，其他的key值不限，才能在point 中显示相应的参数
					return '赢取奖金: ' + this.points["0"].y+'<br/>'+
					'胜率: ' + Math.round(this.points["1"].y)+'%<br/>'+
					'胜: ' + this.points["1"].point.wincount +'场<br/>'+
					'共: ' + this.points["1"].point.totalcount +'场<br/>'+
					'场均: ' + Math.round(this.points["0"].y/this.points["1"].point.totalcount) + '元/场'
					;
				}

		        // pointFormat: '胜率: <b>{point.y:.2f}%</b>'
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
					// zones: [{
					// 	value: 0,
					// 	color: '#ff0080'
					// }],
					threshold: -10
				}
			},
			series: [{
				name: '赢得奖金',
				color:'#f3d64e',
				type: 'column',
				// wincount: personWin,
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
				// wincount:personWin,
				color:'#555555',
				type: 'spline',
				data: personPositonWinRate,
				tooltip: {
					valueSuffix: '°C'
				}
			}]
			// series: [{"type":"column","name":"aaa","data":[200,300,500,100]},{"type":"column","name":"bbb","data":[700,200,500,100]},{"type":"column","name":"ccc","data":[300,100,500,100]}]
		})
		});


		$(function () {
			$('#container-positon-detail').highcharts({
				chart: {
					zoomType: 'xy'
				},
				title: {
					text: '每人在每个方向的汇总'
				},
				subtitle: {
					text: '数据来源: KOG'
				},
		    // xAxis: [{
		    //     categories: ['0','东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)'],
		    //     crosshair: true
		    // }],
		    xAxis: {
		    	type: 'category',
		    },
		    xAxis: {
		    	max: 3,
		    	min: 0,
		    	categories: ['东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)']
		    },
		    yAxis: [
		    // { // Primary yAxis
		    // 	labels: {
		    // 		format: '{value}%',
		    // 		style: {
		    // 			color: Highcharts.getOptions().colors[1]
		    // 		}
		    // 	},
		    // 	title: {
		    // 		text: '胜率',
		    // 		style: {
		    // 			color: Highcharts.getOptions().colors[1]
		    // 		}
		    // 	}
		    // }, 
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
		    	// opposite: true
		    }],
		    tooltip: {
		    	shared: true,
		    	formatter: function () {
		    		console.log(this.points);

					// 此处需要在原data 数组中构造相应数组结构，图标中的数据key值是“y“即可，其他的key值不限，才能在point 中显示相应的参数
					return this.points["0"].series.userOptions.name+': ' + this.points["0"].y+'<br/>'+
					this.points["1"].series.userOptions.name+': ' + this.points["1"].y+'<br/>'+
					this.points["2"].series.userOptions.name+': ' + this.points["2"].y+'<br/>'+
					this.points["3"].series.userOptions.name+': ' + this.points["3"].y+'<br/>'+
					this.points["4"].series.userOptions.name+': ' + this.points["4"].y
					;
				}

		        // pointFormat: '胜率: <b>{point.y:.2f}%</b>'
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
					// zones: [{
					// 	value: 0,
					// 	color: '#ff0080'
					// }],
					threshold: -10
				}
			},
			series: eachPositionDetailWin
			// series: [{"type":"column","name":"aaa","data":[200,300,500,100]},{"type":"column","name":"bbb","data":[700,200,500,100]},{"type":"column","name":"ccc","data":[300,100,500,100]}]
		})
		});



		$(function () {
			$('#container-position-rate-new').highcharts({
				chart: {
					zoomType: 'xy'
				},
				credits: {
				enabled: false //去除水印
			},
			title: {
				text: gameTypeInfo+'每个方向总计'
			},
			subtitle: {
				text: '数据来源: KOG'
			},
		    // xAxis: [{
		    //     categories: ['0','东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)'],
		    //     crosshair: true
		    // }],
		    xAxis: {
		    	type: 'category',
		    	labels: {
					// rotation: -45,
					// style: {
					// 	fontSize: '13px',
					// 	fontFamily: 'Verdana, sans-serif'
					// }
				}
			},
			xAxis: {
				max: 3,
				min: 0,
				categories: ['东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)']
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
		    			color: Highcharts.getOptions().colors[0]
		    		}
		    	},
		    	labels: {
		    		format: '￥{value}',
		    		style: {
		    			color: Highcharts.getOptions().colors[0]
		    		}
		    	},
		    	opposite: true
		    }],
		    tooltip: {
		    	shared: true,
		    	formatter: function () {
		    		console.log(this.points["0"].y);

					// 此处需要在原data 数组中构造相应数组结构，才能在point 中显示相应的参数
					return '赢取奖金: ' + this.points["0"].y+'<br/>'+
					'胜率: ' + this.points["1"].y+'%';
				}

		        // pointFormat: '胜率: <b>{point.y:.2f}%</b>'
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
		    		zones: [{
		    			value: 0,
		    			color: '#ff0080'
		    		}],
		    		threshold: -10
		    	}
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
		    // series: [{
		    // 	name: '甲',
		    // 	type: 'column',
		    // 	yAxis: 1,
		    // 	data: positionWinNew,

		    // }, 
		    // {
		    // 	name: '乙',
		    // 	type: 'column',
		    // 	data: positionWinNew,

		    // }, 
		    // {
		    // 	name: '胜率',
		    // 	type: 'spline',
		    // 	data: positionWinRateNew,

		    // }]
		})
		});

		$(function () {
			$('#container-position-rate').highcharts({
				chart: {
					zoomType: 'xy'
				},
				title: {
					text: gameTypeInfo+'哪儿赢得多'
				},
				subtitle: {
					text: '数据来源: KOG'
				},
		    // xAxis: [{
		    //     categories: ['0','东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)'],
		    //     crosshair: true
		    // }],
		    xAxis: {
		    	type: 'category',
		    	labels: {
					// rotation: -45,
					// style: {
					// 	fontSize: '13px',
					// 	fontFamily: 'Verdana, sans-serif'
					// }
				}
			},
			xAxis: {
				max: 3,
				min: 0,
				categories: ['东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)']
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
		    			color: Highcharts.getOptions().colors[0]
		    		}
		    	},
		    	labels: {
		    		format: '￥{value}',
		    		style: {
		    			color: Highcharts.getOptions().colors[0]
		    		}
		    	},
		    	opposite: true
		    }],
		    tooltip: {
		    	shared: true,
		    	formatter: function () {
		    		console.log(this.points["0"].y);

					// 此处需要在原data 数组中构造相应数组结构，才能在point 中显示相应的参数
					return '赢取奖金: ' + this.points["0"].y+'<br/>'+
					'胜率: ' + this.points["1"].y+'%';
				}

		        // pointFormat: '胜率: <b>{point.y:.2f}%</b>'
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
				color:'#f3d64e',
				type: 'column',
				yAxis: 1,
				data: positionWin,
				tooltip: {
					valueSuffix: ' mm'
				}
			}, {
				name: '赢钱概率',
				color:'#555555',
				type: 'spline',
				data: positionWinRate,
				tooltip: {
					valueSuffix: '°C'
				}
			}]
		})
		});


		$(function () {
			$('#container-position-lose-rate').highcharts({
				chart: {
					zoomType: 'xy'
				},
				title: {
					text: gameTypeInfo+'哪儿输得多'
				},
				subtitle: {
					text: '数据来源: KOG'
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
				categories: ['东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)']
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
		    			color: Highcharts.getOptions().colors[0]
		    		}
		    	},
		    	labels: {
		    		format: '￥{value}',
		    		style: {
		    			color: Highcharts.getOptions().colors[0]
		    		}
		    	},
		    	opposite: true
		    }],
		    tooltip: {
		    	shared: true,
		    	formatter: function () {
		    		console.log(this.points["0"].y);

					// 此处需要在原data 数组中构造相应数组结构，才能在point 中显示相应的参数
					return '输的金额: ' + this.points["0"].y+'<br/>'+
					'输钱概率: ' + this.points["1"].y+'%';
				}

		        // pointFormat: '胜率: <b>{point.y:.2f}%</b>'
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
		    	color:'#ff0080',
		    	type: 'column',
		    	yAxis: 1,
		    	data: positionLose,
		    	tooltip: {
		    		valueSuffix: ' mm'
		    	}
		    }, {
		    	name: '输钱概率',
		    	color:'#555555',
		    	type: 'spline',
		    	data: positionLoseRate,
		    	tooltip: {
		    		valueSuffix: '°C'
		    	}
		    }]
		})
		});

		$(function () {
			$('#container-bubble').highcharts({
				legend: {
					enabled: false,
				},
				chart: {
					type: 'bubble',
					zoomType: 'xy'
				},
				credits: {
					enabled: false
				},
				xAxis: {
					max: 4,
					min: 1,
					categories: ['0','东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)']
				},
				yAxis: {
				// min: 0,
				title: {
					enabled: false,
					text: 'KOG-STAT'
				},
			},
			title: {
				text: '方位总盈亏'
			},
			series: positionStat

		});
		});

// 根据 data 循环自动显示多个图表
//     
var data ={
	"东" : <?php echo json_encode($each_position_win[1]);?>,
	"南" : <?php echo json_encode($each_position_win[2]);?>,
	"西" : <?php echo json_encode($each_position_win[3]);?>,
	"北" : <?php echo json_encode($each_position_win[4]);?>,
};
$(function () {
	for(var temp in data){
		var div = $('<div style="min-width:400px ,height:400px"></div>');
		$('#container-test').append(div);
		div.highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: '对数折线图'
			},
			xAxis: {
				tickInterval: 1
			},
			yAxis: {
				type: 'logarithmic',
				minorTickInterval: 0.1
			},
			tooltip: {
				headerFormat: '<b>{series.name}</b><br />',
				pointFormat: 'x = {point.x}, y = {point.y}'
			},
			series: [{
				name:temp,
				data: data,
				pointStart: 1
			}]
		}
		);
	}});
</SCRIPT>
<script type="text/javascript">
	function statusChange(value) {
		//判断是否第一次进入？
		if (location.href.indexOf('?') == -1) {
			window.location.href = location.href + "?status=" + value;
		} else {
			var prefix = location.href.split('&game_type')[0];//拼接当前的地址
			var url = location.href
			if (value == -1) {
				window.location.href = prefix;
			} else {
				window.location.href = prefix + "&game_type=" + value;
				// window.location.href = url + "&game_type=" + value;
			}
		}
	}
	$(document).ready(function() {
		/* alert("加载完成"); */
		var value = location.href.split('=')[1];
		var select = $("#gameType");
		
	});	
</script>
<body>
	<?php require_once "kog_header.php";?>

	<div class="content">
		<div class="row-full-bottom"><a href="?type=total&y=<?php _e($this_year)?>"><span>概况统计</span></a> &nbsp
			<a href="?type=detail&y=<?php _e($this_year)?>"><span>详情展示</span></a>&nbsp
			<a href="?type=killrank&y=<?php _e($this_year)?>"><span>清台榜</span></a>&nbsp
			<a href="?type=position&y=<?php _e($this_year)?>"><span>方位盈亏</span></a>&nbsp
			<a href="?type=where&showuser=no&y=<?php _e($this_year)?>"><span>个人风水</span></a>
		</div>
		<?php if(!isset($if_data)):?>
			<div id=all_stat>
			<div class="row-full-div">
				<div class="tap"></div>
				<span class="spantxt" ><?php _e($time_zone.": <font style='color: #000;font-weight: bold'>".game_type_array($game_type))?></span>	
					<div class="spantxt-right" >
						<select id="gameType" name="gameType" class="select-game-type" onchange="statusChange(this.value)">
							<option <?php _e(test_js("all"))?> value="all">现金 OR 排位?</option>
							<option <?php _e(test_js("cash"))?> value="cash">现金局</option>
							<option <?php _e(test_js("sng"))?> value="sng">排位赛</option>
							<option <?php _e(test_js("all"))?> value="all">全部场次</option>
						</select>
					</div>
				</div>
				<?php if($_REQUEST['type'] == "total" && !empty($winer_rate)):?>
					<div style="padding-left: 5px;padding-right: 5px" >
						<table  >
							<thead >
								<tr>
									<th>玩家</th>
									<th>总次数</th>
									<th>参与</th>
									<th>赢&胜率</th>
									<th>收支</th>
									<th>场均收支</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($winer_rate as $key => $value):?>
									
									<tr>
										<td class="tdtxt-weight"><?php _e($value['name'])?></td>
										<td class="tdtxt"><?php _e($game_count)?></td>
										<td class="tdtxt"><?php _e(floatval($value['gamecount']))?></td>
										<td class="tdtxt"><?php _e($value['wincont']."(".$value['y']."%)")?></td>
										<td class="tdtxt"><?php _e($total_income['total_uid'][$value['uid']][1])?></td>
										<td class="tdtxt"><?php _e(round($total_income['total_uid'][$value['uid']][1]/$value['gamecount'],2))?></td>
									</tr>
									
								<?php endforeach?>
							</tbody>
						</table>
					</div>
				<?php endif?>
				<?php if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'total'):?>
					<div id="container-timesadd" style="float: left; width: 100%;height:400px"></div>
					<div id="container-timeswinrateadd" style="float: left; width: 100%; min-width:300px;height:400px"></div>
					<div id="container-total" style="float: left; width: 100%; min-width:300px;height:400px;display: none"></div>
					<div id="container-total-avg" style="float: left; width: 100%; min-width:300px;height:400px;display: none"></div>
					<div id="container-winer" style="float: left; width: 100%; min-width:300px;height:300px;display: none"></div>
					<!-- <div id="container-pie" style="float: left; width: 100%; min-width:300px;height:300px"></div> -->
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
						<div id="container-kill" style="min-width:300;"></div>
						<?php elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'position'):?>
							<div id="container-position-rate-new" style="height:300px;margin-top: 50px;padding-top: 20px"></div>

							<div id="container-position-rate" style="height:300px;margin-top: 50px;padding-top: 20px"></div>
							<div id="container-position-lose-rate" style="height:300px;margin-top: 50px;padding-top: 20px"></div>
							<div  id="container-bubble" style="display: none; height:500px;margin-top: 50px;padding-top: 20px"></div>
							<?php elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'where'):?>
								<!-- <div style="min-width:100px;text-align: center; padding-top: 10px;float: left;width: 100%;float: left;color: #555555" id="container-test"></div> -->
								<div id="container-positon-detail" style=";height:300px;margin-top: 50px;padding-top: 20px"></div>
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
								<?php else:?>
									<div id="container" style="margin-top: 50px;padding-top: 20px;" ></div>
								<?php endif?>
								</div>	
							<?php endif?>
							<?php if(isset($if_data)):?>
								<div class="row-full-div">暂无数据，请选择其他时间段</div>
							<?php endif?>
							<div class="row-full-div">
								<div class="cell1-5-heigh-content">&nbsp</div>

								<div class="cell1-5-heigh-content" style="text-align:center;background-color: #03c4eb;">
									<a style="color: #ffffff" href="?type=total&y=2018&game_type=<?php _e($game_type)?>">全部数据</a>
								</div>
								<div class="cell1-5-heigh-content" style="text-align:center;">&nbsp</div>
								<div class="cell1-5-heigh-content" style="text-align:center;background-color: #03c4eb;">
									<a style="color: #FFFFFF" href="?type=total&y=<?php _e(date('Y'))?>&game_type=<?php _e($game_type)?>">今年数据</a>
								</div>
								<div class="cell1-5-heigh-content" style="text-align:center;">&nbsp</div>
							</div>
						</div>
						
						

					</body>
					</html>