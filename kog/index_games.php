<?php
// echo 'Current PHP version: ' . phpversion();
// exit;
require_once('functions.php'); 
$y= isset($_REQUEST['y'])?$_REQUEST['y']:date('Y');
date_default_timezone_set("PRC");
$game_list 	=	get_game_list($y);
if (!empty($game_list)) {
	foreach ($game_list as $key => $value) {
		$game_player 	=	get_game_player($value->id);
		if (is_array($game_player) && !empty($game_player)) {
			foreach ($game_player  as $k => $val) {
				$game_player_str[$value->id] 	= isset($game_player_str[$value->id])?$game_player_str[$value->id]."、".$val->player:$val->player;

			}
		}	
	}
}
$url = "kog_detail.php?gid=";


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Game List</title>
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
	.row-full-table{
		float: left;
		width: 100%;
		text-align: center;
		padding-bottom: 5px;
		padding-left: 5px;
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
</style>

</head>
<?php require_once "kog_header.php";?>
<body>
	<div class="container-fluid mt--6">
      	<div class="row justify-content-center">
	        <div class="col-lg-12 card-wrapper ct-example">
	        	<div class="card">
	            <div class="card-header">
	              <h3 class="mb-0">Game List</h3>
	            </div>
	            <div class="table-responsive">
			        <table class="table align-items-center table-flush table-striped">
			            <thead class="thead-light">
				              <tr>
			               			<th>#</th>
			               			<th>Date</th>
									<th>Player</th>
									<th>Start</th>
									<th>Time</th>
									<th>Level</th>
				              </tr>
			            </thead>
			            <tbody>
			            	<?php foreach($game_list as $key=>$value):?>
								<?php $i = isset($i)?$i+1:1 ?>
								<?php if(isset($game_player_str[$value->id])):?>
									<tr>
										<td class="tdtxt-weight"><?php _e($i)?></td>
										<td class="tdtxt-weight"><a href="<?php _e($url.$value->id)?>"><?php _e($value->date)?></a></td>
										<td class="tdtxt"><?php _e($game_player_str[$value->id])?></td>
										<td class="tdtxt"><?php _e(date('H:i',$value->start_time).'~'.date('H:i',$value->end_time))?></td>
									
										<td class="tdtxt">
											<?php if(!empty($value->end_time)):?>
											<?php _e(round(($value->end_time-$value->start_time)/3600,2))?> H
											<?php else:?>
											未结束
										<?php endif?>
												
										</td>
										<td class="tdtxt"><?php _e($value->chips_level."/".$value->rebuy_rate)?></td>
									</tr>
									
									<?php endif?>
								<?php endforeach?>
						</tbody>
		        	</table>
			    </div>
	        </div>
    	</div>
    	
	</div>
	<div class="content">
		<div class="col text-center">
			&nbsp;
		</div>
		
	</div>
	
</body>


</html>
