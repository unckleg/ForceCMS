<?php

namespace ForceX;

require_once('Zend/Application.php');

/**
 * {@inheritdoc}
 */
class Application extends \Zend_Application
{
    /**
     * {@inheritdoc}
     *
     * Overridden to add ForceX namespace to autoloader
     *
     * @param string $environment
     * @param array $options
     */
    public function __construct($environment, $options = null)
    {
        require_once 'Zend/Loader/Autoloader.php';
        \Zend_Loader_Autoloader::getInstance()->registerNamespace('ForceX');

        parent::__construct($environment, $options);
    }

    /**
     * Get bootstrap object
     *
     * @return Zend_Application_Bootstrap_BootstrapAbstract
     */
    public function getBootstrap()
    {
        if (null === $this->_bootstrap) {
            $this->_bootstrap = new \ForceX\Application\Bootstrap($this);
        }
        return $this->_bootstrap;
    }
}