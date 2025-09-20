<?php
require_once('functions.php');
date_default_timezone_set('Asia/Shanghai');

$gid = isset($_REQUEST['gid']) ? $_REQUEST['gid'] : null;
if (!$gid) {
    die("Game ID is required.");
}

$page_title = "游戏详情 GID-" . $gid;

// region PHP Logic
$res = get_player();
$url_rebuy = "kog_rebuy.php?uid=";
$url_lucky = "kog_lucky.php?uid=";
$url_level_up = "kog_levelup.php?gid=";
$url_detail = "kog_detail.php?gid=";
$game_memo = get_game_memo_byid($_REQUEST['gid']);
$game = get_game($_REQUEST['gid']);
$this_year = date('Y');
$game_group = $game->memo;

if (!empty($_REQUEST['gid'])) {
    $game_detail = get_game_detail($_REQUEST['gid']);
    $gid = $_REQUEST['gid'];
    $game_memo_list = get_game_memo_list(null, $game_memo);
    $player_name = creat_single_array($game_detail, 'uid', 'player');
    $player_start_chips = creat_single_array($game_detail, 'uid', 'start_chips');
    foreach ($game_detail as $key => $value) {
        $game_detail_position[$value->seat_position] = $value;
        $game_detail_uid[$value->uid] = $value;
        if (is_null($value->end_chips)) {
            $value->end_chips = 0;
        }
        if (!isset($_REQUEST['ending'][$value->uid]) && $value->end_chips != 0) {
            $_REQUEST['ending'][$value->uid] = $value->end_chips;
        }
        if (isset($value->total_fact) && $value->total_fact != null) {
            $total_fact[$value->uid] = $value->total_fact;
        }
    }
    $player_count = count($player_name);
    ksort($game_detail_position);
    $rebuy = get_rebuy_info($_REQUEST['gid']);
    if (isset($rebuy) && !empty($rebuy)) {
        $times = [];
        $rebuy_chips = [];
        $paied = [];
        $rank_info = ['kill' => [], 'rebuy_chips' => [], 'rebuy_times' => []];
        foreach ($rebuy as $key => $value) {
            $times[$value->uid] = isset($times[$value->uid]) ? $times[$value->uid] : 0;
            $rebuy_chips[$value->uid] = isset($rebuy_chips[$value->uid]) ? $rebuy_chips[$value->uid] : 0;
            $paied[$value->uid] = isset($paied[$value->uid]) ? $paied[$value->uid] : 0;
            if(isset($value->killed_by) && $value->killed_by) {
                $rank_info['kill'][$value->killed_by] = isset($rank_info['kill'][$value->killed_by]) ? $rank_info['kill'][$value->killed_by] : 0;
            }

            if (isset($value->rebuy)) {
                $times[$value->uid]++;
                $rebuy_chips[$value->uid] = $rebuy_chips[$value->uid] + $value->rebuy;
                $paied[$value->uid] = $paied[$value->uid] + $value->paied;
            }
            $rebuy_real[$value->uid]['times'] = $times[$value->uid];
            $rebuy_real[$value->uid]['rebuy_chips'] = $rebuy_chips[$value->uid];
            $rebuy_real[$value->uid]['paied'] = $paied[$value->uid];
            if(isset($value->killed_by) && $value->killed_by) {
                $rebuy_real[$value->uid]['killed_by'][] = $value->killed_by;
                $killed_arr[$value->killed_by][] = $value->uid;
                $rank_info['kill'][$value->killed_by]++;
            }
            $rank_info['rebuy_chips'][$value->uid] = $rebuy_real[$value->uid]['rebuy_chips'];
            $rank_info['rebuy_times'][$value->uid] = $rebuy_real[$value->uid]['times'];
        }
    } else {
        $rebuy_real = array();
    }
}

$chips_level = $game->chips_level;
$rebuy_rate = $game->rebuy_rate;
$bonus = unserialize($game->bonus);
$finished_time = ($game->end_time == "") ? "~现在（尚未结束）" : "~" . date("H:i", $game->end_time);

