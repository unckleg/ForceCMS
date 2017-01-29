<?php

namespace ForceCMS\Plugins;

use ForceCMS\Filter\Link;
use ForceCMS\Model\Language;

/**
 *
 * @package     ForceCMS
 * @subpackage  Plugins
 * @category    Routing
 * @copyright   Copyright (c) 2012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class Router extends \Zend_Controller_Plugin_Abstract {

    public function __construct($fc) {
        
        $link = new Link();

        // models
        $modelLanguage = new Language();
        
        $ctrl = $fc->getRouter();
        
        // set route for language homepage
        $languages = $modelLanguage->getAll();
        if (count($languages) > 0) {
            foreach ($languages as $language) {
                $ctrl->addRoute("homepage_" . $language->id, new \Zend_Controller_Router_Route("/" . $language->short, [
                        "controller" => "index",
                        "action" => "index",
                        "language" => $language->id
                    ]
                ));
            }
        }
    }
}
