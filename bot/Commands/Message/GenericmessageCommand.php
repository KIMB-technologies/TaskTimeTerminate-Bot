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
use Longman\TelegramBot\Request;

class GenericmessageCommand extends SystemCommand
{

	protected $name = 'genericmessage';
	protected $description = 'Generic fallback message';
	protected $version = '1.0.0';

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
		 * Handle message
		 */
		$handler = $session->getTemp('messageHandler');
		if($handler === false){
			return $this->replyToUser('Please use the commands /ttt and /task!');
		}
		else{
			$text = \TTTBot\Utilities::trimAndConvertInput($this->getMessage()->getText(true));

			switch($handler) {
				case "categoryAdd":
					if(\TTTBot\InputParser::checkCategoryInput($text)){
						\TTTBot\TTTLoader::addReadlineValue($session->getTemp('readlineName'), $text);
						$command = array('c','cats','add');
					}
					else{
						return $this->replyToChat('Invalid category name!');
					}
					break;
				case "categoryDel":
					if(is_numeric($text)){
						\TTTBot\TTTLoader::addReadlineValue($session->getTemp('readlineName'), $text);
						$command = array('c','cats','del');
					}
					else{
						return $this->replyToChat('Invalid category ID!');
					}
					break;
				case "editSync":
					$r = \TTTBot\SettingsHelper::syncStep($session, $text);
					if($r === true){
						$command = array('c','sync','server');
					}
					else{
						return $this->replyToChat($r);
					}
					break;
				case "editData":
					$r = \TTTBot\SettingsHelper::editStep($session, $text);
					if($r === true){
						$command = array('c','e', $session->getTemp('editDay'));
					}
					else{
						return $this->replyToChat($r);
					}
					break;
				case "newTask":
					$th = new \TTTBot\AddTaskHelper($session);
					$th->messageCommand($text);
					$opts = array('parse_mode' => 'Markdown');
					if(!is_null($th->getReplyKeyboard())){
						$opts['reply_markup'] = $th->getReplyKeyboard();
					}
					return $this->replyToChat($th->getReplyText(), $opts);
				default:
					return $this->replyToChat('Unknown message handler – this should not have happened!');
					break;
			}

			$session->setTemp('messageHandler', false);
			\TTTBot\TTTLoader::loadTTT('/code/data/' . $session->getKey());
			$output = \TTTBot\TTTLoader::runTTTCommand($command);
			return $this->replyToUser(
				$output,
				array('parse_mode' => 'Markdown')
			);
		}
	}
}
