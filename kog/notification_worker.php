<?php
// 加载 WordPress 环境，这是让 $wpdb 可用的关键
// __DIR__ 是当前脚本所在目录 (kog), 所以我们需要回到上一层目录
// 强制设置当前脚本的默认时区
date_default_timezone_set('Asia/Shanghai');
require_once(__DIR__ . '/../wp-load.php');
require_once('functions.php');

// 现在 $wpdb 应该可用了
global $wpdb;
$table_name = 'scheduled_notifications';

$now_local = date('Y-m-d H:i:s');

// 1. 查找并锁定到期的任务
// 使用 $wpdb->prepare 来防止SQL注入
$query = $wpdb->prepare(
    "SELECT * FROM {$table_name} WHERE execute_at <= %s AND status = 'pending'",
    $now_local
);
echo $query;
$tasks_to_process = $wpdb->get_results($query);
// var_dump($tasks_to_process);
if (!empty($tasks_to_process)) {
    foreach ($tasks_to_process as $task) {
        // 先将任务标记为 'processing'，防止重复执行
        $wpdb->update(
            $table_name,
            ['status' => 'processing', 'processed_at' => date('Y-m-d H:i:s')],
            ['id' => $task->id]
        );

        // 2. 执行任务：在发送通知前，检查游戏状态
        $game = get_game($task->game_id);
        $is_success = true; // 默认为 true，以便在游戏结束后直接将任务标记为 completed

        if ($game && $game->status == 1) {
            // 游戏仍在进行中，发送通知
            $chat_id = "-1001681233477";
            $is_success = apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => $task->message));
        }

        // 3. 更新最终状态
        $new_status = $is_success ? 'completed' : 'failed';
        $wpdb->update(
            $table_name,
            ['status' => $new_status],
            ['id' => $task->id]
        );
    }
}

echo "Worker finished at " . date('Y-m-d H:i:s') . "\n";

// 确保 functions.php 里的 send_telegram_message 函数存在
// 如果 worker 无法自动加载 functions.php，你可能需要在这里也 require_once(__DIR__ . '/functions.php');
// 但因为我们加载了 wp-load.php，它通常已经加载了主题或插件的 functions.php
?>