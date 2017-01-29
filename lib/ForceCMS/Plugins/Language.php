<?php

namespace ForceCMS\Plugins;

/**
 *
 * @package     ForceCMS
 * @subpackage  Plugins
 * @category    Translations
 * @copyright   Copyright (c) 2012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class Language extends \Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(\Zend_Controller_Request_Abstract $request)
    {
        $modelLanguage = new \ForceCMS\Model\Language();
        $languageId = $request->getParam('language');

        if (isset($languageId) && $languageId != ""  && $languageId != NULL) {
            $language = $modelLanguage->find($languageId)->current();
        } else {
            $language = $modelLanguage->getFirst(1);
        }

        \Zend_Registry::set('language', $language);
    }
}