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

class Utilities {

	/**
	 * Possible chars for:
	 */
	const ID = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890';
	const CODE = 'abcdefghijklmnopqrstuvwxyz01234567890';
	const CAPTCHA = 'abcdefghjkmnpqrstuvwxyz23456789';

	/**
	 * Checks if a String is a valid file-name (file only, no dirs)
	 * @param $name the filename
	 */
	public static function checkFileName($name){
		return is_string($name) && preg_match( '/^[A-Za-z0-9]+$/', $name ) === 1;
	}

	/**
	 * Validates a string by the given rex and cuts lenght
	 * 	**no boolean return**
	 * @param $s the string to check
	 * @param $reg the regular expressions (/[^a-z]/ to allow only small latin letters)
	 * @param $len the maximum lenght
	 * @return the clean string (empty, if other input than string or only dirty characters)
	 */
	public static function validateInput($s, $reg, $len){
		if( !is_string($s) ){
			return '';
		}
		return substr(trim(preg_replace( $reg, '' , $s )), 0, $len);
	}

	/**
	 * Generates a random code
	 * @param $len the code lenght
	 * @param $chars the chars to choose of (string)
	 * 	e.g. consts POLL_ID, ADMIN_CODE
	 */
	public static function randomCode( $len, $chars ){
		$r = '';
		$charAnz = strlen( $chars );
		for($i = 0; $i < $len; $i++){
			$r .= $chars[random_int(0, $charAnz-1)];
		}
		return $r;
	}
}

?>
