
<?php

require_once('functions.php'); 
// var_dump($_REQUEST);
// 	exit;
if (isset($_REQUEST['gid']) && empty($_REQUEST['ending'])) {
	$tmp_chips = get_game_meta_by_meta_key($_REQUEST['gid'],"tmp_chips");
	if (!empty($tmp_chips)) {
		$_REQUEST['ending'] = json_decode($tmp_chips[0]['meta_value'],true);
		// $game_times = count($_REQUEST['ending']);
	}
}

$_REQUEST['action'] = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$game_times = isset($_REQUEST['game_times'])?$_REQUEST['game_times']:'1';
if (!empty($_REQUEST['ending'])) {


	if ($_REQUEST['action'] == 'again') {
		$game_times = count($_REQUEST['ending']) +1;
	}elseif ($_REQUEST['action'] == 'del') {
		$game_times = count($_REQUEST['ending']) -1;
	}else{
		$game_times = count($_REQUEST['ending']);
	}


	save_game_meta($_REQUEST['gid'],json_encode($_REQUEST['ending']));

	foreach ($_REQUEST['ending'] as $key => $value) {

		if (is_array($value)) {
			foreach ($value as $k => $val) {
				$player_ending[$k] = isset($player_ending[$k])?$player_ending[$k]+$val:$val;
				$player_game_count[$key][$k] = isset($player_count[$key][$k])?$player_count[$key][$k]+$val:$val;
			}
		}
	}
	if (is_array($player_ending)) {
		$_REQUEST['ending'] = $player_ending;
	}
}




// if(isset($_REQUEST['submit_type'])&&$_REQUEST['submit_type'] == "Delete"){
// 	$rebuy 			=   get_rebuy_info($_REQUEST['gid']);
// 	if (!empty($rebuy)) {
// 		echo '<script>alert("已经有Rebuy数据了，无法删除");</script>';
// 	}else{
// 		$update = array(
// 			'status' 	=>	'0',
// 			'end_time' 	=>	time(),
// 		);
// 		$where 	= array(
// 			'id' 		=> 	$_REQUEST['gid']a
// 		);
// 		// 修改game 状态
// 		// update_some_table('kog_games',$update,$where);
// 		// header("Location: index.php"); 
// 		$url  = "index.php";
// 		echo '<script>alert("Ok Dealer 删除成功!");location.href="'.$url.'"</script>';
// 	}

// }



$res =get_player();
$url_rebuy = "kog_rebuy.php?uid=";

if (!empty($_REQUEST['gid'])) {
	$game_detail 			=	get_game_detail($_REQUEST['gid']);
	$game 					= 	get_game($_REQUEST['gid']);
	$player_name 			= 	creat_single_array($game_detail,'uid','player');
	$player_start_chips 	= 	creat_single_array($game_detail,'uid','start_chips');
	foreach ($game_detail as $key => $value) {
		$game_detail_position[$value->seat_position] = $value;
		$game_detail_uid[$value->uid] = $value;

		if (!isset($_REQUEST['ending'][$value->uid])&&$value->end_chips != null) {
			$_REQUEST['ending'][$value->uid] = $value->end_chips;
		}

		// 如果有人工教正过的“实际支付金额”，则取这个值进行显示和后面的统计
		if (isset($value->total_fact) && $value->total_fact != null) {
			$total_fact[$value->uid] = $value->total_fact;
		}
	}			
	$player_count = count($player_name);
	ksort($game_detail_position);
	$rebuy 			=   get_rebuy_info($_REQUEST['gid']);
	// $total_arr 		= 	get_total_arr($_REQUEST['gid']);

	if (isset($rebuy)&&!empty($rebuy)) {
		foreach ($rebuy  as $key => $value) {
			
			$times[$value->uid] 						= isset($times[$value->uid])?$times[$value->uid]:0;
			$rebuy_chips[$value->uid] 					= isset($rebuy_chips[$value->uid])?$rebuy_chips[$value->uid]:0;
			$paied[$value->uid] 						= isset($paied[$value->uid])?$paied[$value->uid]:0;
			$rank_info['kill'][$value->killed_by] 		= isset($rank_info['kill'][$value->killed_by])?$rank_info['kill'][$value->killed_by]:0;
			// $killed_by 							= isset($killed_by)?$killed_by:'';	
			if (isset($value->rebuy)) {
				$times[$value->uid]++;
				$rebuy_chips[$value->uid] 				= $rebuy_chips[$value->uid] + $value->rebuy;
				$paied[$value->uid] 					= $paied[$value->uid] +	$value->paied;
			}
			$rebuy_real[$value->uid]['times'] 			= $times[$value->uid];
			$rebuy_real[$value->uid]['rebuy_chips'] 	= $rebuy_chips[$value->uid];
			$rebuy_real[$value->uid]['paied'] 			= $paied[$value->uid];
			$rebuy_real[$value->uid]['killed_by'][] 	= $value->killed_by;
			$killed_arr[$value->killed_by][]			= $value->uid;
			$rank_info['kill'][$value->killed_by]++;
			$rank_info['rebuy_chips'][$value->uid] 		= $rebuy_real[$value->uid]['rebuy_chips'];
			$rank_info['rebuy_times'][$value->uid] 		= $rebuy_real[$value->uid]['times'];
		}
		
	}
	else {
		$rebuy_real = array();
	}
}

