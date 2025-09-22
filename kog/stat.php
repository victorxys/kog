<?php
date_default_timezone_set('Asia/Shanghai');
require_once('functions.php');
$page_title = "Big Date";
// region PHP Data Logic
$_REQUEST['type'] = isset($_REQUEST['type']) ? $_REQUEST['type'] : "total";
$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : null;
$person_name = '';
if (!empty($uid) && !empty(get_player($uid))) {
    $person_name = get_player($uid)[0]->nickname;
}

$game_list = get_game_history_year();
$this_year = isset($_REQUEST['y']) ? $_REQUEST['y'] : date('Y');
$game_memo = null;

if (!empty($_REQUEST['md'])) {
    $game_memo = $_REQUEST['md'];
    $start_time = null;
    $end_time = null;
    $this_year = substr($game_memo, 0, 4);
} else {
    $start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : $this_year . '0101';
    $end_time = isset($_REQUEST['end_time']) ? $_REQUEST['end_time'] : $this_year . '1231';
}

$time_zone_start = $start_time;
$time_zone_end = $end_time;
$time_zone = $time_zone_start . "~" . $time_zone_end;
if (isset($_REQUEST['md']) && $_REQUEST['md'] != "") {
    $time_zone = $_REQUEST['md'];
}

$game_type = (isset($_REQUEST['game_type'])) ? $_REQUEST['game_type'] : null;
$game_type_info = game_type_array($game_type);
$stat_type = isset($_REQUEST['stat_type']) ? $_REQUEST['stat_type'] : 'cahs';

$game = get_game_by_args(array('status' => 2, 'start_time' => $start_time, 'memo' => $game_memo, 'end_time' => $end_time, 'game_type' => $game_type));
$if_data = empty($game) ? 0 : 1;

