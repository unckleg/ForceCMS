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
class TwitterUsername implements \Zend_Filter_Interface
{
    /**
     * {@inheritdoc}
     *
     * @param  mixed $value
     * @throws Zend_Filter_Exception If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        if ($value != '') {
            $value = ltrim($value, '@');
        }

        return $value;
    }
}