<?php

class Default_IndexController extends Zend_Controller_Action
{

    public $request;
    
    protected $_auth;
    protected $_user;

    public function init() {
        
       $this->request = $this->getRequest();
       $this->_auth = Zend_Auth::getInstance();
       $this->_user =  $this->_auth->getIdentity();
    }

    public function indexAction() {
        $consumer = new Consumer_Model_ConsumersUsers;  
        $this->view->consumers = $consumer->getByUserId($this->_user->id);
        $this->view->messages = Default_Model_Temp::selectAllByAccountId($this->_user->account_id);
        
    }

    

    
}

