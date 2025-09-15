<?php
// echo 'Current PHP version: ' . phpversion();
// exit;
require_once('functions.php'); 
// $ip_excel_source = get_ip_info("机房a","excel");
// $ip_db_source 	 = get_ip_info("机房a","db");

$ip_duan = $_REQUEST['ip'];
$ip_db_source 	=get_ip_by_ipduan($ip_duan,'db');

$ip_excel_source 	=get_ip_by_ipduan($ip_duan,'excel');
// echo "<pre>";
// var_dump($ip_excel_source);
// exit();
$ip_excel = get_ip_duan($ip_excel_source);
$ip_db	  =	get_ip_duan($ip_db_source);
$ip_all['db'] 		= 	$ip_db;
$ip_all['excel'] 	=	$ip_excel;
$ip_all['ip']		=	array_unique(array_merge($ip_db['all_ip'],$ip_excel['all_ip']));
foreach ($ip_all['ip'] as $key => $value) {
	$ip_all['duan'] = substr($value,0,strrpos($value,"."));
	$ip_all_duan[$ip_all['duan']][]=$value;
}

asort($ip_all['ip']);

// echo "<pre>";
// var_dump($ip_all);
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
</style>

</head>

<body>
	<?php foreach ($ip_all_duan as $k => $val):?>
		<?php
			$count_db = isset($ip_db[$k]['count'])?$ip_db[$k]['count']:"<span class=wrong> 0 </span>";
			$count_excel = isset($ip_excel[$k]['count'])?$ip_excel[$k]['count']:"<span class=wrong> 0 </span>"
		?>
	<table style="width: 95%;margin-left: auto;margin-right: auto;">
		<thead>
			<td colspan="4" class="gray-bg">数据库中数据 </td>
			<td colspan="4">Excel中数据</td>
			
		</thead>
		<thead>
			<td colspan="4" class="gray-bg">IP段：<?php _e($k)?> 数量<?php _e($count_db)?> </td>
			<td colspan="4">IP段：<?php _e($k)?> 数量<?php _e($count_excel)?> </td>
			
		</thead>
		<thead>
			<td class="gray-bg">IP</td>
			<td class="gray-bg">客户</td>
			<td class="gray-bg">是否分配</td>
			<td class="gray-bg">属性</td>
			<td>IP</td>
			<td>客户</td>
			<td>是否分配</td>
			<td>属性</td>
		</thead>

		
		<?php foreach ($val as $key => $value):?>
			<?php
				$arr_key_db = check_if_ip($value,$ip_all['db']['all_ip']);
				if ($arr_key_db != "-1") {
					$ip_ad_db 		= $ip_db_source[$arr_key_db]->IP;
					$kehu_db 		= $ip_db_source[$arr_key_db]->kehu;
					$zhuangtai_db 	= $ip_db_source[$arr_key_db]->zhuangtai;
					$other_db 		= $ip_db_source[$arr_key_db]->other;
				}else{
					$ip_ad_db		= null;	
					$kehu_db 		= null;
					$zhuangtai_db	= null;
					$other_db 		= null;
				}

				$arr_key_excel = check_if_ip($value,$ip_all['excel']['all_ip']);
				if ($arr_key_excel != "-1") {
					$ip_ad_excel 		= $ip_excel_source[$arr_key_excel]->IP;
					$kehu_excel 		= $ip_excel_source[$arr_key_excel]->kehu;
					$zhuangtai_excel 	= $ip_excel_source[$arr_key_excel]->zhuangtai;
					$other_excel 		= $ip_excel_source[$arr_key_excel]->other;
				}else{
					$ip_ad_excel 		= null;	
					$kehu_excel 		= null;	
					$zhuangtai_excel	= null;	
					$other_excel 		= null;	
				}
			?>
			<tr>
				<?php if ($arr_key_db != "-1"):?>
					<td class="gray-bg"><?php _e(check_if_same($ip_ad_db,$ip_ad_excel))?></td>
					<td class="gray-bg"><?php _e(check_if_like($kehu_db,$kehu_excel))?></td>
					<td class="gray-bg"><?php _e(check_zhuagntai($zhuangtai_db,$zhuangtai_excel))?></td>
					<td class="gray-bg"><?php _e(check_if_like($other_db,$other_excel))?></td>
				<?php else:?>
					<td class="gray-bg"> <span class=wrong> 无此IP </span></td>
					<td class="gray-bg"> </td>
					<td class="gray-bg"> </td>
					<td class="gray-bg"> </td>
				<?php endif;?>
				<?php if ($arr_key_excel!= "-1"):?>
					<td><?php _e(check_if_same($ip_ad_excel,$ip_ad_db))?></td>
					<td><?php _e(check_if_empty($kehu_excel,$kehu_db))?></td>
					<td><?php _e(check_zhuagntai($zhuangtai_excel,$zhuangtai_db))?></td>
					<td><?php _e(check_if_empty($other_excel,$other_db))?></td>
				<?php else:?>
					<td> <span class=wrong> 无此IP </span></td>
					<td> </td>
					<td> </td>
					<td> </td>
				<?php endif;?>
			</tr>
		<?php endforeach;?>
	</table>
	<?php endforeach;?>
</body>
</html>