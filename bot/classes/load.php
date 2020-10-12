<?php
spl_autoload_register(function ($class) {
	if( is_string($class) && substr($class, 0, 7) === 'TTTBot\\' ){ // is namespace TTTBot? 
		$classname = substr($class, 7);
		if( preg_match( '/^[A-Za-z0-9]+$/', $classname ) === 1 ){
			$classfile = __DIR__ . '/' . $classname . '.php';
			if( is_file($classfile) ){
				require_once( $classfile );
			}
		}
	}
});

\TTTBot\Reader::changePath('/code/data/');
?>