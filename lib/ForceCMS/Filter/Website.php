<?php

namespace ForceCMS\Filter;

/**
 * Website filter
 *
 * @package     ForceCMS
 * @subpackage  Filter
 * @category    Filter
 * @copyright   Copyright (c) 2012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class Website implements \Zend_Filter_Interface
{
    /**
     * Implements Zend_Filter_Interface::filter()
     *
     * Return the provided url with http prepended if not already done so
     *
     * @param  string $value Value to filter
     * @return boolean
     */
    public function filter($value)
    {
        $value = (string)$value;

        if (!strlen($value)) {
            return $value;
        }

        if (substr($value, 0, 7) != "http://"
            && substr($value, 0, 8) != "https://") {
            $value = "http://" . $value;
        }

        return $value;
    }
}
