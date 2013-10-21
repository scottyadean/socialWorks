<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initGlobal() {
        
        define('SITE_URL',   'http://socialworks.dev');
        define('SITE_EMAIL', 'info@socialworks.dev');
        define('SITE_NAME',  'social works');
        define('LOGO_NAME',  'socialworks');

    }

    protected function _initAuth() {

        $fc = Zend_Controller_Front::getInstance();
        $acl = new Main_Acl();
        $fc->registerPlugin(new Plugin_Acl($acl));
        
    }

    protected function _initRoutes() {
        
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', 'routes');
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $router->removeDefaultRoutes();
        $router->addConfig($config,'routes');

    }


}

