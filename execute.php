<?php

function exclusion ($message_update, &$asset, &$direction, &$stoploss)
{
	$master_asset[0] = "EURUSD";
	$master_asset[1] = "EURGBP";
	$master_asset[2] = "GBPUSD";
	$master_asset[3] = "GBPCAD";
	$master_asset[4] = "GBPCHF";
	$master_asset[5] = "GBPAUD";
	$master_asset[6] = "GBPNZD";
	$master_asset[7] = "GBPJPY";
	$master_asset[8] = "EURCAD";
	$master_asset[9] = "EURAUD";
	$master_asset[10] = "EURNZD";
	$master_asset[11] = "EURJPY";
	$master_asset[12] = "USDCAD";
	$master_asset[13] = "USDCHF";
	$master_asset[14] = "AUDUSD";
	$master_asset[15] = "NZDUSD";
	$master_asset[16] = "USDJPY";
	$master_asset[17] = "CADCHF";
	$master_asset[18] = "AUDCAD";
	$master_asset[19] = "NZDCAD";
	$master_asset[20] = "CADJPY";
	$master_asset[21] = "AUDCHF";
	$master_asset[22] = "NZDCHF";
	$master_asset[23] = "CHFJPY";
	$master_asset[24] = "AUDNZD";
	$master_asset[25] = "AUDJPY";
	$master_asset[26] = "NZDJPY";
	
	if (strpos($message_update, "#") == true && strpos($message_update, "PENDING ORDER") == false && (strpos($message_update, "BUY") == true || strpos($message_update, "SELL") == true)) 
	{
		if (strpos($message_update, "BUY") == true) $direction = "OP_BUY";
		else $direction = "OP_SELL";
		
		$stoploss = filter_var(substr($message_update,strpos($message_update, "SL - ")+5,6), FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		
		for($c=0; $c<count($master_asset); $c++)
		{
			if (strpos($message_update, $master_asset[$c]) == true)
			{
				$asset = $master_asset[$c];
				return(1);
			}
		}
		return(-1);
	}
	else return(-1);
}

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
$text = strtoupper($text);
//$string_exploded = explode("-",$text);
$response = '';
$asset = NULL;
$direction = NULL;
$stoploss = NULL;

$result = exclusion($text,$asset,$direction,$stoploss);

if ($result == 1)
{
	$response = "Segnale $direction\nAsset $asset\nStopLoss $stoploss";
}
else $response = "Il segnale immesso non è valido.";

/*
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

		$stoploss = filter_var(substr($string_exploded[1],0,10), FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		$tp1 = filter_var(substr($string_exploded[2],0,10), FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		$tp2 = filter_var(substr($string_exploded[1],0,10), FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		
		$response = "Segnale $direction\nAsset $asset\nEntry Level $entry_level\nStopLoss $stoploss\nTake 1 $tp1\nTake2 $tp2";
}
*/

header("Content-Type: application/json");
$parameters = array('chat_id' => $chatId, "text" => $response);
$parameters["method"] = "sendMessage";
echo json_encode($parameters);
