<?php

use ForceCMS_Model_ORM as ORM,
    Model_Admin_Multimedia_Multimedia as Multimedia;

class Admin_MultimediaController extends ForceCMS_Controller_Abstract
{
    private $_widhtXL = 1060;
    private $_heightXL = 1060;

    private $_widhtL = 748;
    private $_heightL = 748;

    private $_widhtS = 100;
    private $_heightS = 100;

    protected $_redirector;
    protected $_flashMessenger;
    protected $_systemMessages;

    public function init()
    {
        $this->_redirector = $this->getHelper('Redirector');
        $this->_flashMessenger = $this->getHelper('FlashMessenger');
        $this->_systemMessages = [
            'success' => $this->_flashMessenger->getMessages('success'),
            'errors' => $this->_flashMessenger->getMessages('errors')
        ];

        $this->view->systemMessages = $this->_systemMessages;
    }

    /**
     * @param Model_Admin_Multimedia_Multimedia $modelMultimedia
     */
    public function indexAction(Multimedia $modelMultimedia)
    {
        // All albums fetching
        $allAlbums = ORM::Mapper_Search($modelMultimedia, [
            'orders' => [
                'album_order' => ORM::COLUMN_ASC
            ]
        ]);

        $this->view->albums = $allAlbums;
    }

    public function createAction()
    {

    }
}