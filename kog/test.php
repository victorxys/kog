<?php
require_once('functions.php');
// define('BOT_TOKEN', '5174575036:AAEwt_eSARtn3rJl6n1Vg5DO5Z0lLZQ1msU');
// define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
// function exec_curl_request($handle) {
//   $response = curl_exec($handle);

//   if ($response === false) {
//     $errno = curl_errno($handle);
//     $error = curl_error($handle);
//     error_log("Curl returned error $errno: $error\n");
//     curl_close($handle);
//     return false;
//   }

//   $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
//   curl_close($handle);

//   if ($http_code >= 500) {
//     // do not wat to DDOS server if something goes wrong
//     sleep(10);
//     return false;
//   } else if ($http_code != 200) {
//     $response = json_decode($response, true);
//     error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
//     if ($http_code == 401) {
//       throw new Exception('Invalid access token provided');
//     }
//     return false;
//   } else {
//     $response = json_decode($response, true);
//     if (isset($response['description'])) {
//       error_log("Request was successful: {$response['description']}\n");
//     }
//     $response = $response['result'];
//   }

//   return $response;
// }

// function apiRequestJson($method, $parameters) {
//     if (!is_string($method)) {
//         error_log("Method name must be a string\n");
//         return false;
//     }

//     if (!$parameters) {
//         $parameters = array();
//     } else if (!is_array($parameters)) {
//         error_log("Parameters must be an array\n");
//         return false;
//     }

//     $parameters["method"] = $method;

//     $handle = curl_init(API_URL);
//     curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
//     curl_setopt($handle, CURLOPT_TIMEOUT, 60);
//     curl_setopt($handle, CURLOPT_POST, true);
//     curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
//     curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

//     return exec_curl_request($handle);
// }

$chat_id = '652145690';
$text = '带有按钮的消息文本222';
$button_1 = "立刻升盲";
$callback = array('a'=>'blind_up','gid'=>'123');
$callback_url = "https://allin.xys.one/kog_detail.php?page_name=Have%20Fun%20!&gid=123";
$callback = json_encode($callback);
$inlineKeyboard = [
    'inline_keyboard' => [
        [
            ['text' => $button_1, 'callback_data' => $callback],
            ['text' => '查看游戏2',  'url' =>  $callback_url]
        ]
    ]
];
$encodedKeyboard = json_encode($inlineKeyboard);




$response =apiRequestJson("sendMessage", [
    'chat_id' => $chat_id,
    'text' => $text,
    'reply_markup' => $encodedKeyboard
]);



if (!$response || $response['ok'] === false) {
    // 输出错误信息进行调试
    echo ("Failed to send message: " . json_encode($response));
    // 根据需要进行错误处理
}

?>