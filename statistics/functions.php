<?php
// define('PATH', dirname(dirname(__FILE__)).'/');
// date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
ini_set('date.timezone','Asia/Shanghai'); 

define('PATH', dirname(dirname(__FILE__)).'/');


require_once(PATH . 'wp-blog-header.php'); 

// get_header('no');
error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * 调试区域，调用本页函数
 */

// get_count_date('11','22');
// get_days_bettwen();

// get_stat_info(1);
// get_stat_list(1);
// get_people_by_cid(1);
// stat_info_people_cid("1","2020-03-04");

// 获取今天已填写数据
function get_today_info_id_num($id_num){
	global $wpdb;
	$today = date("Y-m-d",time());
	$sql = "select * from stat_info where id_num = '".$id_num."' and creatdate = '".$today."'";
	$res = $wpdb->get_row($sql);
	return $res;
}

// 显示层判断如何显示数据
function check_view($str){
	if (isset($str)) {
		return $str;
	}else{
		return "<font color=red>尚未填写</font>";
	}
}

//计算时间天数间隔（隔离第几天）
function count_days($today,$start_date){
	$count_days = intval(strtotime($today) - strtotime($start_date))/86400 +1 ;
	return $count_days;
}

//本社区近日填表情况
function stat_info_people_cid($c_id,$today){
	global $wpdb;
	// 获取今日已填表人员
	$sql = "select * from stat_info where c_id = '".$c_id."' and creatdate like '".$today."%'";
	$today_stat = $wpdb->get_results($sql);
	foreach ($today_stat as $key => $value) {
		$stat_arr[$value->id_num] = $value;
	}
	// echo "<pre>";
	// var_dump($stat_arr);
	// exit;

	// 获取本小区全部人员（已填表的全部人员）id,截止当前日期已隔离人数
	$id_arr = get_people_by_cid($c_id,$today);
	foreach ($id_arr as $key => $value) {
		if (isset($stat_arr[$value->id_num])) {
			$people_stat_today[$value->id_num] = $stat_arr[$value->id_num];
		}else{
			$people_stat_today[$value->id_num] = new \stdClass();
			$people_stat_today[$value->id_num]->id_num 	= $value->id_num;
			$people_stat_today[$value->id_num]->name = $value->name;
			$people_stat_today[$value->id_num]->tmp = "<font color=red>尚未填写</font>";
			$people_stat_today[$value->id_num]->tmp_2 = "<font color=red>尚未填写</font>";
			$people_stat_today[$value->id_num]->chest = '';
			$people_stat_today[$value->id_num]->cough = '';
			$people_stat_today[$value->id_num]->creatdate = '';
			$people_stat_today[$value->id_num]->start_date = $value->start_date;
			$people_stat_today[$value->id_num]->countdate = count_days($today,$value->start_date);
		}
		
	}
	return $people_stat_today;
	echo "<pre>";
	var_dump($people_stat_today);
	exit;
}

//获取本社区所有已填写人员 并且尚未完成隔离的人
function get_people_by_cid($c_id,$today){
	global $wpdb;
	// 获取14天前开始填写数据的人数（身份证为准）
	$sql = "SELECT id_num,name,c_id,start_date FROM stat_info where c_id = '".$c_id."' and start_date >=  DATE_ADD('".$today."',INTERVAL -14 DAY) AND start_date <= '".$today."' GROUP BY id_num,name,c_id,start_date";
	$res = $wpdb->get_results($sql);

	
	
	return $res;
	echo "<pre>";
	var_dump($sql);
	exit;
}

function get_stat_list($c_id){
	global $wpdb;
	//$sql = "SELECT creatdate,count(creatdate) as count_people FROM stat_info where c_id = '".$c_id."' GROUP BY creatdate ";
	$sql = "select creatdate, count(creatdate) as count_people from (select distinct creatdate, id_num from stat_info where c_id = '".$c_id."') AS A  GROUP BY creatdate;";
	$res = $wpdb->get_results($sql);
	$sql_last_update = "select creatdate from stat_info where c_id=".$c_id." order by creatdate asc";
	$res_last_update = $wpdb->get_var($sql_last_update);
	$first_update = date("Y-m-d",strtotime($res_last_update));
	foreach ($res as $key => $value) {
		$value->creatdate = date("Y-m-d",strtotime($value->creatdate));
		$value->today_all_people = count(get_people_by_cid($c_id,$value->creatdate));
	}
	
	return $res;
	echo "<pre>";
	var_dump($res);
	exit;
}

function get_stat_info($c_id,$date=null){
	global $wpdb;
	$sql = "select * from stat_info where c_id = ".$c_id." and creatdate like '".$date."%'";
	$res = $wpdb->get_results($sql);
	return $res;
}

function get_c_name($c_id){
	global $wpdb;
	$sql = "select c_name from stat_cname where ID  = ".$c_id;
	$res = $wpdb->get_var($sql);
	return $res;
}

function get_days_bettwen($start_day){
	$start_times = strtotime($start_day);
	// $now = time();
	$now = strtotime(date("Y-m-d",time()));
	$days = intval(($now - $start_times)/86400) +1 ;// 要算上当天，所以再+1
	return $days;
}

// 获取某人 第一天与已隔离天数
function get_count_date($name,$id_num){
	global $wpdb;
	$sql = "select creatdate from stat_info where 
	name = '".$name."' and 
	id_num = '".$id_num."'
	order by creatdate asc
	";
	$res = $wpdb->get_var($sql);


	$days_arr = array();
	if ($res != null) {
		$days_arr['first_day'] = $res;
		$days_arr['count_day'] = get_days_bettwen($res);

	}else{
		
		$days_arr['first_day'] = date("Y-m-d");
		$days_arr['count_day'] = 1;
	}	
	// var_dump($days_arr);
	return $days_arr;
}



function insert_stat_info($stat_info){
	global $wpdb;
	$creatdate = date("Y-m-d");
	$days_arr = get_count_date($stat_info['name'],$stat_info['id_num']);
	$start_date = $days_arr['first_day'];
	$countdate = $days_arr['count_day'];
	$update_tmp = "";
	if (!empty($stat_info['tmp'])) {
		$update_tmp .= "tmp = '".$stat_info['tmp']."'";
	}
	if (!empty($stat_info['tmp_2'])) {
		if (!empty($update_tmp)) {
			$update_tmp .=" ,";
		}
		$update_tmp .= "tmp_2 = '".$stat_info['tmp_2']."'";
	}
	// $tmp = !empty($stat_info['tmp'])?$stat_info['tmp']:'';
	// $tmp_2 = !empty($stat_info['tmp_2'])?$stat_info['tmp_2']:'';
	if ($countdate == 14) {
		echo "<script type='text/javascript'>alert('隔离已满14天，无需继续填写')</script>";
	}
	// $sql = "insert ignore into tsm_sellinfo ".$sql_col." values ".$sql_str;
	$sql = "insert ignore into stat_info (name,id_num,tmp,tmp_2,creatdate,countdate,c_id,start_date) values "."(
		'".$stat_info['name']."',
		'".$stat_info['id_num']."',
		'".$stat_info['tmp']."',
		'".$stat_info['tmp_2']."',
		'".$creatdate."',
		'".$countdate."',
		'".$stat_info['c_id']."',
		'".$start_date."'
		)";
	$res = $wpdb->query($sql);
	if ($res) {
		echo "<script type='text/javascript'>alert('填写成功，如多次提交已最后一次为准')</script>";
	}else{
		$sql = "update stat_info set " . $update_tmp. "  
		where 
			id_num = '".$stat_info['id_num']."'
		and creatdate = '".$creatdate."'
		";
		// echo $sql;
		$wpdb->query($sql);
		echo "<script type='text/javascript'>alert('更新成功')</script>";
	}
}


// count_person_position_win();
// get_m2();

function m2_num($n){
	$n = intval($n);
	if ($n >= 10 && $n<=99 ) {
		$n10 = intval($n/10); //获得十位数
		$n10_2 = $n10%2;	//十位的模
		$n1  = $n%10;
		$n1_2  = $n1%2;	
		return $n10_2.$n1_2;	
	}else if ($n>= 100 && $n<=999) {
		$n100 = intval($n/100); //获得百位数
		$n100_2 = $n100%2;
		$n10 = intval($n/10); //获得十位数
		$n10_2 = $n10%2;	//十位的模
		$n1  = $n%10;
		$n1_2  = $n1%2;	
		return $n100_2.$n10_2.$n1_2;
	}
	else{
		return false;
	}
}

function get_m2(){
	echo "23的模二数 =". m2_num(23)."<br>";
	$m = 0;
	for ($i=10; $i <100 ; $i++) { 
		$m2_i = m2_num($i);
		// echo $i."	的二模数是：".$m2_i;
		echo "<br>";
		// M2 之和
		// $m2_count = m2_num(23)+m2_num($i);
		$m2_count = decbin(bindec(m2_num(23))+bindec(m2_num($i)));
		// 和的M2
		$count_m2 = m2_num(23+$i);
		if ($m2_count == $count_m2) {
			$m++;
			echo "23与".$i."的<br>[模二数之和]:".m2_num(23)."+".m2_num($i)."=".$m2_count;
			echo "<br>";
			echo "[和之模二数]:23+".$i."=".(23+$i)."(".$count_m2.")";
			echo "<br>===第".$m."个===<br>";
			
		}
		else{
			echo "23与".$i."的<br>[模二数之和]:".m2_num(23)."+".m2_num($i)."=".$m2_count;
			echo "<br>";
			echo "[和之模二数]:23+".$i."=".(23+$i)."(".$count_m2.")";
			echo "<br>===<font color='red'>不相等</font>===<br>";
		}
	}

}