// Full data processing logic from original file
switch ($_REQUEST['type']) {
    case 'total':
    case 'detail': // Add detail case to use the same data logic
        $total_income_real = get_player_total_income_stat($game_memo, $start_time, $end_time, $game_type, "real_money");
        $data_total_real = isset($total_income_real['total']) ? $total_income_real['total'] : [];
        $total_income = get_player_total_income_stat($game_memo, $start_time, $end_time, $game_type, $stat_type);
        $total_times_add = isset($total_income['times_income_add']) ? $total_income['times_income_add'] : [];
        if (!empty($total_times_add)) {
            $i = 0;
            foreach ($total_times_add as $key => $value) {
                if (!empty(get_player($key)[0]->nickname)) {
                    $total_times_add_user[$i]['name'] = get_player($key)[0]->nickname;
                    if (isset($value)) {
                        foreach ($value as $k => $val) {
                            if ($val != -1) {
                                $total_times_add_user[$i]['data'][] = $val;
                                $total_times_add_user[$i]['date'][] = $k;
                            }
                        }
                    }
                    $i++;
                }
            }
        }
        if (!empty($total_income)) {
            $data_total = $total_income['total'];
            $data = $total_income['income'];
            $statTime = $total_income['date'];
            $date_avg = $total_income['avg'];
            $game_count = count($total_income['date']);
        }
        $winer_ra = count_winer_rate($game_memo, $start_time, $end_time, $game_type);
        $user_times_win_rate = $winer_ra['user_times_win_rate'] ?? [];
        if (!empty($user_times_win_rate)) {
            $i = 0;
            foreach ($user_times_win_rate as $key => $value) {
                if(!empty(get_player($key)[0]->nickname)){
                    $user_times_win_rate_user[$i]['name'] = get_player($key)[0]->nickname;
                    if (isset($value)) {
                        foreach ($value as $k => $val) {
                            $user_times_win_rate_user[$i]['data'][] = round($val*100,2);
                            $user_times_win_rate_user[$i]['date'][] = $k;
                        }
                    }
                    $i++;
                }
            }
        }
        unset($winer_ra['monthly_win']);
        unset($winer_ra['user_times_win_rate']);
        if (!empty($winer_ra)) {
            foreach ($winer_ra as $key => $value) {
                if (!empty($value[0])) {
                    $value[1] = $value[1] * 100;
                    $winer_rate[] = array(
                        'gamecount' => $value[2],
                        'name' => $value[0],
                        'y' => $value[1],
                        'wincont' => $value[3],
                        'uid' => $value[4],
                    );
                }
            }
        }
        break;
    case 'killrank':
        $kill_rank = get_best_killer($game_memo, $start_time, $end_time, $game_type);
        $kill_info_data = get_kill_info($game_memo, $start_time, $end_time, $game_type);
        $kill_info_killed_by = $kill_info_data['killed_by'] ?? [];
        $kill_info = $kill_info_data['killed'] ?? [];
        $killed_by_tem = [];
        $kill_graph = [];
        if (is_array($kill_rank) && is_array($kill_info_killed_by)) {
            $i = 0;
            foreach ($kill_rank as $key => $value) {
                $kill_rank_player_json[] = $value[0];
                foreach ($kill_info_killed_by as $k => $val) {
                    $killed_by_tem[$k]['name'] = get_player($k)[0]->nickname;
                    $killed_by_tem[$k]['data'][] = $val[$key] ?? '';
                }
            }
            foreach ($kill_info_killed_by as $k => $val) {
                foreach ($val as $m => $v) {
                    $kill_graph[] = [get_player($k)[0]->nickname, get_player($m)[0]->nickname, $v];
                }
            }
            if (is_array($killed_by_tem)) {
                foreach ($killed_by_tem as $key => $value) {
                    $killed_by[] = $value;
                }
            }
        }
        
        // Calculation for $killed array
        $players = get_player();
        $killed = [];
        if(!empty($players)){
            foreach ($players as $key => $value) {
                $killed[$value->id]['total'] = 0;
                $killed[$value->id]['win'] = 0;
                $killed[$value->id]['lose'] = 0;
                $killed[$value->id]['win_rate'] = 0;
                if (isset($kill_info[$value->id])) {
                    $killed[$value->id]['total'] = array_sum($kill_info[$value->id]);
                    $killed[$value->id]['win'] = array_sum($kill_info[$value->id]);
                    foreach ($kill_info[$value->id] as $k => $val) {
                        $killed[$value->id]['vs'][$k]['win']=$val;
                        $killed[$value->id]['vs'][$k]['lose'] = 0 ;
                        if (isset($kill_info[$k][$value->id])) {
                            $killed[$value->id]['vs'][$k]['lose'] = $kill_info[$k][$value->id];
                        }
                        $killed[$value->id]['vs'][$k]['total'] = $killed[$value->id]['vs'][$k]['win'] + $killed[$value->id]['vs'][$k]['lose'];
                        if ($killed[$value->id]['vs'][$k]['total'] > 0) {
                            $killed[$value->id]['vs'][$k]['win_rate'] =100* round($killed[$value->id]['vs'][$k]['win'] / $killed[$value->id]['vs'][$k]['total'],4)."%";
                        } else {
                            $killed[$value->id]['vs'][$k]['win_rate'] = "0%";
                        }
                    }
                }
                if (isset($kill_info_killed_by[$value->id])) {
                    $killed[$value->id]['total'] = $killed[$value->id]['total'] + array_sum($kill_info_killed_by[$value->id]);
                    $killed[$value->id]['lose'] = array_sum($kill_info_killed_by[$value->id]);
                    foreach ($kill_info_killed_by[$value->id] as $k => $val) {
                        $killed[$value->id]['vs'][$k]['lose']=$val;
                        if (!isset($killed[$value->id]['vs'][$k]['win'])) {
                            $killed[$value->id]['vs'][$k]['win'] = 0;
                        }
                        if (isset($kill_info_killed_by[$k][$value->id])) {
                            // This seems wrong, it should be from kill_info
                             if (isset($kill_info[$value->id][$k])) {
                                $killed[$value->id]['vs'][$k]['win'] = $kill_info[$value->id][$k];
                            }
                        }
                        $killed[$value->id]['vs'][$k]['total'] = $killed[$value->id]['vs'][$k]['win'] + $killed[$value->id]['vs'][$k]['lose'];
                        if ($killed[$value->id]['vs'][$k]['total'] > 0) {
                            $killed[$value->id]['vs'][$k]['win_rate'] =100* round($killed[$value->id]['vs'][$k]['win'] / $killed[$value->id]['vs'][$k]['total'],4)."%";
                        } else {
                             $killed[$value->id]['vs'][$k]['win_rate'] = "0%";
                        }
                    }
                }
                if($killed[$value->id]['win'] == 0){
                    $killed[$value->id]['win_rate'] = "0%";
                }else{
                    if ($killed[$value->id]['total'] > 0) {
                        $killed[$value->id]['win_rate'] = 100*round($killed[$value->id]['win']/$killed[$value->id]['total'],2)."%";
                    } else {
                        $killed[$value->id]['win_rate'] = "0%";
                    }
                }
            }
        }
        break;
    case 'lucky':
        $lucky_info = get_lucky_info($start_time, $game_memo);
        break;
}

