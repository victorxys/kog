<?php

require_once('functions.php');
if (!isset($_REQUEST['players'])) {
	$_REQUEST['players'] = 4;
}
date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海

// 默认类型是sng（排位奖金赛）
$_REQUEST['game_type'] = isset($_REQUEST['game_type'])?$_REQUEST['game_type']:'CASH';

switch ($_REQUEST['players']) {
	case '4':
		// 第3、4名是扣款
	if (!empty($_REQUEST['bonus'][3])) {
		$_REQUEST['bonus'][3] =	"-".abs($_REQUEST['bonus']['3']);
	}
	if (!empty($_REQUEST['bonus'][4])) {
		$_REQUEST['bonus'][4] =	"-".abs($_REQUEST['bonus']['4']);
	}
	break;
	case '3':
	if (!empty($_REQUEST['bonus'][3])) {
		$_REQUEST['bonus'][3] =	"-".abs($_REQUEST['bonus']['3']);
	}
	break;
	case '5':
	if (!empty($_REQUEST['bonus'][3])) {
		$_REQUEST['bonus'][3] =	"-".abs($_REQUEST['bonus']['3']);
	}
	if (!empty($_REQUEST['bonus'][4])) {
		$_REQUEST['bonus'][4] =	"-".abs($_REQUEST['bonus']['4']);
	}
	break;
	default:
		# code...
	break;
}


$player_array	= 	get_player();
$date_show 		= 	date('Y-m-d',time());
foreach ($player_array as $key => $value) {
	$player_name_array[$value->id] = $value->nickname;
}


$_REQUEST['action'] 	= 	isset($_REQUEST['action'])?$_REQUEST['action']:'';

switch ($_REQUEST['action']) {
	case 'creat_game':

	if($gid = creat_game()){
		$url = "kog_detail.php?gid=".$gid;
		echo '<script>alert("Ok Dealer!");location.href="'.$url.'"</script>';
	}
	break;
	
	default:
		# code...
	break;
}

?>	
<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="style.css">
<head>

	<meta charset="UTF-8">
	<title>Creat</title>
	<style type="text/css">

	.div-center-100{
		float: left;
		width: 100%;
		text-align: center;
		padding: 5px;
	}
	.div-center-50{
		float: left;
		width: 50%;
		text-align: center;
		padding: 5px;
	}
	.input-heigh{
		height: 39px;
	}
</style>
</head>
<div class="div-center-100">
	<input class="btn-theme-primary" onclick="location='kog.php?players=3'" style="padding: 10px; width: 30%;font-size: 1em" type="submit" value="3人">
	<input class="btn-theme-primary" onclick="location='kog.php?players=4'" style="padding: 10px; width: 30%;font-size: 1em" type="submit" value="4人">
	<input class="btn-theme-primary" onclick="location='kog.php?players=5'" style="padding: 10px; width: 30%;font-size: 1em" type="submit" value="5人">	
</div>