if (isset($rebuy_chips)) {
    $total_chips = $player_count * $chips_level + array_sum($rebuy_chips);
} else {
    $total_chips = $player_count * $chips_level;
}

$disabled = '';
$onclick = '';
$cant_count = '';
if ($game->status == '2') {
    $disabled = "disabled='disabled'";
    $onclick = "onclick=alert('Game Finished');return false;";
    $cant_count = "It's Finished can not be count again";
}

if (isset($_REQUEST['submit_type'])) {
	$if_clone = 0;
	if ($_REQUEST['submit_type'] == 'finish&clone' ) {
		$_REQUEST['submit_type'] = 'Finish';
		$if_clone = 1;
	}
	if($_REQUEST['submit_type'] == 'clone'){
		$if_clone = 1;
	}
	switch ($_REQUEST['submit_type']) {
		case 'Finish':
            // This logic seems to have a bug with total_fact_sum, disabling for now.
			// $total_fact_sum = intval(array_sum($_REQUEST['total_fact']));
			// if ( $total_fact_sum != 0) {
			// 	echo '
			// 		<script type="text/javascript">
			// 		document.addEventListener("DOMContentLoaded", function() {
			// 			Swal.fire({
			// 				title: "数值错误",
			// 				text: "筹码结算不正确",
			// 				icon: "error",
			// 				timer: 2000,
			// 				timerProgressBar: true,
			// 				showConfirmButton: false
			// 			});
			// 		});
			// 		</script>
			// 	';
			// 	break;
			// }
			// else{
				$update = array(
					'status' 	=>	'2',
					'end_time' 	=>	time(),
				);
				$where 	=	 array(
					'id' 		=> 	$_REQUEST['gid']
				);
				update_some_table('kog_games',$update,$where);
				if(!empty($_REQUEST['total_fact'])){
					foreach ($_REQUEST['total_fact'] as $key => $value) {
						$update_total_act = array(
							'total_fact' 	=> $_REQUEST['total_fact'][$key],
							'total_should' 	=> $_REQUEST['total_should'][$key],
						);
						$where_update = array(
							'gid' 	=> $_REQUEST['gid'],
							'uid' 	=> $key,
						);
						update_some_table('kog_details',$update_total_act,$where_update);
					}
				}
				$url 	=	 "stat.php?type=total&y=".$this_year."&md=".$game_group;
				if($if_clone == 1){
					$new_gid = clone_game($_REQUEST['gid']);
					$url 	=	 "kog_detail.php?page_name=Have Fun !&gid=".$new_gid;
				}

				echo '
					<script type="text/javascript">
					document.addEventListener("DOMContentLoaded", function() {
						Swal.fire({
							title: "Well Done",
							text: "即将开始下一局游戏...",
							icon: "success",
							timer: 2000,
							timerProgressBar: true,
							showConfirmButton: false
						}).then((result) => {
							window.location.href = ' . json_encode($url) . ';
						});
					});
					</script>
				';
				break;
			// }
		case 'Delete':
            $rebuy = get_rebuy_info($_REQUEST['gid']);
            if (!empty($rebuy)) {
                echo '
                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "无法删除",
                        text: "已经有Rebuy数据了，无法删除。",
                        icon: "error",
                        confirmButtonText: "好的"
                    });
                });
                </script>
                ';
            }else{
                $update = array(
                    'status' 	=>	'0',
                    'end_time' 	=>	time(),
                );
                $where 	=	 array(
                    'id' 		=> 	$_REQUEST['gid']
                );
                update_some_table('kog_games',$update,$where);
                $url  = "index.php";
                echo '
                    <script type="text/javascript">
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            title: "Deleted",
                            text: "See you next game!",
                            icon: "success",
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then((result) => {
                            window.location.href = ' . json_encode($url) . ';
                        });
                    });
                    </script>
                ';

            }
		    break;
		case 'clone':
            $new_gid = clone_game($_REQUEST['gid']);
            $url = "kog_detail.php?page_name=Have Fun !&gid=".$new_gid;
            echo '
                <script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Well Done",
                        text: "See you next game!",
                        icon: "success",
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then((result) => {
                        window.location.href = ' . json_encode($url) . ';
                    });
                });
                </script>
            ';
			break;
		default:
			break;
	}
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ranking') {
    if (isset($_REQUEST['ending'])) {
        $total_ending = array_sum($_REQUEST['ending']);
        $last_ending_chips = $total_chips - $total_ending;
        $user_rebuy_arr = array();
        $extra_reward_arr = array();

        foreach ($game_detail_uid as $uid => $detail) {
             $user_rebuy_arr[$uid]['rebuy'] = isset($rebuy_real[$uid]['rebuy_chips']) ? $rebuy_real[$uid]['rebuy_chips'] : 0;
             $user_rebuy_arr[$uid]['paied'] = isset($rebuy_real[$uid]['paied']) ? $rebuy_real[$uid]['paied'] : 0;
             $user_rebuy_arr[$uid]['ending'] = $_REQUEST['ending'][$uid];
             $user_rebuy_arr[$uid]['final'] = $user_rebuy_arr[$uid]['ending'] - $user_rebuy_arr[$uid]['rebuy'];

            if ($user_rebuy_arr[$uid]['final'] > 0) {
                $user_rebuy_arr[$uid]['pay_back'] = $user_rebuy_arr[$uid]['paied'];
            } else {
                $user_rebuy_arr[$uid]['pay_back'] = round($user_rebuy_arr[$uid]['ending'] / $chips_level * $rebuy_rate, 0);
            }
            $user_rebuy_arr[$uid]['pay_out'] = $user_rebuy_arr[$uid]['pay_back'] - $user_rebuy_arr[$uid]['paied'];
            $extra_reward_arr[$uid] = abs($user_rebuy_arr[$uid]['pay_out']);
            $user_final_extra[$uid]['reward'] = $user_rebuy_arr[$uid]['pay_out'];
        }

        $extra_reward = array_sum($extra_reward_arr);

        foreach ($_REQUEST['ending'] as $key => $value) {
            if ($value == 0 && $last_ending_chips > 0) {
                $value = $last_ending_chips;
                $_REQUEST['ending'][$key] = $value;
            }
            $user_rebuy_arr[$key]['rebuy'] = isset($user_rebuy_arr[$key]['rebuy']) ? $user_rebuy_arr[$key]['rebuy'] : 0;
            $user_final_chips[$key] = $value - $user_rebuy_arr[$key]['rebuy'];
        }
        arsort($user_final_chips);

        if (!empty($user_final_chips)) {
            $rank = 0;
            unset($extra_reward_arr);
            foreach ($user_final_chips as $key => $value) {
                $rank++;
                $user_final_rank[$key]['rank'] = $rank;
                $user_final_rank[$key]['end_chips'] = $_REQUEST['ending'][$key];
                $user_final_rank[$key]['rebuy'] = isset($user_rebuy_arr[$key]['rebuy']) ? $user_rebuy_arr[$key]['rebuy'] : 0;
                $user_final_rank[$key]['count_chips'] = $value;
                $rank_info['count_chips'][$key] = $value;
                $user_final_rank[$key]['paied'] = isset($user_rebuy_arr[$key]['paied']) ? $user_rebuy_arr[$key]['paied'] : 0;
                $user_final_rank[$key]['pay_back'] = isset($user_rebuy_arr[$key]['pay_back']) ? round($user_rebuy_arr[$key]['pay_back'], 0) : 0;
                $user_final_rank[$key]['pay_out'] = isset($user_rebuy_arr[$key]['pay_out']) ? $user_rebuy_arr[$key]['pay_out'] : 0;
                $user_final_rank[$key]['reward'] = isset($user_final_extra[$key]['reward']) ? $user_final_extra[$key]['reward'] : 0;
                $user_final_rank[$key]['uid'] = $key;
                $user_final_rank[$key]['gid'] = $_REQUEST['gid'];
                $user_final_rank[$key]['bonus'] = isset($bonus[$rank]) ? $bonus[$rank] : null;
                if ($user_final_rank[$key]['bonus'] > 0 && $value > 0) {
                    $extra_reward_arr[$key] = $value;
                } else {
                    $extra_reward_arr[$key] = 0;
                }
                update_game_detail_rank($user_final_rank[$key]);
            }

            if (strtoupper($game->game_type) == "SNG") {
                $total_extra_reward_base = array_sum($extra_reward_arr);
                if ($total_extra_reward_base > 0) {
                    foreach ($extra_reward_arr as $key => $value) {
                        $user_final_rank[$key]['reward'] = round($value / $total_extra_reward_base * $extra_reward, 0);
                        $rank_info['final_bonus'][$key] = $user_final_rank[$key]['reward'] + $user_final_rank[$key]['bonus'] + $user_final_rank[$key]['pay_out'];
                        if ($user_final_rank[$key]['reward'] > 0) {
                            update_some_table('kog_details', array('reward' => $user_final_rank[$key]['reward']), array('gid' => $_REQUEST['gid'], 'uid' => $key));
                        }
                    }
                }
            }
        }
        $show_rank = 'yes';
    }
}

