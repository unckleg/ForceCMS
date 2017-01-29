<?php

namespace ForceCMS\Plugins;
use ForceCMS\Vendors\Gajus\Dindent\Indenter;

/**
 *
 * @package     ForceCMS
 * @subpackage  Plugins
 * @category    Readabilty
 * @copyright   Copyright (c) 2012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class HtmlBeautifier extends \Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopShutdown()
    {
        $response = \Zend_Controller_Front::getInstance()->getResponse();
        $responseBodyHtml = implode('', $response->getBody(true));

        $beautifier     = new Indenter();
        $beautifiedHtml = $beautifier->indent($responseBodyHtml);

        $response->setBody($beautifiedHtml);
    }
}