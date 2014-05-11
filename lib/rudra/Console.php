<?php

/*
 * @category Lib @package Test Suit @copyright 2011, 2012 Dmitry Sheiko (http://dsheiko.com) @license GNU
 */

/**
 * Logger in FireBug fashion
 */
class Console {
	private static $MODE = false;
	public static function set($set) {
		self::$MODE = $set;
	}
	
	/**
	 * Decorator for trace info
	 * 
	 * @param array $trace
	 *        	- debug_backtrace results
	 * @return string
	 */
	private static function _debugDecorator($trace) {
		return sprintf ( "%s : %d >", str_replace ( BASE_PATH, "", $trace [0] ["file"] ), $trace [0] ["line"] );
	}
	
	/**
	 * Logger implementation
	 *
	 * @param array $args        	
	 * @param string $logName        	
	 */
	private static function _log($args, $logName = "error") {
		$logExt = "." . date ( "Y-m-d" ) . ".log";
		foreach ( $args as $data ) {
			file_put_contents ( BASE_PATH . "/" . LOG_PATH . "/" . $logName . $logExt, 
			print_r ( $data, 1 ) . "\n##################################################################\n", 
			FILE_APPEND );
		}
		if (self::$MODE) {
			echo $data . "<br/>";
		}
	}
	
	/**
	 * Generic logger alias
	 * Usage: console::log(mixed, mixed, .
	 * .)
	 */
	public static function log() {
		$trace = debug_backtrace ();
		$args = func_get_args ();
		$args = array_merge ( array (
				self::_debugDecorator ( $trace ) 
		), $args );
		self::_log ( $args, 'info' );
	}
	public static function error() {
		$trace = debug_backtrace ();
		$args = func_get_args ();
		$args = array_merge ( array (
				self::_debugDecorator ( $trace )
		), $args );
		self::_log ( $args, 'error' );
	}
	public static function exception(Exception $e) {
		$trace = debug_backtrace ();
		$args = func_get_args ();
		$args = array_merge ( array (
				self::_debugDecorator ( $trace ) 
		), $args );
		self::_log ( $args, 'exception' );
	}
}
