<?php

namespace Core\Form\Admin\Blog;

class Tag extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAction('');

        $categoryName = new Zend_Form_Element_Text('title');
        $categoryName->addFilter('StringTrim')
            ->addValidator('StringLength', false, ['min' => 2, 'max' => 255])
            ->setAttribs(['class' => 'form-control', 'placeholder' => 'Unseite ime oznake', 'required' => ''])
            ->setRequired(false);
        $this->addElement($categoryName);

        $this->setElementDecorators([['ViewHelper'], ['Errors']])
            ->setDecorators([['ViewScript', [
                    'viewScript' => 'admin/blog/form/_tag.phtml',
            ]],
            ]);
    }
}