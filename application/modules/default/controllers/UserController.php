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
 
    public function editAction() {
        $id = Zend_Auth::getInstance()->getIdentity()->id;
        $pid = $this->getRequest()->getParam('id', 0);
        
        $isPost = $this->getRequest()->isPost();
        
        if($isPost) {
            
           if($id != $pid) {
            
             $this->_redirect("/my/account");
            return;
           } 
            
            
        }
        
        $usr = new Default_Model_User;
        $user = $usr->findById($id)->toArray(); 
        
        $form = new Application_Form_User;
        $form->build('/account/edit/', $id);
        $form->populate($user);
        
        
        Main_Forms_Handler::onPost($form,
                                    $isPost,
                                    $usr,
                                    "updateUser",
                                    $this->getRequest()->getParams(),
                                    $this->_helper,
                                    '/my/account',
                                    "Account Update successful.",
                                    false);
        
        $this->view->form = $form;
        
    }
    
}

