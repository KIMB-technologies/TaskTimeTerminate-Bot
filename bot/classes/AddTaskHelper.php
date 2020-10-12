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

use Longman\TelegramBot\Entities\Keyboard;

class AddTaskHelper {

	private Session $session;
	private ?Keyboard $keyboard = null;
	private string $answer = "";

	public function __construct(Session $session){
		$this->session = $session;
		TTTLoader::loadTTT('/code/data/' . $this->session->getKey());
	}

	public function taskCommand(string $params) : void {
		if(!empty($params)){
			$params = \explode(' ', $params);
			if(\count($params) === 3){
				$this->runTTT(trim($params[2]), trim($params[0]), trim($params[1]));
			}
			else if(\count($params) === 1 &&
				$this->session->getData('lastCategory') !== false &&
				$this->session->getData('lastTask') !== false
			){
				$time = str_replace('+', '', $params[0]);
				$this->runTTT(trim($time), $this->session->getData('lastCategory'), $this->session->getData('lastTask'));
			}
			else{
				$this->runTTT("", "", "");
				$this->answer = "Use `/task Category Task 20m` or `/task +20m` or just `/task` for Step-by-Step query"; 
			}
		}
		else{
			$this->runTTT("", "", "");
			$this->answer = "Recoding new Task Step-by-Step, one may also use `/task Category Task 20m` or `/task +20m`." . PHP_EOL;
			$this->answer .= TTTLoader::runTTTCommand(['c', 'c', 'list']);
			$this->answer .= "Please choose a category:";

			$this->session->setTemp('messageHandler', 'newTask');
			$this->session->setTemp('taskStep', 'category');
		}
	}

	public function messageCommand(string $text) : void {
		switch($this->session->getTemp('taskStep')){
			case "category":
				$this->answer = "Please give the name of the task now:";
				$this->session->setData('lastCategory', $text);
				$this->session->setTemp('taskStep', 'task');
				break;
			case "task":
				$this->answer = "Please give duration for the task, either a time like `12:00` or a duration like `20m` and `1h10m`:";
				$this->keyboard = (new Keyboard(
					['15m', '30m', '1h'],
					['1h', '1h30m', '2h'],
					['3h', '5h', '8h']
				))->setResizeKeyboard(true)->setOneTimeKeyboard(true);
				$this->session->setData('lastTask', $text);
				$this->session->setTemp('taskStep', 'time');
				break;
			case "time":
				$this->runTTT($text, $this->session->getData('lastCategory'), $this->session->getData('lastTask'));
				$this->session->setTemp('messageHandler', false);
				break;
			default:
				$this->answer .= 'Unknown add task handler – this should not have happened!';
				break;
		}
	}

	private function runTTT(string $time, string $category, string $task) : void {
		if(!empty($time) && !empty($category) && !empty($task)){
			$newTask = array(
				'time' => $time,
				'category' => $category,
				'task' => $task
			);
			$this->session->setData('lastCategory', $category);
			$this->session->setData('lastTask', $task);
		}
		else{
			$newTask = array();
		}

		TTTLoader::runTTTCommand(['r'], $newTask);
		$this->answer .= TTTLoader::runTTTCommand(['o']);
	}

	public function getReplyText() : string {
		return $this->answer;
	}

	public function getReplyKeyboard() : ?Keyboard {
		return $this->keyboard;
	}
}
?>