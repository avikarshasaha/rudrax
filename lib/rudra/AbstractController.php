<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include(LIB_PATH . "/smarty/Smarty.class.php");
include_once("AbstractUser.php");

class AbstractController {

    public $name;
    public static $myController;

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function getHandlerPath() {
        return "";
    }

    public function getViewPath() {
        return "";
    }

    public function invoke(AbstractUser $user) {
        self::$myController = $this;
        $this->invokeHandler($this->getName(),$user);
    }

    public static function invokeHandler($handlerName,AbstractUser $user) {
        $className = ucfirst($handlerName);
        $user->validate();
        include_once(HANDLER_PATH . "/" . self::$myController->getHandlerPath() . $className . ".php");
        $tempClass = new ReflectionClass($className);
        global $temp;
        if ($tempClass->isInstantiable()) {
            $temp = $tempClass->newInstance();
        }
        if ($temp != NULL) {
            $temp->setUser($user);

            if ($tempClass->hasMethod("invokeHandler")) {
                $tpl = new Smarty();
                // $tpl->prepare();
                $tpl->setTemplateDir(APP_PATH.'/view/');
                $tpl->setConfigDir(APP_PATH.'/configs/');
                $tpl->setCompileDir(APP_PATH.'/temp/');
                $tpl->setCacheDir(APP_PATH.'/cache/');
                $temp->setTemplate($tpl);
                $view = $temp->invokeHandler($tpl);
                if (!isset($view)) {
                    $view = $handlerName;
                }
                $tpl->display(VIEW_PATH . "/" . self::$myController->getViewPath() . $view . ".tpl");
            } else if ($tempClass->hasMethod("invoke")) {
                $view = $temp->invoke();
                if (!isset($view)) {
                    $view = $handlerName;
                }
                include VIEW_PATH . '/' . self::$myController->getViewPath() . $view . '.php';
            }
        }
    }

}
