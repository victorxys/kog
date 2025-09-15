<?php
require_once('functions.php'); 



if (empty($_REQUEST['uid'])) {
	die("缺少UID");
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'allin') {
	$action_group_id 	=	insert_gamemeta($_REQUEST['gid'],'action','allin');
	creat_action_info($action_group_id);
	echo "<pre>";
	var_dump($_REQUEST);
	exit;

}
$game_player 	= 	get_game_player($_REQUEST['gid']);

foreach ($game_player as $key => $value) {
	if ($value->uid == $_REQUEST['uid']) {
		continue;
	}
	$game_player_select[$key] = $value;
}





?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>All-In!</title>
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
		text-align: center;
		padding-bottom: 5px;
	}
	.row-full-heigh{
		float: left;
		width: 100%;
		text-align: center;
		padding-bottom: 5px;
		line-height: 50px;
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
	.cell1-5-heigh{
		float: left;
		width: 20%;
		line-height:30px;
	}
	.big-box{
		vertical-align: text-bottom;
		/*margin-bottom: 2px;*/
	}
	.div-called-by{
		line-height: 50px;
	}
</style>
<SCRIPT language="javascript">
	// function test(info){
	// 	// alert (info);
	// 	var d1 = document.getElementById('winner'); 
	// 	d1.insertAdjacentHTML('afterend', '<div id="two">two</div>');
	// 	'<div class="div-called-by"><input type="radio" class="big-box" value="<?php _e($value->uid)?>" name="win"><?php _e($value->player)?></div>'
	// }
</SCRIPT>
</head>
<body>
	<form method="post">
		<div class="row-full">
			<div class="row-full-heigh">
				<div class="div-left-40" style="text-align: center;">
					All-In
				</div>
				<input type="hidden" name="allin_player" value="<?php _e($_REQUEST['uid'])?>">
				<div class="div-input-left">
					<?php _e(get_player($_REQUEST['uid'])[0]->nickname)?>
				</div>
			</div>
			<div class="row-full-heigh">
				<div class="div-left-40" style="text-align: center;">
					Chips
				</div>
				<div class="div-input-left">
					<?php erp_html_form_input( array(
						'name'    	=> 'chips',
						'class'   	=> 'div-input-small',
						'type'    	=> 'text',
						'required' 	=> 'true',
					) ); 
					?>
				</div>
			</div>
			<div class="row-full-bottom"></div>
			<div class="row-full-heigh">
				<div class="div-left-40" style="text-align: center;">
					Fold
				</div>
				<div class="div-1-5" >
					<?php foreach ($game_player_select as $key => $value):?>
						<div class="div-called-by">
							<input type="radio" class="big-box" name="user_action[<?php _e($value->uid)?>]" value="fold" ><?php _e($value->player)?>
						</div>
					<?php endforeach?>
				</div>
			</div>
			<div class="row-full-bottom"></div>
			<div class="row-full-heigh">
				<div class="div-left-40" style="text-align: center;">
					Call
				</div>
				<div class="div-1-5" >

					<?php foreach ($game_player_select as $key => $value):?>
						<div class="div-called-by">
							<input type="radio" class="big-box"   name="user_action[<?php _e($value->uid)?>]" value="call"><?php _e($value->player)?>
						</div>
					<?php endforeach?>
				</div>
			</div>
			<div class="row-full-bottom"></div>
		
			<div class="row-full-heigh" style="padding-top: 20px">
				<div class="div-left-40" style="text-align: center;">
					Winner
				</div>
				<div class="div-input-left" >
					<select  name="winner">
						<option value="">Choose</option>
						<?php foreach ($game_player as $key => $value):?>
							<option value="<?php _e($value->uid)?>"><?php _e($value->player)?></option>	
						<?php endforeach?>

					</select>
				</div>

				
			</div>
			<div class="row-full-heigh">
				<div class="div-left-40" style="text-align: center;">
					Win Chips
				</div>
				<div class="div-input-left">
					<?php erp_html_form_input( array(
						'name'    	=> 'win_chips',
						'class'   	=> 'div-input-small',
						'type'    	=> 'text',
						// 'placeholder'	=> 'Win',
						'required' 	=> 'true',
					) ); 
					?>
				</div>
			</div>
			<div style="text-align: center;padding-top: 10px">
				<?php erp_html_form_input( array(
					'name'    	=> 'action',
							// 'help'    	=> '-',
							// 'value'   	=> isset($_REQUEST['start_time'])?$_REQUEST['start_time']:'',
					'type'    	=> 'hidden',
					'value' 	=> 'allin',
				) ); 
				?>
				<input type="hidden" name="uid" value="<?php _e($_REQUEST['uid'])?>">
				<input type="hidden" name="gid" value="<?php _e($_REQUEST['gid'])?>">
				<input  style="width: 50%;font-size: 1em" type="submit" value="Rebuy">
			</div>
		</div>
	</form>
</body>
</html>