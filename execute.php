<?php

function set_param ($id, $set)
{
	$url = "https://buddyzeus.com/zeusconfig_pageupdate_list01.php?id=".$id."&set=".$set;
    $ch = curl_init();
    $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
	$result = curl_exec($ch);
	//echo $result;
    curl_close($ch);	
}

function all_param($set)
{
	$url = "https://buddyzeus.com/zeusconfig_pageupdate_list02.php?set=".$set;
    $ch = curl_init();
    $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
	$result = curl_exec($ch);
	//echo $result;
    curl_close($ch);	
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
$text = strtolower($text);
$string_exploded = explode(":",$text);	//Delete_License:1
$response = '';

	if (strpos ($text, "/start") === 0 )
	{
		if ($chatId == -1001296319190)
		{
			$response = "Ciao $firstname, Benvenuto nel Pannello di Controllo Licenze di Zeus © v3.2\n\nIn questo gruppo saranno inviate tutte le licenze che verranno generate ed attivate nei vari client MT4.\n Utilizzando il comando \"Delete:(ID)\" dove il parametro (ID) costituisce il progressivo della licenza sarai in grado di modificare da remoto la licenza per la copia di Zeus © rispettivamente a quel Client.\nUtilizzando il comando \"Activate:(ID)\" sarai invece in grado di riattivarla.\nCon i Comandi \"Close_All\" e \"Run_All\" sarai in grado rispettivamente di disattivare o attivare tutte le Licenze Registrate sul DataBase.\n\nTutti i diritti sono riservati. ©\n";
		}
			else
			{
				$response = "Ciao $firstname, con questo Bot sarai in grado di ricevere le notifiche della MetaTrader 4 direttamente sul tuo account Telegram.\n\nIl codice di questa Chat è: $chatId\nInserisci questo codice nei parametri del tuo Expert Advisor e riceverai tutte le notifiche in questa chat.\n\nQuesto Bot è di proprietà di Andrea Zaffignani ed è compatibile solo con i suoi Expert Advisor.\nTutti i diritti sono riservati. ©\n";
			}
	}
		else if ($string_exploded[0] == "delete" && $chatId == -1001296319190)
		{	
			set_param($string_exploded[1],0);
			$response = "La Licenza Associata all'Account ID ".$string_exploded[1]." è stata Correttamente Disabilitata";
		}
			else if ($string_exploded[0] == "activate" && $chatId == -1001296319190)
			{	
				set_param($string_exploded[1],1);
				$response = "La Licenza Associata all'Account ID ".$string_exploded[1]." è stata Correttamente Abilitata";
			}
			else if ($string_exploded[0] == "close_all" && $chatId == -1001296319190)
				{	
					all_param(0);
					$response = "Tutte le Licenze sono state Correttamente Disabilitate";
				}
					else if ($string_exploded[0] == "run_all" && $chatId == -1001296319190)
					{	
						all_param(1);
						$response = "Tutte le Licenze sono state Correttamente Abilitate";
					}
						else
						{
							$response = "Comando Non Abilitato!\r\nContattare il Gestore del Servizio @andreazaff";
						}


header("Content-Type: application/json");
$parameters = array('chat_id' => $chatId, "text" => $response);
$parameters["method"] = "sendMessage";
echo json_encode($parameters);
