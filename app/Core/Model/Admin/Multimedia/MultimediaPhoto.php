<?php

namespace Core\Model\Admin\Multimedia;

class MultimediaPhoto extends \Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_multimedia_photo';

    // soft delete constants read about it on link bellow
    // http://www.pcmag.com/encyclopedia/term/57355/soft-delete
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;

}