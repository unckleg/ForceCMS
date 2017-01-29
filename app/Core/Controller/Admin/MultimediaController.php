<?php

namespace Core\Controller\Admin;

use ForceCMS\Collections\Image\ImageResize;
use ForceCMS\Model\ORM as ORM,
    ForceCMS\Controller\ControllerAbstract,
    Core\Model\Admin\Multimedia\Multimedia as Multimedia;

class MultimediaController extends ControllerAbstract
{
    protected $_s_width = 200;
    protected $_s_height = 100;

    protected $_l_width = 768;
    protected $_l_height = 768;

    protected $_xl_width = 1400;
    protected $_xl_height = 1400;

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

    /**
     * @param array $sortedIds
     */
    public function orderAction($sortedIds, Multimedia $modelMultimedia)
    {
        $this->_helper->viewRenderer->setNoRender(true);

        if (is_array($sortedIds)) {
            $modelMultimedia->updateOrder($sortedIds);
        }
    }

    public function uploadAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);

        $destinationPath   = '/uploads/galleries/album/';
        $destinationFolder = APP_PUBLIC . $destinationPath;

        if (!file_exists( $destinationFolder )) {
            mkdir( $destinationFolder, 0777, true );
        }

        $upload = new \Zend_File_Transfer_Adapter_Http();
        $upload->setDestination($destinationFolder);
        $upload->addValidator('Count', true, 1)
               ->addValidator('MimeType', true, ['image/jpeg', 'image/gif', 'image/png'])
               ->addValidator('ImageSize', false, [
                    'minwidth'  => 150,
                    'minheight' => 150,
                    'maxwidth'  => 4000,
                    'maxheight' => 4000,
                ])
                ->addValidator('FilesSize', false, [
                    'max' => '5MB'
                ])
               ->addFilter('Rename', [
                   'target'    => APP_PUBLIC . $destinationPath .'ForceCMS'. '-' .date("Y-m-d-H-i-s"). '.jpg',
                   'overwrite' => true
               ]);


        if( !$upload->isValid()) {
            $messages = $upload->getMessages();

            $this->_helper->json([
                'jsonrpc' => '2.0',
                'error'   => $messages,
                'result'  => 'error',
                'file'    => $upload->getFileName()
            ]);
        }


        if($upload->isUploaded()) {

            $upload->receive();

            $name = $upload->getFileName();

            $fileExtension = pathinfo($name, PATHINFO_EXTENSION);

            $image = basename($name);

            $resizeImage1 = new ImageResize($destinationFolder . $image);
            $resizeImage1->resizeTo($this->_l_width, $this->_l_height, 'maxwidth');
            $resizeImage1->saveImage(
                $destinationFolder .
                substr($image, 0, -(strlen($fileExtension)+1)) . "-l." .
                $fileExtension
            );

            $resizeImage2 = new ImageResize($destinationFolder . $image);
            $resizeImage2->resizeTo($this->_s_width, $this->_s_height, 'maxwidth');
            $resizeImage2->saveImage(
                $destinationFolder .
                substr($image, 0, -(strlen($fileExtension)+1)) . "-s." .
                $fileExtension
            );

            $resizeImage3 = new ImageResize($destinationFolder . $image);
            $resizeImage3->resizeTo($this->_xl_width, $this->_xl_height, 'maxwidth');
            $resizeImage3->saveImage(
                $destinationFolder .
                substr($image, 0, -(strlen($fileExtension)+1)) . "-xl." .
                $fileExtension
            );

        }

    }
}