// 获取本次的买入及rebuy 规则
$game 			=	get_game($_REQUEST['gid']);
$chips_level 	= 	$game->chips_level;
$rebuy_rate 	=	$game->rebuy_rate;
$bonus 			= 	unserialize($game->bonus);
$finished_time 	= 	isset($game->end_time)?"~".date("H:i",$game->end_time):'';
// echo "<pre>";
// var_dump($rebuy);

$disabled		= '';
$onclick 		= '';
$cant_count 	=	'';
if ($game->status == '2') {
	$disabled="disabled='disabled'";
	$onclick = "onclick=alertMsg();";
	$cant_count 	=	"It's Finished can not be count again";

}

	// 根据手中筹码量 计算 退还、及最终计算排名的筹码量
if (isset($_REQUEST['ending'])) {
// if ($_REQUEST['action'] == "ranking"){
	$user_rebuy_arr = array();
	// rebuy超过一定限额后（输的最大金额），根据总筹码量，价格超出部分以及个人盈利占比进行平分（分给赢了的），避免浪all
	// 也就是说这样就没有输和赢的上限了，只不过超过输的最大金额后，受益减少很多。
	// 所以有 extra_reward 这个数据
	$extra_reward_arr 	=	array();
	// echo "<pre>";
	foreach ($rebuy as $key => $value) {

			// echo "最开始:".$extra_reward."<br>";
			// echo "REQUEST['ending'][$value->uid]:".$_REQUEST['ending'][$value->uid]."<br>";
		$user_rebuy_arr[$value->uid]['rebuy']	=	isset($user_rebuy_arr[$value->uid]['rebuy'])?$user_rebuy_arr[$value->uid]['rebuy']:0;
		$user_rebuy_arr[$value->uid]['paied']	=	isset($user_rebuy_arr[$value->uid]['paied'])?$user_rebuy_arr[$value->uid]['paied']:0;
		$user_rebuy_arr[$value->uid]['final']	= 	isset($user_rebuy_arr[$value->uid]['final'])?$user_rebuy_arr[$value->uid]['final']:0;
		$user_rebuy_arr[$value->uid]['rebuy']	=	$user_rebuy_arr[$value->uid]['rebuy']+$value->rebuy;
		$user_rebuy_arr[$value->uid]['paied']	=	$user_rebuy_arr[$value->uid]['paied']+$value->paied;
		$user_rebuy_arr[$value->uid]['ending']	=	$_REQUEST['ending'][$value->uid];
		$user_rebuy_arr[$value->uid]['final']	=	$_REQUEST['ending'][$value->uid]-$user_rebuy_arr[$value->uid]['rebuy'];
			//根据最后手中筹码找零

			// if ($value->uid == 3) {
			// 	echo "<pre>";
			// 	var_dump($user_rebuy_arr[$value->uid]['final']);

			// }
		if ($user_rebuy_arr[$value->uid]['final'] > 0) {
			$user_rebuy_arr[$value->uid]['pay_back'] 	=	$user_rebuy_arr[$value->uid]['paied'];
		} else {
			$user_rebuy_arr[$value->uid]['pay_back']	= 	round($user_rebuy_arr[$value->uid]['ending']/$chips_level*$rebuy_rate,0);	
		}
		$user_rebuy_arr[$value->uid]['pay_out'] 		= 	$user_rebuy_arr[$value->uid]['pay_back']-$user_rebuy_arr[$value->uid]['paied'];
		$extra_reward_arr[$value->uid] 					=	abs($user_rebuy_arr[$value->uid]['pay_out']);
		$user_final_extra[$value->uid]['reward']			=	$user_rebuy_arr[$value->uid]['pay_out'];
		// if ($user_rebuy_arr[$value->uid]['pay_out'] != 0) {
		// 	update_some_table('kog_details',array('reward'=>intval($user_rebuy_arr[$value->uid]['pay_out'])),array('gid'=>$_REQUEST['gid'],'uid'=>$value->uid));
		// }
			// var_dump($user_rebuy_arr[$value->uid]['pay_out'] );
			// echo "累加后:".$extra_reward."<br>";
			// // echo "<pre>";
			// var_dump($user_rebuy_arr[$value->uid]);
			// exit;
	}
	$extra_reward = array_sum($extra_reward_arr);
	// echo "<pre>";
	// var_dump($user_rebuy_arr);
	// exit;
		// echo "extra_reward=".$extra_reward;
	foreach ($_REQUEST['ending'] as $key => $value) {
		$user_rebuy_arr[$key]['rebuy'] 	=	isset($user_rebuy_arr[$key]['rebuy'])?$user_rebuy_arr[$key]['rebuy']:0;
		$user_final_chips[$key] 		= $value - $user_rebuy_arr[$key]['rebuy'];
	}
	arsort($user_final_chips);
	if (!empty($user_final_chips)) {
		$rank 	=	0;
		unset($extra_reward_arr);
		foreach ($user_final_chips as $key => $value) {
			$rank++;
			$user_final_rank[$key]['rank'] 			= $rank;
			$user_final_rank[$key]['end_chips'] 	= $_REQUEST['ending'][$key];
			$user_final_rank[$key]['rebuy'] 		= isset($user_rebuy_arr[$key]['rebuy'])?$user_rebuy_arr[$key]['rebuy']:0;
			$user_final_rank[$key]['count_chips'] 	= $value;
			$rank_info['count_chips'][$key] 		= $value;
			$user_final_rank[$key]['paied'] 		= isset($user_rebuy_arr[$key]['paied'])?$user_rebuy_arr[$key]['paied']:0;
			$user_final_rank[$key]['pay_back']		= isset($user_rebuy_arr[$key]['pay_back'])?round($user_rebuy_arr[$key]['pay_back'],0):0;
			$user_final_rank[$key]['pay_out']		= isset($user_rebuy_arr[$key]['pay_out'])?$user_rebuy_arr[$key]['pay_out']:0;
			$user_final_rank[$key]['reward']		= isset($user_final_extra[$key]['reward'])?$user_final_extra[$key]['reward']:0;
			$user_final_rank[$key]['uid'] 			= $key;
			$user_final_rank[$key]['gid'] 			= $_REQUEST['gid'];
			$user_final_rank[$key]['bonus'] 		= $bonus[$rank];
			if ($user_final_rank[$key]['bonus'] > 0 && $value > 0) {
				$extra_reward_arr[$key] = $value;
			} else{
				$extra_reward_arr[$key] = 0;
			}
				// $extra_reward 							= $extra_reward+$user_final_rank[$key]['pay_out'];
			// echo "<pre>";
			// var_dump($user_final_rank[$key]);
			// exit;
			update_game_detail_rank($user_final_rank[$key]);
		}
		// echo "<pre>";
		// var_dump($extra_reward_arr);
		// exit;
		if (strtoupper($game->game_type) == "SNG" ) {
			foreach ($extra_reward_arr as $key => $value) {
				$user_final_rank[$key]['reward']	= round($value/array_sum($extra_reward_arr)*$extra_reward,0);
				$rank_info['final_bonus'][$key]	 	= $user_final_rank[$key]['reward'] + $user_final_rank[$key]['bonus']+$user_final_rank[$key]['pay_out'];
			// 计算后，只有更新大于0的 额外奖赏（小于零的上面已经计算过，这里这个Uid对应的 额外 reward 是0 所以会有错
				if ($user_final_rank[$key]['reward']>0) {
					update_some_table('kog_details',array('reward'=>$user_final_rank[$key]['reward']),array('gid'=>$_REQUEST['gid'],'uid'=>$key));
				}
				
			}
		}

	}
	// rank_info 数组中直接存储各个荣誉标签及排名结果，取最大值，就是第一名了，但要考虑到“并列”第一的问题（kill的数据）
	// update gamemeta for gid: kill_rank、times_rank、mnoey_rank、mvp、
	// 只有有人rebuy 才会涉及到 最佳清台、MVP等
	if (isset($rebuy)&&!empty($rebuy)) {
		$best_killer 	= array_keys($rank_info['kill'],max($rank_info['kill']));
		$rank_1 		= array_keys($rank_info['count_chips'],max($rank_info['count_chips']));
		$rebuy_chips_1 	= array_keys($rank_info['rebuy_chips'],max($rank_info['rebuy_chips']));
		$rebuy_times_1 	= array_keys($rank_info['rebuy_times'],max($rank_info['rebuy_times']));

		// 最佳清台
		
		if (count($best_killer) == 1) {
			update_gamemeta($_REQUEST['gid'],'best_killer',$best_killer[0]);
			$user_final_rank[$best_killer[0]]['best_killer'] 	= $best_killer[0];
		} else {
			// 清台最多的人中，排名最高的是 best_killer
			foreach ($rank_info['count_chips'] as $key => $value) {
				if (in_array($key, $best_killer)) {
					update_gamemeta($_REQUEST['gid'],'best_killer',$key);
					$user_final_rank[$key]['best_killer'] 		= $key;
					break;
				}
			}
		}
		// mvp 如果冠军 也是 清台最多的，那就是MVP
		if (in_array($rank_1[0], $best_killer)) {
			update_gamemeta($_REQUEST['gid'],'mvp',$rank_1[0]);
			$user_final_rank[$rank_1[0]]['mvp']		=	$rank_1[0];
		} else {
			update_gamemeta($_REQUEST['gid'],'mvp','');
		}
	}	
	$show_rank = 'yes';
}
$game_meta	= get_gamemeta($_REQUEST['gid'],null);
foreach ($game_meta as $key => $value) {
	$game_meta_uid[]=$value->meta_value;
}
?>
<?php require_once "kog_header.php";?>
<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<title>Playing!</title><!-- 
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css"> -->
	
	<script src="assets/js/jquery-3.2.1.min.js" ></script>
	<script src="assets/js/layer/layer.js" ></script>


	
	