$total_cost = 0;
if (isset($winer_rate) && is_array($winer_rate)) {
    foreach ($winer_rate as $key => $value) {
        $uid_for_order = $value['uid'];
        $order_array[$uid_for_order]['uid'] = $uid_for_order;
        $order_array[$uid_for_order]['name'] = $value['name'];
        $order_array[$uid_for_order]['gamecount'] = floatval($value['gamecount']) . '/' . ($game_count ?? 0) . '/' . ($total_income['play_memo'] ?? 0);
        $order_array[$uid_for_order]['wincont'] = $value['wincont'] . "(" . round($value['y'], 2) . "%)";
        $order_array[$uid_for_order]['total_income'] = $total_income['total_uid'][$uid_for_order][1];
        $order_array[$uid_for_order]['game_cost_player'] = $total_income['total_uid'][$uid_for_order][2];
        $order_array[$uid_for_order]['total_income_pre'] = ($value['gamecount'] > 0) ? round($total_income['total_uid'][$uid_for_order][1] / $value['gamecount'], 2) : 0;
        $total_income_order[$uid_for_order] = $order_array[$uid_for_order]['total_income'];
        $total_cost += $order_array[$uid_for_order]['game_cost_player'];
    }
    if(isset($total_income_order)) arsort($total_income_order);

    $user_should_win = [];
    $user_should_lose = [];
    foreach ($total_income_order as $key => $value) {
        if ($value >= 0) {
            $user_should_win[$key]['should_get'] = $value;
            $user_should_win[$key]['get'] = $value;
        } else {
            $user_should_lose[$key] = $value;
        }
    }
    if(isset($user_should_win)) arsort($user_should_win);
    if(isset($user_should_lose)) ksort($user_should_lose);

    $should_get = [];
    if(isset($user_should_lose) && isset($user_should_win)){
        foreach ($user_should_lose as $key => $value) {
            $lose_id = $key;
            $lose = $value;
            foreach ($user_should_win as $k => $val) {
                $lose_should = $val['should_get'] + $lose;
                if ($lose_should >= 0) {
                    $should_get[$lose_id][$k]['get'] = abs($lose);
                    $user_should_win[$k]['get'] = abs($lose);
                    $should_get[$lose_id][$k]['should_get'] = $lose_should;
                    $user_should_win[$k]['should_get'] = $lose_should;
                    $lose = 0;
                    break;
                } else {
                    $should_get[$lose_id][$k]['get'] = $val['should_get'];
                    $user_should_win[$k]['get'] = $val['should_get'];
                    $should_get[$lose_id][$k]['should_get'] = 0;
                    $user_should_win[$k]['should_get'] = 0;
                    $lose = $lose_should;
                }
            }
        }
    }
    $pay_info_graph = [];
    foreach ($should_get as $key => $value) {
        foreach ($value as $k => $val) {
            $pay_info_graph[] = [get_player($key)[0]->nickname, get_player($k)[0]->nickname, $val['get']];
        }
    }
}

// endregion

