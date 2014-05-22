<?php

/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
*/
include_once (LIB_PATH . "/smarty/Smarty.class.php");
include_once (RUDRA . "/controller/AbstractController.php");

class AbstractTemplateController extends AbstractController {

	public function getHandlerPath() {
		return "";
	}

	public function getViewPath() {
		return "";
	}

	public function invokeHandler(User $user, $handlerName) {
		$className = ucfirst($handlerName );
		$user->validate();
		include_once(RUDRA . "/handler/AbstractHandler.php");
		include_once (HANDLER_PATH . "/" . $this->getHandlerPath() . $className . ".php");
		$tempClass = new ReflectionClass($className );
		global $temp;
		if ($tempClass->isInstantiable()) {
			$temp = $tempClass->newInstance();
		}
		
		if ($temp != NULL) {
			$temp->setUser($user );

			if ($tempClass->hasMethod("invokeHandler" )) {
				$tpl = new Smarty();
				// $tpl->prepare();
				$tpl->setTemplateDir(get_include_path() .Config::get('VIEW_PATH'));
				$tpl->setConfigDir(get_include_path() . Config::get('CONFIG_PATH'));
				$tpl->setCompileDir(get_include_path() . Config::get('TEMP_PATH'));
				$tpl->setCacheDir(get_include_path() . Config::get('CACHE_PATH'));
				// $tpl->testInstall(); exit;
				$tpl->debugging = Config::get('SMARTY_DEBUG');
				$temp->setTemplate($tpl);
				$view = RudraX::invokeMethodByReflectionClass($tempClass,$temp,'invokeHandler',array(
						'tpl' => $tpl,
						'viewModel' => $tpl,
						'user' => $user
				));
				//$view = $temp->invokeHandler($tpl );
				if (! isset($view )) {
					$view = $handlerName;
				}
				$tpl->display($this->getViewPath() . $view . Config::get('TEMP_EXT'));
			} else if ($tempClass->hasMethod("invoke" )) {
				$view = $temp->invoke();
				if (! isset($view )) {
					$view = $handlerName;
				}
				include $this->getViewPath() . $view . '.php';
			}
		}
	}
}