// 获取 favorite
function get_favorite(){
	global $wpdb;
	$sql = "SELECT
			tsm_itemname.`Name`,tsm_itemname.`ID`,tsm_favorite.`memo`
			FROM
			tsm_favorite
			JOIN tsm_itemname
			ON tsm_favorite.itemid = tsm_itemname.ID
			WHERE tsm_favorite.isdel is NULL
			ORDER BY
			tsm_favorite.`order` ASC
			";
	$res = $wpdb->get_results($sql);

	return $res;
}





/**
 * 批量更新函数
 * @param $data array 待更新的数据，二维数组格式
 * @param array $params array 值相同的条件，键值对应的一维数组
 * @param string $field string 值不同的条件，默认为id
 * @return bool|string
 */
function batchUpdate($data, $field, $params = [])
{
   if (!is_array($data) || !$field || !is_array($params)) {
      return false;
   }

    $updates = parseUpdate($data, $field);
    $where = parseParams($params);

    // 获取所有键名为$field列的值，值两边加上单引号，保存在$fields数组中
    // array_column()函数需要PHP5.5.0+，如果小于这个版本，可以自己实现，
    // 参考地址：http://php.net/manual/zh/function.array-column.php#118831
    $fields = array_column($data, $field);
    $fields = implode(',', array_map(function($value) {
        return "'".$value."'";
    }, $fields));

    $sql = sprintf("UPDATE `%s` SET %s WHERE `%s` IN (%s) %s", 'tsm_itemname', $updates, $field, $fields, $where);

   return $sql;
}


/**
 * 数组 转 对象
 *
 * @param array $arr 数组
 * @return object
 */
function array_to_object($arr) {
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)array_to_object($v);
        }
    }
 
    return (object)$arr;
}


/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function object_to_array($obj) {
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    }
 
    return $obj;
}

/**
 * 将二维数组转换成CASE WHEN THEN的批量更新条件
 * @param $data array 二维数组
 * @param $field string 列名
 * @return string sql语句
 */
function parseUpdate($data, $field)
{
    $sql = '';

    $keys = array_keys(current($data));
    foreach ($keys as $column) {

        $sql .= sprintf("`%s` = CASE `%s` \n", $column, $field);
        foreach ($data as $line) {
            $sql .= sprintf("WHEN '%s' THEN '%s' \n", $line[$field], $line[$column]);
        }
        $sql .= "END,";
    }

    return rtrim($sql, ',');
}

/**
 * 解析where条件
 * @param $params
 * @return array|string
 */
function parseParams($params)
{
   $where = [];
   foreach ($params as $key => $value) {
      $where[] = sprintf("`%s` = '%s'", $key, $value);
   }
   
   return $where ? ' AND ' . implode(' AND ', $where) : '';
}

//========end

// 格式化输出
function prt_n($value,$decimal=2){
	if ($value >=100 && $value <10000) {
		$division = 100;
		$units = "S";
	}elseif ($value >= 10000 ) {
		$division = 10000;
		$units = "G";
	}else{
		$division = 1;
		$units = "C";
	}
	// if ($unit == 1) {
	// 	$unit = $units;
	// }else{
	// 	$unit = "";
	// }
	$res = round($value/$division,$decimal).$units;
	return $res;
}
function prt_pcnt($value,$decimal=2){
	return round($value*100,$decimal)."%";
}

// 排除物品清单
function get_ignore(){
	global $wpdb;
	$sql = "SELECT
			itemid
			FROM
			tsm_ignore
			ORDER BY
			tsm_ignore.`order` ASC
			";
	$res = $wpdb->get_col($sql);
	return $res;
}

// Invest 建议
function get_invest_advance($invest = array()){
	global $wpdb;
	$defult_arr = array(
		'values_ave' => '100000', // 平均市值 10G
		'market_ave' => '1000', // 平均市场价 10 S
		'min_ave' => '1000', // 平均最低价 10 S
		'quantity' => '10', // 今日总数量 10
		'markvalues_division_ave' => 0.9, // 市场总值 比 市场均值
		'min_mkt_scale' => 0.8, 		// 最小价 比 市场价
		'min_division_ave_min' => 0.8, 		// 最小值与最小均值 比（下限）——买入
		'min_division_ave_max' => 1.2, 		// 最小值与最小均值 比（上限）——卖出
		'Name' => array('卷轴','绷带'),
	);
	$invest = array_merge($defult_arr);
	// echo "<pre>";
	// var_dump($invest);
	// exit;
	// min(平均)/min < 0.8
	// $invest_reason_2 = 'minBuyout/';
	$lastscan = get_tsm_scat_update();
	$sql = "SELECT
				*,
				( marketValue * quantity ) AS `all_values` ,
				( minBuyout/min_ave) AS `min2ave`
			FROM
				tsm_auction_anly 
			WHERE
				1 = 1				
				AND lastScan >= '".$lastscan."' 
				";
	// 平均总市值 > 10G
	$sql .= " AND values_ave > ".$invest['values_ave'];
	// 平均市场价 > 10S
	$sql .= " AND market_ave > ".$invest['market_ave'];
	// 平均最低价 > 10S
	$sql .= " AND min_ave > ".$invest['min_ave'];
	// 今日总数量 > 10 个
	$sql .=" AND quantity > ".$invest['quantity'];
	// 当前市值低于平均市值 50%
	$sql .= " AND markvalues_division_ave < ".$invest['markvalues_division_ave'];
	// 今日最小价 小于 今日市场价 的 80%
	$sql .= " AND min_mkt_scale < ".$invest['min_mkt_scale'];

	$sql .= " AND ( minBuyout/min_ave <".$invest['min_division_ave_min']." or minBuyout/min_ave >".$invest['min_division_ave_max'].")";
	// 按名称过滤干扰项
	if (is_array($invest['Name'])) {
		$sql .= " AND (";
		foreach ($invest['Name'] as $key => $value) {
			$sql .= " `Name` NOT LIKE '%".$value."%' AND";
		}
		$sql .= " 2=2)";
	}
	$ignore = get_ignore();
	if (is_array($ignore)) {
		$sql .= " AND itemString not in (";
		foreach ($ignore as $key => $value) {
			$sql .= $value.",";
		}
		$sql .= "0)";
	}
	
	$sql .= " ORDER BY `min2ave`";
	// $sql .= " AND (`Name` NOT LIKE '%卷轴%' AND `Name` NOT LIKE '%绷带%')";

	$res = $wpdb->get_results($sql);
	if (empty($res)) {
		$res = "暂无数据,请修改匹配条件";
	}
	return $res;
}
// insert_market_min_ave();
// 批量更新所有产品的当前市场平均值、最小值平均值
function insert_market_min_ave (){
	global $wpdb;
	$sql = "select * from tsm_auction_db where quantity >10";
	$res = $wpdb->get_results($sql);
	$update_array = array();
	foreach ($res as $key => $value) {
		// 每日最小值累加
		$item_info_array[$value->itemString]['min_sum'] = isset($item_info_array[$value->itemString]['min_sum'])?$item_info_array[$value->itemString]['min_sum']+$value->minBuyout:$value->minBuyout;
		// 每日市场价累加
		$item_info_array[$value->itemString]['market_sum'] = isset($item_info_array[$value->itemString]['market_sum'])?$item_info_array[$value->itemString]['market_sum']+$value->marketValue:$value->marketValue; 
		// 每日数量累加
		$item_info_array[$value->itemString]['quantity_sum'] = isset($item_info_array[$value->itemString]['quantity_sum'])?$item_info_array[$value->itemString]['quantity_sum']+$value->quantity:$value->quantity; 
		// 每日市值计算并累加
		$item_info_array[$value->itemString]['values_sum'] = isset($item_info_array[$value->itemString]['values_sum'])?$item_info_array[$value->itemString]['values_sum']+($value->marketValue*$value->quantity):$value->marketValue*$value->quantity; 
		// 每日堆叠数累加
		$item_info_array[$value->itemString]['num_sum'] = isset($item_info_array[$value->itemString]['num_sum'])?$item_info_array[$value->itemString]['num_sum']+$value->numAuctions:$value->numAuctions; 
		// 一共的天数
		$item_info_array[$value->itemString]['count'] = isset($item_info_array[$value->itemString]['count'])?$item_info_array[$value->itemString]['count']+1:1;
	}
	foreach ($item_info_array as $key => $value) {
		$update_array[] =array(
			'ID' => $key,
			'min_ave' => $value['min_sum']/$value['count'],
			'market_ave' => $value['market_sum']/$value['count'],
			'values_ave' => $value['values_sum']/$value['count'],
			'num_ave' => $value['num_sum']/$value['count'],
			'quantity_ave' => $value['quantity_sum']/$value['count']
		);
	}
			// echo "<pre>";
			// var_dump($update_array);
			$sql_update_all = batchUpdate($update_array,'ID');
			$res = $wpdb->query($sql_update_all);
			if ($res) {
				return true;
			}else{
				return false;
			}
			// exit;
			
	// var_dump($update_array);
	// exit;
	  	
	
}

// get_market_min_com();
// 搜索当前偏离过高的
function get_market_min_com($scale = null){
	global $wpdb;
	if ($scale == null) {
		$scale = 0.8;
	}
	$sql = "select * from tsm_auction_db where min_mkt_scale < '".$scale."' and lastScan = '".get_option_by_key('last_scan_date')."' ORDER BY min_mkt_scale";
	$res = $wpdb->query($sql);
	// var_dump($sql);
	return $res;
}
// get_option_by_key("last_scan_date");


// 获取option_mata 中的信息
function get_option_by_key($meta_key){
	global $wpdb;
	$sql = "select meta_value from tsm_option_meta where meta_key = '".$meta_key."'";
	$res = $wpdb->get_var($sql);
	return $res;
}

