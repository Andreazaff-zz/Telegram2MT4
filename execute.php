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
		$pos_dir = strpos($string_exploded[0], "buy");
		$asset = substr($string_exploded[0],$pos_dir+4,6);
		$entry_level = filter_var(substr($string_exploded[0],$pos_dir+4,strlen($string_exploded[0])-$pos_dir), FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		
	}
	if (strpos($string_exploded[0], "sell") == true) 
	{
   		$direction = "SELL";
		$pos_dir = strpos($string_exploded[0], "sell");
		$asset = substr($string_exploded[0],$pos_dir+5,6);
		$entry_level = filter_var(substr($string_exploded[0],$pos_dir+5,strlen($string_exploded[0])-$pos_dir), FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		
	}

		$stoploss = filter_var(substr($string_exploded[1],0,6), FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		$tp1 = filter_var(substr($string_exploded[2],0,6), FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		$tp2 = filter_var(substr($string_exploded[1],0,6), FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		
		$response = "Segnale $direction\nAsset $asset\nEntry Level $entry_level\nStopLoss $stoploss\nTake 1 $tp1\nTake2 $tp2";
}


header("Content-Type: application/json");
$parameters = array('chat_id' => $chatId, "text" => $response);
$parameters["method"] = "sendMessage";
echo json_encode($parameters);
