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

class TttCommand extends SystemCommand
{

	protected $name = 'ttt';
	protected $description = 'Main TTT Command';
	protected $usage = '/ttt';
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
		 * Handle normal TTT Commands
		 */
		// get command
		$command = $this->getMessage()->getText(true);
		$command = explode(' ', $command);

		// disallowed
		$disallow = new \TTTBot\CommandFilter(array(
				['record|r|change|new'],
				['settings|preferences|p|conf|c','sync','directory'],
				['end|begin|pause|stop|e|start|t|toggle'],
				['settings|preferences|p|conf|c','merge']
			));
		if($disallow->matches($command)){
			return $this->replyToUser('This command is not supported in the Telegram Client!' . PHP_EOL . 'Use /task to start new tasks!');
		}

		//readline??
		$readline = new \TTTBot\CommandFilter(array(
				['settings|preferences|p|conf|c','cats|categories|c','add'],
				['settings|preferences|p|conf|c','cats|categories|c','del'],
				['settings|preferences|p|conf|c','edit|e'],
				['settings|preferences|p|conf|c','sync','server'],
			));

		$suffix = '';
		if($readline->matches($command)){
			switch($readline->lastMatchId()){
				case 0:
					$session->setTemp('messageHandler', 'categoryAdd');
					$command =  array('c','cats','list');
					$suffix = "Name of category to add [Only A-Z, a-z, 0-9 and -]:";
					$session->setTemp('readlineName', $suffix);
					break;
				case 1:
					$session->setTemp('messageHandler', 'categoryDel');
					$command =  array('c','cats','list');
					$suffix = "Type ID to delete category, else abort:";
					$session->setTemp('readlineName', $suffix);
					break;
				case 2:
					$day = $command[2];
					if(\preg_match('/^\d{4}-(1|0)\d-[0-3]\d$/', $day) === 1){
						$session->setTemp('messageHandler', 'editData');
						$suffix = "Give ID of task to edit, else exit:";
						\TTTBot\TTTLoader::addReadlineValue($suffix, 'e' );
						$command = array('c','e', $day);
						$session->setTemp('editDay', $day);
						$session->setTemp('readlineName', $suffix);
						$suffix = "For all upcoming, type `.` to remain unchanged." . PHP_EOL . "Give ID of task to edit:";
					}
					else{
						return $this->replyToUser('Please give a date YYYY-MM-DD, e.g. /ttt c e 2020-10-03!');
					}
					break;
				case 3:
					$session->setTemp('messageHandler', 'editSync');
					$command = array('c','sync');
					$suffix ="Delete server sync (y/n)?";
					$session->setTemp('readlineName', $suffix);
					break;
				default:
					break;
			}
		}
		
		// execute
		\TTTBot\TTTLoader::loadTTT('/code/data/' . $session->getKey());
		$output = \TTTBot\TTTLoader::runTTTCommand($command);
		return $this->replyToUser(
			$output . ( empty($suffix) ? '' : PHP_EOL . $suffix),
			array('parse_mode' => 'Markdown')
		);
	}
}