// search_item_by_name("世界");
// 根据名称模糊查找内容
function search_item_by_name($item_name){
	global $wpdb;
	if (empty($item_name)) {
		return;
	}
	//找到相似内容
	$sql_item = "SELECT * FROM tsm_itemname WHERE name like '%".$item_name."%'";
	$res_item = $wpdb->get_results($sql_item);
	$num = count($res_item);
	
	// if ($num == 1) {
	// 	$res_item = get_tsm_info_by_name($res_item[0]->Name);
	// }
	return $res_item;
	// echo "<pre>";
	// var_dump($res_item);
	// exit;	
	
}

// 获取精确的物品商业信息
function get_tsm_info_by_name($item_name){
	global $wpdb;
	$sql = "SELECT
			tsm_itemname.ID,
			tsm_auction_db.*,
			tsm_itemname.`Name` as name
			FROM
			tsm_itemname
			JOIN tsm_auction_db
			ON tsm_itemname.ID = tsm_auction_db.itemString
			where name = '".$item_name."'
			order by tsm_auction_db.lastScan
	";
	// echo $sql;
	$res = $wpdb->get_results($sql);
	return $res;
	// echo "<pre>";	
	// var_dump($res);
}

// 获取精确的物品商业信息
function get_tsm_info_by_id($item_id){
	global $wpdb;
	$sql = "SELECT
			tsm_itemname.ID as item_id,
			tsm_auction_db.*,
			tsm_itemname.`Name` as name
			FROM
			tsm_itemname
			JOIN tsm_auction_db
			ON tsm_itemname.ID = tsm_auction_db.itemString
			where tsm_itemname.ID = '".$item_id."'
			order by tsm_auction_db.lastScan
	";
	// echo $sql;
	$res = $wpdb->get_results($sql);
	return $res;
	// echo "<pre>";	
	// var_dump($res);
}


// check_repeat_item('93186','1573098743');
// 检查数据是否重复，主要就是 itemString与lastScan
// 返回此条数据，或 NULL
function check_repeat_item($itemString, $lastScan){
	global $wpdb;
	// $sql = "SELECT * FROM ".$wpdb->prefix."tsm_auction_db". " where "
	$item  = $wpdb->get_row( "SELECT * FROM tsm_auction_db"." WHERE itemString =".$itemString." and lastScan = ".$lastScan. "" );
	return $item;
}
// 获取上次文件更新时间从库中
function get_last_file_update(){
	global $wpdb;
	$sql = "select meta_value from tsm_option_meta where meta_key = 'last_file_update'";
	$res = $wpdb->get_var($sql);
	return $res;
}

// 获取上次tsm_scan更新时间从库中
function get_tsm_scat_update(){
	global $wpdb;
	$sql = "select meta_value from tsm_option_meta where meta_key = 'last_scan_date'";
	$res = $wpdb->get_var($sql);
	return $res;
}

function check_if_file_update(){
	$last_file_update = get_last_file_update();
	$filename = '/Users/victor/MegaSyncFile/TradeSkillMaster.lua';
	// 修改日期
	// var_dump($filename);
	$time_m = filemtime($filename);
	if ($time_m > $last_file_update) {
		return true;
	}else{
		// echo "1111";
		return false;
	}
}

function check_last_scat_date($tsm_scan_date){
	$last_tsm_scan = get_tsm_scat_update();
	if ($tsm_scan_date > $last_tsm_scan) {
		return true;
	}else{
		return false;
	}
}

function insert_sell_info($sell_array){
	global $wpdb;
	$sql_str = null;
	$sql_col = '('.$sell_array[0].')';
	// $sql_col = $sell_array[0];
	unset($sell_array[0]);
	foreach ($sell_array as $key => $value) {
		// echo $value;
		// exit;
		// $sql_str .=$value;
		$value = trim($value);
		$sql_str .= "('".str_replace(",", "','", $value)."'),";
	}
	
	// $sql_str = rtrim($sql_str, "'),(");
	$sql_str = rtrim($sql_str, ",''),");
	// $sql_str = mb_substr($sql_str, 0,-4);
	// $sql_str = rtrim($sql_str, ",' ");	
	
	$sql_str .= "')";
	$sql = "insert ignore into tsm_sellinfo ".$sql_col." values ".$sql_str;
	$res = $wpdb->query($sql);

}

function get_tsm_scandate_by_file(){
	$filename = "/Users/victor/MegaSyncFile/TradeSkillMaster.lua";
	$file_in        = fopen($filename, 'r');     //以只读方式('r')打开文件'abc.txt',
	while(!feof($file_in)){                      //如果没有达到文件尾，就继续循环
	    $line       = fgets($file_in);           //从文件中读出一行，放到变量$line中，
	                                             //文件指针移动到下一行
	   

	    $tsm_scan  = strpos($line, '@internalData@auctionDBScanTime'); 

	    // 以下几个要注意在文件中出现的先后顺序
	    // TSM 中扫描的时间
	    if($tsm_scan !== false){                    //如果值不是逻辑值false，则:
	        $result_tsm_scan = $line;                     //得到结果,
	        break;
	    }

	}
	$tsm_scan_arr = explode("= ",$result_tsm_scan);
	// 获取最终的tsm扫描时间
	$tsm_scan_str = $tsm_scan_arr[1];
	$tsm_scan_str = trim($tsm_scan_str);
	$tsm_scan_str = rtrim($tsm_scan_str,',');
	fclose($file_in);                            //关闭文件
	return $tsm_scan_str;
}


// get_tsm_and_insert();
// 获取TSM内容
function get_tsm_and_insert(){
	global $wpdb;
	// $filename = "https://mega.nz/#!pg5lASJA!Dtzall1ZKBoPUka2QvUERDakSgG1htqA5DJu3pX2G0w";
	$filename = "/Users/victor/MegaSyncFile/TradeSkillMaster.lua";
	$file_in        = fopen($filename, 'r');     //以只读方式('r')打开文件'abc.txt',
	while(!feof($file_in)){                      //如果没有达到文件尾，就继续循环
	    $line       = fgets($file_in);           //从文件中读出一行，放到变量$line中，
	                                             //文件指针移动到下一行
	    $occur      = strpos($line, '@internalData@csvAuctionDBScan');     //把'李四'在$line中出现的位置保存到$occur中，
	    // 获取销售情况，谁买的
	    $sell		= strpos($line, '@internalData@csvSales'); 
	    // $occur      = strpos($line, '"帕奇维克", -- [1]');     //把'李四'在$line中出现的位置保存到$occur中，

	    $tsm_scan  = strpos($line, '@internalData@auctionDBScanTime'); 

	    // 以下几个要注意在文件中出现的先后顺序
	    // TSM 中扫描的时间
	    if($tsm_scan !== false){                    //如果值不是逻辑值false，则:
	        $result_tsm_scan = $line;                     //得到结果,
	    }


	                                             //找不到则保存逻辑值false
	    // 获取拍卖行数据
	    if($occur !== false){                    //如果值不是逻辑值false，则:
	        $result = $line;                     //得到结果,
	        // 因为先有拍卖行数据，所以这里把'退出'注销了，否则不会继续获取“销售数据”
	        // break;                               //退出循环
	    }
	    // 获取销售
	    if($sell !== false){                    //如果值不是逻辑值false，则:
	        $result_sell = $line;                     //得到结果,
	        break;                               //退出循环
	    }
		
	}
	
	// echo $result;                              //输出结果
	$str2arr = explode("=",$result);
	$sell2arr = explode("= ",$result_sell);
	$tsm_scan_arr = explode("= ",$result_tsm_scan);
	// 获取最终的tsm扫描时间
	$tsm_scan_str = $tsm_scan_arr[1];
	$tsm_scan_str = trim($tsm_scan_str);
	$tsm_scan_str = rtrim($tsm_scan_str,',');


	$ifupdate = check_last_scat_date($tsm_scan_str);
	if (!$ifupdate) {
		echo "无可更新内容";
		return false;
	}
	
	$auxtion_arr = explode('\ni:',str_replace('"','',$str2arr[1]));
	$sell_arr = explode('\ni:',str_replace('"','',$sell2arr[1]));
	// $tsm_scan_arr = explode('\ni:',str_replace('"','',$tsm_scan2arr[1]));

	if (is_array($sell_arr)) {
		insert_sell_info($sell_arr);
	}

	$item_array = array();
	
	// var_dump($auxtion_arr);
	// exit;
	$valueStr = null;
	if (is_array($auxtion_arr)) {
		foreach ($auxtion_arr as $key => $value) {
			if($key != 0){
				$item_array[$key]['itemString1'] = explode(",", $value)[0];
				$item_array[$key]['itemString'] = explode(":", $item_array[$key]['itemString1'])[0];
				$item_array[$key]['minBuyout'] = explode(",", $value)[1];
				$item_array[$key]['marketValue'] = explode(",", $value)[2];
				$item_array[$key]['numAuctions'] = explode(",", $value)[3];
				$item_array[$key]['quantity'] = explode(",", $value)[4];
				$item_array[$key]['lastScan'] = explode(",", $value)[5];
				$item_array[$key]['scanDate'] = date('Y-m-d H:i:s', explode(",", $value)[5]);
				// echo "<pre>";
				// var_dump($item_array);
				// exit;
				// $res = check_repeat_item($item_array[$key]['itemString'],$item_array[$key]['lastScan']);
				$res = null;
				if (empty($res)) {
					if ($item_array[$key]['marketValue']==0 or empty($item_array[$key]['marketValue'])) {
						$item_array[$key]['marketValue'] = 1;
					}
						$valueStr .= "(
						'".$item_array[$key]['itemString']."',
						'".$item_array[$key]['minBuyout']."',
						'".$item_array[$key]['marketValue']."',
						'".$item_array[$key]['minBuyout']/$item_array[$key]['marketValue']."',
						'".$item_array[$key]['numAuctions']."',
						'".$item_array[$key]['quantity']."',
						'".$item_array[$key]['lastScan']."',
						'".$item_array[$key]['scanDate']."'),";
				}
				$count =$key;
			}
			
		}
		// echo $valueStr;
		if (isset($valueStr)) {
			$valueStr = rtrim($valueStr,",");
			$table 		="tsm_auction_db";
			// $sql = "insert into ".$table."(itemString,minBuyout,marketValue,min_mkt_scale,numAuctions,quantity,lastScan,scanDate) values ".$valueStr; 
			$sql = "insert ignore into ".$table."(itemString,minBuyout,marketValue,min_mkt_scale,numAuctions,quantity,lastScan,scanDate) values ".$valueStr; 
			$res =  $wpdb->query($sql);
			if ($res) {
				$sql_update = "update tsm_option_meta set meta_value = '".$tsm_scan_str."' where meta_key = 'last_scan_date'";
				$wpdb->query($sql_update);
				// 更新所有的平均值
				insert_market_min_ave();
				echo "共导入".$count."条数据";
			}
		}else{
			echo "没有可导入的数据";
		}
		
	}
	// var_dump($sql);
	fclose($file_in);                            //关闭文件
}


