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

class TTTLoader {

	const TTT_LOADER = '/code/ttt/core/load.php';
	const CONF_CLASSES = array(
		'/code/ttt/core/Reader.php',
		'/code/ttt/core/JSONReader.php',
		'/code/ttt/core/Utilities.php',
		'/code/ttt/core/Config.php',
	);

	private static bool $tttLoaded = false;
	private static string $directory = '';
	private static array $readlineValues = array();

	public static function loadTTT( string $directory ){
		if(!self::$tttLoaded){
			if(\substr($directory, -1) !== '/'){
				$directory .= '/';
			}

			if( !\is_dir($directory) ){
				mkdir($directory, 0740, true);
			}
			if( !\is_file($directory . 'config.json') ){
				\file_put_contents(
						$directory . 'config.json',
						\json_encode(
							array(
								"savedir" => $directory .'tttdata',
								"sleep" => 60
							), 
							JSON_PRETTY_PRINT
						)
					);
			}
			if( !\is_file($directory . 'tttdata/telegram.json') ){
				\file_put_contents($directory . 'tttdata/telegram.json', '[]');
			}

			foreach( self::CONF_CLASSES as $classes ){
				require_once( $classes );
			}
			\Config::init($directory);
			require_once( self::TTT_LOADER );

			self::$tttLoaded = true;
			self::$directory = $directory;
		}
		else {
			die();
			file_put_contents('/code/data/error.log', "Error: TTT already loaded" . PHP_EOL, FILE_APPEND);
		}
	}

	public static function addReadlineValue(string $key, string ...$value) : void {
		self::$readlineValues[$key] = count($value) === 1 ? $value[0] : $value;
	}

	public static function runTTTCommand( array $args, array $newTask = array() ) : string {
		if( self::$tttLoaded ){
			$t = new JSONReader('telegram', true, self::$directory .'tttdata' );
			$t->setValue(['readline'], self::$readlineValues);
			if(!empty($newTask)){
				$t->setValue(['dialog'], $newTask);
			}
			$t->__destruct();

			$args = array_merge(array('ttt'), $args);
			\ob_start();
			$parser = new \CLIParser(count($args), $args);
			$cli = new \CLI($parser);
			$cli->checkTask();
			unset($cli);
			$r = \ob_get_contents();
			\ob_end_clean();
			gc_collect_cycles();
			return  '```' . PHP_EOL . self::clearColors($r) . PHP_EOL . '```';
		}
		else {
			file_put_contents('/code/data/error.log', "Error: Load TTT before running command!" . PHP_EOL, FILE_APPEND);
			return "";
		}
	}

	private static function clearColors(string $s) : string {
		return \str_replace( array(
					"\e[0;31m",
					"\e[0;30m",
					"\e[0;32m",
					"\e[0;33m",
					"\e[0;34m",
					"\e[0;37m",
					"\e[0;0m"
				),
				'',
				$s
			);
	}
}
?>