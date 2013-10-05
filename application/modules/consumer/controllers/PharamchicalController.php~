<?php

class Consumer_PharamchicalController extends Zend_Controller_Action {    
   
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $format;
   public $callback;
   
   
   protected $_model;
   
   public function init() {
    
    
        $this->_model = new Default_Model_Pharamchical;
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->sort = $this->getRequest()->getParam('sort', false);
        $this->post = $this->getRequest()->isPost();
        $this->format = $this->getRequest()->getParam('format', false);
        $this->callback = $this->getRequest()->getParam('callback', null);
        
    }
   
    public function indexAction() {
        
          $this->view->pharamchicals = $this->_model->indexPharamchical();
    }
    
    
    public function createAction() {
    
        $form = new Application_Form_Pharamchical;
        $form->build($this->uri);
        
        if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
    
            if( $lastid = $this->_model->createPharamchical($form->getValues())){
                
                $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Pharamchical added.") );  
                $this->_helper->redirector->gotoUrl($this->uri);
                   
            }
        
        }
        
        $this->view->form = $form;
    }   
    
    public function readAction() {
   
        if($this->xhr) {
        
            $this->_helper->layout->disableLayout();
        
        }  
   
        $this->view->pharamchical = $this->_model->readPharamchical($this->id)->toArray();
    
       
    
    }
    
    
    public function updateAction(){
    
        $form = new Application_Form_Pharamchical;
        $form->build($this->uri, $this->id);
        $pharamchicalData = $this->_model->readPharamchical($this->id)->toArray();
        $form->populate($pharamchicalData );
        
        if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
    
            if( $lastid = $this->_model->updatePharamchical($this->id, $form->getValues())){
                
                $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Pharamchical info updated.") );  
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
        $pid = $this->getRequest()->getParam('pharamchical', null);
        $do  = $this->getRequest()->getParam('do', null);
         
        if( $this->getRequest()->isPost() && !is_null($pid) && !is_null($do) ){
            
            $this->_helper->viewRenderer->setNoRender(true);
            
            $consumePharamchicals = new Consumer_Model_ConsumersPharamchicals;

            if( $do == 'remove' ) {
                $res = $consumePharamchicals->remove($id, $pid );
            }

            if( $do == 'assign' ) {
                $res = $consumePharamchicals->assign($id, $pid );
            }

            print json_encode( array('success'=>(bool)$res, 'do'=>$do, 'focus'=>$pid, 'consumer'=>$id) );

            return;
        }

    
        if( !is_null($id) ){
        
            $this->view->id = $id;
            $c = new Consumer_Model_Consumer;
            $consumerInfo = $c->findById($id);
            $this->view->assigned =  $c->getConsumerPharamchicals();
            
            $pharamchicals = new Default_Model_Pharamchical;
            $this->view->pharamchicals = $pharamchicals->indexPharamchical()->toArray();
            
            
            $this->view->assignedIds = array();
            foreach( $this->view->assigned as $assigned ){
                $this->view->assignedIds[] = $assigned['id'];
            }
            
           
            
        }
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
   
  
}  