/**
 * [update_real_chips description]
 * 批量更新 real_chips 这个字段，根据最后剩余筹码 - 初始筹码
 * 增加 real_chips 自断后执行一次即可
 * @return [type] [description]
 */
function update_real_chips(){
	global $wpdb;
	$gamedetail = get_game_by_args(array("status" => 2));
	foreach ($gamedetail as $key => $value) {
		
		$game_detail_users = get_game_detail($value->id);
		if ($game_detail_users) {
			foreach ($game_detail_users as $k => $val) {
				$real_chips = ($val->count_chips - $val->start_chips)/10;

				$table 		=	$wpdb->prefix."kog_details";

				$update 	=	array(
					'real_chips'	=> 	$real_chips,
				);
				$where 		=	array(
					'id'			=>	$val->id,
				);
				if (isset($val->id)) {
					if ($wpdb->update($table,$update,$where)){
						echo $val->id."更新".$real_chips."</br>";
					}
					else{
						echo "update 失败";
					}
				}else{
					echo "id不合法";
				}
			}
		}
		
	}
}

/**
 * [game_type_array 根据KEY值返回相应数组的value
 * @param  [type] $val [description]
 * @return [type]      [description]
 */
function game_type_array($val){
	$val = strtoupper($val);
	$game_type = array(
		'CASH' 	=> '现金局',
		'SNG'	=> '排位赛',
	);
	if (array_key_exists($val, $game_type)) {
		return $game_type[$val];
	}else{
		return "全部场次";
	}
}

/**
 * 根据方向ID 获取方向的名称/描述
 * @param  [type] $position_id [description]
 * '东(皮椅子)', '南(黑椅子)', '西(黑椅子)' ,'北(红椅子)
 * @return [type]              [description]
 */
function get_position_name($position_id){
	$array_position = array(
		1 => "东(皮椅子)",
		2 => "南(黑椅子)",
		3 => "西(黑椅子)",
		4 => "北(红椅子)",
	);
	if (array_key_exists($position_id, $array_position)) {
		return $array_position[$position_id];
	}
	else {
		return $position_id;
	}
}

/**
 * 计算每个人在每个方位的表现（1~4代表东南西北）
 * @return [type] [description]
 */
function count_person_position_win($start_time=null, $end_time=null, $game_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 		=	"SELECT d.uid, d.* FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}

	$res  		= 	$wpdb->get_results($sql);
	$res_uid	=  	$wpdb->get_col($sql);
	$res_uid 	=	array_unique($res_uid);
	$person_position['uid'] 	=	 $res_uid;

	if (is_array($res) && !empty($res)) {
		foreach ($res_uid as $key => $value) {
			for ($i=1; $i <=4 ; $i++) { 
				// 预先对每个方位赋值，只算4个方位 所以这里 i <= 4，
				$person_position['win'][$value][$i]=0;
				$person_position['win_rate'][$value][$i]=0;
				$person_position['win_info']['win'][$value][$i]=0;
				$person_position['win_info']['total'][$value][$i]=0;
			}
		}
		foreach ($res as $key => $value) { 
			// 针对实际支付的补丁，有时候会打折，根据数据库中 total_fact字段来判断
			$value->bonus = !empty($value->total_fact)?$value->total_fact:$value->bonus;
			$value->reward = !empty($value->total_fact)?0:$value->reward;

			$person_position['win'][$value->uid][$value->seat_position] 	= 	isset($person_position['win'][$value->uid][$value->seat_position])?$person_position['win'][$value->uid][$value->seat_position] + $value->bonus + $value->reward : $value->bonus +$value->reward;
			// ksort($person_position['win'][$value->uid]);
			$person_position_count[$value->uid][$value->seat_position] 		=	isset($person_position_count[$value->uid][$value->seat_position])?$person_position_count[$value->uid][$value->seat_position]+1:1;
			if ($value->bonus >0) {
				$peson_win_count[$value->uid][$value->seat_position] = isset($peson_win_count[$value->uid][$value->seat_position])?$peson_win_count[$value->uid][$value->seat_position]+1:1;
			}
			$peson_win_count[$value->uid][$value->seat_position]	=	isset($peson_win_count[$value->uid][$value->seat_position])?$peson_win_count[$value->uid][$value->seat_position]:0;
			$person_position['win_rate'][$value->uid][$value->seat_position] 	=	round($peson_win_count[$value->uid][$value->seat_position]/$person_position_count[$value->uid][$value->seat_position],4)*100;
			$person_position['win_info']['win'][$value->uid][$value->seat_position] 	=	$peson_win_count[$value->uid][$value->seat_position];
			$person_position['win_info']['total'][$value->uid][$value->seat_position] 	=	$person_position_count[$value->uid][$value->seat_position];
			// 获取每个方位上每人的输赢的情况，按每个方位展示一张统计图（同时显示盈亏、胜率），同时按key值对相应数组进行排序
			$person_position['each_position']['win'][$value->seat_position][$value->uid] = isset($person_position['each_position']['win'][$value->seat_position][$value->uid])?$person_position['each_position']['win'][$value->seat_position][$value->uid]+$value->bonus + $value->reward:$value->bonus + $value->reward;
			$person_position['each_position']['win_rate'][$value->seat_position][$value->uid] = $person_position['win_rate'][$value->uid][$value->seat_position];
			ksort($person_position['each_position']);
			ksort($person_position['each_position']['win']);
			ksort($person_position['each_position']['win_rate']);
			ksort($person_position['each_position']['win'][$value->seat_position]);
			ksort($person_position['each_position']['win_rate'][$value->seat_position]);
		}
	}
	return $person_position;
}

/**
 * 计算每个位置（东南西北）-1、2、3、4、5号位置赢的比例
 * 直接计算每个位置的总盈余
 * 计算谁再哪个位置输赢情况
 * @return [type] [description]
 */
function cont_position_winer_new($start_time=null, $end_time=null,$game_type=null,$stat_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 	=	"SELECT d.* FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}
	$res  	= 	$wpdb->get_results($sql);
	$i = 0;
	if(is_array($res) && !empty($res)){
		foreach ($res as $key => $value) {
			// 目前只统计4个人玩的东南西北，5人玩的数据太少，不做统计
			if ($value->seat_position > 4) {
				continue;
			}
			// 累计每个方位出现的游戏数量，单独累计到用户身上就是累计每个用户在每个方位的总游戏次数
			$position_count[$value->seat_position] 								=	isset($position_count[$value->seat_position])?$position_count[$value->seat_position]+1:1;
			$position_count['user'][$value->uid][$value->seat_position] 		=	isset($position_count['user'][$value->uid][$value->seat_position])?$position_count['user'][$value->uid][$value->seat_position]+1:1;

			// 都转成整数型
			$value->bonus 	=	intval($value->bonus);
			
			// 获胜 计算一次赢钱，用于算胜率
			if($value->bonus >0){
				$position_win[$value->seat_position]['times'] 	=	isset($position_win[$value->seat_position]['times'])?$position_win[$value->seat_position]['times']+1:1;	
			}
			// 不光累加 bonus,还得累加额外的 reward 
			// 针对实际支付的补丁，有时候会打折，根据数据库中 total_fact字段来判断
			$value->bonus = !empty($value->total_fact)?$value->total_fact:$value->bonus;
			$value->reward = !empty($value->total_fact)?0:$value->reward;

			$position_win[$value->seat_position]['bonus']	=	isset($position_win[$value->seat_position]['bonus'])?$position_win[$value->seat_position]['bonus']+ $value->bonus + $value->reward:$value->bonus+$value->reward;
			// $position_win['user'][$value->uid][$value->seat_position]	=	isset($position_win['user'][$value->uid][$value->seat_position])?$position_win['user'][$value->uid][$value->seat_position]+$value->bonus:$value->bonus;
			
			// 为了让每个位置都有值，这里对于没取到的，统一赋值0
			// 但对于[user]数组中，可能会出现某人在某个位置没有数据，所以数组中并不是1~4都有，这样的情况在前台调用并整理新数组的时候再做处理
			
			// $position_lose['user'][$value->uid][$value->seat_position]['times'] 		=	isset($position_lose['user'][$value->uid][$value->seat_position]['times'])?$position_lose['user'][$value->uid][$value->seat_position]['times']:0;			
			// 算概率，为最终数组赋值（累计盈亏总金额）
			$position_win[$value->seat_position]['times'] 	=	isset($position_win[$value->seat_position]['times'])?$position_win[$value->seat_position]['times']:0;	
			$position_win_rate['position'][$value->seat_position] 			= 	round($position_win[$value->seat_position]['times']/$position_count[$value->seat_position],4)*100;
			// $position_win_rate['user'][$value->uid][$value->seat_position] 			= 	round($position_win['user'][$value->uid][$value->seat_position]['times']/$position_count['user'][$value->uid][$value->seat_position],4)*100;
			$position_stas['win'][$value->seat_position] 		=	$position_win[$value->seat_position]['bonus'];
			ksort($position_stas['win']);
			ksort($position_win_rate['position']);
			
		}
		
		// 不排序了，直接按东南西北（1~4)的位置顺序显示
		// arsort($position_win_rate); 
		// arsort($position_lose_rate);
		// $position_stas['win'] 			= 	$position_stas['win'];
		// $position_stas['lose'] 			= 	$position_lose;
		

		$position_stas['win_rate'] 		= 	$position_win_rate['position'];
		// $position_stas['user']['win_rate'] 		= 	$position_win_rate['user'];
		
		// $position_stas['user']['win']	=	$position_win['user'];
		// $position_stas['user']['win_times']	=	$position_win_times['user'];
		
		foreach ($position_win_rate['position'] as $key => $value) {
			$position_stas['user_position']['bonus'][$key] 		= 	0;
			$position_stas['user_position']['win_rate'][$key] 	= 	0;
		}



		return $position_stas;
	}
}

