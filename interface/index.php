<?php
// echo 'Current PHP version: ' . phpversion();
// exit;
require_once('functions.php'); 

$ip_duan_info = array_unique(get_ip_all_ipduan());
$ip_duan_db 	= get_ip_all_ipduan("DB");
$ip_duan_excel 	= get_ip_all_ipduan("Excel");




// echo "<pre>";
// var_dump($ip_duan_db);
// exit();



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
	.wrong{
		color: #cc0000;
	}
	.gray-bg{
		background-color: #f1f1f1;
	}
	a{
		color: #555ddd;
	}
</style>

</head>

<body>
	<div style="width: 80%;margin-left: auto;margin-right: auto;">
	<table >
		<thead>
			<td>  </td>
			<td class="gray-bg">数据库中数据 </td>
			<td >Excel中数据</td>
		</thead>
		<thead>
			<td>编号</td>
			<td class="gray-bg">IP段 </td>
			<td>IP段 </td>
			
		</thead>
		<?php foreach ($ip_duan_info as $key => $value):?>
			<?php
				$i = isset($i)?$i+1:1;
			?>
			<tr>
				<td><?php _e($i)?></td>
				<?php if (in_array($value, $ip_duan_db)):?>
					<td class="gray-bg">
					<?php if(in_array($value, $ip_duan_excel)):?>
						<a href="ip_details?ip=<?php _e($value)?>"><?php _e($value)?></a>
					<?php else:?>
						<?php _e($value)?>
					<?php endif;?>
					</td>
				<?php else:?>
					<td class="gray-bg"> <span class=wrong> 无此IP段 </span></td>
				<?php endif;?>
				<?php if (in_array($value, $ip_duan_excel)):?>
					<td ><?php _e($value)?></td>
				<?php else:?>
					<td> <span class=wrong> 无此IP段 </span></td>
				<?php endif;?>
			</tr>
		<?php endforeach;?>
	</table>
</div>
</body>
</html>