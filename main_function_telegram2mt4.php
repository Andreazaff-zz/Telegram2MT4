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
$string_exploded = explode("\n",$text);	//Delete_License:1
$response = '';
$index_entry = -1;

	for($c=0; $c<count($string_exploded);$c++)
	{
		if (strpos($string_exploded[$c], "BUY") !== false) 
		{
    		$direction = "BUY";
			$index_entry = $c;
		}
		if (strpos($string_exploded[$c], "SELL") !== false) 
		{
    		$direction = "SELL";
			$index_entry = $c;
		}
	}

	if ($index_entry == -1) 
	{
		$response = "Nessun Segnale Inserito";
	}
	else
	{
		$entry_level = filter_var($string_exploded[$index_entry], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		$stoploss = filter_var($string_exploded[$index_entry+1], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		$tp1 = filter_var($string_exploded[$index_entry+2], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		$tp2 = filter_var($string_exploded[$index_entry+3], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		
		$response = "Segnale $direction\nEntry Level $entry_level\nStopLoss $stoploss\nTake 1 $tp1\nTake2 $tp2";
	}


header("Content-Type: application/json");
$parameters = array('chat_id' => $chatId, "text" => $response);
$parameters["method"] = "sendMessage";
echo json_encode($parameters);
