<?php

class Application_Form_PasswordReset extends Zend_Form
{

    public function init()
    {
       $this->setName("passwordreset");
       $this->setMethod('post');
       $this->setAction('/reset/password');

       $this->addElement('text', 'password', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
            'label'      => 'New Password:'
        ));
       $this->addElement('text', 'passwordverify', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
            'label'      => 'New Password Verify'
        ));

        $this->addElement('submit', 'update_password', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Update Password'));      
    

    }


}

