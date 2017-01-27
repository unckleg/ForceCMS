<?php

namespace Core\Form\Admin\Blog;

use Model_Admin_Blog_BlogPost as BlogPost,
    Model_Admin_Blog_BlogCategory as BlogCategory;

class Post extends Zend_Form
{
    protected $_task;
    protected $_photo;

    public function __construct($task = null, $photo = null, $options = null)
    {
        isset($task) ? $this->_task = $task : $this->_task = 'create';
        isset($image) ? $this->_photo = $photo : '';

        parent::__construct($options);
    }

    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAction('');
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);

        $validatorRequired = new Zend_Validate_NotEmpty();

        $postTitle = new Zend_Form_Element_Text('title');
        $postTitle->addFilter('StringTrim')
          ->addValidator('StringLength', false, ['min' => 3, 'max' => 500])
          ->addValidator($validatorRequired)
          ->setAttribs([
              'class' => 'form-control input-lg',
              'placeholder' => 'Unesite naslov za ovu objavu'])
          ->setRequired(true);
        $this->addElement($postTitle);

        $postText = new Zend_Form_Element_Textarea('text');
        $postText->addValidator('StringLength')
             ->setAttribs(['class' => 'form-control', 'id' => 'editor'])
             ->setRequired(false);
        $this->addElement($postText);

        $postStatus = new Zend_Form_Element_Select('status');
        $postStatus->addMultiOptions([
            BlogPost::STATUS_VISIBLE => 'Objavi',
            BlogPost::STATUS_ONHOLD => 'Na čekanju',
            BlogPost::STATUS_FOR_MODERATOR => 'Na odobrenje'
        ])->setAttribs(['class' => 'form-control']);
        $this->addElement($postStatus);

        $postPublishingDate = new Zend_Form_Element_Text('date_published');
        $postPublishingDate->addFilter('StringTrim')
           ->addValidator($validatorRequired)
           ->setAttribs([
             'class' => 'form-control mypicker',
             'size' => 16,
             'readonly' => '',
           ]);
        $this->addElement($postPublishingDate);

        $postCategories = new Zend_Form_Element_Multiselect('categories');
        $postCategories->addFilter('StringTrim')
           ->addValidator($validatorRequired)
           ->setAttribs([
               'id' => 'multiple',
               'class' => 'form-control select2-multiple',
               'multiple',
               'placeholder' => 'Izaberi kategoriju'
           ])
           ->removeDecorator(true);

        // categories fetching and form injecting
        $modelBlogCategories = new BlogCategory();
        $categories = $modelBlogCategories->getAll();
        foreach ($categories as $category) {
            $postCategories->addMultiOption($category->id, $category->name);
        }
        $this->addElement($postCategories);

        $postTags = new Zend_Form_Element_Text('tags');
        $postTags->addFilter('StringTrim')->setAttribs([
            'class' => 'form-control input-large',
            'data-role' => 'tagsinput',
            'style' => 'width: 100%',
            'placeholder' => 'oznaka1, oznaka2, oznaka3'
        ]);
        $this->addElement($postTags);

        $keywords = Zend_Registry::get('config')->keywords;
        $postPhoto = new Zend_Form_Element_File('featured_image');
        $postPhoto->addValidator('Count', true, 1)
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
        $postPhoto->getTransferAdapter()->setOptions(['useByteString' => false]);
        $postPhoto->addFilter('Rename', ['target' => APP_PUBLIC . '/uploads/posts/'.date("Y-m-d-H-i-s") . '-' . $keywords . '.jpg', 'overwrite' => true]);
        $this->addElement($postPhoto);

        $postCommentsStatus = new Zend_Form_Element_Select('comments_enabled');
        $postCommentsStatus->addFilter('StringTrim')
           ->addValidator($validatorRequired)
           ->addMultiOptions([
               BlogPost::COMMENT_ENABLED => 'Dozvoljeno komentarisanje',
               BlogPost::COMMENT_DISABLED => 'Zabrani komentarisanje'
           ]);
        $postCommentsStatus->setAttribs(['class' => 'form-control']);
        $this->addElement($postCommentsStatus);

        $this->setElementDecorators([['ViewHelper'], ['Errors']])
            ->setDecorators([['ViewScript', [
                'viewScript' => 'admin/blog/form/_post.phtml',
                'task' => $this->_task,
                'photo' => $this->_photo
            ]],
            ]);
    }
}