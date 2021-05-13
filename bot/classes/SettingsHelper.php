<?php
/** 
 * TaskTimeTerminate Telegram Bot
 * https://github.com/KIMB-technologies/TaskTimeTerminate-Bot
 * 
 * (c) 2020 KIMB-technologies 
 * https://github.com/KIMB-technologies/
 * 
 * released under the terms of GNU Public License Version 3
 * https://www.gnu.org/licenses/gpl-3.0.txt
 * 
 * The project is based on 
 * 	https://github.com/php-telegram-bot/example-bot/
 * 	(c) PHP Telegram Bot Team
 * 	released under the terms of MIT License 
 * 	https://github.com/php-telegram-bot/example-bot/blob/master/LICENSE
 */

namespace TTTBot;

class SettingsHelper {

	public static function syncStep(Session $session, string $text) {
		switch( $session->getTemp('readlineName') ){
			case "Delete server sync (y/n)?":
				if($text === 'y'){
					TTTLoader::addReadlineValue("Delete server sync (y/n)?", 'y' );
					return true;
				}
				
				$suffix = "Server URI:\t\t";
				break;

			case "Server URI:\t\t":
				if( !@filter_var($text, FILTER_VALIDATE_URL)
					|| substr($text, 0, 4) !== 'http'
					|| strpos($text, 'localhost') !== false
					|| strpos($text, ':9000') !== false
					|| strpos($text, '127.0.0.1') !== false
				){
					return "Invalid URL!";
				}
				$session->setTemp('syncUri', $text);
				$suffix = "Sync group:\t\t";
				break;

			case "Sync group:\t\t":
				if( !InputParser::checkGroupInput($text) ){
					return "Invalid Group Name!";
				}
				$session->setTemp('syncGroup', $text);
				$suffix = "Client token:\t\t";
				break;

			case "Client token:\t\t":
				if( !InputParser::checkTokenInput($text) ){
					return "Invalid Token!";
				}
				$session->setTemp('syncToken', $text);
				$suffix = "Name of this client:\t";
				break;

			case "Name of this client:\t":
				if( !InputParser::checkDeviceName($text) ){
					return "Invalid Device Name!";
				}
				
				TTTLoader::addReadlineValue("Delete server sync (y/n)?", 'n' );
				TTTLoader::addReadlineValue("Server URI:\t\t", $session->getTemp('syncUri') );
				TTTLoader::addReadlineValue("Sync group:\t\t", $session->getTemp('syncGroup') );
				TTTLoader::addReadlineValue("Client token:\t\t", $session->getTemp('syncToken') );
				TTTLoader::addReadlineValue("Name of this client:\t", $text );
				return true;

			default:
				return "Error";
		}

		$session->setTemp('readlineName', $suffix);
		return $suffix;
	}

	public static function editStep(Session $session, string $text) {
		switch( $session->getTemp('readlineName') ){
			case "Give ID of task to edit, else exit:":
				if( \preg_match('/^\d+$/', $text) !== 1 ){
					return "Invalid ID given!";
				}
				$session->setTemp('editId', intval($text));
				$suffix = "Delete task (y/n)?";
				break;

			case "Delete task (y/n)?":
				if($text === 'y'){
					TTTLoader::addReadlineValue("Give ID of task to edit, else exit:", $session->getTemp('editId'), 'e' );
					TTTLoader::addReadlineValue("Delete task (y/n)?", 'y' );
					return true;
				}
				$suffix = "Give a new name [Only A-Z, a-z, 0-9, _ and -]:";
				break;

			case "Give a new name [Only A-Z, a-z, 0-9, _ and -]:":
				$session->setTemp('editName', $text === '.' ? '' : $text);
				$suffix = "Give a new category [Use a name containing A-Z, a-z, 0-9 and -, no ID]:";
				break;

			case "Give a new category [Use a name containing A-Z, a-z, 0-9 and -, no ID]:":
				$session->setTemp('editCategory', $text === '.' ? '' : $text);
				$suffix = "Give a new duration (will change the end time; format e.g. 1h10m, 10m, 1h):";
				break;

			case "Give a new duration (will change the end time; format e.g. 1h10m, 10m, 1h):":
				TTTLoader::addReadlineValue("Give ID of task to edit, else exit:", $session->getTemp('editId'), 'e' );
				TTTLoader::addReadlineValue("Delete task (y/n)?", 'n' );

				TTTLoader::addReadlineValue("Give a new name [Only A-Z, a-z, 0-9, _ and -]:", $session->getTemp('editName'));
				TTTLoader::addReadlineValue("Give a new category [Use a name containing A-Z, a-z, 0-9 and -, no ID]:", $session->getTemp('editCategory'));
				TTTLoader::addReadlineValue("Give a new duration (will change the end time; format e.g. 1h10m, 10m, 1h):", $text === '.' ? '' : $text);
				return true;
				
			default:
				return "Error";
		}

		$session->setTemp('readlineName', $suffix);
		return $suffix;
	}

	
}
?>