<?php

class Application_Form_Password extends Zend_Form
{


    public function init() {
    
       $this->setName("passwordreset");
       $this->setMethod('post');
       $this->setAction('/password');
       $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
            'label'      => 'Username:'
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Reset'));      
    

    }


}

