<?php

/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
*/
include_once (LIB_PATH . "/smarty/Smarty.class.php");
include_once (RUDRA . "/controller/AbstractController.php");
include_once (RUDRA . "/model/Header.php");

class AbstractPageController extends AbstractController {

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
				$temp->setTemplate($tpl );
				$header = new Header($tpl);
				$view = RudraX::invokeMethodByReflectionClass($tempClass,$temp,'invokeHandler',array(
					'tpl' => $tpl,
					'viewModel' => $tpl,
					'user' => $user,
					'header' => $header
				));
				//$view = $temp->invokeHandler($tpl );
				if (! isset($view )) {
					$view = $handlerName;
				}

				$tpl->assign('CONTEXT_PATH',CONTEXT_PATH);
				$tpl->assign('RESOURCE_PATH',Config::get('RESOURCE_PATH'));
				$tpl->assign('CSS_FILES',$header->css);
				$tpl->assign('SCRIPT_FILES',$header->scripts);
				$tpl->assign('BODY_FILES',$view . Config::get('TEMP_EXT'));
				//echo get_include_path();
				//$tpl->display($this->getViewPath() . $view . Config::get('TEMP_EXT'));
				$tpl->display(get_include_path().RUDRA."/view/full.tpl");
				
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
