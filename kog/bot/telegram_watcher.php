<?php
// require_once('functions.php');

// 这个是 守护机器人，用来定时触发的
// watcher_auto_bot
file_get_contents("https://api.telegram.org/bot5174575036:AAEwt_eSARtn3rJl6n1Vg5DO5Z0lLZQ1msU/sendMessage?chat_id=-1001681233477&text=Hello world again!");
define('BOT_TOKEN_2', '5122644514:AAGubGVAOAiwYkvqtF2-pKP2DCn4JXuHY5M');
define('API_URL_2', 'https://api.telegram.org/bot'.BOT_TOKEN_2.'/');
define('WEBHOOK_URL_2', 'https://bot.xys.one/telegram_wtcher.php');



ini_set('error_reporting', E_ALL);

// $chat_id = "-1001681233477";
// $tel_bot = "https://api.telegram.org/".$bot_key."/sendMessage?chat_id=".$chat_id."&text=";






function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $payload = json_encode($parameters);
  header('Content-Type: application/json');
  header('Content-Length: '.strlen($payload));
  echo $payload;

  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successful: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL_2.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL_2);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POST, true);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}

function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];

    if (strpos($text, "/start") === 0) {
      apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello', 'reply_markup' => array(
        'keyboard' => array(array('Hello', 'Hi')),
        'one_time_keyboard' => true,
        'resize_keyboard' => true)));
    } else if (strpos($text, "/keyboard") === 0) {
    	$keyboard = [
			    'inline_keyboard' => [
			        [
			            ['text' => 'forward me to groups', 'callback_data' => 'someString'],
			            ['text' => 'forward me to groups2', 'callback_data' => 'someString2']
			        ]
			    ]
			];
			$encodedKeyboard = json_encode($keyboard);


    	apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello', 'reply_markup' => $keyboard));
    } else if (strpos($text, "/code") === 0){
    	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $message));
    } else if (strpos($text, "/battle") === 0){
    	$message = "让我看看你能看到我吗？";
    	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $message));
    } else if ($text === "Hello" || $text === "Hi") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Nice to meet you'));
    } else if (strpos($text, "/stop") === 0) {
      // stop now
    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => 'Cool'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
  }
}

// 对回调内容进行处理
function processCallback($callback){
	
	$message = $callback['message'];
	$message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  $data = json_decode($callback['data'],true);
  $action = $data['a'];
  $gid = $data['gid'];

	// apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $data));
  if ($action == 'straddle') {
  	// 开始强抓
  	$text = $gid;
  	update_gamemeta($gid,'straddle',"20");
		if(update_gamemeta($gid,'straddle_time',time())){
			$blind_up_next = date("H:i",(time()+3600));
			$text = "Gid:".$gid."「开始强抓」
			Straddle: 20
			下次升盲时间: $blind_up_next";

		}
  } else if($action == "blind_up"){
  	// 升盲
  	// 反馈当前盲注级别、历时时间、应该升盲倍数
  	$blind_level = isset(get_game_meta_by_meta_key($gid,'blind_level')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'blind_level')['0']['meta_value']:"1";
  	$small_blind = isset(get_game_meta_by_meta_key($gid,'small_blind')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'small_blind')['0']['meta_value']:"5";
		$big_blind = isset(get_game_meta_by_meta_key($gid,'big_blind')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'big_blind')['0']['meta_value']:"10";
		$straddle = isset(get_game_meta_by_meta_key($gid,'straddle')['0']['meta_value'])?get_game_meta_by_meta_key($gid,'straddle')['0']['meta_value']:"0";
  	$blind_level_to = $blind_level+1;
		$blind_levle_n = "blind_level_".($blind_level_to);
		update_gamemeta($gid,'blind_level',$blind_level_to);
		$delta = pow(2,($blind_level_to-1));
		$small_blind = $small_blind * $delta ;
		$big_blind = $big_blind* $delta;
		$straddle = $straddle* $delta;
		$blind_up_next = date("H:i",(time()+3600));
		if(update_gamemeta($gid,$blind_levle_n,time())){
			$text = "Gid:".$gid."「开始升盲」
			盲注级别: $blind_level -> $blind_level_to
			SB: $small_blind
			BB: $big_blind
			Straddle: $straddle
			下次升盲时间: $blind_up_next
			";

		}
		// $text = "当前盲注级别: $blind_level";
  	// 再接受确定的升盲倍数
  	// 调用已有方法，写入升盲倍数、升盲时间
  }
  apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $text));
}

function processWatcher($message) {
  // process incoming message by Watcher_auto_bot
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $message));
  // if (isset($message['text'])) {
  //   // incoming text message
  //   $text = $message['text'];

  //   if (strpos($text, "/start") === 0) {
  //     apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello', 'reply_markup' => array(
  //       'keyboard' => array(array('Hello', 'Hi')),
  //       'one_time_keyboard' => true,
  //       'resize_keyboard' => true)));
  //   } else if (strpos($text, "/keyboard") === 0) {
  //   	$keyboard = [
		// 	    'inline_keyboard' => [
		// 	        [
		// 	            ['text' => 'forward me to groups', 'callback_data' => 'someString'],
		// 	            ['text' => 'forward me to groups2', 'callback_data' => 'someString2']
		// 	        ]
		// 	    ]
		// 	];
		// 	$encodedKeyboard = json_encode($keyboard);


  //   	apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello', 'reply_markup' => $keyboard));
  //   } else if (strpos($text, "/code") === 0){
  //   	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $message));
  //   } else if (strpos($text, "/battle") === 0){
  //   	$message = "让我看看你能看到我吗？";
  //   	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $message));
  //   } else if ($text === "Hello" || $text === "Hi") {
  //     apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Nice to meet you'));
  //   } else if (strpos($text, "/stop") === 0) {
  //     // stop now
  //   } else {
  //     apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => 'Cool'));
  //   }
  // } else {
  //   apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
  // }
}


if (php_sapi_name() == 'cli') {
  // if run from console, set or delete webhook
  apiRequest('setWebhook', array('url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL_2));
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
	  processWatcher($update["message"]);
	}
	if (isset($update['callback_query'])) {
		processCallback($update['callback_query']);
		// apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $update['callback_query']));
	}
}





?>