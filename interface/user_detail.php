<?php

require_once('functions.php'); 
$rebuy_arr 	= get_rebuy_info($_REQUEST['gid'],$_REQUEST['uid']);
$player_arr = get_player();
$player_name_arr 	= creat_single_array($player_arr,'id','nickname');
$game 				= 	get_game($_REQUEST['gid']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php _e($player_name_arr[$_REQUEST['uid']])?>!</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
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
		border:1px solid;
		border-radius:5px;
		text-align: center;
		font-size: 0.5em;
		margin-right: 3px;
		background-color: #03c4eb;
		color:#fff;
		-moz-border-radius:5px; /* Old Firefox */
	}
</style>

</head>
<body>
	
	<div id="full" class="content"> <!-- 座位图 -->
		<div class="row-full">日期:<?php _e(date('Y-m-d H:i',$game->start_time))?></div>
		
		<div class="row-full"> --Rebuy List-- </div>
		<div id='player-list' class="row-full">
			<div class="cell1-5-heigh">ID</div>
			<div class="cell1-5-heigh">Chips</div>
			<div class="cell1-5-heigh">Paied</div>
			<div class="cell1-5-heigh">Killed by</div>
			<div class="cell1-5-heigh">Time</div>
		</div>
		<div class="row-full">
			<?php if(!empty($rebuy_arr)):?>
				<?php foreach ($rebuy_arr as $key => $val) :?>
					<div class="cell1-5-heigh-content"><?php _e($key+1)?></div>
					<div class="cell1-5-heigh-content"><?php _e($val->rebuy)?></div>
					<div class="cell1-5-heigh-content">￥<?php _e($val->paied)?></div>
					<div class="cell1-5-heigh-content"><?php _e($player_name_arr[$val->killed_by])?></div>
					<div class="cell1-5-heigh-content"><?php _e(date('H:i',$val->created_at))?></div>
					<?php $count_rebuy= isset($count_rebuy)?$count_rebuy+$val->rebuy:$val->rebuy?>
					<?php $count_paied= isset($count_paied)?$count_paied+$val->paied:$val->paied?>
				<?php endforeach?>
				<?php if(count($rebuy_arr)>1):?>
					<div class="cell1-5-heigh-content">总计</div>
					<div class="cell1-5-heigh-content"><?php _e($count_rebuy)?></div>
					<div class="cell1-5-heigh-content">￥<?php _e($count_paied)?></div>
					<div class="cell1-5-heigh-content"> &nbsp </div>
					<div class="cell1-5-heigh-content"> &nbsp </div>
				<?php endif?>
			<?php else:?>
				<div style="height: 50px;float: left;width: 100%;text-align: center;line-height: 50px"> No Rebuy!</div>
			<?php endif?>
		</div>
		
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
					<?php if(empty($val['reward'])):?>
						<div class="div-1-4-title">Pay out</div>
						<div class="div-1-4-content"><span>￥<?php _e(hmtl_if_negative($val['pay_out']))?></span></div>
						<?php $win="Lose: ".hmtl_if_negative(($val['bonus']+$val['pay_out']))?>
					<?php endif?>
					<?php if(!empty($val['reward'])):?>
						<div class="div-1-4-title">Extra Reward</div>
						<div class="div-1-4-content"><span>￥<?php _e($val['reward'])?></span></div>
						<?php $win="Win: ".hmtl_if_negative(($val['bonus']+$val['reward']))?>
					<?php endif?>
					<div class="row-full-win" style="font-size: 1.5em;"><?php _e($win)?></div>
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
			<?php if($game->status == 1):?>	
				<form method="post">
					<input type="hidden" name="action" value="finish">
					<input type="hidden" name="gid" value="<?php _e($_REQUEST['gid'])?>">
					<div class="row-full"><input type="submit" value="Finish"></div>
				</form>
			<?php elseif($game->status == 2):?>
				<div class="row-full">Finised</div>
			<?php endif?>

		</div>
	<?php endif?>
</body>
</html>