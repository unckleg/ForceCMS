<?php

/**
 * Bootstraping database and starting app.
 *
 * @package     ForceCMS
 * @subpackage  Application
 * @category    Starter|Bootstrap
 * @copyright   Copyright (c) 20012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class Start extends \ForceX\Application\Bootstrap
{
    protected function _initDatabases() {
        $this->bootstrap('db')->getResource('db');
    }
}