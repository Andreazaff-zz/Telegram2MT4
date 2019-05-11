<?php

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if(!$update)
{
  exit;
}
$message = isset($update['message']) ? $update['message'] : "";
$messageId = isset($message['message_id']) ? $message['message_id'] : "";
$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
$firstname = isset($message['chat']['first_name']) ? $message['chat']['first_name'] : "";
$lastname = isset($message['chat']['last_name']) ? $message['chat']['last_name'] : "";
$username = isset($message['chat']['username']) ? $message['chat']['username'] : "";
$date = isset($message['date']) ? $message['date'] : "";
$text = isset($message['text']) ? $message['text'] : "";
$text = trim($text);
$text = strtolower($text);
$string_exploded = explode("-",$text);	//Delete_License:1
$response = '';

if ($text == "ciao")
{	
	$response = "Ciao!!";
}
else
{
	if (strpos($string_exploded[0], "buy") == true) 
	{
   		$direction = "BUY";
	}
	if (strpos($string_exploded[0], "sell") == true) 
	{
   		$direction = "SELL";
	}

		$entry_level = filter_var($string_exploded[0], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		$stoploss = filter_var($string_exploded[1], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		$tp1 = filter_var($string_exploded[2], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		$tp2 = filter_var($string_exploded[3], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		
		$response = "Segnale $direction\nEntry Level $entry_level\nStopLoss $stoploss\nTake 1 $tp1\nTake2 $tp2";
}


header("Content-Type: application/json");
$parameters = array('chat_id' => $chatId, "text" => $response);
$parameters["method"] = "sendMessage";
echo json_encode($parameters);
