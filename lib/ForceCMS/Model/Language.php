<?php

namespace ForceCMS\Model;

/**
 * @package     ForceCMS
 * @subpackage  Model
 * @category    Translation
 * @copyright   Copyright (c) 20012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
class Language extends \Zend_Db_Table_Abstract
{
    // table name
    protected $_name = 'language';

    const STATUS_VISIBLE = 1;
    
    /**
     * @param int $languageId
     * @return null|object with keys as language table columns or NULL if not found
     */
    public function getLanguageById($languageId) {
        $select = $this->select();
        $select->where('id = ?', $languageId);

        $row = $this->fetchRow($select);

        if ($row instanceof \Zend_Db_Table_Row) {
            return $row;
        } else {
            return;
        }
    }

    /**
     * @return object
     */
    public function getFirst() {
        $mdl = new self();
        $select = $mdl->select();
        
        $select->where('status = (?)', self::STATUS_VISIBLE);
        $select->order('priority ASC');

        return $mdl->fetchRow($select);
    }

    public function getAll() {
        $mdl = new self();
        $select = $mdl->select();
        
        $select->where('status = (?)', self::STATUS_VISIBLE);
        
        $select->order('priority ASC');

        return $mdl->fetchAll($select);
    }
}
