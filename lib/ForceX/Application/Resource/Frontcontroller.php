<?php

namespace ForceX\Application\Resource;

use ForceX\Controller\Dispatcher\Standard;


/**
 * {@inheritdoc}
 */
class Frontcontroller extends \Zend_Application_Resource_Frontcontroller
{

    protected static $_dispatcher;

    protected $_moduleControllerDirectoryName = 'Controller';

    /**
     * {@inheritdoc}
     *
     * Overridden to use new front controller
     *
     * @return \ForceX\Controller\Front
     */
    public function getFrontController()
    {
        if (null === self::$_dispatcher) {
            self::$_dispatcher = new Standard();
        }
        parent::getFrontController()
            ->setDispatcher(self::$_dispatcher)
            ->setModuleControllerDirectoryName($this->_moduleControllerDirectoryName);
        if (!parent::getFrontController()->getParam('noViewRenderer') && !\Zend_Controller_Action_HelperBroker::hasHelper('viewRenderer')) {
            \Zend_Controller_Action_HelperBroker::getStack()->offsetSet(-80, new \ForceX\Controller\Action\Helper\ViewRenderer());
        }
        return $this->_front;
    }
}