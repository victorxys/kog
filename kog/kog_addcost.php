<?php
// kog_addcost.php
require_once('functions.php'); 
// 处理 POST 请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 获取提交的数据
  $cost = $_POST['cost'];
  $memo = $_POST['memo'];
  // $gid  = $_POST['memo'];
  // 本次Game各人收入与支出
  if (!isset($memo) || empty($memo)) {
    echo "memo_blank";
    exit;
  }
  $total_income	=	get_player_total_income_stat($memo);


  if (isset($total_income['total_uid']) && is_array($total_income['total_uid'])) {
  	$total_result['sum'] = 0;
  	foreach ($total_income['total_uid'] as $key => $value) {
  		if ($value[1] >0) {
  			$total_result['sum'] += $value[1]; //总数
  		}
  	}  	
  	foreach ($total_income['total_uid'] as $key => $value) {
  		if ($value[1] >0) {
  			$total_result['rate'][$key] = round($value['1']/$total_result['sum']*$cost); //每个人赢的比例计算付出成本，直接四舍五入
        // $total_result['rate'][$key] = intval($total_result['rate'][$key]);
  		}else{
  			$total_result['rate'][$key] = 0;
  		}
  	}
  }
  $pay_info = $total_result['rate'];

  // 调用 record_cost() 函数进行处理
  $res = record_cost($memo, $cost, $pay_info);

  // echo "<pre>";
  // var_dump ($res);
  // // var_dump ($total_income['total_uid']);
  // exit;
  
  // 返回响应值
  if ($res === true) {
    echo 'success';
  }else{
    echo "error";
  }
  exit; // 终止脚本执行，确保只返回 AJAX 响应
}


?>
