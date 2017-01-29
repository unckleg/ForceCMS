<?php

namespace Core\Model\Admin\Multimedia;

class Multimedia extends \Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'cms_multimedia';

    // soft delete constants
    const IS_DELETED = 1;
    const IS_ACTIVE = 0;

    const STATUS_VISIBLE = 1;
    const STATUS_HIDDEN = 0;

    public function updateOrder($sortedIds) {
        $select = $this->select();

        $rows = $this->fetchAll($select);
        if(!empty($rows)) {
            foreach ($rows as $key => $row){
                foreach ($sortedIds as $skey => $svalue) {
                    if($row->id == $svalue){
                        $row->album_order = $skey + 1;
                        $row->save();
                    }
                }

            }
        }
    }
}