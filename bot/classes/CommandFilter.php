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

class CommandFilter {

	private array $list = array();
	private int $matchId = -1;

	public function __construct(array $list){
		$this->list = $list;
	}

	public function matches($command) : bool {
		if( is_string($command) ){
			$command = \explode(' ', $command);
		}
		else if(!is_array($command)){
			return false;
		}
		foreach($this->list as $id => $parts){
			$match = true;
			foreach($parts as $i => $p){
				if(!isset($command[$i])){
					$match = false;
					break;
				}
				$poss = \explode('|', $p);
				$match &= (in_array($command[$i], $poss , true) || $p === '*');
			}
			if($match){
				$this->matchId = $id;
				return true;
			}
		}
		return false;
	}

	public function lastMatchId() : int {
		return $this->matchId;
	}

	public function lastMatchCommand() : array {
		return $this->list[$this->matchId];
	}
}
?>