<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Initializes the Autoloader
     * @return unknown_type
     */
    protected function _initAutoload() {
        $autoloader = new Zend_Application_Module_Autoloader(array('namespace' => 'Default','basePath'  => dirname(__FILE__)));
        return $autoloader;
    }

}

