<?php

namespace Core\Form;

class Login extends \Zend_Form
{
   public function init() {
        $username = new \Zend_Form_Element_Text('username');
        $username->addFilter('StringTrim')
                 ->addFilter('StringToLower')
                 ->setRequired(true);
        $this->addElement($username);

        $password = new \Zend_Form_Element_Password('password');
        $password->setRequired(true);
        $this->addElement($password);
    }
}