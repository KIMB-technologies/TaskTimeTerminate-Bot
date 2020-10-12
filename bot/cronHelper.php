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

require_once __DIR__ . '/classes/load.php';

$key = $argv[1];
if(!empty($key) && is_dir('/code/data/' . $key)){
	TTTBot\TTTLoader::loadTTT('/code/data/' . $key);
	TTTBot\TTTLoader::runTTTCommand(['r'], array(
		'time' => "",
		'category' => "",
		'task' => ""
	));
	echo "ok" . PHP_EOL;
}
else{
	echo "error" . PHP_EOL;
}