<?php

namespace ForceCMS\Plugins;

/**
 *
 * @package     ForceCMS
 * @subpackage  Plugins
 * @category    Flexibility
 * @copyright   Copyright (c) 2012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class HtmlMinifier extends \Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopShutdown()
    {
//        $response = \Zend_Controller_Front::getInstance()->getResponse();
//        $responseBodyHtml = implode('', $response->getBody(true));
//
//
//        $response->setBody($minifiedContents);
    }
}