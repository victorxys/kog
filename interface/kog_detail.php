
<?php

require_once('functions.php'); 

// echo "<pre>";
// var_dump ($_REQUEST);
// exit;

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
// 			'id' 		=> 	$_REQUEST['gid']
// 		);
// 		// 修改game 状态
// 		// update_some_table('kog_games',$update,$where);
// 		// header("Location: index.php"); 
// 		$url  = "index.php";
// 		echo '<script>alert("Ok Dealer 删除成功!");location.href="'.$url.'"</script>';
// 	}

// }

if (isset($_REQUEST['submit_type'])) {
	switch ($_REQUEST['submit_type']) {
		case 'Finish':
		
		if (array_sum($_REQUEST['total_fact']) != 0) {
			echo "[Get in Fact]错误，各位玩家合计应为“0”，请确认后重新输入";
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
			break;
		}
		case 'Delete':
		$rebuy 			=   get_rebuy_info($_REQUEST['gid']);
		if (!empty($rebuy)) {
			echo '<script>alert("已经有Rebuy数据了，无法删除");</script>';
		}else{
			$update = array(
				'status' 	=>	'0',
				'end_time' 	=>	time(),
			);
			$where 	= array(
				'id' 		=> 	$_REQUEST['gid']
			);
		// 修改game 状态
		// update_some_table('kog_games',$update,$where);
		// header("Location: index.php"); 
			$url  = "index.php";
			echo '<script>alert("Ok Dealer 删除成功!");location.href="'.$url.'"</script>';
		}
		break;
		default:
			# code...
		break;
	}
}

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
<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<title>Playing!</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	
	<script src="assets/js/jquery-3.2.1.min.js" ></script>
	<script src="assets/js/layer/layer.js" ></script>


	
	
	<style type="text/css">
	a{
		color: #000;
	}
	.cell1-3{
		float: left;
		width: 33%;
		text-align: center;
	}
	.row-full{
		float: left;
		width: 100%;
		padding-left: 20px;
		padding-right: 20px;
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
	.row-full-bottom{
		float: left;
		width: 100%;
		text-align: center;
		padding-bottom: 5px;
		border-bottom-style: solid;
		border-bottom-width: 1px;
		font-size: 1.5em;

	}
	bottom
	
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
	.div-tag{
		float: right;
		width: 18%;
		height: 20px;
		line-height: 20px;
		border:1px solid;
		border-radius:5px;
		text-align: center;
		font-size: 0.5em;
		margin-right: 3px;
		background-color: #03c4eb;
		color:#fff;
		-moz-border-radius:5px; /* Old Firefox */
	}
	.div{
		background: #fff;
		border: 1px solid #ccc;
		box-shadow: 0 0 0 3px #eee;
		margin: 40px auto;
		width: 40%;
		min-width: 800px;
		min-height: 500px;
		border-radius: 3px;
		padding: 0 0 30px;
		position: relative;
	}
	.div h1{
		display: block;
		font-size: 1.5em;
		-webkit-margin-before: 0.83em;
		-webkit-margin-after: 0.83em;
		-webkit-margin-start: 0px;
		-webkit-margin-end: 0px;
		font-weight: bold;
		margin: 20px;
	}
	.div2{
		padding: 20px;
		background: #fafafa;
		border: 1px solid #ddd;
		border-left: 0;
		border-right: 0;
		font-size: 14px;
		line-height: 20px;
	}
	.div3{
		padding: 20px;
		background: #2b2f3b;
		font-size: 12px;
		color:#fff;
		line-height: 25px;
	}
	input[type=button]{
		background: #4081BE;
		padding: 6px 12px;
		cursor: pointer;
		border: 0;
		color: #fff;
		border-radius: 3px;
		text-transform: uppercase;
		font-weight: bold;
		font-size: 13px;
	}
	.div3 .fff{
		color:#fff;
	}
	ul{
		position: fixed;
		top: 100px;
		left: 50px;
		z-index: 10;
		border: 2px solid #ccc;
		padding: 20px;
		border-radius: 5px;
		background-color: #fff;
		box-shadow: 1px 1px 5px #f9f9f9;
	}
	ul b{
		margin: 5px;
		width: 100%;
		display: block;
		font-size: 16px;
	}
	ul a{
		padding-left: 20px;
		color:#555;
		width: 100%;
		display: block;
		font-size: 14px;
		text-decoration:none;
		line-height: 30px;
	}
	input[type=button]:hover{
		background:#3772A8;
	}
	.yushe{
		background-color: #555!important;
		color:#fff!important;
	}
	.yushe .box-title{
		color:#fff!important;
	}
	.player-list{
		display: inline;
		padding: 5px;
	}
</style>
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
		sumValue = a-b;
		// if (isNaN(totalSum))
		// {totalSum=0}
		// totalValue= Number(a)+Number(totalSum);

		// alert(totalValue);
		self['final_chips['+uid+']'].value =sumValue;

		document.getElementById('final_chips_'+uid).innerHTML= sumValue;
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
</head>
<!-- <a type="button" id="test1" data-gid="16" name="" value="16">aaaaaaa</a> -->
<body>
	<!-- <input type="text" value="" name="ending" id="end_chips" class="div-input-small" required="required" 
	onkeyup ="value=value.replace(/[^\d\.]/g,'');SumNum(this.value);">
	<div id="final_chips">123</div> -->
	<?php require_once "kog_header.php";?>

	<div id="full" class="content" style="max-width: 600px; text-align: center;"> 
		<div class="row-full"><?php _e(game_type_array($game->game_type)."：".date('Y-m-d H:i',$game->start_time).$finished_time)?></div>
		<?php if($player_count<=4):?>
			<div><!-- 座位图 -->
				<div id="row1" class="row-full">
					<div id="cell1-3" class="cell1-3">&nbsp</div>
					<div id="cell1-3" class="cell1-3">
						<?php if(isset($game_detail_position[4]->player)):?>
							<div><?php _e($game_detail_position[4]->player)?></div>
							<div class="small-text">红椅子</div>
							<?php if($game->status == 1):?>
								<div style="line-height: 20px"><a href="<?php _e($url_rebuy.$game_detail_position[4]->uid."&gid=".$game_detail_position[4]->gid)?>">Rebuy</a></div>
							<?php endif?>
							<?php else:?>
								<div class="small-text">红椅子</div>
							<?php endif?>
						</div>
						<div id="cell1-3" class="cell1-3" style="text-align: right;">
							<img style="height: 35px" src="assets/img/arr.png">
							<span style="text-align: right;padding-right: 20px">北</span>
						</div>
					</div>
					<div id="cell1-5" class="cell1-5">
						<div id="playernone" class="row-full">&nbsp</div>
						<div id="player1" class="row-full">
							<?php if(isset($game_detail_position[3]->player)):?>
								<div style="line-height: 20px"><?php _e($game_detail_position[3]->player)?></div>
								<div class="small-text" style="line-height: 20px">黑椅子</div>
								<?php if($game->status == 1):?>
									<div style="line-height: 20px"><a href="<?php _e($url_rebuy.$game_detail_position[3]->uid."&gid=".$game_detail_position[3]->gid)?>">Rebuy</a></div>
								<?php endif?>
								<?php else:?>
									<div class="small-text" style="line-height: 20px">黑椅子</div>
								<?php endif?>
							</div>
							<div id="playernone" class="row-full">&nbsp</div>
						</div>
						<div id="cell3-5" class="cell3-5">
							<img src="assets/img/table.png">
						</div>
						<div id="cell1-5" class="cell1-5">
							<div id="playernone" class="row-full">&nbsp</div>
							<div id="player1" class="row-full">
								<?php if(isset($game_detail_position[1]->player)):?>
									<div style="line-height: 20px"><?php _e($game_detail_position[1]->player)?></div>
									<div class="small-text" style="line-height: 20px">皮椅子</div>
									<?php if($game->status == 1):?>
										<div style="line-height: 20px"><a href="<?php _e($url_rebuy.$game_detail_position[1]->uid."&gid=".$game_detail_position[1]->gid)?>">Rebuy</a></div>
									<?php endif?>
									<?php else:?>
										<div class="small-text" style="line-height: 20px">皮椅子</div>
									<?php endif?>
								</div>
								<div id="playernone" class="row-full">&nbsp</div>
							</div>
							<div id="row1" class="row-full">
								<div id="cell1-3" class="cell1-3">&nbsp</div>
								<div id="cell1-3" class="cell1-3">
									<?php if(isset($game_detail_position[2]->player)):?>
										<div><?php _e($game_detail_position[2]->player)?></div>
										<div class="small-text">黑椅子</div>
										<?php if($game->status == 1):?>
											<div style="line-height: 20px"><a href="<?php _e($url_rebuy.$game_detail_position[2]->uid."&gid=".$game_detail_position[2]->gid)?>">Rebuy</a></div>
										<?php endif?>
										<?php else:?>
											<div class="small-text">黑椅子</div>
										<?php endif?>
									</div>
									<div id="cell1-3" class="cell1-3">&nbsp</div>
								</div>
							</div> <!-- 座位图-end -->
						<?php endif?>
						<?php if($player_count > 4):?>
							<table>

								<?php foreach($game_detail_position as $key=>$val):?>
									<tr>
										<td style="border:0px;width: 33%;"><?php _e($key)?>号位</td>
										<td style="border:0px;width: 33%"><a href="<?php _e($url_rebuy.$val->uid."&gid=".$val->gid)?>"><?php _e($val->player)?></a></td>
										<td style="border:0px;"><a href="<?php _e($url_rebuy.$val->uid."&gid=".$val->gid)?>">Rebuy</a></td>
									</tr>

								<?php endforeach?>

							</table>
						<?php endif?>
						<div class="row-full"> --Rebuy List-- </div>
						<div id='player-list' class="row-full">
							<div class="cell1-5-heigh">&nbsp</div>
							<div class="cell1-5-heigh">Rebuy</div>
							<div class="cell1-5-heigh">Chips</div>
							<div class="cell1-5-heigh">Paied</div>
							<div class="cell1-5-heigh">Killed by</div>
						</div>
						<div class="row-full">
							<?php foreach ($game_detail_position as $key => $val) :?>
								<div class="cell1-5-heigh"><?php _e($val->player)?></div>
								<div class="cell1-5-heigh-content"><?php _e(isset($rebuy_real[$val->uid]['times'])?$rebuy_real[$val->uid]['times']:'--')?></div>
								<div class="cell1-5-heigh-content"><?php _e(isset($rebuy_real[$val->uid]['rebuy_chips'])?$rebuy_real[$val->uid]['rebuy_chips']:'--')?></div>
								<div class="cell1-5-heigh-content"><?php _e(isset($rebuy_real[$val->uid]['paied'])?"￥".$rebuy_real[$val->uid]['paied']:'--')?></div>
								<div class="cell1-5-heigh-content">
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
								</div>
							<?php endforeach?>
						</div>
						<form method="post">
							<div class="row-full" name="final-count"> --Final Count-- </div>
							<div id='player-list' class="row-full">
								<div class="cell1-5-heigh">&nbsp</div>
								<div class="cell1-5-heigh">Ending</div>
								<div class="cell1-5-heigh">Rebuy</div>
								<div class="cell1-5-heigh">Paied</div>
								<div class="cell1-5-heigh">Final</div>
							</div>
							<div class="row-full">
								<?php foreach ($game_detail_position as $key => $val) :?>
									<div class="cell1-5-heigh"><?php _e($val->player)?></div>
									<div class="cell1-5-heigh">
										<div class="div-input-left-end">
											<?php
											$rebuy_sum 		= isset($rebuy_real[$val->uid]['rebuy_chips'])?$rebuy_real[$val->uid]['rebuy_chips']:'0';
											$function_sum 	= "onkeyup =\"value=value.replace(/[^\d\.]/g,'');SumNum(this.value,".$rebuy_sum.",".$val->uid.");\"";
											?>
											<?php erp_html_form_input( array(
												'name'    	=> 'ending['.$val->uid.']',
												'id' 		=> 'end_chips['.$val->uid.']',
												'value'   	=> isset($_REQUEST['ending'][$val->uid])?$_REQUEST['ending'][$val->uid]:'',
												'class'   	=> 'div-input-small',
												'type'    	=> 'text',
												'function' 	=> $function_sum,
												'required' 	=> 'true',
												'readonly'	=> $disabled,
											) ); 
											?>
										</div>
									</div>
									<div class="cell1-5-heigh-content"><?php _e(isset($rebuy_real[$val->uid]['rebuy_chips'])?$rebuy_real[$val->uid]['rebuy_chips']:'--')?>
									<input type="hidden" id="rebuy[<?php _e($val->uid)?>]" name="uid[<?php _e($val->uid)?>]" value="<?php _e(isset($rebuy_real[$val->uid]['rebuy_chips'])?$rebuy_real[$val->uid]['rebuy_chips']:'0')?>">
								</div>
								<div class="cell1-5-heigh-content"><?php _e(isset($rebuy_real[$val->uid]['paied'])?"￥".$rebuy_real[$val->uid]['paied']:'--')?></div>
								<div class="cell1-5-heigh" id="final_chips_<?php _e($val->uid)?>">
									<?php _e(isset($user_final_rank[$val->uid]['count_chips'])?$user_final_rank[$val->uid]['count_chips']:0)?>
								</div>
								<input type="hidden" name="final_chips[<?php _e($val->uid)?>]" id="final_chips[<?php _e($val->uid)?>]" value="<?php _e(isset($user_final_rank[$val->uid]['count_chips'])?$user_final_rank[$val->uid]['count_chips']:0)?>">
							<?php endforeach?>
				<!-- <div class="cell1-5-heigh-content">总计</div>
					<div class="cell1-5-heigh-content" id="total_sum">0</div> -->
					<input type="hidden" name="uid" value="<?php _e($val->uid)?>">
					<input type="hidden" name="gid" value="<?php _e($_REQUEST['gid'])?>">
					<input type="hidden" name="action" value="ranking">
					<?php if(empty($cant_count)):?>
						
						<input <?php _e($disabled)?> <?php _e($onclick)?> style="width: 50%;font-size: 1em" type="submit" value="Rank!">
					<?php endif?>
					<?php _e($cant_count)?>
				</div>
				<div class="row-full-bottom"></div>
			</form>
			<?php
				// echo "<pre>";
				// var_dump($user_final_rank);
				// exit;
			?>
			<?php if(isset($show_rank)&&$show_rank=='yes'):?>
				<div class="row-full" name="final-count"> --Rank-- </div>
				<?php foreach ($user_final_rank as $key=>$val):?>
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
						<form method="post">
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
					<?php endforeach?>
					<div class="row-full-bottom">
						<?php if($game->status == 1):?>	

							<input type="hidden" name="action" value="finish">
							<input type="hidden" name="gid" value="<?php _e($_REQUEST['gid'])?>">
							<div class="div-1-2"><input type="submit" name="submit_type" value="Delete"></div>
							<div class="div-1-2"><input type="submit" name="submit_type" value="Finish"></div>


							<?php elseif($game->status == 2):?>
								<div class="row-full">Finished</div>
							<?php endif?>
						</div>
					</form>
				</div>
			<?php endif?>
			
		</body>
		</html>