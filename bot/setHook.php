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
// error reporting in dev
error_reporting( !empty($_ENV['DEVMODE']) && $_ENV['DEVMODE'] == 'true' ? E_ALL : 0 );

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/classes/load.php';

try {
	$telegram = new Longman\TelegramBot\Telegram(
		TTTBot\Config::get('api_key'),
		TTTBot\Config::get('bot_username')
	);

	if ( TTTBot\Config::unregisterWebhook() && is_file('/code/data/WEBHOOK_REGISTERED') ){
		$result = $telegram->deleteWebhook();
		echo $result->getDescription() . PHP_EOL;
		if($result->isOk()){
			unlink('/code/data/WEBHOOK_REGISTERED');
		}
	}
	else if( !is_file('/code/data/WEBHOOK_REGISTERED') && !TTTBot\Config::unregisterWebhook() ){
		$token = TTTBot\Utilities::randomCode(100, TTTBot\Utilities::ID);
		$result = $telegram->setWebhook( TTTBot\Config::get('webhook', 'url') . '?token=' . $token, array(
			'allowed_updates' => 'message'
		));
		if($result->isOk()){
			file_put_contents('/code/data/WEBHOOK_REGISTERED', $token);
		}
		echo $result->getDescription() . PHP_EOL;
	}
	else {
		echo "Webhook seems registered or should not be registered." . PHP_EOL;
	}
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
	echo "Error un/registering webhook" . PHP_EOL;
	echo $e->getMessage() . PHP_EOL;
}