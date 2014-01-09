<?php

class Application_Form_Login extends Zend_Form
{

    public function build($reloc = '/') {

       $this->setName("login");
       $this->setMethod('post');
       $this->setAction('/login');
       $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
            'label'      => 'Username:',
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
            'label'      => 'Password:',
        ));
        
        $this->addElement('hidden', 'reloc', array(
            'value'      => $reloc,
            'required'   => false,
            'label'      => '',
            'ignore'     => true
        ));
        
        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'class'    => "btn btn-small btn-primary",
            'label'    => 'Login',
        ));  
        
        
              
    }

}
