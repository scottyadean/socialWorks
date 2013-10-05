<?php

class Default_IndexController extends Zend_Controller_Action
{

    public $request;

    public function init() {
        /* Initialize action controller here */
       $this->request = $this->getRequest();

    }

    public function indexAction() {
        // action body
        //$this->_helper->layout->setLayout('plain');
 

        
    }



    
}

