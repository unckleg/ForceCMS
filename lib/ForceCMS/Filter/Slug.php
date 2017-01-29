<?php

namespace ForceCMS\Filter;

/**
 * Slug filter
 *
 * @package     ForceCMS
 * @subpackage  Filter
 * @category    Filter
 * @copyright   Copyright (c) 2012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class Slug implements \Zend_Filter_Interface
{
    /**
    * Creates a URL friendly slug (NOT UNIQUE)
    *
    * @param string $str
    * @return string
    */
    public function filter($str)
    {
       $str = strtolower(trim($str));
       $str = preg_replace('/[^a-z0-9-]/', '-', $str);
       $str = preg_replace('/-+/', "-", $str);
       $str = \preg_replace('/-$/', '', $str);
       return $str;
    }
}