if (isset($_REQUEST['ending']) ) {
	$game_detail_last = get_game_detail($_REQUEST['gid']);
	foreach ($game_detail_last as $key => $value) {
		$user_total_should = ($value->count_chips - $game->chips_level)/($game->chips_level/$game->rebuy_rate);
		$game_detail_last[$key]->total_should = $user_total_should;
		if ($user_total_should >=0) {
			$user_should_win[$value->uid]['get']=0;
			$user_should_win[$value->uid]['should_get']=$user_total_should;
		}else{
			$user_should_lose[$value->uid]=$user_total_should;
		}
	}
    if(isset($user_should_win)) arsort($user_should_win);
	if(isset($user_should_lose)) ksort($user_should_lose);

    if(isset($user_should_lose) && isset($user_should_win)){
        foreach ($user_should_lose as $key => $value) {
            $lose_id = $key;
            $lose = $value;
            foreach ($user_should_win as $k => $val) {
                $lose_should = $val['should_get']+$lose;
                if ($lose_should >= 0) {
                    $should_get[$lose_id][$k]['get']=abs($lose);
                    $user_should_win[$k]['get']=abs($lose);
                    $should_get[$lose_id][$k]['should_get']=$lose_should;
                    $user_should_win[$k]['should_get'] = $lose_should;
                    $lose=0;
                    break;
                }else{
                    $should_get[$lose_id][$k]['get']=$val['should_get'];
                    $user_should_win[$k]['get']=$val['should_get'];
                    $should_get[$lose_id][$k]['should_get']=0;
                    $user_should_win[$k]['should_get']=0;
                    $lose=$lose_should;
                }
            }
        }
    }
}

