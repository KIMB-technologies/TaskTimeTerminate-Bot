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

class Config {

	private static array $conf = array();
	private static bool $confInit = false;

	private const DEFAULT = array(
		'api_key' => 'your:bot_api_key',
		'bot_username' => 'username_bot',
		'webhook' => array(
			'url' => 'https://example.com/path/to/hook-or-manager.php'
		),
		'commands'	 => array(
			'paths'   => array(
				__DIR__ . '/../Commands',
			)
		),
		'limiter' => array(
			'enabled' => true,
		)
	);

	private static function setUp(){
		if( !self::$confInit ){
			self::$conf = self::DEFAULT;

			self::$conf['api_key'] = !empty($_ENV['TELEGRAM_API_KEY']) && is_string($_ENV['TELEGRAM_API_KEY']) ? $_ENV['TELEGRAM_API_KEY'] : '';
			self::$conf['bot_username'] = !empty($_ENV['TELEGRAM_BOT_NAME']) && is_string($_ENV['TELEGRAM_BOT_NAME']) ? $_ENV['TELEGRAM_BOT_NAME'] : '';
			
			self::$conf['webhook']['url'] = !empty($_ENV['WEBHOOK_URL']) && is_string($_ENV['WEBHOOK_URL']) ? $_ENV['WEBHOOK_URL'] : '';
			if( substr(self::$conf['webhook']['url'], -8) !== 'hook.php' ){
				if( substr(self::$conf['webhook']['url'], -9, 1) !== '/' ){
					self::$conf['webhook']['url'] .= '/';
				}
				self::$conf['webhook']['url'] .= 'hook.php';
			}

			self::$confInit = true;
		}
	}

	public static function get(string ...$key) {
		self::setUp();

		$data = self::$conf;
		foreach($key as $v){
			$data = $data[$v];
		}
		return $data;
	}

	public static function unregisterWebhook() : bool {
		return !empty($_ENV['WEBHOOK_UNREGISTER']) && $_ENV['WEBHOOK_UNREGISTER'] === 'true';
	}

	public static function getUserRegisterToken() : string {
		return !empty($_ENV['REGISTER_TOKEN']) && is_string($_ENV['REGISTER_TOKEN']) ? $_ENV['REGISTER_TOKEN'] : '';
	}

	public static function isDevMode() : bool {
		return !empty($_ENV['DEVMODE']) && $_ENV['DEVMODE'] == 'true';
	}

}