<?php

require_once('functions.php');
if (!isset($_REQUEST['players'])) {
	$_REQUEST['players'] = 4;
}
date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海

// 默认类型是sng（排位奖金赛）
// echo $_REQUEST['game_type'];
$_REQUEST['game_type'] = isset($_REQUEST['game_type'])?$_REQUEST['game_type']:'CASH';
$cash_checked = "";
$sng_checked = "";
switch ($_REQUEST['game_type']) {
	case 'CASH':
		$cash_checked = "checked=''";
		break;
	case 'SNG':
	$sng_checked = "checked=''";
	break;
}
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

if ($_REQUEST['game_type'] == "ONLINE") {
	$start_chips = "200";
}else {
	$start_chips = "2000";
}

$player_array	= 	get_player();
$date_show 		= 	date('Y-m-d',time());
foreach ($player_array as $key => $value) {
	$player_name_array[$value->id] = $value->nickname;
}


$_REQUEST['action'] 	= 	isset($_REQUEST['action'])?$_REQUEST['action']:'';



?>	
<?php require_once "kog_header.php";?>
<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
<!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
<link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <!-- Argon CSS -->
  <link rel="stylesheet" href="assets/css/argon.css?v=1.1.0" type="text/css">
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
<?php
switch ($_REQUEST['action']) {
	case 'creat_game':

	if($gid = creat_game()){
		$url = "kog_detail.php?page_name=Have Fun !&gid=".$gid;
		// echo '<script>alert("Ok Dealer!");location.href="'.$url.'"</script>';
		echo '
			<script type="text/javascript">
				swal({
					        title:"Ok Let\'s All",
					        icon:"success",
					        text: "Success",
					        type: "success",
					        
					    }).then(function () {
					        window.location.href = "'.$url.'"
					    })

			</script>
		';

	}
	break;
	
	default:
		# code...
	break;
}