<SCRIPT language="javascript">
	function SumNum(a,b,uid)
	{
		var sumValue
		var totalSum
		var totalValue
		// a=eval(end_chips_1.value);
		// alert('fasdf'+uid);
		// alert()
		// b=eval(rebuy.value);
		// b=100

		// totalSum = Number(document.getElementById('total_sum').innerHTML);
		if (isNaN(a))
			{a=0}
		if (isNaN(b))
			{b=0}
		sumValue = a/10;
		// if (isNaN(totalSum))
		// {totalSum=0}
		// totalValue= Number(a)+Number(totalSum);

		// alert(sumValue);
		// self['final_chips['+uid+']'].value =sumValue;
		document.getElementById('end_money['+b+']['+uid+']').value = sumValue;

		// document.getElementById('final_chips_'+uid).innerHTML= sumValue;
		// document.getElementById('total_sum').innerHTML = totalValue;
	}
	function SumChips(a,i,uid,start_chips,rebuy_sum)
	{
		var sumValue
		var totalSum
		var totalValue
		// a=eval(end_chips_1.value);
		// alert('fasdf'+uid);
		// alert()
		// b=eval(rebuy.value);
		// b=100

		// totalSum = Number(document.getElementById('total_sum').innerHTML);
		if (isNaN(a))
			{a=0}
		if (isNaN(start_chips))
			{start_chips=0}
		if (isNaN(rebuy_sum))
			{rebuy_sum=0}
		sumValue = a*10+start_chips;
		// if (isNaN(totalSum))
		// {totalSum=0}
		// totalValue= Number(a)+Number(totalSum);

		// alert('end_chips['+i+']['+uid+']');
		// self['final_chips['+uid+']'].value =sumValue; end_chips[0][1] end_chips[0][1]
		document.getElementById('end_chips['+i+']['+uid+']').value = sumValue;
		// document.getElementById('end_chips['+i+']['+uid+']').innerHTML= sumValue;
		// document.getElementById('total_sum').innerHTML = totalValue;
	}
