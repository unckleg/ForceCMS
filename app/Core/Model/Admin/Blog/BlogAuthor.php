<?php


class Model_Admin_Blog_BlogAuthor extends \Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_blog_author';

    // soft delete constants read about it on link bellow
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;

}