/**
 * 计算每个位置（东南西北）-1、2、3、4、5号位置赢的比例
 * 计算谁再哪个位置输赢情况
 * @return [type] [description]
 */
function cont_position_winer($start_time=null, $end_time=null,$game_type=null,$stat_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 	=	"SELECT d.* FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}
	$res  	= 	$wpdb->get_results($sql);
	if(is_array($res) && !empty($res)){
		foreach ($res as $key => $value) {
			if ($value->seat_position == 5) {
				continue;
			}
			$position_count[$value->seat_position] 				=	isset($position_count[$value->seat_position])?$position_count[$value->seat_position]+1:1;

			// 针对实际支付的补丁，有时候会打折，根据数据库中 total_fact字段来判断
			$value->bonus = !empty($value->total_fact)?$value->total_fact:$value->bonus;
			$value->reward = !empty($value->total_fact)?0:$value->reward;

			if ($value->bonus >0) {
				$position_win[$value->seat_position]['times'] 	=	isset($position_win[$value->seat_position]['times'])?$position_win[$value->seat_position]['times']+1:1;
				$value->bonus 	=	intval($value->bonus);
				$position_win[$value->seat_position]['bonus']	=	isset($position_win[$value->seat_position]['bonus'])?$position_win[$value->seat_position]['bonus']+$value->bonus+ $value->reward:$value->bonus+ $value->reward;

			}
			else{
				$position_lose[$value->seat_position]['times'] 	=	isset($position_lose[$value->seat_position]['times'])?$position_lose[$value->seat_position]['times']+1:1;
				$position_lose[$value->seat_position]['bonus']	=	isset($position_lose[$value->seat_position]['bonus'])?$position_lose[$value->seat_position]['bonus']+$value->bonus+ $value->reward:$value->bonus+ $value->reward;
			}
			$position_win[$value->seat_position]['bonus']		=	isset($position_win[$value->seat_position]['bonus'])?$position_win[$value->seat_position]['bonus']:0;
			$position_lose[$value->seat_position]['bonus']		=	isset($position_lose[$value->seat_position]['bonus'])?$position_lose[$value->seat_position]['bonus']:0;
			$position_win[$value->seat_position]['times'] 		=	isset($position_win[$value->seat_position]['times'])?$position_win[$value->seat_position]['times']:0;
			$position_lose[$value->seat_position]['times'] 		=	isset($position_lose[$value->seat_position]['times'])?$position_lose[$value->seat_position]['times']:0;
			

			
			

			$position_win_rate[$value->seat_position] 			= 	round($position_win[$value->seat_position]['times']/$position_count[$value->seat_position],4)*100;
			$position_lose_rate[$value->seat_position] 			= 	round($position_lose[$value->seat_position]['times']/$position_count[$value->seat_position],4)*100;
			$position_stas['win'][$value->seat_position] 		=	$position_win[$value->seat_position]['bonus'];
			$position_stas['lose'][$value->seat_position] 		=	$position_lose[$value->seat_position]['bonus'];
			ksort($position_stas['win']);
			ksort($position_stas['lose']);


		}

		ksort($position_win_rate);
		ksort($position_lose_rate);
		// $position_stas['win'] 			= 	$position_win;
		// $position_stas['lose'] 			= 	$position_lose;
		$position_stas['win_rate'] 		= 	$position_win_rate;
		$position_stas['lose_rate'] 	= 	$position_lose_rate;
		return $position_stas;
	}
}

/**
 * 或取游戏每个人的获胜/赢钱率（只要Bonus > 0 即视为获胜）——这是针对SNG的还要考虑现金的
 * 现金的是最后剩余筹码-初始筹码为正，就是获胜
 * @return [type]      [description]
 */
function count_winer_rate($start_time=null, $end_time=null, $game_type=''){
	global $wpdb;
	// 找到已经 Finiesh 的游戏进行统计（因为统计数据也是找这些数据进行统计的）
	$sql 	=	"SELECT d.*,g.game_type,g.start_time,g.end_time,g.date FROM ".$wpdb->prefix."kog_games as g left join ".$wpdb->prefix."kog_details as d on g.id = d.gid WHERE g.status = 2";
	if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}
	$res  	= 	$wpdb->get_results($sql);
	// echo "<pre>";
	// var_dump ($res);
	// exit;
	// 
	// 获取状态是2的全部场次及详情
	$kog_games 	= 	get_game_by_args(array('status'=>2,'start_time'=>$start_time,'end_time'=>$end_time,'game_type'=>$game_type));
		// 初始化一个数组用来填写每场的数据，没参加的当场就是0
	$players = get_player();
	if (isset($kog_games)) {
		foreach ($kog_games as $key => $value) {
			foreach ($players as $k => $val) {
				$user_times_win_rate[$val->id][$value->date] = 0;
			}
		}

	}
	if (is_array($res) && !empty($res)) {
		// 计算场次
		// $game_list 		=	get_game_list();
		// $game_count 	=	count($game_list);
		
		foreach ($res as $key => $value) {
			if ($value->uid == '1') {
				// var_dump($value);
			}
			if (strtoupper($value->game_type) == 'SNG') {

				$condition = $value->bonus;
			}else{
				$condition = $value->total_fact;
				
			}

			$user_times_win_count_end[$value->uid] = isset($user_times_win_count_end[$value->uid])?$user_times_win_count_end[$value->uid]:0;
			// 计算赢了的用户及次数
			if ($condition > 0) {
				$user_times_win[$value->uid] 	= 	isset($user_times_win[$value->uid])?$user_times_win[$value->uid]+1:1;	
				// if ($value->uid == 1) {
				// 	echo "<pre>";
				// 	var_dump ($user_times_win[1]."[$value->date]:");
				// }
				$user_win_count[$value->uid] 	= isset($user_win_count[$value->uid])?$user_win_count[$value->uid]+1:1;
				$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']]	= isset($monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']])?$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']]+1:1;
				// 在相应日期计算截止到当天的胜利总场次，便于后面累加
				$user_times_win_count[$value->uid][$value->date] 	= 	$user_times_win[$value->uid];
				// $user_times_win_count[$value->uid][$value->date] 	= 	isset($user_times_win_count[$value->uid][$value->date])?$user_times_win[$value->uid]+1:$user_times_win[$value->uid];

			} else {
				$user_win_count[$value->uid] 	= isset($user_win_count[$value->uid])?$user_win_count[$value->uid]:0;
				$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']]	= isset($monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']])?$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['win_count'][get_time_ym($value->start_time)['m']]:0;
				// $user_times_win_count[$value->uid][$value->date] 	=	prev($user_times_win_count[$value->uid]);
				$user_times_win_count[$value->uid][$value->date] 	=	$user_times_win_count_end[$value->uid];
			}
			$user_times_win_count_end[$value->uid] = $user_times_win_count[$value->uid][$value->date];
			
			// 计算每个用户参与的次数
			$count_gamer[$value->uid] 		=	isset($count_gamer[$value->uid])?$count_gamer[$value->uid]+1:1;
			$game_count 					=	$count_gamer[$value->uid];
			// 计算每个选手的胜率，选手获胜次数，与选手参与次数，而不是全部的game总量
			$game_win_rate[$value->uid]		= round($user_win_count[$value->uid]/$game_count,4);

			


			
			// 按日期计算截止到当天的参与的总场次
			// $user_times_game_count[$value->uid][$value->date] 	= 	isset($user_times_game_count[$value->uid][$value->date])?$user_times_game_count[$value->uid][$value->date]+1:1;
			// 累加参与总场次
			$user_times_game[$value->uid] = isset($user_times_game[$value->uid])?$user_times_game[$value->uid]+1:1;
			$user_times_game_count[$value->uid][$value->date] 	= 	isset($user_times_game_count[$value->uid][$value->date])?$user_times_game[$value->uid]+1:$user_times_game[$value->uid];

			$user_times_win_rate[$value->uid][$value->date]		=	round($user_times_win_count[$value->uid][$value->date]/$user_times_game_count[$value->uid][$value->date],4);
			// $count_gamer[$value->uid] 	=	isset($count_gamer[$value->uid])?$count_gamer[$value->uid]+1:1;
		}
		if (isset($user_times_win_rate)) {
			foreach ($user_times_win_rate as $key => $value) {
				$this_value = 0;
				foreach ($value as $k => $val) {
					if ($val != 0) {
						$this_value = $val;
					}
					// $this_value = isset($this_value)?$this_value:0;
					$user_times_win_rate[$key][$k] = $this_value;
				}
			}
		}

		// echo "<pre>";
		// var_dump ($user_times_win_rate);
		// // // var_dump ($user_times_win_count[1]);
		// // // var_dump ($user_times_game_count[1]);
		// exit;

		// 对胜率数组排序
		arsort($game_win_rate);
		foreach ($game_win_rate as $key => $value) {
			$new_game_win_rate[$key][0]	= get_player($key)[0]->nickname;
			$new_game_win_rate[$key][1]	= $value;
			// $new_game_win_rate[$key][2]['count']	= $count_gamer[$key];
			// $new_game_win_rate[$key][2]['win']		= $user_win_count[$key];
			$new_game_win_rate[$key][2]		= $count_gamer[$key];
			$new_game_win_rate[$key][3]		= $user_win_count[$key];
			$new_game_win_rate[$key][4]		= $key;
		}

		foreach ($new_game_win_rate as $key => $value) {
			$final_game_win_rate[] = $value;
		}
		$final_game_win_rate['monthly_win'] = $monthly_income;
		$final_game_win_rate['user_times_win_rate'] = $user_times_win_rate;
		// echo "<pre>";
		// var_dump ($final_game_win_rate);
		// exit;
		return $final_game_win_rate;
	}
	else {
		return false;
	}

}