</SCRIPT>
<script>
	$(document).ready(function(){

		$('#test1').on('click', function(){
			// layer.msg('hello');
			// alert("hello");
			var str = $('#test1').val();
			alert(str);
			layer.open({
				type: 2,
				title: 'aciton页',
				shadeClose: true,
				shade: 0.8,
				area: ['380px', '50%'],
			  	content: 'kog_detail.php?gid='+str //iframe的url
			  });

		});
	})
</script>

<?php
if (isset($_REQUEST['submit_type'])) {
	switch ($_REQUEST['submit_type']) {
		case 'Finish':
		
		if (array_sum($_REQUEST['total_fact']) != 0) {
			

			// echo "[Get in Fact]错误，各位玩家合计应为“0”，请确认后重新输入";
			echo '
				<script type="text/javascript">
					swal({
					        title:"数值错误",
					        icon:"error",
					        text: "Get in Fact 中填写的正、负合计应为0",
					    })
				</script>
			';
			break;
		}
		else{
			$update = array(
				'status' 	=>	'2',
				'end_time' 	=>	time(),
			);
			$where 	= array(
				'id' 		=> 	$_REQUEST['gid']
			);
		// 修改game 状态
			update_some_table('kog_games',$update,$where);
			if(!empty($_REQUEST['total_fact'])){
				foreach ($_REQUEST['total_fact'] as $key => $value) {
					$update_total_act = array(
						'total_fact' 	=> $_REQUEST['total_fact'][$key],
					// 'reward' 	=> 0,
						'total_should' 	=> $_REQUEST['total_should'][$key],
					);
					$where_update = array(
						'gid' 	=> $_REQUEST['gid'],
						'uid' 	=> $key,
					);
					update_some_table('kog_details',$update_total_act,$where_update);
				}
			}
			$url = "kog_detail_1b1.php?page_name=Have Fun !&gid=".$_REQUEST['gid'];
			echo '
				<script type="text/javascript">
					swal({
					        title:"Well Done",
					        icon:"success",
					        text: "See you next game!",
					    }).then(function () {
					        window.location.href = "'.$url.'"
					    })
				</script>
			';
			break;
		}
		case 'Delete':
		$rebuy 			=   get_rebuy_info($_REQUEST['gid']);
		if (!empty($rebuy)) {
			echo '<script>swal("已经有Rebuy数据了，无法删除");</script>';
		}else{
			$update = array(
				'status' 	=>	'0',
				'end_time' 	=>	time(),
			);
			$where 	= array(
				'id' 		=> 	$_REQUEST['gid']
			);
		// 修改game 状态
		update_some_table('kog_games',$update,$where);
		// header("Location: index.php"); 
			$url  = "index.php";
			echo '<script>
				swal({
					        title:"Deleted",
					        icon:"success",
					        text: "See you next game!",
					    }).then(function () {
					        window.location.href = "'.$url.'"
					    })
			</script>';

		}
		break;
		default:
			# code...
		break;
	}
}
?>
</head>
<!-- <a type="button" id="test1" data-gid="16" name="" value="16">aaaaaaa</a> -->
<body>
	<!-- <input type="text" value="" name="ending" id="end_chips" class="div-input-small" required="required" 
	onkeyup ="value=value.replace(/[^\d\.]/g,'');SumNum(this.value);">
	<div id="final_chips">123</div> -->