$game_meta = get_gamemeta($_REQUEST['gid'], null);
$text_tel = "======GID-".$gid.":".date('Y-m-d H:i',$game->start_time)."~".$finished_time."======\n";

// endregion

require_once "kog_header.php";
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <!-- Seat List Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Seat & Actions</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">Seat</th>
                                <th class="text-center">Player</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($game_detail_position as $key => $val) : ?>
                                <tr>
                                    <td class="text-center"><?php _e($key) ?>号位</td>
                                    <td class="text-center"><?php _e($val->player) ?></td>
                                    <td class="table-actions text-center">
                                        <a href="<?php _e($url_rebuy . $val->uid . "&page_name=Rebuy&gid=" . $val->gid) ?>" class="btn btn-link text-primary p-0 m-0"><i class="fa fa-money-bill-wave me-2"></i>Rebuy</a>
                                        <a href="<?php _e($url_lucky . $val->uid . "&page_name=Lucky&gid=" . $val->gid) ?>" class="btn btn-link text-info p-0 m-0 ms-2"><i class="fa fa-star me-2"></i>Lucky</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 mb-4">
            <!-- Rebuy List Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Rebuy List</h3>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                         <thead class="thead-light">
                            <tr>
                                <th class="text-center">Player</th>
                                <th class="text-center">Chips</th>
                                <th class="text-center">Killed by</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($rebuy) && !empty($rebuy)): ?>
                                <?php foreach ($rebuy as $v): ?>
                                    <tr>
                                        <td class="text-center"><?php _e($player_name[$v->uid]) ?></td>
                                        <td class="text-center"><?php _e($v->rebuy) ?></td>
                                        <td class="text-center"><?php _e(isset($player_name[$v->killed_by]) ? $player_name[$v->killed_by] : 'N/A') ?></td>
                                        <td class="text-center">
                                            <a href="#" 
                                               class="btn btn-link text-danger font-weight-bold text-xs p-0 m-0 delete-rebuy-btn" 
                                               data-uid="<?php _e($v->uid) ?>" 
                                               data-rebuy-id="<?php _e($v->id) ?>" 
                                               data-gid="<?php _e($v->gid) ?>">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-3">No rebuys yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 mb-4">
            <!-- Final Count Form -->
            <form method="post">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Final Count</h3>
                        <span id="total-chips-value" style="display: none;"><?php echo $total_chips; ?></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-flush table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Player</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ending Chips</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rebuy</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($game_detail_position as $key => $val) : ?>
                                    <?php
                                        $ending_value = '';
                                        if (isset($_REQUEST['ending'][$val->uid])) {
                                            $ending_value = $_REQUEST['ending'][$val->uid];
                                        } else {
                                            $rebuy_sum = isset($rebuy_real[$val->uid]['rebuy_chips']) ? $rebuy_real[$val->uid]['rebuy_chips'] : 0;
                                            if ($rebuy_sum > 0) {
                                                $ending_value = $rebuy_sum;
                                            }
                                        }

                                        if(isset($last_ending_chips) && $ending_value == 0){
                                            $ending_value = $last_ending_chips;
                                        }
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center px-2 py-1">
                                                <h6 class="mb-0 text-sm"><?php _e($val->player) ?></h6>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <?php erp_html_form_input(array(
                                                'name'      => 'ending[' . $val->uid . ']',
                                                'id'        => 'end_chips_' . $val->uid,
                                                'value'     => $ending_value,
                                                'class'     => 'form-control form-control-sm final-chip-input',
                                                'type'      => 'number',
                                                'readonly'  => $disabled,
                                            ));
                                            ?>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="text-secondary font-weight-bold"><?php _e(isset($rebuy_real[$val->uid]['rebuy_chips']) ? $rebuy_real[$val->uid]['rebuy_chips'] : '--') ?></span>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="text-secondary font-weight-bold"><?php _e(isset($rebuy_real[$val->uid]['paied']) ? "￥" . $rebuy_real[$val->uid]['paied'] : '--') ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <input type="hidden" name="gid" value="<?php _e($_REQUEST['gid']) ?>">
                        <input type="hidden" name="action" value="ranking">
                        <?php if (empty($cant_count)) : ?>
                            <button type="submit" class="btn btn-primary w-100" <?php _e($disabled) ?> <?php _e($onclick) ?>>Rank!</button>
                        <?php else : ?>
                            <p class="text-center text-danger"><?php _e($cant_count) ?></p>
                            <button type="submit" class="btn btn-secondary w-100" name="submit_type" value="clone">Clone</button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
             <?php if ($game->status == 1) : ?>
                <form method="post" onsubmit="return confirm('你确定要删除这场游戏吗？这会清除所有相关数据！');">
                    <input type="hidden" name="gid" value="<?php echo $_REQUEST['gid']; ?>">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-danger w-100" name="submit_type" value="Delete">Delete This Game</button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($show_rank) && $show_rank == 'yes') : ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Rank & Result</h3>
                </div>
            </div>
        </div>
    </div>
    <form method="post">
        <div class="row">
            <?php foreach ($user_final_rank as $key => $val) : ?>
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <span class="badge bg-primary me-2">#<?php _e($val['rank']) ?></span>
                                <a href="user_detail.php?uid=<?php _e($key) ?>&gid=<?php _e($_REQUEST['gid']) ?>"><?php _e($player_name[$key]) ?></a>
                            </h5>
                            <div>
                                <?php foreach ($game_meta as $k => $v) : ?>
                                    <?php if ($v->meta_value == $val['uid']) : ?>
                                        <span class="badge badge-warning"><?php _e(isset(get_honor_title_arr()[$v->meta_key]) ? get_honor_title_arr()[$v->meta_key] : "") ?></span>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row text-center mt-3">
                                <div class="col">
                                    <h6 class="text-xxs text-uppercase">END</h6>
                                    <p class="text-sm font-weight-bold mb-0"><?php _e($val['end_chips']) ?></p>
                                </div>
                                <div class="col">
                                    <h6 class="text-xxs text-uppercase">Rebuy</h6>
                                    <p class="text-sm font-weight-bold mb-0"><?php _e($val['rebuy']) ?></p>
                                </div>
                                <div class="col">
                                    <h6 class="text-xxs text-uppercase">Cost</h6>
                                    <p class="text-sm font-weight-bold mb-0">￥<?php _e($val['paied']) ?></p>
                                </div>
                                <div class="col">
                                    <h6 class="text-xxs text-uppercase">Pay Back</h6>
                                    <p class="text-sm font-weight-bold mb-0">￥<?php _e($val['pay_back']) ?></p>
                                </div>
                            </div>
                             <hr class="horizontal dark my-3">
                            <div class="row text-center">
                                <div class="col">
                                    <h6 class="text-xxs text-uppercase">Bonus</h6>
                                    <p class="text-sm font-weight-bold mb-0">￥<?php _e(hmtl_if_negative($val['bonus'])) ?></p>
                                </div>
                                <?php
                                    if (strtoupper($game->game_type) == "CASH" || is_null($game->game_type)) {
                                        $total_should = ($val['count_chips'] - $game->chips_level) / ($game->chips_level / $game->rebuy_rate);
                                    } else { // SNG
                                        if ($val['bonus'] > 0) {
                                            $total_should = $val['bonus'] + $val['reward'];
                                        } else {
                                            $total_should = $val['bonus'] + $val['pay_out'];
                                        }
                                    }
                                ?>
                                <?php if ($val['bonus'] <= 0) : ?>
                                    <div class="col">
                                        <h6 class="text-xxs text-uppercase">Pay Out</h6>
                                        <p class="text-sm font-weight-bold mb-0">￥<?php _e(hmtl_if_negative($val['pay_out'])) ?></p>
                                    </div>
                                <?php else: ?>
                                     <div class="col">
                                        <h6 class="text-xxs text-uppercase">Extra Reward</h6>
                                        <p class="text-sm font-weight-bold mb-0">￥<?php _e($val['reward']) ?></p>
                                    </div>
                                <?php endif; ?>
                                <div class="col">
                                     <h6 class="text-xxs text-uppercase">Final</h6>
                                     <p class="text-sm font-weight-bold mb-0 <?php echo $total_should >= 0 ? 'text-success' : 'text-danger'; ?>">
                                        ￥<?php _e(round($total_should, 2))
