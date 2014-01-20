<?php

class Default_IndexController extends Zend_Controller_Action
{

    public $id;
    public $xhr;
    public $uri;
    public $post;
    public $request;
    public $callback;
     
    protected $_auth;
    protected $_user;

    
    
   
   public function init() {
    
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->post = $this->getRequest()->isPost();
        $this->request = $this->getRequest();

        $this->_auth = Zend_Auth::getInstance();
        $this->_user =  $this->_auth->getIdentity();
    }

    public function indexAction() {
        $consumer = new Consumer_Model_ConsumersUsers;  
        $this->view->consumers = $consumer->getByUserId($this->_user->id);
        $this->view->messages = Default_Model_Temp::selectAllByAccountId($this->_user->account_id);        
    }

    
    
    public function appointmentsAction(){
        
        $consumer = new Consumer_Model_ConsumersUsers; 
        $consumers = $consumer->getByUserId($this->_user->id);
        $medAppt = new Consumer_Model_ConsumersMedical;
        
        $query = array();
        
        foreach($consumers as $c) {
            $query[] = $c['id'];
            
        }
        
        $result = $medAppt->withInAWeek($query)->toArray();
        $this->_asJson($result);
        
    }
    
    
    
     protected function _asJson(array $data) {
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }
    

    
}

