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

class Session {

	private int $id;
	private string $key;

	private static ?JSONReader $sessionFile = null;

	public function __construct( int $chatIt ){
		if( is_null(self::$sessionFile) ){
			self::$sessionFile = new JSONReader('sessions', true);
		}

		$this->id = $chatIt;
		$this->key = \hash('sha256', $this->id);

		if(!self::$sessionFile->isValue([$this->key])){
			self::$sessionFile->setValue([$this->key], array(
				'id' => $this->id,
				'data' => array(
					'temp' => array(),
					'persistent' => array()
				),
				'lastUsed' => time(),
				'created' => time()
			));
		}
		else{
			self::$sessionFile->setValue([$this->key, 'lastUsed'], time());
		}
	}

	public function getData(string $key) {
		if(self::$sessionFile->isValue([$this->key, 'data', 'persistent', $key])){
			return self::$sessionFile->getValue([$this->key, 'data', 'persistent', $key]);
		}
		return false;
	}

	public function setData(string $key, $data) : bool {
		return self::$sessionFile->setValue([$this->key, 'data', 'persistent', $key], $data);
	}

	public function getTemp(string $key) {
		if(self::$sessionFile->isValue([$this->key, 'data', 'temp', $key]) &&
			self::$sessionFile->getValue([$this->key, 'data', 'temp', $key, 'time']) > time()
		){
			return self::$sessionFile->getValue([$this->key, 'data', 'temp', $key, 'data']);
		}
		return false;
	}

	public function setTemp(string $key, $data, int $timeout = 600) : bool {
		return self::$sessionFile->setValue([$this->key, 'data', 'temp', $key], array(
			'time' => time() + $timeout,
			'data' => $data
		));
	}

	public function getKey() : string {
		return $this->key;
	}

	public static function fullSessionArray() : array {
		if( !is_null(self::$sessionFile) ){
			return self::$sessionFile->getArray();
		}
		else{
			$r = new JSONReader('sessions');
			$data = $r->getArray();
			$r->__destruct();
			unset($r);
			return $data;
		}
	}
}
?>