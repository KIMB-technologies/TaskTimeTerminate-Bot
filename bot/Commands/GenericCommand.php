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

class GenericCommand extends SystemCommand
{

	protected $name = 'generic';
	protected $description = 'Generic fallback command';
	protected $version = '1.0.0';
	protected $private_only = false;

	public function execute(): ServerResponse
	{
		return $this->replyToChat(
			'This is not a command for this bot.' . PHP_EOL .
			'Use one of /start, /ttt or /task!'
		);
	}
}