function get_position_stat($start_time=null, $end_time=null,$game_type=null,$stat_type=null){
	$res = get_player_total_income_stat($start_time, $end_time,$game_type,$stat_type)['res'];
	if (!empty($res)) {
		foreach ($res as $key => $value) {
			// 针对实际支付的补丁，有时候会打折，根据数据库中 total_fact字段来判断
			$value->bonus = !empty($value->total_fact)?$value->total_fact:$value->bonus;
			$value->reward = !empty($value->total_fact)?0:$value->reward;

			$position_stat[$value->uid]['name'] = $value->player;
			// 用来控制气泡的位置错开
			$position_stat[$value->uid]['data'][] = array(intval($value->seat_position)+(5-rand(2,8))*0.08, $value->reward + $value->bonus,abs($value->reward + $value->bonus));
		}
		foreach ($position_stat as $key => $value) {
			$position_stat_res[]=$value;
		}
		return $position_stat_res;
	}else{
		return false;
	}
}

function get_best_killer($start_time=null, $end_time=null, $game_type=''){
	global $wpdb;
	$sql 	=	"SELECT killed_by FROM ".$wpdb->prefix."kog_rebuy as r 
	left join ".$wpdb->prefix."kog_games as g on g.id = r.gid where g.status <> 0";
	if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}
	$rebuy 	= 	$wpdb->get_col($sql);
	$player 	=	get_player();
	$player_arr	= 	creat_single_array($player,'id','id');
	$player_name =	creat_single_array($player,'id','nickname');
	$ac 		= 	array_count_values($rebuy);
	arsort($ac);
	foreach ($player_arr as $key => $value) {

		if (!array_key_exists($value, $ac)) {
			$ac[$key] = 0;
		}
	}
	$kill_rank 	=	array();
	// array($value['name'],array_sum($value['data']));
	foreach ($ac as $key => $value) {
		$kill_rank[] 	=	array($player_name[$key],$value);
	}
	return $kill_rank;
}
// echo "<pre>";
// var_dump (if_in_this_month("1521204268","2018","02"));
// exit;


/**
 * [if_in_this_month description] 判断一个时间戳是否属于某年某月
 * @param  [type] $time  [1521204268] 时间戳
 * @param  [type] $year  [2018] 2018
 * @param  [type] $month [03]
 * @return [type]        [description]
 */
function if_in_this_month($time,$year,$month){
	$ym 		= $year.$month;
	$ym_start 	= $ym."01";
	$ym_end 	= date('Ymt',strtotime($ym_start));
	$ym_start_time 	= strtotime($ym_start);
	$ym_end_time 	= strtotime($ym_end);
	if ($time <= $ym_end_time && $time>= $ym_start_time) {
		return true;
	}
	else{
		return false;
	}
}

/**
 * [get_time_ym description] 根据时间戳返回所属的年、月、日信息
 * @param  [type] $time [description]
 * @return [type]       [description]返回一维数组 y  m   d 供分别使用
 * 
 */
function get_time_ym($time){
	return array("y"=>date('Y',$time),"m"=>date('M',$time),"d"=>date('d',$time));
}


function get_player_total_income_stat($start_time=null, $end_time=null,$game_type=null,$stat_type='',$timezone="all"){
	global 	$wpdb;
	$table 		=	$wpdb->prefix."kog_details";
	$sql 		=	"SELECT d.*,start_time,end_time,game_type,`date`
	FROM ".$wpdb->prefix."kog_details as d 
	left join ".$wpdb->prefix."kog_games as g 
	on g.id = d.gid where g.status =2";
	if(!is_null($start_time)){
		$start_time = str_replace("-","",$start_time);
		$sql = $sql.' and g.date>='.$start_time;
	}
	if(!is_null($end_time)){
		$end_time = str_replace("-","",$end_time);
		$sql = $sql.' and g.date<='.$end_time;
	}
	if(!empty($game_type)){
		$sql = $sql.' and g.game_type like '."'".$game_type."'";
	}
	$res 		= 	$wpdb->get_results($sql);
	
	$monthly_income = array();
	if (!empty($res)) {

		// 获取状态是2的全部场次及详情
		$kog_games 	= 	get_game_by_args(array('status'=>2,'start_time'=>$start_time,'end_time'=>$end_time,'game_type'=>$game_type));
		// 初始化一个数组用来填写每场的数据，没参加的当场就是0
		$players = get_player();
		if (isset($kog_games)) {
			foreach ($kog_games as $key => $value) {
				foreach ($players as $k => $val) {
					$total_income_add[$val->id][$value->date] = 0;
				}
			}

		}
		foreach ($res as $key => $value) {

			// 默认统计的是现金，如果是筹码，则需要用筹码的字段”real_chips"代替 total_fact
			if ($stat_type == "chips") {
				$value->total_fact = !is_null($value->real_chips)?$value->real_chips:$value->total_fact;
			}
			if ($stat_type == 'real_money'){
				if ($value->game_type == "SNG") {
					$value->total_fact = !is_null($value->total_should)?$value->total_should:$value->total_fact;
				}elseif ($value->game_type == "CASH") {
					$value->total_fact = !is_null($value->total_should)?$value->real_chips:$value->real_chips;
					# code...
				}
				
			}
			// 针对实际支付的补丁，有时候会打折，根据数据库中 total_fact字段来判断
			$value->bonus = !empty($value->total_fact)?$value->total_fact:$value->bonus;
			$value->reward = !empty($value->total_fact)?0:$value->reward;

			$total_income_stat['income_1'][$value->uid]['name'] =	$value->player;
			$total_income_stat['income_1'][$value->uid]['data'][$value->gid] = $value->bonus + $value->reward;
			$total_income_stat['date_1'][$value->gid]	= date('m-d',$value->start_time);
			$total_income_stat['name'][$value->gid][$value->uid]	= $value->player;

			// $total_count = isset($total_count)?$value->bonus + $value->reward:0;
			$total_count = $value->bonus + $value->reward;
			$user_total_count[$value->id] = isset($user_total_count[$value->id])?$user_total_count[$value->id]+$total_count:$total_count;


			$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_date'][get_time_ym($value->start_time)['m']]	= isset($monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_date'][get_time_ym($value->start_time)['m']])?$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_date'][get_time_ym($value->start_time)['m']]+$value->bonus + $value->reward:$value->bonus + $value->reward;
			
			$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_times'][get_time_ym($value->start_time)['m']]	= isset($monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_times'][get_time_ym($value->start_time)['m']])?$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_times'][get_time_ym($value->start_time)['m']]+1:1;
			$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['avg_date'][get_time_ym($value->start_time)['m']]	= round($monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_date'][get_time_ym($value->start_time)['m']]/$monthly_income[$value->uid][get_time_ym($value->start_time)['y']]['total_times'][get_time_ym($value->start_time)['m']],2);

			// 每次累加的总和及明细
			$total_income_everytimes[$value->uid]['total'] = isset($total_income_everytimes[$value->uid]['total'])?$total_income_everytimes[$value->uid]['total']+ $total_count:$total_count;
			$total_income_everytimes[$value->uid][$value->date]= isset($total_income_everytimes[$value->uid][$value->date])?$total_income_everytimes[$value->uid][$value->date] + $total_count:$total_count;
			
			// 每次累积的总和，用来真正画图用的
			$total_income_add[$value->uid][$value->date] = $total_income_everytimes[$value->uid]['total'];
			

		}
		if (isset($total_income_add)) {
			foreach ($total_income_add as $key => $value) {
				$this_value = 0;
				foreach ($value as $k => $val) {
					if ($val != 0) {
						$this_value = $val;
					}
					// $this_value = isset($this_value)?$this_value:0;
					$total_income_add[$key][$k] = $this_value;
				}
			}
		}
		// echo "<pre>";
		// var_dump ($total_income_add);
		// exit;
		// echo "<pre>";
		// var_dump($total_income_add);
		// var_dump(array_sum($total_income_everytimes[1]));
		// var_dump($total_income_add);
		// var_dump ($monthly_income[1][2018]["total_date"]);
		// var_dump (array_sum($monthly_income[1][2018]["total_date"]));
		// exit;
		// $kog_games 	= 	get_game();
		// echo "<pre>";
		// // var_dump ($res);
		// var_dump ($monthly_income);
		// exit;

		if (!empty($kog_games)) {
			foreach ($kog_games as $key => $value) {
				foreach ($total_income_stat['income_1'] as $k => $val) {
					if (!isset($val['data'][$value->id])) {
						$total_income_stat['income_1'][$k]['data'][$value->id] = 0;
					}
					// 排序一下，否则新人的数据会出现在第一次游戏数据中
					ksort($total_income_stat['income_1'][$k]['data']);
				}

			}
		}

		// 前台图表显示数据的data的数组游标是要从0开始的自然游标，所以重新整理数据
		foreach ($total_income_stat['date_1'] as $key => $value) {
			$total_income_stat['date'][] 	=	$value;
		}

		/**
		 * $b = array(
             array('name'=>'北京', 'y'=>20.2),
             array('name'=>'上海', 'y'=>9.6),
             array('name'=>'武汉', 'y'=>16.6),
     );
     $data = json_encode($b);
     echo($data);
		 */
		// echo "<pre>";
		// var_dump($res);

		// var_dump ($total_income_stat['income_1']);
		// // var_dump ($total_income_stat['date_1']);
		// exit;
     foreach ($total_income_stat['income_1'] as $key => $value) {
     	$total_income_stat['income_2'][$key]['name']=$value['name'];
     	$total_income_stat['total'][] 	=	array($value['name'],array_sum($value['data']),round(array_sum($value['data'])/count($value['data']),2));
			$total_income_stat['total_uid'][$key] 	=	array($value['name'],array_sum($value['data']));//存一个对应用户id的数据，便于使用
			foreach ($value['data'] as $k => $val) {
				$total_income_stat['income_2'][$key]['data'][]=$val;
			}

		}
		foreach ($total_income_stat['income_2'] as $key => $value) {
			$total_income_stat['income'][] 	= 	$value;
		}
		// echo "<pre>";
		// var_dump($total_income_stat['income_1']);
		// var_dump (array_sum($total_income_stat['income_1'][1]['data']));
		// var_dump ($total_income_stat['total']);
		// exit;
		// 对总数的二维数组排序
		foreach ($total_income_stat['total'] as $key => $value) {
			$sort_total[]= $value[1];
		}
		array_multisort($sort_total,SORT_DESC,$total_income_stat['total']);

		foreach ($total_income_stat['total'] as $key => $value) {
			$total_income_stat['avg'][] = array($value['0'], $value['2']);
		}

		unset($total_income_stat['date_1']);
		unset($total_income_stat['income_1']);
		unset($total_income_stat['income_2']);
		unset($total_income_stat['name']);
		$total_income_stat['res'] 	=	$res;

		
		// echo "<pre>";
		// var_dump ($total_income_stat['avg']);
		// exit;

		$total_income_stat['res_total'] 	=	$total_income_stat['total'];
		$total_income_stat['monthly_income'] 	=	$monthly_income;
		$total_income_stat['times_income_add'] 	=	$total_income_add;
		// echo "<pre>";
		// var_dump ($total_income_stat['times_income_add']);
		// exit;
		return 	$total_income_stat;
		// return 	$res;
	} else {
		return 	false;
	}
}



















