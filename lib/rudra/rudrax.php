<?php 

class Config {
	public static function get($section,$prop=NULL){
		return $GLOBALS['CONFIG'][$section];
	}
}
class RudraX {
	public static function includeTemplates(){
		self::includeUser();
		include_once(RUDRA."/controller/AbstractTemplateController.php");
		if (file_exists(get_include_path() . CONTROLLER_PATH . "/TemplateController.php" )) {
			include_once (CONTROLLER_PATH . "/TemplateController.php");
		} else {
			include_once (RUDRA . "/controller/TemplateController.php");
		}
	}
	public static function includePages(){
		self::includeUser();
		include_once(RUDRA."/controller/AbstractPageController.php");
		if (file_exists(get_include_path() . CONTROLLER_PATH . "/PageController.php" )) {
			include_once (CONTROLLER_PATH . "/PageController.php");
		} else {
			include_once (RUDRA . "/controller/PageController.php");
		}
	}
	public static function includeUser(){
		if (file_exists ( get_include_path () . MODEL_PATH . "/User.php" )) {
			include_once (MODEL_PATH . "/User.php");
		} else {
			include_once (RUDRA . "/_model_User.php");
		}
	}
	public static function getDB($configname){
		include_once (RUDRA . "/db/AbstractDb.php");
		return new AbstractDb(Config::get($configname));
	}
	public static function getArgsArray(ReflectionMethod $reflectionMethod,$argArray){
		$arr = array();
		foreach($reflectionMethod->getParameters() as $key => $val){
			$arr[$val->getName()] = isset($argArray[$val->getName()]) ?
			$argArray[$val->getName()] : (isset($_REQUEST[$val->getName()])
					? $_REQUEST[$val->getName()] : NULL);
		}
		return $arr;
	}
	public static function invokeMethodByReflectionClass (ReflectionClass $reflectionClass,$object,$methodName,$argArray){
		$reflectionMethod = $reflectionClass->getMethod($methodName);
		return call_user_func_array(array($object, $methodName), self::getArgsArray($reflectionMethod,$argArray));
	}
	public static function invokeMethod ($object,$methodName,$argArray){
		$reflectionClass = new ReflectionClass(get_class($object));
		$reflectionMethod = $reflectionClass->getMethod($methodName);
		return call_user_func_array(array($object, $methodName), self::getArgsArray($reflectionMethod,$argArray));
	}
}
class DBUtils {
	public static function getDB($configname){
		return RudraX::getDB($configname);
	}
}
?>