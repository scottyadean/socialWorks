<?php

class Default_UserController extends Zend_Controller_Action {

    
    public function init() {
     

    }

    public function indexAction() {
       
       $this->_helper->layout->disableLayout();
            
       $id = $this->getRequest()->getParam('id', null);
       $usr = new Default_Model_User;
       $this->view->user = $usr->findById($id);
    }


    public function accountAction() {  
      $id = Zend_Auth::getInstance()->getIdentity()->id;
      $usr = new Default_Model_User;
      $this->view->user = $usr->findById($id)->toArray(); 
    }
    
}

