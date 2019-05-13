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
		$stoploss = preg_replace('/\s+/', '', $stoploss);
		
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

$servername = "37.60.237.198";
$username = "buddyzeu_andreaz";
$password = "DGv-FeU-eEP-W7u";
$dbname = "buddyzeu_licenze";
$tablename = "FxMind_Builders_Signals";

$prova_asset = "AUDUSD";
$prova_dir = "OP_SELL";
$prova_prezzo = 1.1010;
/*
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) 
	{
		//die("Connection failed: " . $conn->connect_error);
		$response = "Errore di Connessione al DB";
	} 
/*
	$sql = "INSERT INTO `".$tablename."`(`id_signal`, `asset`, `direction`, `stoploss`, `traded_flag`) 
	VALUES (NULL,`".$asset."`,`".$direction."`,".$stoploss.",1)";

	$sql = "INSERT INTO `".$tablename."`(`id_signal`, `asset`, `direction`, `stoploss`, `traded_flag`) 
	VALUES (NULL,'$prova_asset','$prova_dir',$prova_prezzo,1)";

	if ($conn->query($sql) === false) 
	{
		$response = "Errore Query di Immissione Segnale nel DB";
		echo "Error: " . $sql . "<br>" . $conn->error;
	} 

	$conn->close();
*/
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

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) 
	{
		//die("Connection failed: " . $conn->connect_error);
		$response = "Errore di Connessione al DB";
	} 
	
	$sql = "INSERT INTO `".$tablename."`(`id_signal`, `asset`, `direction`, `stoploss`, `traded_flag`) 
	VALUES (NULL,'$prova_asset','$prova_dir',$prova_prezzo,1)";


	if ($conn->query($sql) === false) 
	{
		//$response = "Errore Query di Immissione Segnale nel DB";
		$response = "Error: " . $sql . "<br>" . $conn->error;
	} 

	$conn->close();
}
else $response = "Il segnale immesso non è valido.";

header("Content-Type: application/json");
$parameters = array('chat_id' => $chatId, "text" => $response);
$parameters["method"] = "sendMessage";
echo json_encode($parameters);