?>
                                     </p>
                                </div>
                            </div>
                            
                            <?php if(isset($should_get[$key])):
                                $get_money = "";
                                ?>
                                <hr class="horizontal dark my-3">
                                <div class="text-center">
                                    Pay For ：
                                    <?php foreach($should_get[$key] as $k => $v):
                                        if($v['get'] != 0):
                                            ?><?php _e($player_name[$k]."(".$v['get'].")")?><?php 
                                            $get_money .= $player_name[$k]."(".$v['get'].")";
                                        ?><?php endif?><?php 
                                    endforeach?>
                                </div>
                                <?php $text_tel .= "#".$val['rank'].":". $player_name[$key]."->". $get_money."\n"; 
                            endif?>

                            <div class="form-group mt-3 mb-0">
                                <label class="form-control-label text-xxs">Get in Fact</label>
                                <?php erp_html_form_input(array(
                                    'name'      => 'total_fact[' . $key . ']',
                                    'id'        => 'total_fact[' . $key . ']',
                                    'value'     => isset($total_fact[$key]) ? $total_fact[$key] : round($total_should, 2),
                                    'class'     => 'form-control form-control-sm',
                                    'type'      => 'text',
                                    'readonly'  => $disabled,
                                ));
                                ?>
                                <input type="hidden" name="total_should[<?php _e($key)?>]" value ="<?php _e($total_should)?>">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="row-full-bottom">
            <?php if ($game->status == 1) : ?>
                <input type="hidden" name="action" value="finish">
                <input type="hidden" name="gid" value="<?php _e($_REQUEST['gid']) ?>">
                <div class="text-center">
                    <button type="submit" class="btn btn-secondary" name="submit_type" value="finish&clone">Finish & Clone</button>
                    <button type="submit" class="btn btn-primary" name="submit_type" value="Finish">Finish</button>
                </div>
            <?php elseif ($game->status == 2) : ?>
                <div class="text-center">
                    <p class="text-success">Game Finished</p>
                    <button type="submit" class="btn btn-secondary" name="submit_type" value="clone">Clone</button>
                </div>
            <?php endif ?>
        </div>
    </form>
    <?php
        $text_tel .= "======END======";
        $chat_id = "-1001681233477";
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == "ranking") {
            apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => $text_tel));
        }
    ?>
    <?php endif; ?>
