<?php

namespace ForceCMS\Filter;

/**
 * Url filter
 *
 * @package     ForceCMS
 * @subpackage  Filter
 * @category    Filter
 * @copyright   Copyright (c) 2012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class Url implements \Zend_Filter_Interface
{
    public function filter($value)
    {
        $filteredValue = $value;

        $filteredValue = \preg_replace('/^\/+/', '', $filteredValue);
        $filteredValue = \preg_replace('/\/{2,}/', '/', $filteredValue);

        return $filteredValue;
    }
}