<div class="main-content" id="panel">
   
    <!-- Header -->
    <!-- Header -->
    
    <!-- Page content -->
    <div class="container-fluid mt--6">
    	 <div class="row-full" style="color: #ffffff;padding-bottom: 20px"><?php _e(game_type_array($game->game_type)."：".date('Y-m-d H:i',$game->start_time).$finished_time)?>
				
			</div>

    	<div class="row justify-content-center">
	        <div class="col-lg-12 card-wrapper ct-example">
				<div class="card">
        			<!-- Card header -->
			        <div class="card-header border-0">
			          <div class="row">
			            <div class="col-8">
			              <h3 class="mb-0">Seat</h3>
			            </div>
			            <div class="col-4 text-right">
		                    <a href='kog_detail.php?page_name=Have fun!&gid=<?php _e($_REQUEST['gid'])?> ' >Normal ?</a>
                      	</div>
			          </div>
			        </div>
			        <!-- Light table -->
			        <div class="table-responsive">
			          <table class="table align-items-center table-flush table-striped">
			            <thead class="thead-light">
			              <tr>
			                <th>Seat</th>
			                <th>Player</th>
			                <th>Rebuy</th>
			              </tr>
			            </thead>
			            <tbody>
			            	<?php foreach($game_detail_position as $key=>$val):?>
								<tr>
									<td class="table-user"><?php _e($key)?>号位</td>
									<td ><?php _e($val->player)?></td>
									<td class="table-actions">
						                  <a href="<?php _e($url_rebuy.$val->uid."&page_name=Rebuy&gid=".$val->gid)?>" class="table-action" data-toggle="tooltip" data-original-title="Edit product">
						                    <i class="ni ni-money-coins text-blue"></i>
						                  </a>
						                </td>
									
								</tr>
							<?php endforeach?>
			            </tbody>
			          </table>
			        </div>
			    </div>
			    <div class="card">
			    	<div class="card-header">
			    		<h3 class="mb-0">Rebuy List</h3>
			    	</div>
			    	<div class="table-responsive">
			          <table class="table align-items-center table-flush table-striped">
			            <thead class="thead-light">
			              <tr>
			                <th></th>
			                <th>Rebuy</th>
			                <th>Chips</th>
			                <!-- <th>Paied</th> -->
			                <th>Killed by</th>
			              </tr>
			            </thead>
			            <tbody>
			            	<?php foreach ($game_detail_position as $key => $val) :?>
								<tr>
									<td class="table-user"><?php _e($val->player)?></td>
									<td ><?php _e(isset($rebuy_real[$val->uid]['times'])?$rebuy_real[$val->uid]['times']:'--')?></td>
									<td ><?php _e(isset($rebuy_real[$val->uid]['rebuy_chips'])?$rebuy_real[$val->uid]['rebuy_chips']:'--')?></td>
									<!-- <td ><?php _e(isset($rebuy_real[$val->uid]['paied'])?"￥".$rebuy_real[$val->uid]['paied']:'--')?></td> -->
									<td >
										<?php
										if (isset($rebuy_real[$val->uid]['killed_by'])) {
											if (is_array($rebuy_real[$val->uid]['killed_by'])) {
												$killed_by_player 	= '';
												foreach ($rebuy_real[$val->uid]['killed_by'] as $k => $v) {
													$killed_by_player 	= isset($killed_by_player)?$killed_by_player:'';
													if (empty($killed_by_player)) {
														echo $player_name[$v];
													} else {
														echo "|".$player_name[$v];
													}

													$killed_by_player = $player_name[$v];

												}
											} else {
												echo $player_name;
											}
										} else {
											echo "--";
										}

										?>
									</td>
									
								</tr>
							<?php endforeach?>
			            </tbody>
			          </table>
			        </div>
			    </div>
			    <form method="post">
			    <!-- 同一场可以再来一局，最后一起计算，算作一局胜率 -->
			    <?php
			    	for ($i=0; $i < $game_times ; $i++) { 
			    		# code...
			    	
			    ?>
			    <div class="card"> <!--Final count card/>
        			<!-- Card header -->
			        <div class="card-header border-0">
			        	<div class="row align-items-center">
			                <div class="col-8">
			                  <!-- Title -->
			                  <h3 class="mb-0">Final Count</h3>
			                </div>
			                <div class="col-4 text-right">
			                	<!-- onclick="window.location.href='kog_detail.php?page_name=Have fun!&gid=<?php _e($_REQUEST['gid'])?>&action=again'" -->
			                  <?php
			                  	if(!isset($player_game_count[$i][$val->uid])){
			                  ?>
			                  <a href="kog_detail_1b1.php?page_name=Have fun!&gid=<?php _e($_REQUEST['gid'])?>&action=del" class="btn btn-sm btn-neutral">Delete</a>
			                  <?php
			                  	};
			                  ?>
			                </div>
		              </div>
			          
			        </div>
			        <!-- Light table -->
			        <div class="table-responsive">
			          <table class="table align-items-center table-flush table-striped">
			            <thead class="thead-light">
			              <tr>
			                <th> </th>
			                <th>Ending Chips</th>
			                <th>Money</th>
			                <th>Paied</th>
			                <th>Final</th>
			              </tr>
			            </thead>
			            <tbody>
			            	<?php foreach ($game_detail_position as $key => $val) :?>
								<tr>
									<td class="table-user"><?php _e($val->player)?></td>
									<td >
										<div class="div-input-left-end">
											<?php
											$rebuy_sum 		= isset($rebuy_real[$val->uid]['rebuy_chips'])?$rebuy_real[$val->uid]['rebuy_chips']:'0';
											$function_sum 	= "onkeyup =\"value=value.replace(/[^\d\.]/g,'');SumNum(this.value,".$i.",".$val->uid.");\"";
											?>
											<?php erp_html_form_input( array(
												'name'    	=> 'ending['.$i.']['.$val->uid.']',
												'id' 		=> 'end_chips['.$i.']['.$val->uid.']',
												'value'   	=> isset($player_game_count[$i][$val->uid])?$player_game_count[$i][$val->uid]:'',
												'class'   	=> 'form-control form-control-sm',
												'type'    	=> 'text',
												'function' 	=> $function_sum,
												'required' 	=> 'true',
												'readonly'	=> $disabled,
											) ); 
											?>
										</div>
									</td>
									<td >
										<?php 
										//_e(isset($rebuy_real[$val->uid]['rebuy_chips'])?$rebuy_real[$val->uid]['rebuy_chips']:'--')?>
										<div class="div-input-left-end">
											<?php
											$rebuy_sum 		= isset($rebuy_real[$val->uid]['rebuy_chips'])?$rebuy_real[$val->uid]['rebuy_chips']:'0';
											$function_get_chips 	= "onkeyup =\"SumChips(this.value,".$i.",".$val->uid.",".$player_start_chips[$val->uid].");\"";
											?>
											<?php erp_html_form_input( array(
												'name'    	=> 'ending_money['.$i.']['.$val->uid.']',
												'id' 		=> 'end_money['.$i.']['.$val->uid.']',
												'value'   	=> isset($player_game_count[$i][$val->uid])?($player_game_count[$i][$val->uid]-$player_start_chips[$val->uid])/10:'',
												'class'   	=> 'form-control form-control-sm',
												'type'    	=> 'text',
												'function' 	=> $function_get_chips,
												'required' 	=> 'true',
												'readonly'	=> $disabled,
											) ); 
											?>
										</div>

										<input type="hidden" id="rebuy[<?php _e($val->uid)?>]" name="uid[<?php _e($val->uid)?>]" value="<?php _e(isset($rebuy_real[$val->uid]['rebuy_chips'])?$rebuy_real[$val->uid]['rebuy_chips']:'0')?>">
									</td>
									<td ><?php _e(isset($rebuy_real[$val->uid]['paied'])?"￥".$rebuy_real[$val->uid]['paied']:'--')?></td>
									<td >
										<span id=final_chips_<?php _e($val->uid)?>></span>
										<?php _e(isset($user_final_rank[$val->uid]['count_chips'])?$user_final_rank[$val->uid]['count_chips']:0)?>
									</td>
								</tr>
								<input type="hidden" name="final_chips[<?php _e($val->uid)?>]" id="final_chips[<?php _e($val->uid)?>]" value="<?php _e(isset($user_final_rank[$val->uid]['count_chips'])?$user_final_rank[$val->uid]['count_chips']:0)?>">
							<?php endforeach?>
			            </tbody>
			          </table>
			        </div>
			    </div><!-- End Final coutn/-->
			    <?php
			    	}
			    ?>

			    	<input type="hidden" name="uid" value="<?php _e($val->uid)?>">
					<input type="hidden" name="gid" value="<?php _e($_REQUEST['gid'])?>">
					<input type="hidden" name="action" value="ranking">
					<?php if(empty($cant_count)):?>
						<div class="row row-example">
						<div class="col">
						<!-- <button onclick="window.location.href='/page2'">Continue</button> window.location.href-->
						<button onclick="window.location.href='kog_detail_1b1.php?page_name=Have fun!&gid=<?php _e($_REQUEST['gid'])?>&action=again'" type="button" class="btn btn-secondary btn-lg btn-block" <?php _e($disabled)?> <?php _e($onclick)?> type="submit" value="again">Again!</button>
					</div>
					<div class="col">
						<button type="submit" class="btn btn-primary btn-lg btn-block" <?php _e($disabled)?> <?php _e($onclick)?> type="submit" value="Rank!">Rank!</button>
						</div>
					</div>
						<!-- <input <?php _e($disabled)?> <?php _e($onclick)?> style="width: 50%;font-size: 1em" type="submit" value="Rank!"> -->
					<?php endif?>
					<?php _e($cant_count)?>
				<div class="row-full-bottom">&nbsp</div>
				</form>
				<?php if(isset($show_rank)&&$show_rank=='yes'):?>
				<form method="post">
				<?php foreach ($user_final_rank as $key=>$val):?>
				
				<?php /**
				<div class="card">
					<div class="card-header">
					13	
					</div>
					<div class="card-body">
						
						
						<div class="row-full" style="text-align: left;padding-right: 20px">#<a href="user_detail.php?uid=<?php _e($key)?>&gid=<?php _e($_REQUEST['gid'])?>"><?php _e($val['rank'].":".$player_name[$key])?></a>
								<?php if(isset($game_meta)):?>
									
									<?php foreach($game_meta as $k=>$v):?>
										<?php if($v->meta_value == $val['uid']):?>
											<div class="div-tag"><?php echo(get_honor_title_arr()[$v->meta_key])?></div>
										<?php endif?>
									<?php endforeach?>
									
								<?php endif?>
						</div>
							<div class="row-full">
								<div class="div-1-4-title">Start Chips</div>
								<div class="div-1-4-content"><?php _e($player_start_chips[$key])?></div>
								<div class="div-1-4-title">End Chips</div>
								<div class="div-1-4-content"><?php _e($val['end_chips'])?></div>
							</div>
							<div class="row-full">
								<div class="div-1-4-title">Rebuy</div>
								<div class="div-1-4-content"><?php _e($val['rebuy'])?></div>
								<div class="div-1-4-title">Cost</div>
								<div class="div-1-4-content">￥<?php _e($val['paied'])?></div>
							</div>
							<div class="row-full">
								<div class="div-1-4-title">&nbsp</div>
								<div class="div-1-4-content">&nbsp</div>
								<div class="div-1-4-title">Pay back</div>
								<div class="div-1-4-content">￥<?php _e($val['pay_back'])?></div>
							</div>
							<div class="row-full">
								Final Chips: <?php _e($val['count_chips'])?>
							</div>
							<div class="row-full"><!-- 结账金额 -->
								<div class="div-1-4-title">Bonus</div>
								<div class="div-1-4-content"><span>￥<?php _e(hmtl_if_negative($val['bonus']))?></span></div>
								<?php
								if (strtoupper($game->game_type) == "CASH" || is_null($game->game_type)) {
									if ($val['count_chips'] - $game->chips_level > 0) {
										$if_win = "Win: ";
									}else{
										$if_win = "Lose: ";
									}
									$win_total_should =$if_win.hmtl_if_negative(($val['count_chips'] - $game->chips_level)/($game->chips_level/$game->rebuy_rate));
								}
								else{
									if ($val['bonus'] >0) {
										$if_win = "Win: ";
										$win_total_should = $if_win.hmtl_if_negative(($val['bonus']+$val['reward']));
									}else{
										$if_win = "Lose: ";
										$win_total_should = $if_win.hmtl_if_negative(($val['bonus']+$val['pay_out']));
									}
								}
								?>
								<?php if($val['bonus']<=0):?>
									<div class="div-1-4-title">Pay out</div>
									<div class="div-1-4-content"><span>￥<?php _e(hmtl_if_negative($val['pay_out']))?></span></div>
									<?php $win=$win_total_should;
									$total_should = $val['bonus']+$val['pay_out'];
									?>
								<?php endif?>
								<?php if($val['bonus']>0):?>
									<div class="div-1-4-title">Extra Reward</div>
									<div class="div-1-4-content"><span>￥<?php _e($val['reward'])?></span></div>
									<?php 
									$win=$win_total_should;
									$total_should = $val['bonus']+$val['reward'];
									?>
								<?php endif?>
								<?php
								// 根据 Gametype 是现金桌CASH 就直接用筹码算，是SNG就按排位的Bouns 算
								if (strtoupper($game->game_type) == "CASH" || is_null($game->game_type)) {
									$total_should = ($val['count_chips'] - $game->chips_level)/($game->chips_level/$game->rebuy_rate);
								}
								else{

								}						
								$total_fact_view = isset($total_fact[$key])?$total_fact[$key]:$total_should;
								$total_fact_html = isset($total_fact[$key])?"<div class='row-full'>(实际：".$total_fact[$key].")</div>":'';
								?>
								<div class="div-1-4-title">&nbsp</div>
								<div class="div-1-4-content">Get in Fact</div>
								
								<div class="div-1-4-title">
									<?php erp_html_form_input( array(
										'name'    	=> 'total_fact['.$key.']',
										'id' 		=> 'total_fact['.$key.']',
										'value'   	=> $total_fact_view,
										'class'   	=> 'div-input-small',
										'type'    	=> 'text',
									// 'function' 	=> $function_sum, // 禁止输入负值
										'required' 	=> 'true',
										'readonly'	=> $disabled,
									) ); 
									?>
								</div>
								<input type="hidden" name="total_should[<?php _e($key)?>]" value ="<?php _e($total_should)?>">
								<div class="div-1-4-content">&nbsp</div>
								<div class="row-full-win" style="font-size: 1.5em;"><?php _e($win.$total_fact_html)?></div>
								<div class="row-full">
									<div class="div-1-4-title">Kill &nbsp</div>
									<div class="div-1-4-title" style="width: 35%; text-align: left;">

										<?php
										if (!empty($killed_arr[$key])) {
											echo count($killed_arr[$key])."（";
											$kill_person = "";
											foreach ($killed_arr[$key] as $k => $v) {
												$kill_person 	= empty($kill_person)?$player_name[$v]:$kill_person."、".$player_name[$v];
											}
											echo $kill_person."）";
										} else {
											echo "0";
										}
										?>
									</div>
									<div class="div-1-2" style="width: 40%; text-align:left;color: #ccc"></div>
								</div>

								<div class="row-full-bottom"></div>
							</div>
						
					</div><!-- card-body -->
				</div>
				注释用**/?>

				<div class="card card-profile">
					<div class="card-header">
						#<a href="user_detail.php?uid=<?php _e($key)?>&gid=<?php _e($_REQUEST['gid'])?>"><?php _e($val['rank'].":".$player_name[$key])?></a>
								<?php if(isset($game_meta)):?>
									
									<?php foreach($game_meta as $k=>$v):?>
										<?php if($v->meta_value == $val['uid']):?>
											<div class="div-tag"><?php echo(get_honor_title_arr()[$v->meta_key])?></div>
										<?php endif?>
									<?php endforeach?>
									
								<?php endif?>
					</div>
		            <div class="card-body pt-0">
		              <div class="row">
		                <div class="col">
		                  <div class="card-profile-stats d-flex justify-content-center">
		                    <div>
		                      <span class="heading"><?php _e($val['end_chips'])?></span>
		                      <span class="description">END</span>
		                    </div>
		                    <div>
		                      <span class="heading"><?php _e($val['rebuy'])?></span>
		                      <span class="description">Rebuy</span>
		                    </div>
		                    <div>
		                      <span class="heading">￥<?php _e($val['paied'])?></span>
		                      <span class="description">Cost</span>
		                    </div>
		                  </div>
		                  <div class="card-profile-stats d-flex justify-content-center">
		                    <div>
		                      <span class="heading">￥<?php _e($val['pay_back'])?></span>
		                      <span class="description">Pay back</span>
		                    </div>
		                    <div>
		                      <span class="heading">￥<?php _e(hmtl_if_negative($val['bonus']))?></span>
		                      <span class="description">Bonus</span>
		                    </div>

		                    	<?php
								if (strtoupper($game->game_type) == "CASH" || is_null($game->game_type)) {
									if ($val['count_chips'] - ($game->chips_level*$game_times) > 0) { // 因为引入了“次数”概念，这里乘以了一个次数，所有计算最终的时候都改成了 从$game->chips_level  ($game->chips_level*$game_times)
										$if_win = "Win: ";
									}else{
										$if_win = "Lose: ";
									}
									$win_total_should =$if_win.hmtl_if_negative(($val['count_chips'] - ($game->chips_level*$game_times))/($game->chips_level/$game->rebuy_rate));
								}
								else{
									if ($val['bonus'] >0) {
										$if_win = "Win: ";
										$win_total_should = $if_win.hmtl_if_negative(($val['bonus']+$val['reward']));
									}else{
										$if_win = "Lose: ";
										$win_total_should = $if_win.hmtl_if_negative(($val['bonus']+$val['pay_out']));
									}
								}
								?>
								<?php if($val['bonus']<=0):?>
									 <div>
				                      <span class="heading">￥<?php _e(hmtl_if_negative($val['pay_out']))?></span>
				                      <span class="description">Pay out</span>
				                    </div>
									
									<?php $win=$win_total_should;
									$total_should = $val['bonus']+$val['pay_out'];
									?>
								<?php endif?>
								<?php if($val['bonus']>0):?>
									<div>
				                      <span class="heading">￥<?php _e($val['reward'])?></span>
				                      <span class="description">Extra Reward</span>
				                    </div>
									
									<?php 
									$win=$win_total_should;
									$total_should = $val['bonus']+$val['reward'];
									?>
								<?php endif?>
								<?php
								// 根据 Gametype 是现金桌CASH 就直接用筹码算，是SNG就按排位的Bouns 算
								if (strtoupper($game->game_type) == "CASH" || is_null($game->game_type)) {
									$total_should = ($val['count_chips'] - ($game->chips_level*$game_times))/($game->chips_level/$game->rebuy_rate);
								}
								else{

								}
								$total_fact_view = isset($total_fact[$key])?$total_fact[$key]:$total_should;
								$total_fact_html = isset($total_fact[$key])?"<div class='row-full'>(实际：".$total_fact[$key].")</div>":'';
								?>

							
		                    <!-- <div>
		                      <span class="heading">￥<?php _e($val['paied'])?></span>
		                      <span class="description">Cost</span>
		                    </div> -->
		                  </div>
		                </div>
		              </div>
		              <div class="form-group">
			                  	<label class="form-control-label">Get in Fact</label>
			                  	<?php erp_html_form_input( array(
										'name'    	=> 'total_fact['.$key.']',
										'id' 		=> 'total_fact['.$key.']',
										'value'   	=> $total_fact_view,
										'class'   	=> 'form-control form-control-sm',
										'type'    	=> 'text',
									// 'function' 	=> $function_sum, // 禁止输入负值
										'required' 	=> 'true',
										'readonly'	=> $disabled,
									) ); 
									?>
			                </div>
		              <div class="text-center">
		              	<input type="hidden" name="total_should[<?php _e($key)?>]" value ="<?php _e($total_should)?>">
								
		                <h5 class="h3">
		                  <?php _e($win.$total_fact_html)?><span class="font-weight-light"></span>
		                </h5>
		                <h5 class="h3">
		                  <!-- Final Chips <span class="font-weight-light">: <?php _e($val['count_chips'])?></span> -->
		                  <?php
								if (strtoupper($game->game_type) == "CASH" || is_null($game->game_type)) {
									if ($val['count_chips'] - ($game->chips_level*$game_times) > 0) {
										$if_win = "Win: ";
									}else{
										$if_win = "Lose: ";
									}
									$win_total_should =$if_win.hmtl_if_negative(($val['count_chips'] - ($game->chips_level*$game_times))/($game->chips_level/$game->rebuy_rate));
								}
								else{
									if ($val['bonus'] >0) {
										$if_win = "Win: ";
										$win_total_should = $if_win.hmtl_if_negative(($val['bonus']+$val['reward']));
									}else{
										$if_win = "Lose: ";
										$win_total_should = $if_win.hmtl_if_negative(($val['bonus']+$val['pay_out']));
									}
								}
								?>
		                </h5>
		                <div class="h5 font-weight-300">
		                  <i class="ni location_pin mr-2">Killed:</i><?php
										if (!empty($killed_arr[$key])) {
											echo count($killed_arr[$key])."（";
											$kill_person = "";
											foreach ($killed_arr[$key] as $k => $v) {
												$kill_person 	= empty($kill_person)?$player_name[$v]:$kill_person."、".$player_name[$v];
											}
											echo $kill_person."）";
										} else {
											echo "0";
										}
										?>
		                </div>
		              </div>
		            </div>
		          </div>
						<?php endforeach?>
							<div class="row-full-bottom">
								<?php if($game->status == 1):?>	

									<input type="hidden" name="action" value="finish">
									<input type="hidden" name="gid" value="<?php _e($_REQUEST['gid'])?>">
									
									<div class="text-center">
							
											<button type="submit" class="btn btn-secondary btn-lg" name="submit_type" value="Delete">Delete</button>
										
											<button type="submit" class="btn btn-primary btn-lg" name="submit_type" value="Finish">Finish</button>
									</div>
									<div>
										&nbsp;
									</div>


								<?php elseif($game->status == 2):?>
									<div class="text-center">Finished</div>
									<div>
										&nbsp;
									</div>
								<?php endif?>
							</div>
							</form>
		
				<?php endif?>
			</div>	
		</div>
	</div>
</div>	



		

			
			
		</body>
		</html>