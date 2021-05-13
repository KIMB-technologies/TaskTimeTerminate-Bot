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

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class StartCommand extends SystemCommand
{

	protected $name = 'start';
	protected $description = 'Start the bot';
	protected $usage = '/start';
	protected $version = '1.0.0';
	protected $private_only = false;

	public function execute(): ServerResponse
	{
		$chat = $this->getMessage()->getChat();
		if(!$chat->isPrivateChat()){
			return $this->replyToUser('This bot is for usage in private Chats only!');
		}

		$session = new \TTTBot\Session($chat->getId());
		if($session->getData('registered')){
			return $this->replyToUser('You have started the bot already. Use /ttt or /task!');
		}
		else {
			$token = \TTTBot\Utilities::trimAndConvertInput($this->getMessage()->getText(true));
			if( empty($token) ){
				return $this->replyToUser(
					'This bot requires you to register for the usage. Please get a token from your admin or host your own version!' . PHP_EOL .
					'https://github.com/KIMB-technologies/TaskTimeTerminate-Bot' . PHP_EOL . PHP_EOL . 
					'If you have a token please run: ' . PHP_EOL . 
					"\t/start token"
				);
			}
			else if( $token !== \TTTBot\Config::getUserRegisterToken() ) {
				return $this->replyToUser('You entered an incorrect token for registration!');
			}
			else{
				$session->setData('registered', true);
				return $this->replyToUser('Registration successful! Use /ttt or /task! now');
			}
		}
	}
}
