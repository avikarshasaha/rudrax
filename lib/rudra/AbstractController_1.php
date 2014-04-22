<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("model/User.php");

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

    public function invoke() {
        self::$myController = $this;
        $this->invokeHandler($this->getName());
    }

    public static function invokeHandler($handlerName) {
        $className = ucfirst($handlerName);
        global $user;
        $user = new User();
        $user->validate();
        include_once(HANDLER_PATH."/" . self::$myController->getHandlerPath() . $className . ".php");
        $tempClass = new ReflectionClass($className);
        global $temp;
        if ($tempClass->isInstantiable()) {
            $temp = $tempClass->newInstance();
        }
        if ($temp != NULL) {
            $temp->setUser($user);
            $temp->initView();
            $view = $temp->getView();
            if (!isset($view)) {
                $view = $handlerName;
            }
            $temp->setView($view);

            if ($tempClass->hasMethod("invokeTemplate")) {
                $tpl = new TemplatePower(VIEW_PATH . "/" . self::$myController->getViewPath() . $view . ".tpl");
                $tpl->prepare();
                $temp->setTemplate($tpl);
                $temp->invokeTemplate();
                $tpl->printToScreen();
            } else if ($tempClass->hasMethod("invoke")) {
                $temp->invoke();
                include VIEW_PATH . '/' . self::$myController->getViewPath() . $view . '.php';
            }
        }
    }

}
