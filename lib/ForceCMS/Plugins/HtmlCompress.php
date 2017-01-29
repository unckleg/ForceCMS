<?php

namespace ForceCMS\Plugins;

/**
 *
 * @package     ForceCMS
 * @subpackage  Plugins
 * @category    Flexibility
 * @copyright   Copyright (c) 2012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class HtmlCompress extends \Zend_Controller_Plugin_Abstract
{
    /*public function dispatchLoopShutdown()
    {
        $response = Zend_Controller_Front::getInstance()->getResponse();
        $responseBodyHtml = implode('', $response->getBody(true));

        $htmlCompact = new ForceCMS_Collections_Markup_HtmlCompact();
        $compressedHtml = $htmlCompact->htmlCompact($responseBodyHtml);

        $response->setBody($compressedHtml);
    }*/
}