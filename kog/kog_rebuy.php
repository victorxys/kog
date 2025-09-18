<?php
require_once('functions.php'); 

require_once ("kog_header.php");

if (empty($_REQUEST['uid'])) {
	die("缺少UID");
}

if (isset($_REQUEST['rebuy_id'])) {
	del_rebuy_info($_REQUEST['rebuy_id']);

}

$game 			= 	get_game($_REQUEST['gid']);
$game_player 	= 	get_game_player($_REQUEST['gid']);

foreach ($game_player as $key => $value) {
	if ($value->uid == $_REQUEST['uid']) {
		continue;
	}
	$game_player_select[$key] = $value;
	$game_player_select_array[$value->uid] = $value->player;
}





?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Rebuy!</title>
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
	              	<h3 class="mb-0">Rebuyer: <?php _e( get_player($_REQUEST['uid'])[0]->nickname)?></h3>
	            </div>
	            <div class="card-body">


		<div class="col-lg-12 card-wrapper ct-example">
			<div class="form-group row" style="margin-bottom: 1rem">
				<label for="example-password-input" class="col-md-2 col-form-label form-control-label">Killed by</label>
				
				<div class="col-md-10">

					<?php erp_html_form_input( array(
                                'name'    => 'killed_by',
                                'class'   => 'form-control',
                                'type'    => 'select',
                                'id'      => 'killed_by',
                        		'required' => true,
                                'options' => array( '' => '- Select -' ) + $game_player_select_array
                            ) ); 
					?>
				</div>
			</div>

			<div class="form-group row" style="margin-bottom: 1rem">
				<label for="example-password-input" class="col-md-2 col-form-label form-control-label">Chips</label>
				
				<div class="col-md-10">
					<?php erp_html_form_input( array(
						'name'    	=> 'chips',
						'class'   	=> 'form-control',
						'type'    	=> 'text',
						'required' 	=> 'true',
						'value' 	=> $game->chips_level,
					) ); 
					?>
				</div>
			</div>

			<div class="form-group row" >
				<label for="example-password-input" class="col-md-2 col-form-label form-control-label">Paied(￥)</label>
				
				<div class="col-md-10">

					<?php erp_html_form_input( array(
						'name'    	=> 'paied',
						'class'   	=> 'form-control',
						'type'    	=> 'number',
						'placeholder'	=> '￥',
						'value'		=> $game->rebuy_rate,
						'required' 	=> 'true',
					) ); 
					?>
				</div>
			</div>
		</div>
			
			
			<div style="text-align: center;padding-top: 10px">
				<?php erp_html_form_input( array(
					'name'    	=> 'action',
							// 'help'    	=> '-',
							// 'value'   	=> isset($_REQUEST['start_time'])?$_REQUEST['start_time']:'',
					'type'    	=> 'hidden',
					'value' 	=> 'rebuy',
				) ); 
				?>
				<input type="hidden" name="uid" value="<?php _e($_REQUEST['uid'])?>">
				<input type="hidden" name="gid" value="<?php _e($_REQUEST['gid'])?>">
				<!-- <input type="submit" value="Rebuy!" class="btn btn-primary btn-lg btn-block" > -->
				<button type="submit" class="btn btn-primary btn-lg btn-block">Rebuy</button>
			</div>
	            </div>
	        </div>
    	</div>
	</div>		
	</form>
</body>
<?php
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'rebuy') {
	if(creat_rebuy_info($_REQUEST['uid'])){
		$url = "kog_detail.php?gid=".$_REQUEST['gid'];
			echo '
				<script type="text/javascript">
				document.addEventListener("DOMContentLoaded", function() {
					Swal.fire({
						title: "Success",
						text: "Let\'s all in again!",
						icon: "success",
						timer: 2000,
						timerProgressBar: true,
						showConfirmButton: false
					}).then((result) => {
						/* 定时器结束后或者用户意外关闭弹窗后都会跳转 */
						window.location.href = ' . json_encode($url) . ';
					});
				});
				</script>
			';
	}
}
if (isset($_REQUEST['rebuy_id'])) {
	
		$url = "kog_detail.php?gid=".$_REQUEST['gid'];
			echo '
				<script type="text/javascript">
				document.addEventListener("DOMContentLoaded", function() {
					Swal.fire({
						title: "Success",
						text: "Rebuy clear!",
						icon: "success",
						timer: 2000,
						timerProgressBar: true,
						showConfirmButton: false
					}).then((result) => {
						/* 定时器结束后或者用户意外关闭弹窗后都会跳转 */
						window.location.href = ' . json_encode($url) . ';
					});
				});
				</script>
			';
}
?>



</html>