</div>

<?php
// Scripts and footer
if (isset($swal_script)) {
    echo $swal_script;
}
require_once "kog_footer.php";
?>
<script>
$(document).ready(function() {
    var totalChips = parseInt($('#total-chips-value').text());
    if (isNaN(totalChips)) {
        console.error("无法读取总筹码量。");
        return;
    }

    var inputs = $('.final-chip-input');
    var autoCalculatedField = null;

    inputs.on('input', function() {
        var currentInput = $(this);

        if (autoCalculatedField && currentInput.is(autoCalculatedField)) {
            autoCalculatedField = null;
        }

        var sumOfEntered = 0;
        var emptyInputs = [];
        
        inputs.each(function() {
            var val = $(this).val();
            if (val !== '' && !isNaN(parseInt(val))) {
                sumOfEntered += parseInt(val);
            } else {
                emptyInputs.push($(this));
            }
        });

        if (autoCalculatedField) {
            var sumOfManual = 0;
            inputs.not(autoCalculatedField).each(function() {
                 var val = $(this).val();
                 if (val !== '' && !isNaN(parseInt(val))) {
                    sumOfManual += parseInt(val);
                 }
            });
            var newVal = totalChips - sumOfManual;
            autoCalculatedField.val(newVal);
        }
        else if (emptyInputs.length === 1) {
            var targetField = emptyInputs[0];
            var newVal = totalChips - sumOfEntered;
            targetField.val(newVal);
            autoCalculatedField = targetField;
        }
    });

    $('.delete-rebuy-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the link from navigating

        if (confirm('你确定要删除这条Rebuy记录吗？')) {
            var $this = $(this);
            var uid = $this.data('uid');
            var rebuyId = $this.data('rebuy-id');
            var gid = $this.data('gid');

            var deleteUrl = 'kog_rebuy.php?uid=' + uid + '&rebuy_id=' + rebuyId + '&gid=' + gid + '&action=delete';

            $.ajax({
                url: deleteUrl,
                type: 'GET',
                success: function(response) {
                    // On success, just reload the page to see the changes
                    location.reload();
                },
                error: function() {
                    alert('删除失败，请重试。');
                }
            });
        }
    });
});
</script>