<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initDefaultTimezone()
    {
        date_default_timezone_set('GMT');
    }

    protected function _initAutoload()
    {

        /* Zend Autoloader */
        require_once 'Zend/Loader/Autoloader.php';

        $loader = Zend_Loader_Autoloader::getInstance()
                        ->setFallbackAutoloader(true);

        $moduleLoader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => '',
                    'basePath' => APPLICATION_PATH
                ));

        return $moduleLoader;
    }

    protected function _initView()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');

        $view = $layout->getView();
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        $view->setEncoding('UTF-8');
        $view->doctype('XHTML1_STRICT');
        $view->headMeta()->appendHttpEquiv(
                'Content-Type', 'text/html;charset=utf-8'
        );

        // add global script path
        $view->addScriptPath(APPLICATION_PATH . '/views/scripts');

        // add global helpers
        $view->addHelperPath(APPLICATION_PATH . '/views/helpers', 'Zend_View_Helper_');

        //add Jquery helpers
        $view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");

        // add Zmz helpers
        $view->addHelperPath("Zmz/View/Helper", "Zmz_View_Helper");
    }

    protected function _initActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPrefix('Zmz_Controller_Action_Helper');
    }

    /**
     * Read project configuration file and put data into a global variable
     */
    protected function _initProjectConfig()
    {
        $filename = APPLICATION_PATH . '/configs/project.ini';
        if (file_exists($filename)) {
            $projectConfig = new Zend_Config_Ini($filename, $this->getEnvironment());
            $config = $projectConfig->toArray();
        } else {
            throw new Exception('File "' . $filename . '" not found');
        }

        if (!is_array($config)) {
            throw new Exception('$tmpArrayConfig is not an array');
        }

        $projectConfig = new Zmz_Object($config);
        $projectConfig->setThrowException(true);

        Zend_Registry::set('projectConfig', $projectConfig);
    }

    /**
     * Add required routes to the router
     */
    protected function _initRoutes()
    {
        $this->bootstrap('frontController');
        $front = $this->frontController;
        $front->setBaseUrl('/');
        $router = $front->getRouter();

        $filename = APPLICATION_PATH . '/configs/routes.ini';
        if (file_exists($filename)) {
            $routerConfig = new Zend_Config_Ini($filename, $this->getEnvironment(), false);
            Zend_Registry::set('routerConfig', $routerConfig);
        } else {
            throw new Exception('File "' . $filename . '" not found');
        }

        if (isset($routerConfig->routes)) {
            $router->addConfig($routerConfig, 'routes');
        }
    }

}

