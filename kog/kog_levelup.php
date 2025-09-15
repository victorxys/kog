<?php
require_once('functions.php'); 

require_once ("kog_header.php");

if (empty($_REQUEST['gid'])) {
	die("缺少GID");
}else{
	$gid = $_REQUEST['gid'];
}
$blind_level = isset(get_game_meta_by_meta_key($gid,'blind_level')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'blind_level')['0']['meta_value']:"1";
$small_blind = isset(get_game_meta_by_meta_key($gid,'small_blind')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'small_blind')['0']['meta_value']:"5";
$big_blind = isset(get_game_meta_by_meta_key($gid,'big_blind')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'big_blind')['0']['meta_value']:"10";
$straddle = isset(get_game_meta_by_meta_key($gid,'straddle')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'straddle')['0']['meta_value']:"0";
$straddle_time = isset(get_game_meta_by_meta_key($gid,'straddle_time')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'straddle_time')['0']['meta_value']:"";

if ($_REQUEST['level_action'] == 'straddle') {
	$blind_disabled = "disabled";
	$straddle_disabled = "";
}else{
	$blind_disabled = "";
	$straddle_disabled = "disabled";
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Level Up</title>
<!-- 	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css"> -->
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
</style>
</head>
<body>
	<form method="post">
	<div class="container-fluid mt--6">
      	<div class="row justify-content-center">
	        <div class="col-lg-12 card-wrapper ct-example">
	        	<div class="card">
	            <div class="card-header">
	              	<h3 class="mb-0">Level Up: <?php _e($_REQUEST['level_action'])?></h3>
	            </div>
	            <div class="card-body">


		<div class="col-lg-12 card-wrapper ct-example">
			<div class="form-group row" >
				<label for="example-password-input" class="col-md-2 col-form-label form-control-label">Small blind</label>
				
				<div class="col-md-10">

					<input name="small_blind" type="text" <?php _e($blind_disabled)?> value="<?php _e($small_blind)?>" class="form-control" >
				</div>
			</div>
			<div class="form-group row" >
				<label for="example-password-input" class="col-md-2 col-form-label form-control-label">Big blind</label>
				
				<div class="col-md-10">

					<input name="big_blind" type="text" <?php _e($blind_disabled)?> value="<?php _e($big_blind)?>" class="form-control" >
				</div>
			</div>
			<div class="form-group row" >
				<label for="example-password-input" class="col-md-2 col-form-label form-control-label">Straddle</label>
				
				<div class="col-md-10">

					<input name="straddle" type="text" <?php _e($straddle_disabled)?> value="<?php _e($straddle)?>" class="form-control" >
				</div>
			</div>
			<div class="form-group row" >
				<label for="example-password-input" class="col-md-2 col-form-label form-control-label">Blind Level</label>
				
				<div class="col-md-10">

					<input name="blind_level" type="text" <?php _e($blind_disabled)?> value="<?php _e($blind_level)?>" class="form-control" >
				</div>
			</div>
			
		</div>
			
			
			<div style="text-align: center;padding-top: 10px">
				<?php erp_html_form_input( array(
					'name'    	=> 'action',
							// 'help'    	=> '-',
							// 'value'   	=> isset($_REQUEST['start_time'])?$_REQUEST['start_time']:'',
					'type'    	=> 'hidden',
					'value' 	=> $_REQUEST['level_action'],
				) ); 
				?>
				<input type="hidden" name="uid" value="<?php _e($_REQUEST['uid'])?>">
				<input type="hidden" name="gid" value="<?php _e($_REQUEST['gid'])?>">
				<!-- <input type="submit" value="Rebuy!" class="btn btn-primary btn-lg btn-block" > -->
				<button type="submit" class="btn btn-primary btn-lg btn-block">Level UP</button>
			</div>
	            </div>
	        </div>
    	</div>
	</div>		
	</form>
</body>
<?php

if ($straddle_time!="") {
	$straddle_time = date('H:i',$straddle_time);
}else{
	$straddle_time = "--";
}

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action'] == "straddle") {
		// 强抓
		$straddle_to = $_REQUEST['straddle'];
		update_gamemeta($gid,'straddle',$straddle_to);
		update_gamemeta($gid,'straddle_time',time());
	}elseif ($_REQUEST['level_action'] == "blind_up") {
		// 获取当前盲注级别 blind_level
		$blind_level_to = $_REQUEST['blind_level'];
		$blind_levle_n = "blind_level_".($blind_level_to);
		update_gamemeta($gid,'blind_level',$blind_level_to);
		update_gamemeta($gid,$blind_levle_n,time());
	}



	$url = "kog_detail.php?gid=".$_REQUEST['gid'];
		echo '<script>
			swal({
				        title:"Success",
				        icon:"success",
				        text: "Let\'s all in again!",
				    }).then(function () {
				        window.location.href = "'.$url.'"
				    })
		</script>';	
}
?>



</html>