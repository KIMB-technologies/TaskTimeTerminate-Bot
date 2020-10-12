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
use Longman\TelegramBot\Exception\TelegramException;


class TaskCommand extends SystemCommand
{

	protected $name = 'task';
	protected $description = 'Main Task Command';
	protected $usage = '/task';
	protected $version = '1.0.0';
	protected $private_only = false;

	public function execute(): ServerResponse
	{
		/**
		 * Authenticate
		 */
		$chat = $this->getMessage()->getChat();
		if(!$chat->isPrivateChat()){
			return $this->replyToUser('This bot is for usage in private Chats only!');
		}
		$session = new \TTTBot\Session($chat->getId());
		if(!$session->getData('registered')){
			return $this->replyToUser('Please start the bot via /start!');
		}

		/**
		 * Handle new Tasks
		 */

		$text = \TTTBot\Utilities::trimAndConvertInput($this->getMessage()->getText(true));
		
		$th = new \TTTBot\AddTaskHelper($session);
		$th->taskCommand($text);

		$opts = array('parse_mode' => 'Markdown');
		if(!is_null($th->getReplyKeyboard())){
			$opts['reply_markup'] = $th->getReplyKeyboard();
		}
		return $this->replyToChat($th->getReplyText(), $opts);

	}
}
