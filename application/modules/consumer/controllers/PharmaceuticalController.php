<?php

class Consumer_PharmaceuticalController extends Zend_Controller_Action {    
   
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $format;
   public $callback;
   
   
   protected $_model;
   
   public function init() {
    
        $this->_model = new Default_Model_Pharmaceutical;
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->sort = $this->getRequest()->getParam('sort', false);
        $this->post = $this->getRequest()->isPost();
        $this->format = $this->getRequest()->getParam('format', false);
        $this->callback = $this->getRequest()->getParam('callback', null);
        
    }
   
    public function indexAction() {
        
          $this->view->pharmaceuticals = $this->_model->indexPharmaceutical();
    }
    
    
    public function createAction() {
    
        $form = new Application_Form_Pharmaceutical;
        $form->build($this->uri);
        
        if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
    
            if( $lastid = $this->_model->createPharmaceutical($form->getValues())){
                
                $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Pharmaceutical added.") );  
                $this->_helper->redirector->gotoUrl($this->uri);
                   
            }
        
        }
        
        $this->view->form = $form;
    }   
    
    public function readAction() {
   
        if($this->xhr) {
        
            $this->_helper->layout->disableLayout();
        
        }  
   
        $this->view->pharmaceutical = $this->_model->readPharmaceutical($this->id)->toArray();
    
       
    
    }
    
    
    public function updateAction(){
    
        $form = new Application_Form_Pharmaceutical;
        $form->build($this->uri, $this->id);
        $pharmaceuticalData = $this->_model->readPharmaceutical($this->id)->toArray();
        $form->populate($pharmaceuticalData);
        
        if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
    
            if( $lastid = $this->_model->updatePharmaceutical($this->id, $form->getValues())){
                
                $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Pharmaceutical info updated.") );  
                $this->_helper->redirector->gotoUrl($this->uri);
                   
            }
        
        }
        
        $this->view->form = $form;
    
    }
    
    
    
    
    public function deleteAction() {
    
    
    }
    
    
    public function manufacturersAction() {
        
            
      $this->_helper->layout->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);
      $data = json_encode( Main_Forms_DrugManufacturers::names(strtoupper($this->sort)) );
      $this->getResponse()->setHeader('Content-Type', 'application/json')->appendBody($data);

    }
    
 
    
    public function assignAction() {
        
        $this->_helper->layout->disableLayout();
        $id = $this->getRequest()->getParam('id', null);
        $pid = $this->getRequest()->getParam('pharmaceutical', null);
        $do  = $this->getRequest()->getParam('do', null);
         
        if( $this->getRequest()->isPost() && !is_null($pid) && !is_null($do) ){
            
            $this->_helper->viewRenderer->setNoRender(true);
            
            $consumePharmaceuticals = new Consumer_Model_ConsumersPharmaceuticals;

            if( $do == 'remove' ) {
                $res = $consumePharmaceuticals->remove($id, $pid );
            }

            if( $do == 'assign' ) {
                $res = $consumePharmaceuticals->assign($id, $pid );
            }

            print json_encode( array('success'=>(bool)$res, 'do'=>$do, 'focus'=>$pid, 'consumer'=>$id) );

            return;
        }

    
        if( !is_null($id) ){
        
            $this->view->id = $id;
            $c = new Consumer_Model_Consumer;
            $consumerInfo = $c->findById($id);
            $this->view->assigned =  $c->getConsumerPharmaceuticals();
           
            $pharmaceuticals = new Default_Model_Pharmaceutical;
            $this->view->pharmaceuticals = $pharmaceuticals->indexPharmaceutical()->toArray();
            
            
            $this->view->assignedIds = array();
            
            foreach( $this->view->assigned as $assigned ) {
                $this->view->assignedIds[] = $assigned['id'];
            }
            
        }
        
        
    }
    
    
   protected function _asJson(array $data) {
        
        $this->_helper->viewRenderer->setNoRender(true);        
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }
    
    
    
    
    
    
    
    
    
   
  
}  
