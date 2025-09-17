<?php
    require_once(__DIR__ . '/functions.php');
    require_once(__DIR__ . '/kog_header.php');
// 开启错误提示，便于调试
error_reporting(E_ALL);
ini_set('display_errors', 1);

function parse_and_recover_game_data($raw_data) {
    global $wpdb;
    $output = "";

    // 1. 获取所有玩家, 创建 昵称 => ID 的映射
    $all_players = get_player();
    $player_map = array();
    if (!empty($all_players)) {
        foreach ($all_players as $player) {
            $player_map[$player->nickname] = $player->id;
        }
    }
    $output .= "成功获取 " . count($player_map) . " 位玩家的映射。\n";

    // 2. 解析数据
    $gid = null;
    $start_time = null;
    $end_time = null;
    $payouts = array();
    $final_chips = array();
    $rebuys = array();
    $all_player_names = array();

    $lines = explode("\n", $raw_data);
    $section = '';

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        if (strpos($line, '=====GID') !== false) {
            preg_match('/GID-(\d+):(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2})~~(\d{2}:\d{2})/i', $line, $matches);
            if ($matches) {
                $gid = (int)$matches[1];
                $start_time = strtotime($matches[2] . ' ' . $matches[3]);
                $end_time = strtotime($matches[2] . ' ' . $matches[4]);
                $output .= "解析到 GID: $gid, 开始时间: " . date('Y-m-d H:i', $start_time) . ", 结束时间: ". date('Y-m-d H:i', $end_time) . "。\n";
            }
            continue;
        }

        if (stripos($line, 'CHIPS:') !== false) {
            $section = 'chips';
            continue;
        } elseif (stripos($line, 'REBUY:') !== false) {
            $section = 'rebuy';
            continue;
        } elseif (preg_match('/^#\\d+:/', $line)) {
            $section = 'payout';
        }

        switch ($section) {
            case 'payout':
                preg_match('/#\\d+:(.+?)->(.+)/', $line, $payout_matches);
                if ($payout_matches) {
                    $payer = trim($payout_matches[1]);
                    $all_player_names[] = $payer;
                    $receivers_str = trim($payout_matches[2]);
                    preg_match_all('/([^\(]+)\((\d+\.?\d*)\)/u', $receivers_str, $receiver_matches,PREG_SET_ORDER);
                    foreach ($receiver_matches as $match) {
                        $receiver = trim($match[1]);
                        $amount = (float)$match[2];
                        $payouts[] = ['from' => $payer, 'to' => $receiver, 'amount' => $amount];
                        $all_player_names[] = $receiver;
                    }
                }
                break;
            case 'chips':
                $parts = preg_split('/\\s+/', $line);
                if (count($parts) >= 2) {
                    $final_chips[trim($parts[0])] = (int)$parts[1];
                    $all_player_names[] = trim($parts[0]);
                }
                break;
            case 'rebuy':
                preg_match('/(\\S+)\\s+被\\s+(\\S+)\\s+清台/u', $line, $rebuy_matches);
                if ($rebuy_matches) {
                    $rebuys[] = ['loser' => trim($rebuy_matches[1]), 'winner' => trim($rebuy_matches[2])];
                    $all_player_names[] = trim($rebuy_matches[1]);
                    $all_player_names[] = trim($rebuy_matches[2]);
                }
                break;
        }
    }
    $all_player_names = array_unique($all_player_names);

    if (!$gid) {
        return "<div class='alert alert-danger'>错误: 未能解析到 GID。请检查第一行格式。</div>";
    }

    $output .= "解析完成: " . count($payouts) . "条支付记录, " . count($final_chips) . "条筹码记录, " .count($rebuys) . "条清台记录。\n";

    // 3. 计算最终盈亏 (total_fact)
    $total_facts = array();
    foreach ($all_player_names as $name) {
        $total_facts[$name] = 0;
    }
    foreach ($payouts as $payout) {
        if (isset($total_facts[$payout['from']])) {
            $total_facts[$payout['from']] -= $payout['amount'];
        }
        if (isset($total_facts[$payout['to']])) {
            $total_facts[$payout['to']] += $payout['amount'];
        }
    }
    $output .= "已计算 " . count($total_facts) . " 位玩家的最终盈亏。\n";

    // 4. 数据库操作
    $wpdb->show_errors();

    // 4.1 写入 kog_kog_games
    $game_data = [
        'id' => $gid,
        'date' => date('Ymd', $start_time),
        'memo' => date('Ymd', $start_time),
        'start_time' => $start_time,
        'end_time' => $end_time,
        'chips_level' => 3000,
        'rebuy_rate' => 100,
        'bonus' => "N;",
        'status' => 2,
        'created_by' => 1,
        'game_type' => 'CASH'
    ];
    if ($wpdb->replace($wpdb->prefix . 'kog_games', $game_data) !== false) {
        $output .= "成功: 写入/更新牌局到 kog_games (GID: $gid)。\n";
    } else {
        return "<div class='alert alert-danger'><strong>写入 kog_games 失败！</strong> 错误: " . $wpdb->last_error . "</div>";
    }

    // 4.2 写入 kog_kog_rebuy
    $wpdb->delete($wpdb->prefix . 'kog_rebuy', ['gid' => $gid]);
    $output .= "信息: 已清理 GID: $gid 的旧rebuy记录。\n";
    foreach ($rebuys as $rebuy) {
        $loser_id = isset($player_map[$rebuy['loser']]) ? $player_map[$rebuy['loser']] : null;
        $winner_id = isset($player_map[$rebuy['winner']]) ? $player_map[$rebuy['winner']] : null;
        if ($loser_id && $winner_id) {
            $rebuy_data = [
                'gid' => $gid,
                'uid' => $loser_id,
                'rebuy' => 3000,
                'paied' => 100,
                'killed_by' => $winner_id,
                'created_at' => $start_time + 1
            ];
            $wpdb->insert($wpdb->prefix . 'kog_rebuy', $rebuy_data);
            $output .= "成功: 为 " . $rebuy['loser'] . " 插入一条rebuy记录。\n";
        } else {
            $output .= "<strong class='text-warning'>警告: rebuy记录中的玩家 '" . $rebuy['loser'] . "' 或 '". $rebuy['winner'] . "' 在数据库中未找到，跳过此条记录。</strong>\n";
        }
    }

    // 4.3 写入 kog_kog_details
    $wpdb->delete($wpdb->prefix . 'kog_details', ['gid' => $gid]);
    $output .= "信息: 已清理 GID: $gid 的旧详情记录。\n";

    $rank_map = array();
    if(!empty($final_chips)){
        arsort($final_chips);
        $rank_map = array_keys($final_chips);
    }
    $seat_counter = 1;
    foreach ($all_player_names as $name) {
        $uid = isset($player_map[$name]) ? $player_map[$name] : null;
        if ($uid) {
            $rank = array_search($name, $rank_map);
            $current_chips = isset($final_chips[$name]) ? $final_chips[$name] : 0;

            $detail_data = [
                'gid' => $gid,
                'uid' => $uid,
                'player' => $name,
                'start_chips' => 3000,
                'end_chips' => $current_chips,
                'rank' => ($rank !== false) ? $rank + 1 : 0,
                'bonus' => 0,
                'seat_position' => $seat_counter,
                'count_chips' => ($current_chips === 0) ? 3000 : $current_chips,
                'total_should' => isset($total_facts[$name]) ? $total_facts[$name] : 0,
                'total_fact' => isset($total_facts[$name]) ? $total_facts[$name] : 0
            ];
            $wpdb->insert($wpdb->prefix . 'kog_details', $detail_data);
            $seat_counter++; // 递增座位号
        } else {
             $output .= "<strong class='text-warning'>警告: 玩家 '" . $name . "' 在数据库中未找到，跳过该玩家的详情记录。</strong>\n";
        }
    }
    $output .= "成功: 为 " . count($all_player_names) . " 位玩家写入详情记录。\n";

    $output .= "\n<strong>恢复完成！</strong>";
    return "<div class='alert alert-success'>" . nl2br(htmlspecialchars($output)) . "</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['game_data'])) {
    echo "<div class='card'><div class='card-body'>"; // Changed from \"<div...\" to "<div..."
    echo "<pre><strong>处理日志:</strong>\n"; // Changed from \"<pre...\" to "<pre..." and escaped newline
    $result_output = parse_and_recover_game_data($_POST['game_data']);
    echo $result_output;
    echo "</pre></div></div>"; // Changed from \"</pre...\" to "</pre..."
}

?>

<div class="card">
    <div class="card-header">
        <h3 class="mb-0">从文本恢复游戏数据</h3>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label for="game_data">粘贴游戏数据:</label>
                <textarea class="form-control" id="game_data" name="game_data" rows="15" placeholder="在此处粘贴从Telegram或其他地方复制的完整游戏数据，包括GID、结算、筹码和清台信息。格式如下：

======GID-996:2025-08-16 16:35~~17:23======
#1:翠->Holy(166)续(134)
#2:晓雨->续(129.5)
#3:仉->续(4.5)
======END======

CHIPS:
翠 3000
晓雨 1705
仉 2955
Holy 4660
续 5680

REBUY:
翠 被 续 清台
"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">恢复数据</button>
        </form>
    </div>
</div>

<?php
require_once("kog_footer.php");
?>
