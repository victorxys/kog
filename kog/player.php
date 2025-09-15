<?php

require_once('functions.php');

date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
// 获取全部Player
$players = get_player();

// echo "<pre>";
// var_dump($players);
// exit;






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
	case 'creat_player':
	if (isset($_REQUEST['newplayers']) && !empty($_REQUEST['newplayers'])) {
		creat_player($_REQUEST['newplayers']);
	}
	
		$url = "player.php?page_name=Player Set";
		// echo '<script>alert("Ok Dealer!");location.href="'.$url.'"</script>';
		echo '
			<script type="text/javascript">
				swal({
					        title:"Welcome & Have Fun",
					        icon:"success",
					        text: "Success",
					        type: "success",
					        
					    }).then(function () {
					        window.location.href = "'.$url.'"
					    })

			</script>
		';

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
              <h3 class="mb-0">Players</h3>
            </div>
          	<div class="card-body">
            	<div class="row">
		            <?php 
		            	if (empty($players)) {
		            		for ($i=1; $i <7 ; $i++) { 
		            	
		            ?>
				            <div class="col-md-12">
				              <div class="form-group">
				                <label class="form-control-label" for="example3cols1Input">Player<?php _e($i)?></label>
				                <input  type="text" class="form-control" id="example3cols1Input" name="newplayers[<?php _e($i)?>]" >

				              </div>
				            </div>

		            <?php
		            		# code...
		            		}
		            	} else{
		            		foreach ($players as $key => $value) {
		            ?>
		            		<div class="col-md-12">
				              <div class="form-group">
				               <!--  -->
				                <input disabled="disabled" type="text" class="form-control" id="example3cols1Input" name="players" value="<?php  _e($value->nickname)?>">

				              </div>
				            </div>
		            
				    <?php
		            		}
		            ?>
		            		<div class="col-md-12">
				              <div class="form-group">
				                <label class="form-control-label" for="example3cols1Input">Add New Player</label>
				                <input  type="text" class="form-control" id="example3cols1Input" name="newplayers[0]" >

				              </div>
				            </div>
		            <?php
		            	}
		            ?>
		          </div>
		           <button type="submit" name="action" value="creat_player" class="btn btn-primary btn-lg btn-block">Welcome</button>
            </div>
          	
          </div>
          
        </div>
      </div>
       
    </div>
  </div>
<!-- Footer -->
<footer class="footer pt-0">
	<div class="row align-items-center justify-content-lg-between"></div>
</footer>
</form>					
</body>

</html>