function get_honor_title_arr(){
	$honor_title = array(
		'best_killer' 	=> 	'最佳清台',
		'mvp' 			=>	'MVP',
		'over_god'		=>	'超神',
		'dark_priest'	=>	'最佳暗牧',
		'priest'		=>	'有容奶大',
	);

	return $honor_title;

}

function get_gamemeta($gid,$meta_key=null,$arr='OBJECT'){
	global $wpdb;
	$table 		=	$wpdb->prefix."kog_gamemeta";
	$where 		=	" where gid =".$gid;
	if (!empty($meta_key)) {
		$where .= " and meta_key = ".$meta_key;
	}
	$sql = "SELECT * from ".$table.$where;
	$cur = $wpdb->get_results( $sql,$arr);
	return $cur; 	
}

function insert_gamemeta($gid,$meta_key,$meta_value){
	global $wpdb;
	$table 		=	$wpdb->prefix."kog_gamemeta";
	$insert 	= 	array(
		'gid' 			=> 	$gid,
		'meta_key' 		=> 	$meta_key,
		'meta_value' 	=> 	$meta_value,
	);
	$wpdb->insert($table,$insert);
	return $wpdb->insert_id;
}

function update_gamemeta($gid,$meta_key,$meta_value){
	global $wpdb;
	$table 		=	$wpdb->prefix."kog_gamemeta";

	$update 	=	array(
		'meta_value'	=> 	$meta_value,
	);
	$where 		=	array(
		'gid'			=>	$gid,
		'meta_key'		=>	$meta_key,
	);
	if (empty($meta_value)) {
		$wpdb->delete($table,$where);
		return;
	}
	$cur = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $table WHERE gid = %d AND meta_key = %s", $gid, $meta_key) );

	if ($cur) {
		$res_update =	$wpdb->update($table,$update,$where);
		return $cur->id;
	}
	if (!$cur) {
		$insert 	= 	array(
			'gid' 			=> 	$gid,
			'meta_key' 		=> 	$meta_key,
			'meta_value' 	=> 	$meta_value,
		);
		$wpdb->insert($table,$insert);
		return $wpdb->insert_id;
	}
}

function get_game_status($status){
	$array 	= array(
		'0'	=> '已删除',
		'1'	=> '进行中',
		'2'	=> '已结束',
	);
}

function get_game_list($y=null){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE status <> 0";
	if (!is_null($y)){
		$time_y = mktime(0,0,0,1,1,$y);
		$sql 	= $sql . " and start_time >= '".$time_y."'";
	}
	$res 	= 	$wpdb->get_results($sql);
	return $res;

}

function hmtl_if_negative($value){
	if ($value<0) {
		return "<span class='red-text'>".$value."</span>";
	} else {
		return "<span>".$value."</span>";
	}
}

function update_some_table($table,$update,$where){
	global $wpdb;
	$table = $wpdb->prefix.$table;
	return $wpdb->update($table,$update,$where);
}

function update_game_detail_rank($rank_data){
	global $wpdb;

	$table 		= $wpdb->prefix."kog_details";
	$update 	= array(
		'end_chips'		=>	$rank_data['end_chips'],
		'rank'			=>	$rank_data['rank'],
		'bonus'			=>	$rank_data['bonus'],
		'count_chips'	=>	$rank_data['count_chips'],
		'reward'		=>	isset($rank_data['reward'])?$rank_data['reward']:0,
	);

	$where 		= array(
		'uid' 	=> $rank_data['uid'],
		'gid' 	=> $rank_data['gid'],
	);
	return $wpdb->update($table,$update,$where);
}

function get_game($gid = null){
	global $wpdb;
	if (!empty($gid)) {
		$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE id = ".$gid;
		$res 	= 	$wpdb->get_row($sql);
	} else {
		$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE  status <> 0 ";
		$res 	= 	$wpdb->get_results($sql);
	}	
	return 	$res;
}

function get_game_by_args($args = array()){
	global $wpdb;
	$defaults 	=	array(
		'status' 	=> 	false,
		'id' 		=>	false,
		'start_time' 		=>	false,
		'end_time' 		=>	false,
		'game_type' 		=>	false,
	);

	$args = wp_parse_args($args , $defaults);

	$sql = "SELECT * FROM ".$wpdb->prefix."kog_games where 1=1 ";

	if (!empty($args['status'])) {
		$sql .= " and status = '".$args['status']."'";
	} else{
		$sql .= " and status <> 0 ";
	}
	
	if (!empty($args['id'])) {
		$sql .= " and id = '".$args['id']."'";
	}

	if(!empty($args['start_time'])){
		$args['start_time'] = str_replace("-","",$args['start_time']);
		$sql = $sql.' and date>='.$args['start_time'];
	}
	if(!empty($args['end_time'])){
		$args['end_time'] = str_replace("-","",$args['end_time']);
		$sql = $sql.' and date<='.$args['end_time'];
	}
	if(!empty($args['game_type'])){
		$sql = $sql.' and game_type like '."'".$args['game_type']."'";
	}
	// echo "<pre>";
	// var_dump ($sql);
	// exit;
	if (!empty($args['id'])) {
		// $sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE id = ".$args['id'];
		$res 	= 	$wpdb->get_row($sql);
	} else {
		// $sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_games WHERE  status <> 0 ";
		$res 	= 	$wpdb->get_results($sql);
	}
	return 	$res;
}

function get_game_detail($gid){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_details WHERE gid = ".$gid;
	$res 	= 	$wpdb->get_results($sql);
	return 	$res;
}

function get_rebuy_info($gid,$uid=null){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_rebuy where gid = ".$gid;
	if(!empty($uid)){
		$sql = $sql . " and uid = ".$uid;
	}
	$res 	= 	$wpdb->get_results($sql);
	return $res;
}

function get_game_player($gid){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_details where gid = ".$gid." order by `rank`,`seat_position`";
	$res 	= 	$wpdb->get_results($sql);
	if (!empty($res)) {
		return $res;
	}else{
		return false;
	}
}

/**
 * 根据action gourp id (来自 gamemeta 表，就是插入的 meta_id)
 * @param  [type] $action_group_id [description]
 * @return [type]                  [description]
 */
function creat_action_info($action_group_id){
	global $wpdb;
	$table 	=	$wpdb->prefix.'kog_action';
	$date 	=	array(
		'gid' 			=> 	$_REQUEST['gid'],
		'action_group' 	=>	$action_group_id,
		'created_at' 	=>	time(),	
	);
	$allin 	=	array_merge($date, array('action' => 'allin','uid'=>$_REQUEST['allin_player'],'chips'=>$_REQUEST['chips']));
	$wpdb->insert($table,$allin);
	$win	=	array_merge($date, array('action' => 'win','uid'=>$_REQUEST['winner'],'chips'=>$_REQUEST['win_chips']));
	$wpdb->insert($table,$win);
	foreach ($_REQUEST['user_action'] as $key => $value) {
		$action_date 	= 	array_merge($date,array('action' => $value,'uid'=>$key));
		$wpdb->insert($table,$action_date);
	}
}

function creat_rebuy_info($uid){
	global $wpdb;

	$rebuy_date			=	array(
		'uid' 			=> 	$_REQUEST['uid'],
		'gid' 			=> 	$_REQUEST['gid'],
		'rebuy' 		=> 	$_REQUEST['chips'],
		'paied'		 	=> 	$_REQUEST['paied'],
		'killed_by' 	=>  $_REQUEST['killed_by'],
		'created_at' 	=>  time(),
	);

	$wpdb->insert($wpdb->prefix . 'kog_rebuy', $rebuy_date);
	$rid=$wpdb->insert_id;
	return $rid;
}

