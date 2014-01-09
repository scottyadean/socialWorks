<?php

class Tools_IndexController extends Zend_Controller_Action {
    
    public $xhr;
    
    protected $_user;
    
    public function init() {   
         $this->_user = Zend_Auth::getInstance()->getIdentity();
         $this->xhr = $this->getRequest()->isXmlHttpRequest();
    }
    
    public function indexAction() {
    }
    
    public function milesabAction() {        
        $this->_helper->layout->setLayout('simple');
    }
    
    public function chatAction() {
        $this->view->messages = Default_Model_Temp::selectAllByAccountId($this->_user->account_id);
        
    }
    
    public function calendarAction() {
        
        if($this->xhr){
            $this->_helper->layout->disableLayout();
        }
        
        $this->view->year  = $this->getRequest()->getParam('year', date("Y"));
        $this->view->month = $this->getRequest()->getParam('month', date("n"));
        $this->view->day   = $this->getRequest()->getParam('day', date("d")); 
    }
    
    

}