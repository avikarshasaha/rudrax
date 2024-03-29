<?php 
require_once "Console.php";
class Config {
	public static function get($section,$prop=NULL){
		return $GLOBALS['CONFIG'][$section];
	}
}
class HttpRequest {
	private static $request;
	private $map = array();
	public function get($key){
		if(isset($this->map[$key])){
			return $this->map[$key];
		} else if(isset($_REQUEST[$key])){
			return $_REQUEST[$key];
		} else {
			return NULL;
		}
	}
	public function set($key,$value){
		$this->map[$key] = $value;
		return $this;
	}
	public function getAllParams(){
		return $this->map;
	}
	public function loadParams($params){
		$this->map = array_merge($this->map,$params);
		return $this;
	}
	public static function getInstance(){
		if(!isset(self::$request)){
			self::$request = new HttpRequest();
		}
		return self::$request;
	}
}


class RudraX {
	public static $REQUEST_MAPPED = FALSE;
	public static function setProjectConfiguration($file){
		ob_start ();
		session_start ();
		$GLOBALS ['CONFIG'] = parse_ini_file ($file, TRUE );
		set_include_path ($GLOBALS['CONFIG']['WORK_DIR']);
		define("BASE_PATH", dirname(__FILE__) );
		define("LOG_PATH", $GLOBALS ['CONFIG']['LOG_PATH']);
		//define("APP_PATH", realpath(BASE_PATH . "/../../"));
		define ( 'APP_PATH', $GLOBALS ['CONFIG']['APP_PATH']);
		define ( 'LIB_PATH', $GLOBALS ['CONFIG']['LIB_PATH'] );
		define ( 'RUDRA', $GLOBALS ['CONFIG']['RUDRA'] );
		define ( 'VIEW_PATH', $GLOBALS ['CONFIG']['VIEW_PATH']);
		define ( 'HANDLER_PATH', $GLOBALS ['CONFIG']['HANDLER_PATH'] );
		define ( 'DEBUG_ENABLED', $GLOBALS ['CONFIG']['DEBUG_ENABLED'] );
		define ( 'MODEL_PATH', $GLOBALS ['CONFIG']['MODEL_PATH'] );
		define ( 'CONTROLLER_PATH', $GLOBALS ['CONFIG']['CONTROLLER_PATH'] );
		define('Q',(isset($_REQUEST['q']) ? $_REQUEST['q'] : NULL));

		define ( 'CONTEXT_PATH', (
		(Q==NULL) ? strstr($_SERVER['REQUEST_URI'],"?".$_SERVER['QUERY_STRING'],TRUE) : strstr($_SERVER['REQUEST_URI'],Q,true)
		));
		Console::set(TRUE);
	}
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
	public static function getArgsArray($reflectionMethod,$argArray){
		$arr = array();
		$request =  HttpRequest::getInstance();
		foreach($reflectionMethod->getParameters() as $key => $val){
			$arr[$val->getName()] = isset($argArray[$val->getName()])
			? $argArray[$val->getName()]
			:(($request->get($val->getName()))
					? $request->get($val->getName())
					:($val->isDefaultValueAvailable()
							? $val->getDefaultValue() : NULL
					)
			);
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
	public static function invokePlug ($callback){
		RudraX::includePages();
		$reflectionMethod = new ReflectionFunction($callback);
		return call_user_func_array($callback, self::getArgsArray($reflectionMethod,array()));
	}
	public static function invokePage ($callback){
		RudraX::includePages();
		return RudraX::invokePlug($callback);
	}
	public static function invokeTemplate ($callback){
		RudraX::includeTemplates();
		return RudraX::invokePlug($callback);
	}
	public static function invokeTunnel ($callback){
		if (file_exists(get_include_path() . CONTROLLER_PATH . "/NotificationController.php" )) {
			include_once (CONTROLLER_PATH . "/NotificationController.php");
		} else {
			include_once (RUDRA . "/controller/NotificationController.php");
		}
		return RudraX::invokePlug($callback);
	}

	public static function mapRequest ($mapping,$callback){
		if(self::$REQUEST_MAPPED) return;
		$mapper = preg_replace('/\{(.*?)\}/m','(?P<$1>[\w\.]+)', str_replace('/','#',$mapping));
		$varmap = array();
		preg_match("/".$mapper."/",str_replace( array("/"),
		array("#"),Q),$varmap);

		$request =  HttpRequest::getInstance()->loadParams($varmap);
		$argArray = self::getArgsArray(new ReflectionFunction($callback),$varmap);
		$request->loadParams($argArray);

		if(count($varmap)>0){
			self::$REQUEST_MAPPED = TRUE;
			return call_user_func_array($callback, $argArray);
		}
	}

}
class DBUtils {
	public static function getDB($configname){
		return RudraX::getDB($configname);
	}
}

?>