<form method="post" >
	<div style="padding:5px">
		<div class="div-center-100" ><?php _e($date_show)?></div>
		<div class="div-center-100">
			<div style="float: left; width: 100%; padding-bottom: 30px">
				<div class="div-text-title">Game Type</div>
				<div class="div-input-left" >
					<?php erp_html_form_input( array(
						'name'    	=> 'game_type',
						'options'   => array('CASH' => "现金",'SNG' => "排名"),
						'value' 	=> isset($_REQUEST['game_type'])?$_REQUEST['game_type']:'',
						// 'class'   	=> 'div-input-small',
						'type'    	=> 'radio_group',
						'required' 	=> 'true',
						'function' 	=> 'onclick=location="kog.php?players='.$_REQUEST['players'].'&game_type=',
					) ); 
					?>
				</div>
			</div>
			<div style="float: left; width: 100%; padding-bottom: 30px">
				<div class="div-text-title">Chips Start</div>
				<div class="div-input-left">
					<?php erp_html_form_input( array(
						'name'    	=> 'chips_start',
						'value'   	=> isset($_REQUEST['chips_start'])?$_REQUEST['chips_start']:'1000',
						'class'   	=> 'div-input-small',
						'type'    	=> 'text',
						'required' 	=> 'true',
					) ); 
					?>
				</div>
			</div>
			<div style="float: left; width: 100%; padding-bottom: 30px">
				<div class="div-text-title">RMB(￥)</div>
				<div class="div-input-left">
					<?php erp_html_form_input( array(
						'name'    	=> 'rebuy_rate',
						'value'   	=> isset($_REQUEST['rebuy_rate'])?$_REQUEST['rebuy_rate']:'100',
						'class'   	=> 'div-input-small',
						'type'    	=> 'text',
						'placeholder'    	=> '￥',
						'required' 	=> 'true',
					) ); 
					?>
				</div>
			</div>
			<div style="float: left; width: 100%; padding-bottom: 30px">
				<div class="div-text-title">Start Time</div>
				<div class="div-input-left">
					<?php erp_html_form_input( array(
						'name'    	=> 'start_time',
						'value'   	=> isset($_REQUEST['start_time'])?$_REQUEST['start_time']:date('H:i',time()),
						'class'   	=> 'div-input-small',
						'type'    	=> 'text',
						'placeholder'    	=> 'hh:mm',
						'required' 	=> 'true',
					) ); 
					?>
				</div>
			</div>
			

			
		</div>
		
		<?php if($_REQUEST['game_type'] == 'SNG'):?>
			<div class="div-center-50">
				<div class="div-center-100">
					Position
				</div>
				<?php 
				if (isset($_REQUEST['players']) && $_REQUEST['players']>0) {
					for ($i=1; $i <= $_REQUEST['players'] ; $i++) { 
						?>
						<div class="div-quters-left" style="line-height: 34px"><?php _e($i)?>号位</div>
						<div class="div-quters-text-left">
							<?php erp_html_form_input( array(
								'name'    => 'position['.$i.']',
								'class'   => 'erp-hrm-select2',
								'type'    => 'select',
								'required' => true,
								'options' => array( '' => '- Select -' ) + $player_name_array
							) ); 
							?>
						</div>
						<?php
					}
				}
				?>
			</div>

			<div class="div-center-50">
				<div class="div-center-100">
					Bonus
				</div>
				<?php 
				if (isset($_REQUEST['players']) && $_REQUEST['players']>0) {
					for ($i=1; $i <= $_REQUEST['players'] ; $i++) { 
						?>
						<div class="div-quters-left" style="line-height: 34px"><?php _e($i)?>th</div>
						<div class="div-quters-text-left" style="line-height: 34px">
							<?php erp_html_form_input( array(
								'name'    	=> 'bonus['.$i.']',
								'class'		=> 'input-heigh',
									// 'help'    	=> '+',
									// 'value'   	=> isset($_REQUEST['start_time'])?$_REQUEST['start_time']:'',
								// 'class'   	=> 'div-input-small-50',
								'type'    	=> 'text',
								'placeholder'    	=> '￥',
								'required' 	=> 'true',
							) ); 
							?>
						</div>
						<?php
					}
				}
				?>
			</div>
		<?php endif?>
		<?php if($_REQUEST['game_type'] == 'CASH'):?>
			<div class="div-center-100">
				<div class="div-center-100">
					Position
				</div>
				<?php 
				if (isset($_REQUEST['players']) && $_REQUEST['players']>0) {
					for ($i=1; $i <= $_REQUEST['players'] ; $i++) { 
						?>
						<div class="div-quters-left" style="line-height: 34px"><?php _e($i)?>号位</div>
						<div class="div-quters-text-left">
							<?php erp_html_form_input( array(
								'name'    => 'position['.$i.']',
								'class'   => 'erp-hrm-select2',
								'type'    => 'select',
								'required' => true,
								'options' => array( '' => '- Select -' ) + $player_name_array
							) ); 
							?>
						</div>
						<?php
					}
				}
				?>
			</div>
		<?php endif?>
		<div style="text-align: center;padding-top: 10px">
			<?php erp_html_form_input( array(
				'name'    	=> 'action',
							// 'help'    	=> '-',
							// 'value'   	=> isset($_REQUEST['start_time'])?$_REQUEST['start_time']:'',
				'type'    	=> 'hidden',
				'value' 	=> 'creat_game',
			) ); 
			?>
			<input  style="width: 50%;font-size: 1em" type="submit" value="Shuffl & Deal">
		</div>
	</div>
</form>					
</body>
</html>