<?php

namespace ForceCMS\Plugins;

/**
 *
 * @package     ForceCMS
 * @subpackage  Plugins
 * @category    Security
 * @copyright   Copyright (c) 2012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class Admin extends \Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(\Zend_Controller_Request_Abstract $request) {

        $controllerName = $request->getControllerName();

        if (preg_match('/^admin_/', $controllerName)) {

            if (!\Zend_Auth::getInstance()->hasIdentity() && $controllerName != 'admin_session') {

                $flashMessenger = \Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                $flashMessenger->addMessage('You must login to grant access to dashboard', 'errors');

                $redirector = \Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
                $redirector->setExit(true)
                         ->gotoRoute([
                            'controller' => 'admin_session',
                            'action' => 'login'
                         ], 'default', true);
            }
        }
    }
}