?>
</head>
<form method="post" >
<!-- Main content -->
  <div class="main-content" id="panel">
    
    <!-- Header -->
    <!-- Header -->
    
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row justify-content-center">
        <div class="col-lg-12 card-wrapper ct-example">
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">How Many People</h3>
            </div>
            <div class="card-body">
              <button type="button" class="btn btn-primary btn-lg" onclick="location='kog.php?players=3'" type="submit" >3 人</button>
              <button type="button" class="btn btn-primary btn-lg" onclick="location='kog.php?players=4'" type="submit" >4 人</button>
              <button type="button" class="btn btn-primary btn-lg" onclick="location='kog.php?players=5'" type="submit" >5 人</button>
              <button type="button" class="btn btn-primary btn-lg" onclick="location='kog.php?players=6'" type="submit" >6 人</button>
            </div>
          </div>
          <!-- Sizes -->
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">Game Type</h3>
            </div>
            <div class="card-body">
				<div class="custom-control custom-radio mb-3">
			            <input name="game_type" class="custom-control-input" id="customRadio5" type="radio" onclick="location='kog.php?players=<? _e($_REQUEST['players']) ?>&game_type=CASH'" value = "CASH" <?php _e($cash_checked)?>>
			            <label class="custom-control-label" for="customRadio5">CASH</label>
			    </div>
				<div class="custom-control custom-radio mb-3">
			            <input name="game_type" class="custom-control-input" id="customRadio6" type="radio" onclick="location='kog.php?players=<? _e($_REQUEST['players']) ?>&game_type=SNG'" value = "SNG" <?php _e($sng_checked)?>>
			            <label class="custom-control-label" for="customRadio6">SNG</label>
			    </div>
            </div>
          </div>
          <div class="card">
          	<div class="card-header">
              <h3 class="mb-0">Chips & Money</h3>
            </div>
          	<div class="card-body">
            	<div class="row">
		            <div class="col-md-4">
		              <div class="form-group">
		                <label class="form-control-label" for="example3cols1Input">Chips Start</label>
		                <input name="chips_start" type="number" class="form-control" id="example3cols1Input" placeholder="One of three cols" value=<?php _e(isset($_REQUEST['chips_start'])?$_REQUEST['chips_start']:$start_chips)?>>

		              </div>
		            </div>
		            <div class="col-md-4">
		              <div class="form-group">
		                <label class="form-control-label" for="example3cols2Input">RMB(￥)</label>
		                <input name="rebuy_rate" type="text" class="form-control" id="example3cols2Input" placeholder="One of three cols" value=<?php _e(isset($_REQUEST['rebuy_rate'])?$_REQUEST['rebuy_rate']:'200')?>>
		              </div>
		            </div>
		            <div class="col-md-4">
		              <div class="form-group">
		                <label class="form-control-label" for="example3cols3Input">Start </label>
		                <input name="start_time" type="text" class="form-control" id="example3cols3Input" placeholder="One of three cols" value=<?php _e(isset($_REQUEST['start_time'])?$_REQUEST['start_time']:date('H:i',time()))?>>
		              </div>
		             
		            </div>
		          </div>
            </div>
          	
          </div>
          <?php if($_REQUEST['game_type'] == 'SNG'):?>
          <div class="card">
          	<div class="card-header">
          		<h3 class="mb-0">Bonus</h3>
          	</div>
          	<div class="card-body">
					<?php 
					if (isset($_REQUEST['players']) && $_REQUEST['players']>0) {
						for ($i=1; $i <= $_REQUEST['players'] ; $i++) { 
							?>
							<div class="form-group row" >
								<label class="col-md-2 col-form-label form-control-label" for="exampleFormControlSelect1"><?php _e($i)?>th</label>
							
								<div class="col-md-10">
								<?php erp_html_form_input( array(
											'name'    	=> 'bonus['.$i.']',
											'class'		=> 'form-control',
												// 'help'    	=> '+',
												// 'value'   	=> isset($_REQUEST['start_time'])?$_REQUEST['start_time']:'',
											// 'class'   	=> 'div-input-small-50',
											'type'    	=> 'number',
											'placeholder'    	=> '￥',
											'required' 	=> 'true',
										) ); 
										?>
								</div>
							</div>
					<?php
							}
						}
					?>
			</div>
		  </div>
		  <?php endif;?>
          <div class="card">
          	<div class="card-header">
          		<h3 class="mb-0">Position</h3>
          	</div>
          	<div class="card-body">
          		<?php if($_REQUEST['game_type'] == 'SNG'):?>
					<?php 
					if (isset($_REQUEST['players']) && $_REQUEST['players']>0) {
						for ($i=1; $i <= $_REQUEST['players'] ; $i++) { 
							?>
						<div class="form-group row" >
								<label class="col-md-2 col-form-label form-control-label" for="exampleFormControlSelect1"><?php _e($i)?>号位</label>
								<div class="col-md-10">
									<?php erp_html_form_input( array(
										'name'    => 'position['.$i.']',
										'class'   => 'form-control',
										'type'    => 'select',
										'required' => true,
										'options' => array( '' => '- Select -' ) + $player_name_array
									) ); 
									?>
								</div>
						</div>
					
							<?php
						}
					}
					?>
				<?php endif?>
			
		
				<?php if($_REQUEST['game_type'] == 'CASH' || $_REQUEST['game_type'] == 'ONLINE'):?>
				<?php 
				if (isset($_REQUEST['players']) && $_REQUEST['players']>0) {
					for ($i=1; $i <= $_REQUEST['players'] ; $i++) { 
						?>
						<div class="form-group row" >
							<label class="col-md-2 col-form-label form-control-label" for="exampleFormControlSelect1"><?php _e($i)?>号位</label>
							<div class="col-md-10">
								<?php erp_html_form_input( array(
									'name'    => 'position['.$i.']',
									'class'   => 'form-control',
									'type'    => 'select',
									'required' => true,
									'options' => array( '' => '- Select -' ) + $player_name_array
								) ); 
								?>
							</div>
						</div>
						
						<?php
					}
				}
				?>
			
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
					<?php erp_html_form_input( array(
						'name'    	=> 'page_name',
									// 'help'    	=> '-',
									// 'value'   	=> isset($_REQUEST['start_time'])?$_REQUEST['start_time']:'',
						'type'    	=> 'hidden',
						'value' 	=> 'have fun !',
					) ); 
					?>
				</div>
			</div>
          </div>
        </div>
      </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">Shuffl & Deal</button>
    </div>
  </div>
<!-- Footer -->
<footer class="footer pt-0">
	<div class="row align-items-center justify-content-lg-between"></div>
</footer>
</form>					
</body>

</html>