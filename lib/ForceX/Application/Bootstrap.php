<?php

namespace ForceX\Application;

/**
 * {@inheritdoc}
 */
class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Adds ForceX\Application library to plugin prefix path
     *
     * @param File $application
     */
    public function __construct($application)
    {
        $this->getPluginLoader()->addPrefixPath(
            'ForceX\\Application\\Resource\\',
            'ForceX/Application/Resource'
        );
        parent::__construct($application);
    }
}