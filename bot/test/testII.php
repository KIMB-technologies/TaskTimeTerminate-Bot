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

if(empty($_ENV['DEVMODE']) || $_ENV['DEVMODE'] != 'true' ){
	die('Development mode only!');
}
error_reporting( E_ALL );

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../classes/load.php';

$chatid = 746901670;
$command = "ttt ";

// parse
$command =  array_slice(explode(' ', $command), 1);
// session
$session = new \TTTBot\Session($chatid);

// ????

// run
\TTTBot\TTTLoader::loadTTT('/code/data/' . $session->getKey());
echo \TTTBot\TTTLoader::runTTTCommand($command);