function get_player($uid = null){
	global $wpdb;
	$sql 	=	"SELECT * FROM ".$wpdb->prefix."kog_user";
	if (!empty($uid)){
		$sql = $sql." where id = '".$uid."'";
	}
	$res 	=	$wpdb->get_results($sql);
	return 	$res;
}

/**
 * 将一个二维数组中某两个值拿出来组成一个一维数组，并且新数组的key 是二维数组中的某个value, 
 * @param  [type] $array [description]
 * @param  [type] $key   [作为新数组key, 是原数组的某一个key],一般是id
 * @param  [type] $value [作为新数组的Value，是原数组的某一个key],一般是id 对应的某个值。如id=5, name=abc
 * @return [type]        [description]
 */
function creat_single_array($array,$key,$value){
	if (empty($array)) {
		return false;
	} else {
		foreach ($array as $k => $val) {
			if (is_object($val)) {
				$single_array[$val->$key] = $val->$value;
			}
			else if (is_array($val)) {
				$single_array[$val->$key] = $val[$value];
			}
			else {
				return false;
			}
		}
		// $single_array 	= array_unique($single_array);
		return $single_array;
	}
}

function creat_game(){
	global $wpdb;
	$_REQUEST['bonus']  = 	isset($_REQUEST['bonus'])?$_REQUEST['bonus']:null;
	$game_data 			=	array(
		'date' 			=> 	date('Ymd',time()),
		'start_time' 	=> 	time(),
		'chips_level' 	=> 	$_REQUEST['chips_start'],
		'rebuy_rate' 	=> 	$_REQUEST['rebuy_rate'],
		'bonus' 		=>  serialize($_REQUEST['bonus']),
		'status' 		=> 	1,
		'created_by'	=>	1,
		'game_type' 	=>	$_REQUEST['game_type'],
	);
	$wpdb->insert($wpdb->prefix . 'kog_games', $game_data);
	if($gid=$wpdb->insert_id){
		creat_game_detail($wpdb->insert_id);
	}
	return $gid;
}

function creat_game_detail($gid){
	global $wpdb;
	$player_arr 	=	get_player();
	$player 		=	creat_single_array($player_arr,'id','nickname');
	foreach ($_REQUEST['position'] as $key => $value) {
		if (!empty($value)) {
			$game_data 			=	array(
				'gid' 			=>	$gid,
				'uid' 			=>	$value,
				'player'		=>	$player[$value],
				'start_chips' 	=> 	$_REQUEST['chips_start'],
				'seat_position' => 	$key
			);
			$wpdb->insert($wpdb->prefix . 'kog_details', $game_data);
		}
	}
	return true;
}

/**
 * Prints a HTML input field
 *
 * @param  array   the args
 *
 * @return void
 */
function erp_html_form_input( $args = array() ) {
	$defaults = array(
		'placeholder'   => '',
		'required'      => false,
		'type'          => 'text',
		'class'         => '',
		'tag'           => '',
		'wrapper_class' => '',
		'label'         => '',
		'name'          => '',
		'id'            => '',
		'value'         => '',
		'help'          => '',
		'addon'         => '',
		'readonly'      => '',
		'addon_pos'     => 'before',
		'custom_attr'   => array(),
		'options'       => array(),
		'style'         => '',
		'function'      => '',
	);

	$field    = wp_parse_args( $args, $defaults );
	$field_id = empty( $field['id'] ) ? $field['name'] : $field['id'];

	$field_attributes = array_merge( array(
		'name'        => $field['name'],
		'id'          => $field_id,
		'class'       => $field['class'],
		'placeholder' => $field['placeholder'],
	), $field['custom_attr'] );

	if ( $field['required'] ) {
		$field_attributes['required'] = 'required';
	}

	$custom_attributes = erp_html_form_custom_attr( $field_attributes );

    // open tag
	if ( ! empty( $field['tag'] ) ) {
		echo '<' . $field['tag'] . ' class="erp-form-field ' . esc_attr( $field['name'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '">';
	}

	if ( ! empty( $field['style'])) {
		$field['style'] = "style='".$field['style']."'";
	}

	if ( ! empty( $field['label'] ) ) {
		erp_html_form_label( $field['label'], $field_id, $field['required'] );
	}

	if ( ! empty( $field['addon'] ) ) {
		echo '<div class="input-group">';

		if ( $field['addon_pos'] == 'before' ) {
			echo '<span class="input-group-addon">' . $field['addon'] . '</span>';
		}
	}

	switch ( $field['type'] ) {
		case 'text':
		case 'email':
		case 'number':
		case 'hidden':
		case 'url':
		echo '<input  ' . $field['style'] . ' ' . $field['readonly'] . ' type="' . $field['type'] . '" value="' . esc_attr( $field['value'] ) . '" ' . implode( ' ', $custom_attributes ) . ' ' . $field['function'] . ' />';
		break;

		case 'select':
		if ( $field['options'] ) {
			echo '<select ' . implode( ' ', $custom_attributes ) . '>';
			foreach ($field['options'] as $key => $value) {
				printf( "<option value='%s'%s>%s</option>\n", $key, selected( $field['value'], $key, false ), $value );
			}
			echo '</select>';
		}
		break;

		case 'textarea':
		echo '<textarea ' . $field['style'] . '  ' . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $field['value'] ) . '</textarea>';
		break;

		case 'wysiwyg':
		$editor_args = [
			'editor_class'  => $field['class'],
			'textarea_rows' => isset( $field['custom_attr']['rows'] ) ? $field['custom_attr']['rows'] : 10,
			'media_buttons' => isset( $field['custom_attr']['media'] ) ? $field['custom_attr']['media'] : false,
			'teeny'         => isset( $field['custom_attr']['teeny'] ) ? $field['custom_attr']['teeny'] : true,

		];

		wp_editor( $field['value'], $field['name'], $editor_args );
		break;

		case 'checkbox':
            //echo '<input type="hidden" value="off" name="' . $field['name'] . '" />';
		echo '<span class="checkbox">';
		echo '<label for="' . esc_attr( $field_attributes['id'] ) . '">';
		echo '<input type="checkbox" '.checked( $field['value'], 'on', false ).' value="on" ' . implode( ' ', $custom_attributes ) . ' />';
		echo wp_kses_post( $field['help'] );
		echo '</label>';
		echo '</span>';
		break;

		case 'multicheckbox':

		echo '<span class="checkbox">';
		unset( $custom_attributes['id'] );

		foreach ( $field['options'] as $key => $value ) {
			echo '<label for="' . esc_attr( $field_attributes['id'] ) . '-' . $key .'">';
			if ( ! empty( $field['value'] ) ) {
				if ( is_array( $field['value'] ) ) {
					$checked = in_array( $key, $field['value'] ) ? 'checked' : '';
				} else if ( is_string( $field['value'] ) ) {
					$checked = in_array( $key, explode(',', $field['value'] ) ) ? 'checked' : '';
				} else {
					$checked = '';
				}
			} else {
				$checked = '';
			}

			echo '<input type="checkbox" '. $checked .' id="' . esc_attr( $field_attributes['id'] ) . '-' . $key . '" value="'.$key.'" ' . implode( ' ', $custom_attributes ) . ' />';
			echo '<span class="checkbox-value">' . wp_kses_post( $value ) . '</span>';
			echo '</label>';
		}
		echo '</span>';
		break;

		case 'radio':
		if ( $field['options'] ) {
			foreach ( $field['options'] as $key => $value) {
				echo '<input type="radio" '.checked( $field['value'], $key, false ).' value="'.$key.'" ' . implode( ' ', $custom_attributes ) . ' />'. $value . '&nbsp;';
			}
		}
		break;
		case 'radio_group':
		if ( $field['options'] ) {
			foreach ( $field['options'] as $key => $value) {
				echo '<div style="float:left;width:100px;font-size:0.9em"><input type="radio" '.checked( $field['value'], $key, false ). $field['function'].$key .'" value="'.$key.'" ' . implode( ' ', $custom_attributes ) . '  />'. $value . '&nbsp;</div>';
			}
		}
		break;


        // 增加一个label的类型，便于用这个直接显示结果。
		case 'span':
		erp_html_form_label($field['value']);
		break;

		default:
            # code...
		break;
	}

	if ( ! empty( $field['addon'] ) ) {

		if ( $field['addon_pos'] == 'after' ) {
			echo '<span class="input-group-addon">' . $field['addon'] . '</span>';
		}

		echo '</div>';
	}

	if ( $field['type'] != 'checkbox' ) {
		erp_html_form_help( $field['help'] );
	}

    // closing tag
	if ( ! empty( $field['tag'] ) ) {
		echo '</' . $field['tag'] . '>';
	}
} 

/**
 * Handles an elements custom attribute
 *
 * @param  array   attributes as key/value pair
 *
 * @return array
 */
function erp_html_form_custom_attr( $attr = array(), $other_attr = array() ) {
	$custom_attributes = array();

	if ( ! empty( $attr ) && is_array( $attr ) ) {
		foreach ( $attr as $attribute => $value ) {
			if ( $value != '' ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}
	}

	if ( ! empty( $other_attr ) && is_array( $other_attr ) ) {
		foreach ( $attr as $attribute => $value ) {
			$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
		}
	}

	return $custom_attributes;
}

/**
 * Prints a label attribute
 *
 * @param  string  label vlaue
 * @param  string  id field for label
 *
 * @return void
 */
function erp_html_form_label( $label, $field_id = '', $required = false ) {
	$req = $required ? ' <span class="required">*</span>' : '';
	echo '<label for="' . esc_attr( $field_id ) . '">' . wp_kses_post( $label ) . $req . '</label>';
}

/**
 * Prints help text
 *
 * @param  string  the description
 *
 * @return void
 */
function erp_html_form_help( $value = '' ) {
	if ( ! empty( $value ) ) {
		echo '<span class="description">' . wp_kses_post( $value ) . '</span>';
	}
}


?>	