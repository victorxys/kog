<?php
// 这个程序是处理 Bot 接收到的消息的，接收后会通过 webhook中设置的回调地址，访问这里。
require_once('../functions.php');


define('WEBHOOK_URL', 'https://bot.xys.one/telegram_webhook.php');

if (php_sapi_name() == 'cli') {
  // if run from console, set or delete webhook
  apiRequest('setWebhook', array('url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL));
  exit;
}

if (file_get_contents("php://input")) {
	$content = file_get_contents("php://input");
	$update = json_decode($content, true);
	if (!$update) {
	  // receive wrong update, must not happen
	  exit;
	}

	if (isset($update["message"])) {
	  processMessage($update["message"]);
	}
	if (isset($update['callback_query'])) {
		processCallback($update['callback_query']);
		// apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $update['callback_query']));
	}
}





?>