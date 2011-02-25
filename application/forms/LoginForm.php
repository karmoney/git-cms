<?php

class Default_Form_LoginForm extends Zend_Form
{
    public function init()
    {
        $this->setAction('/auth/login/');
        
        $username = $this->createElement('text','username');
        $username->setLabel('Username: *')
                ->setRequired(true);
                
        $password = $this->createElement('password','password');
        $password->setLabel('Password: *')
                ->setRequired(true);
                
        $signin = $this->createElement('submit','submit');
        $signin->setLabel('submit');
        $this->addElements(array(
                        $username,
                        $password,
                        $signin,
        ));
    }
}