require_once("kog_header.php");
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form class="row align-items-center" method="get">
                        <input type="hidden" name="type" value="<?php echo htmlspecialchars($_REQUEST['type']); ?>">
                        <div class="col-auto">
                            <div class="horizontal-scroll-wrapper">
                                <a href="?type=total&y=<?php _e($this_year) ?>" class="btn btn-sm <?php echo $_REQUEST['type'] == 'total' ? 'btn-primary' : 'btn-outline-primary'; ?>">Overview</a>
                                <a href="?type=detail&y=<?php _e($this_year) ?>" class="btn btn-sm <?php echo $_REQUEST['type'] == 'detail' ? 'btn-primary' : 'btn-outline-primary'; ?>">Details</a>
                                <a href="?type=killrank&y=<?php _e($this_year) ?>" class="btn btn-sm <?php echo $_REQUEST['type'] == 'killrank' ? 'btn-primary' : 'btn-outline-primary'; ?>">Killer</a>
                                <a href="?type=lucky&y=<?php _e($this_year) ?>" class="btn btn-sm <?php echo $_REQUEST['type'] == 'lucky' ? 'btn-primary' : 'btn-outline-primary'; ?>">Lucky</a>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control form-control-sm" name="y" onchange="this.form.submit()">
                                <option value="">Select Year</option>
                                <?php foreach ($game_list as $year_item) : ?>
                                    <option value="<?php echo $year_item; ?>" <?php echo ($this_year == $year_item) ? 'selected' : ''; ?>><?php echo $year_item; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($if_data) : ?>
        <?php if ($_REQUEST['type'] == 'total') : ?>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><?php _e($time_zone . ":" . $game_type_info) ?> &nbsp; 总成本：<?php _e(round($total_cost, 0)) ?> </h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">Player</th>
                                        <th class="text-center">参与/总局数</th>
                                        <th class="text-center">Win&Rate</th>
                                        <th class="text-center">Income-Cost(￥)</th>
                                        <th class="text-center">Income.avg(￥)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($total_income_order)): foreach ($total_income_order as $key => $value) : ?>
                                        <tr>
                                            <td><p class="text-center"><?php _e($order_array[$key]['name']) ?></p></td>
                                            <td><p class="text-center"><?php _e($order_array[$key]['gamecount']) ?></p></td>
                                            <td><p class="text-center"><?php _e($order_array[$key]['wincont']) ?></p></td>
                                            <td><p class="text-center"><?php _e($order_array[$key]['total_income']); if($order_array[$key]['game_cost_player'] > 0) { _e("-" . $order_array[$key]['game_cost_player'] . " = " . ($order_array[$key]['total_income'] - $order_array[$key]['game_cost_player'])); } ?></p></td>
                                            <td><p class="text-center"><?php _e($order_array[$key]['total_income_pre']) ?></p></td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header"><h6 class="mb-0">Cumulative Income Trend</h6></div>
                        <div class="card-body"><div id="container-timesadd" style="height: 400px"></div></div>
                    </div>
                </div>
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header"><h6 class="mb-0">Cumulative Win Rate Trend</h6></div>
                        <div class="card-body"><div id="container-timeswinrateadd" style="height: 400px"></div></div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                 <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header"><h6 class="mb-0">Profit Margin</h6></div>
                        <div class="card-body"><div id="container-pie" style="height: 400px"></div></div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header"><h6 class="mb-0">ALL支付关系</h6></div>
                        <div class="card-body"><div id="container-fuqian"></div></div>
                    </div>
                </div>
            </div>
        <?php elseif ($_REQUEST['type'] == 'detail') : ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h6 class="mb-0">Income Details</h6></div>
                        <div class="card-body"><div id="container-detail" style="height: 800px"></div></div>
                    </div>
                </div>
            </div>
        <?php elseif ($_REQUEST['type'] == 'killrank') : ?>
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header"><h6 class="mb-0">Killer Board</h6></div>
                        <div class="card-body"><div id="container-duidie" style="height: 400px"></div></div>
                    </div>
                </div>
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header"><h6 class="mb-0">Kill Relationship</h6></div>
                        <div class="card-body"><div id="container-hexuan"></div></div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6 class="mb-0">All In 胜率</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <?php foreach($killed as $key => $value): if ($value['total'] > 0): ?>
                                <div class="table-responsive p-0">
                                    <div class="card-header border-bottom">
                                        <h6 class="mb-0"><?php _e(get_player($key)[0]->nickname)?>: <?php _e($value['win_rate'])?>&nbsp;(Win:<?php _e($value['win'])?> / Total:<?php _e($value['total'])?>)</h6>
                                    </div>
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">VS 玩家</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Win / Total</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">胜率</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(isset($value['vs'])): ksort($value['vs']); foreach($value['vs'] as $k => $val): if ($val['total'] > 0): ?>
                                            <tr>
                                                <td><div class="d-flex px-2 py-1"><h6 class="mb-0 text-sm"><?php _e(get_player($k)[0]->nickname)?></h6></div></td>
                                                <td><p class="text-sm font-weight-bold mb-0"><?php _e($val['win'])?>/<?php _e($val['total'])?></p></td>
                                                <td class="align-middle text-center text-sm"><span class="badge badge-sm bg-gradient-success"><?php _e($val['win_rate'])?></span></td>
                                            </tr>
                                            <?php endif; endforeach; endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6 class="mb-0">接 All In 赢了</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                        <?php if(isset($kill_info)): foreach($kill_info as $key => $value): ?>
                            <div class="table-responsive p-0">
                                <div class="card-header border-bottom">
                                    <h6 class="mb-0"><?php _e(get_player($key)[0]->nickname)?> (<?php _e(array_sum($value))?>次)</h6>
                                </div>
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">玩家</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">被清台（次）</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">占比</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php arsort($value); foreach($value as $k => $val): ?>
                                        <tr>
                                            <td><div class="d-flex px-2 py-1"><h6 class="mb-0 text-sm"><?php _e(get_player($k)[0]->nickname)?></h6></div></td>
                                            <td><p class="text-sm font-weight-bold mb-0"><?php _e($val)?></p></td>
                                            <td class="align-middle text-center text-sm"><span class="badge badge-sm bg-gradient-info"><?php _e(round($val/array_sum($value)*100,2))?>%</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6 class="mb-0">推 All In 输了</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                        <?php if(isset($kill_info_killed_by)): foreach($kill_info_killed_by as $key => $value): ?>
                            <div class="table-responsive p-0">
                                <div class="card-header border-bottom">
                                    <h6 class="mb-0"><?php _e(get_player($key)[0]->nickname)?>: 被清台(<?php _e(array_sum($value))?>次)</h6>
                                </div>
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">玩家</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">清台</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">占比</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php arsort($value); foreach($value as $k => $val): ?>
                                        <tr>
                                            <td><div class="d-flex px-2 py-1"><h6 class="mb-0 text-sm"><?php _e(get_player($k)[0]->nickname)?></h6></div></td>
                                            <td><p class="text-sm font-weight-bold mb-0"><?php _e($val)?></p></td>
                                            <td class="align-middle text-center text-sm"><span class="badge badge-sm bg-gradient-danger"><?php _e(round($val/array_sum($value)*100,2))?>%</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($_REQUEST['type'] == 'lucky') : ?>
             <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h6 class="mb-0">Lucky Stars</h6></div>
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">Player</th><th class="text-center">Total</th><th class="text-center">Royal Flush</th><th class="text-center">Straight Flush</th><th class="text-center">Four of a Kind</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($lucky_info)): foreach($lucky_info as $k => $val):?>
                                    <tr>
                                        <td class="text-center"><?php _e(get_player($k)[0]->nickname)?></td>
                                        <td class="text-center"><?php _e($val['total']['times'] . "次 (￥" . $val['total']['money'] . ")")?></td>
                                        <td class="text-center"><?php _e($val['royal straight flush']['times'] . "次 (￥" . $val['royal straight flush']['money'] . ")")?></td>
                                        <td class="text-center"><?php _e($val['straight flush']['times'] . "次 (￥" . $val['straight flush']['money'] . ")")?></td>
                                        <td class="text-center"><?php _e($val['four']['times'] . "次 (￥" . $val['four']['money'] . ")")?></td>
                                    </tr>
                                <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="row">
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <h5 class="mb-0">No data for this period.</h5>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once("kog_footer.php"); ?>

