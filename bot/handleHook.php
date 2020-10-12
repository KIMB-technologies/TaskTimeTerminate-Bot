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

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/classes/load.php';

try {
	$telegram = new Longman\TelegramBot\Telegram(
		TTTBot\Config::get('api_key'),
		TTTBot\Config::get('bot_username')
	);

	$telegram->addCommandsPaths(TTTBot\Config::get('commands','paths'));
	$telegram->enableLimiter(TTTBot\Config::get('limiter'));
	
	$telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
	if( TTTBot\Config::isDevMode()){
		file_put_contents('/code/data/error.log', date('d-m-Y H:i') . ' -- ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
	}
}