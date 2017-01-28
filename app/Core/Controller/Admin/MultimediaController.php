<?php

namespace Core\Controller\Admin;

use ForceCMS\Model\ORM as ORM,
    ForceCMS\Controller\ControllerAbstract,
    Core\Model\Admin\Multimedia\Multimedia as Multimedia;

class MultimediaController extends ControllerAbstract
{
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