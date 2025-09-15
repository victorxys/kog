<?php
// echo 'Current PHP version: ' . phpversion();
// exit;
require_once('functions.php'); 
$y= isset($_REQUEST['y'])?$_REQUEST['y']:date('Y');
date_default_timezone_set("PRC");
$game_year_list = get_game_history_year();
$this_year = date('Y');
$game_list 	=	get_game_list($y);
$game_memo_list 	=	get_game_memo_list($y);
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

// 标签样式数组 
$span_style = array(
	"default",
	"primary",
	"secondary",
	"info",
	"success",
	"danger",
	"warning"
	);
if (isset($game_memo_list['memo_total']['duration']) && is_array($game_memo_list['memo_total']['duration'])){
	krsort($game_memo_list['memo_total']['duration']);
}else{
	// echo "暂无今年数据，请新建游戏";
}

// echo "<pre>";
// var_dump($game_memo_list['memo_total']);
// exit;
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
<script type="text/javascript">
	function startYearChange(value) {
		//判断是否第一次进入？
		if (location.href.indexOf('?') == -1) {
			window.location.href = location.href + "?status=" + value;
		} else {
			var prefix = location.href.split('&y')[0];//切分url并确保前缀，然后在前缀基础上拼接当前的地址
			var url = location.href
			if (value == -1) {
				window.location.href = prefix;
			} else {
				if (value == 'all'){
					window.location.href = prefix + "&y=all&end_y=no";
				}else{
					window.location.href = prefix + "&y=" + value ;
					
				}
				// window.location.href = url + "&game_type=" + value;
			}
		}
	}
</script>
</head>
<?php require_once "kog_header.php";?>
<body>
	<div class="container-fluid mt--6">
      	<div class="row justify-content-center">
	        <div class="col-lg-12 card-wrapper ct-example">
	        	<div class="card">
	            <div class="card-header">
			              <div class="row align-items-center">
				                <div class="col-7">
				                  <!-- Title -->
				                  <h5 class="h3 mb-0">Game List</h5>
				                </div>
				                 <div class="col-5 text-right">
				                  <select class="form-control" id="start_year" name="start_year" onchange="startYearChange(this.value)">
				                  	<option select="selected" value=<?php _e($this_year)?>>今年</option>
									<?php foreach ($game_year_list as $key => $value): ?>
										<option <?php _e(test_js($value))?> value=<?php _e($value)?> ><?php _e($value)?></option>
									<?php endforeach ?>	
									<option <?php _e(test_js('all'))?> value="all">全部</option>		                    
				                    </select>		                  
				                </div>
			              </div>
		            </div>
	            <div class="table-responsive">
			        <table class="table align-items-center table-flush table-striped">
			            <thead class="thead-light">
				              <tr>
			               			<th>#</th>
			               			<th>Date</th>
									<th>Games</th>
									<th>Result</th>
									<th>Duration</th>
				              </tr>
			            </thead>
			            <tbody>
			            	<?php if (isset($game_memo_list['memo_total']['duration']) && is_array($game_memo_list['memo_total']['duration'])): ?>
				            	<?php foreach($game_memo_list['memo_total']['duration'] as $key=>$value):?>
									<?php $i = isset($i)?$i+1:1 ?>
									
										<tr>
											<td class="tdtxt-weight"><?php _e($i)?></td>
											<td class="tdtxt-weight"><a href=kog_summary.php?memo=<?php _e($key)?>><?php _e($key)?></a></td>
											<td class="tdtxt"><?php _e($game_memo_list['memo_total']['count_games'][$key])?></td>
											<td class="tdtxt" style="text-align:left;">
												
												<?php 
												$cost = get_cost($key,null,'total');
												if (!empty($cost) && isset($cost)) {
													echo "<span  class='badge badge-pill badge-default'>
								                    	成本:".$cost."
								                    </span>";
												}
												
												foreach($game_memo_list['memo_total']['player_info'][$key] as $k=>$val){
													if ($val->total_fact >=0) {
														echo "
														<span class='badge badge-pill badge-".$span_style[4]."'>".$val->player.":".$val->total_fact."</span>
														";
													}else{
														echo "
														<span class='badge badge-pill badge-".$span_style[5]."'>".$val->player.":".$val->total_fact."</span>
														";

													}
													
													// $game_player_res[$key] = isset($game_player_res[$key])?$game_player_res[$key]." ".$val->player." : $".$val->total_fact:$val->player.": $".$val->total_fact;
												}
												
												?>
												
												
											</td>
											<td class="tdtxt">
											<?php 
											$duration = $value/3600;
											if ($duration >= 1) {
												$duration = round(($value/3600),1)." H";
											}else{
												$duration = round(($value/60),1)." Min";
											}
											echo $duration;

											?></td>
										</tr>
										
										
								<?php endforeach?>
							<?php endif ?>
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
