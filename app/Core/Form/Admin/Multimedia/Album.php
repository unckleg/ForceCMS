<?php

namespace Core\Form\Admin\Multimedia;


class Album extends \Zend_Form
{

    public function __construct($options = null)
    {

        parent::__construct($options);
    }

    public function init()
    {
        $this->setMethod(\Zend_Form::METHOD_POST);
        $this->setAction('');
        $this->setEnctype(\Zend_Form::ENCTYPE_MULTIPART);

        $validatorRequired = new \Zend_Validate_NotEmpty();

        $albumTitle = new \Zend_Form_Element_Text('album_name');
        $albumTitle
            ->addFilter('StringTrim')
            ->addValidator('StringLength', false, ['min' => 3, 'max' => 255])
            ->setAttribs(['class' => 'form-control', 'placeholder' => 'Unesite naziv albuma', 'required' => ''])
            ->setRequired(true);
        $this->addElement($albumTitle);

        $albumUrl = new \Zend_Form_Element_Text('url_name');
        $albumUrl
            ->addFilter('StringTrim')
            ->setAttribs(['class' => 'form-control', 'placeholder' => 'http://facebook.com/galerija-neka/'])
            ->setRequired(false);
        $this->addElement($albumUrl);


        $keywords = \Zend_Registry::get('config')->keywords;
        $albumPhoto = new \Zend_Form_Element_File('album_photo');
        $albumPhoto->addValidator('Count', true, 1)
            ->addValidator('MimeType', true, ['image/jpeg', 'image/gif', 'image/png'])
            ->addValidator('ImageSize', false, [
                'minwidth' => 150,
                'minheight' => 150,
                'maxwidth' => 4000,
                'maxheight' => 4000,
            ])
            ->addValidator('FilesSize', false, ['max' => '10MB'])
            ->setValueDisabled(true)
            ->setRequired(false);
        $albumPhoto->getTransferAdapter()->setOptions(['useByteString' => false]);
        $albumPhoto->addFilter('Rename', ['target' => APP_PUBLIC . '/uploads/albums/'.date("Y-m-d-H-i-s"). '-' . $keywords . '.jpg', 'overwrite' => true]);
        $this->addElement($albumPhoto);

        $this->setElementDecorators([['ViewHelper'], ['Errors']])
            ->setDecorators([['ViewScript', [
                'viewScript' => 'admin/multimedia/form/_album.phtml',
            ]],
        ]);
    }
}