<script src="https://code.highcharts.com/12.4.0/highcharts.js"></script>
<script src="https://code.highcharts.com/12.4.0/highcharts-more.js"></script>
<script src="https://code.highcharts.com/12.4.0/modules/exporting.js"></script>
<script src="https://code.highcharts.com/12.4.0/modules/sankey.js"></script>
<script src="https://code.highcharts.com/12.4.0/modules/dependency-wheel.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/i18n/zh-CN.js"></script>
<script src="https://code.highcharts.com/12.4.0/modules/accessibility.js"></script>
<script type="text/javascript">
    $(function () {
        <?php
            // Initialize all variables to null to avoid JS errors if they are not set
            $data_total = $data_total ?? null;
            $data_total_real = $data_total_real ?? null;
            $winer_rate = $winer_rate ?? null;
            $total_times_add_user = $total_times_add_user ?? null;
            $user_times_win_rate_user = $user_times_win_rate_user ?? null;
            $kill_rank = $kill_rank ?? null;
            $killed_by = $killed_by ?? [];
            $killed = $killed ?? [];
            $kill_rank_player_json = $kill_rank_player_json ?? [];
            $kill_graph = $kill_graph ?? [];
            $pay_info_graph = $pay_info_graph ?? [];
            $game_type_info = $game_type_info ?? null;
            $time_zone = $time_zone ?? null;
            $statTime = $statTime ?? [];
            $data = $data ?? [];
        ?>
        var statPlayer = <?php echo json_encode($data_total) ?>;
        var dataTotalReal = <?php echo json_encode($data_total_real) ?>;
        var winerRate = <?php echo json_encode($winer_rate) ?>;
        var totalTimesAddUser = <?php echo json_encode($total_times_add_user) ?>;
        var userTimesWinRateUser = <?php echo json_encode($user_times_win_rate_user) ?>;
        var killRank = <?php echo json_encode($kill_rank) ?>;
        var killedBy = <?php echo json_encode($killed_by) ?>;
        var killRankPlayerJson = <?php echo json_encode($kill_rank_player_json) ?>;
        var killGraph = <?php echo json_encode($kill_graph) ?>;
        var payInfoGraph = <?php echo json_encode($pay_info_graph) ?>;
        var gameTypeInfo = <?php echo json_encode($game_type_info) ?>;
        var statTimeZone = <?php echo json_encode($time_zone) ?>;
        var statTimej = <?php echo json_encode($statTime) ?>;
        var statTestj = <?php echo json_encode($data) ?>;

        if ($('#container-total').length) {
            Highcharts.chart('container-total', {
                colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
                chart: { type: 'column' },
                title: { text: gameTypeInfo+'总收支' },
                subtitle: { text: statTimeZone },
                xAxis: { type: 'category', labels: {} },
                yAxis: { title: { text: '总金额' } },
                legend: { enabled: true },
                tooltip: { pointFormat: '{series.name}: <b>{point.y:.1f}元</b><br>', shared: true },
                credits: { enabled: false },
                series: [{
                    name: '实收',
                    data: statPlayer,
                    dataLabels: { enabled: true, color: '#FFFFFF', align: 'center', style: { textShadow: '0 0 1px black' } }
                },{
                    name: '应收',
                    data: dataTotalReal,
                    dataLabels: { enabled: true, color: '#FFFFFF', align: 'center', style: { textShadow: '0 0 1px black' } }
                }]
            });
        }

        if ($('#container-winer').length) {
            Highcharts.chart('container-winer', {
                colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
                chart: { type: 'column' },
                title: { text: gameTypeInfo+'胜率' },
                subtitle: { text: statTimeZone },
                xAxis: { type: 'category', labels: {} },
                yAxis: { title: { text: '胜率' } },
                tooltip: { formatter: function () { return '<b>'+this.point['name'] + '</b><br/>' + this.series.name + ': ' + this.y+'%<br/>' + this.point.gamecount + '场胜' + this.point.wincont +'场'; } },
                credits: { enabled: false },
                series: [{
                    name: '胜率',
                    data: winerRate,
                    dataLabels: { enabled: true, format: '{point.y:.2f}%' }
                }]
            });
        }

        if ($('#container-pie').length) {
            Highcharts.chart('container-pie', {
                colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
                chart: { plotBackgroundColor: null, plotBorderWidth: null, plotShadow: false, type: 'pie' },
                title: { text: gameTypeInfo+'盈利比例' },
                tooltip: { pointFormat: '{point.name}: <b>{point.percentage:.1f}%</b>' },
                plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.percentage:.1f} %' } } },
                credits: { enabled: false },
                series: [{
                    name: '盈利占比',
                    data: statPlayer
                }]
            });
        }

        if ($('#container-timesadd').length) {
            Highcharts.chart('container-timesadd', {
                colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
                chart: { type: 'spline' },
                title: { text: '' },
                xAxis: { categories: totalTimesAddUser ? totalTimesAddUser.map(s => s.date).flat() : [] },
                yAxis: { title: { text: false } },
                tooltip: { valueSuffix: '元' },
                plotOptions: { spline: { lineWidth: 4, states: { hover: { lineWidth: 5 } }, marker: { enabled: false } } },
                credits: { enabled: false },
                series: totalTimesAddUser
            });
        }

        if ($('#container-timeswinrateadd').length) {
            Highcharts.chart('container-timeswinrateadd', {
                colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
                chart: { type: 'spline' },
                title: { text: '' },
                xAxis: { categories: userTimesWinRateUser ? userTimesWinRateUser.map(s => s.date).flat() : [] },
                yAxis: { title: { text: false } },
                tooltip: { valueSuffix: '%' },
                plotOptions: { spline: { lineWidth: 4, states: { hover: { lineWidth: 5 } }, marker: { enabled: false } } },
                credits: { enabled: false },
                series: userTimesWinRateUser
            });
        }

        if ($('#container-duidie').length) {
            Highcharts.chart('container-duidie', {
                colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac'],
                chart: { type: 'column' },
                title: { text: '清台榜' },
                xAxis: { categories: killRankPlayerJson },
                yAxis: { title: { text: '清台总量' }, stackLabels: { enabled: true } },
                plotOptions: { column: { stacking: 'normal' } },
                tooltip: { formatter: function () { return  this.series.name + ': ' + this.y ; } },
                credits: { enabled: false },
                series: killedBy
            });
        }

        if ($('#container-fuqian').length) {
            Highcharts.chart('container-fuqian', {
                title: { text: '支付关系' },
                credits: { enabled: false },
                series: [{
                    keys: ['from', 'to', 'weight'],
                    data: payInfoGraph,
                    type: 'dependencywheel',
                    name: '支付详情',
                    dataLabels: { color: '#333', textPath: { enabled: true, attributes: { dy: 5 } }, distance: 10 },
                    size: '95%'
                }]
            });
        }

        if ($('#container-hexuan').length) {
            Highcharts.chart('container-hexuan', {
                title: { text: 'All in 胜负关系' },
                credits: { enabled: false },
                series: [{
                    keys: ['from', 'to', 'weight'],
                    data: killGraph,
                    type: 'dependencywheel',
                    name: 'All in 输给了谁 ',
                    dataLabels: { color: '#333', textPath: { enabled: true, attributes: { dy: 5 } }, distance: 10 },
                    size: '95%'
                }]
            });
        }

        if ($('#container-detail').length) {
            Highcharts.chart('container-detail', {
                colors: ['#536bdb', '#1cc7ea', '#f22f52', '#2fc682', '#9055A2','#f09aac','#ffcc00','#ff9900'],
                chart: { type: 'bar' },
                title: { text: '' },
                xAxis: { categories: statTimej, labels: {} },
                yAxis: { title: { enabled: false, text: false } },
                plotOptions: { bar: { stacking: 'normal', dataLabels: { enabled: true, color: 'white', style: { textShadow: '0 0 3px black' } } } },
                credits: { enabled: false },
                series: statTestj
            });
        }
    });
</script>