<?php

namespace Core;

use ForceCMS\Controller\Plugin\Acl,
    ForceCMS\Model\Language,
    ForceCMS\Model\Themes,
    ForceCMS\Plugins\Language as LanguagePlugin,
    ForceCMS\Plugins\Admin,
    ForceCMS\Plugins\CsrfProtect,
    ForceCMS\Plugins\HtmlCompress,
    ForceCMS\Plugins\Router;
use ForceCMS\Plugins\Caching;

/**
 * Bootstraps required resources for the application
 * @package     ForceCMS
 * @subpackage  Application
 * @category    Bootstrap
 * @copyright   Copyright (c) 2012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class Bootstrap extends \ForceX\Application\Module\Bootstrap {

    /**
     * Config inicialization and logging app.
     */
    protected function _initAppConfig()
    {
        $config = new \Zend_Config_Ini(
            APPLICATION_PATH . '/Config/config.ini',
            APPLICATION_ENV
        );
        \Zend_Registry::set('config', $config);
    }

    /**
     * Cache inicialization and caching path definition.
     * TODO: Allow database caching.
     */
    public function _initCache() {
        $cache = \Zend_Cache::factory(
            'Core', 'File', [
                'lifetime' => 3600 * 24 * 7, /* 7 days */
                'automatic_serialization' => true
            ], ['cache_dir' => APPLICATION_PATH . '/Data/cache']
        );

        \Zend_Registry::set('Cache', $cache);
    }

    protected function getThemeByControllerName() { // TODO: Move to some model
        $router = new \Zend_Controller_Router_Rewrite();
        $request = new \Zend_Controller_Request_Http();
        $router->route($request);
        $controllerName = $request->getControllerName();

        if(!preg_match('/^admin_/', $controllerName)) {
            return TRUE;
        } elseif(preg_match('/^admin_/', $controllerName)) {
            return FALSE;
        } else {
            return 'default';
        }
    }

    protected function _initLayout() {
        $requestStatus = $this->getThemeByControllerName();
        $activeTheme = Themes::getActiveTheme();

        // set layout based on request status
        if($requestStatus && $activeTheme !== NULL) {
            $path = APP_PUBLIC . '/themes/' . $activeTheme->theme_folder . '/templates';
            \Zend_Registry::set('theme', $activeTheme);
            \Zend_Layout::startMvc()
                ->setLayout('layout')
                ->setLayoutPath($path)
                ->setContentKey('content');
        } elseif(!$requestStatus) {
            \Zend_Layout::startMvc()
                ->setLayout('backend')
                ->setLayoutPath(APPLICATION_PATH . "/Core/View/layouts")
                ->setContentKey('content');
        } else {
            \Zend_Layout::startMvc()
                ->setLayout('layout')
                ->setLayoutPath(APPLICATION_PATH . "/Core/View/layouts")
                ->setContentKey('content');
        }

    }

    /**
     * View inicialization preLoading.
     * @return mixed|null|\Zend_View
     */
    protected function _initView() {
        $this->view = new \Zend_View();
        $requestStatus = $this->getThemeByControllerName();

        // theme folder based on requestStatus
        if ($requestStatus) {
            $activeTheme = Themes::getActiveTheme();
            $path = APP_PUBLIC . '/themes/' . $activeTheme->theme_folder . '/templates';
            $this->view->addScriptPath($path);
            $this->view->setScriptPath($path);
            $this->view->headTitle('Web site title')->setSeparator(' - ');
        } else {
            $this->view->headTitle('Force CMS')->setSeparator(' - ');
        }

        // set helpers path to view render object
        $viewRenderer = \Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($this->view);
        $viewRenderer->view->addHelperPath(APPLICATION_PATH . '/../lib/ForceCMS/Helpers/View', '')
                           ->addHelperPath(APPLICATION_PATH . '/../lib/ForceCMS/Helpers/Action', '')
                           ->addHelperPath(APPLICATION_PATH . '/../lib/ForceCMS/Helpers/View/Modules', '');

        return $this->view;
    }

    /**
     * Translate inicialization preLoading.
     * @return array|\Zend_Registry
     */
    protected function _initTranslate() {

        $modelLanguage = new Language();
        $languages = $modelLanguage->getAll();

        if (count($languages) > 0) {
            $i = 0;
            foreach ($languages as $value) {
                if ($i == 0) {
                    $translate = new \Zend_Translate([
                        'adapter' => 'array',
                        'content' => APPLICATION_PATH . '/Translate/languages/' . $value->short . '.php',
                        'locale' => $value->short
                        ]
                    );
                } else {
                    $translate->addTranslation([
                            'adapter' => 'array',
                            'content' => APPLICATION_PATH . '/Translate/languages/' . $value->short . '.php',
                            'locale' => $value->short
                        ]
                    );
                }
                $i++;
            }

            $i = 0;
            foreach ($languages as $value) {
                if ($i == 0) {
                    $translate->setLocale($value->short);
                }
                $i++;
            }

            \Zend_Registry::set('Zend_Translate', $translate);
        }
    }

    /**
     * Plugin preDispatching and preLoading.
     * @return mixed
     */
    protected function _initPlugins() {
        $fc = \Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Acl())
           ->registerPlugin(new Router(\Zend_Controller_Front::getInstance()))
           ->registerPlugin(new Admin())
           ->registerPlugin(new Caching())
           ->registerPlugin(new LanguagePlugin())
           ->registerPlugin(new CsrfProtect())
           ->registerPlugin(new HtmlCompress());
    }
}
