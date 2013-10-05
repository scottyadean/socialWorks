<?php

class Consumer_PhysicianController  extends Zend_Controller_Action {    
   
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $format;
   public $callback;
   
   public function init() {
    
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->sort = $this->getRequest()->getParam('sort', false);
        $this->post = $this->getRequest()->isPost();
        $this->format = $this->getRequest()->getParam('format', false);
        $this->callback = $this->getRequest()->getParam('callback', null);
        
        
    }
   
    /**
    * List a all physicians
    * @param $id
    * @return<array, json>
    */
   public function indexAction(){
    
    $physicians =  new  Default_Model_Physician;
    
    $form = new Application_Form_Physician;
    $form->build();
    
    if($this->xhr) {
            $this->_helper->layout->disableLayout();
    }
     
    if( $this->post ) {
    
        if( $form->isValid($this->getRequest()->getPost())  ){
    
        if( $lastid = $physicians->create($form->getValues())){
            
            if($this->xhr) {
            
                $this->_helper->viewRenderer->setNoRender(true);    
                print json_encode( array('success'=>true, 'id'=>$lastid, 'message'=>'New Physician added.', 'errors'=>array()) );
                return;
            
            }else{
                
                
                $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Physician added.") );  
                $this->_helper->redirector->gotoUrl($this->uri);
                
            }
               
        }
    
        }else{
            
            if($this->xhr){
                $this->_helper->viewRenderer->setNoRender(true);
                print json_encode( array('success'=>false, 'message'=>'Errors Processing Form', 'errors'=>$form->getErrors()) );
                return;
            }else{
                $this->_helper->flashMessenger->addMessage(array('alert alert-error'=>"Errors Processing Form.") ); 
            }
            
            
        }
    
    }
   
     
     
    
    $this->view->physicians = $physicians->all();
    $this->view->form = $form;
    
 
   }
    
   /**
    * List a single physician
    * @param $id
    * @return<array, json>
    */
   public function viewAction(){
    
        $id = $this->getRequest()->getParam('id', null);
        $this->view->render =  $this->getRequest()->getParam('render', 'table');
        $this->view->extend = $this->getRequest()->getParam('extend', false);
        
        if($this->xhr) {
            $this->_helper->layout->disableLayout();
        }
         
        $physician =  new  Default_Model_Physician;
        $this->view->physician = $physician->findById($id); 
    
   }
   
   
   
   public function editAction() {
    
    $id = $this->getRequest()->getParam('id', null);
    
    $physician =  new  Default_Model_Physician;
    $physicianData = $physician->findById($id);
    
    $form = new Application_Form_Physician;
    $form->build('/physician/edit/'.$id, $id);
    $form->populate( $physicianData->toArray() );
    
    if($this->xhr) {
            $this->_helper->layout->disableLayout();
    }
     
    if( $this->post ) {
    
        if( $form->isValid($this->getRequest()->getPost())  ){
    
        if( $lastid = $physician->updatePhysician($id, $form->getValues())){
            
            if($this->xhr) {
            
                $this->_helper->viewRenderer->setNoRender(true);    
                print json_encode( array('success'=>true, 'id'=>$lastid, 'message'=>'Physician updated.', 'errors'=>array()) );
                return;
            
            }else{
                
                
                $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Physician updated.") );  
                $this->_helper->redirector->gotoUrl($this->uri);
                
            }
               
        }
    
        }else{
            
            if($this->xhr){
                $this->_helper->viewRenderer->setNoRender(true);
                print json_encode( array('success'=>false, 'message'=>'Errors Processing Form', 'errors'=>$form->getErrors()) );
                return;
            }else{
                $this->_helper->flashMessenger->addMessage(array('alert alert-error'=>"Errors Processing Form.") ); 
            }
            
            
        }
    
    }
   
     

    $this->view->form = $form;
    
   }




    public function assignAction() {
        
        $this->_helper->layout->disableLayout();
        $id = $this->getRequest()->getParam('id', null);
        $pid = $this->getRequest()->getParam('physician', null);
        $do  = $this->getRequest()->getParam('do', null);
         
        if( $this->getRequest()->isPost() && !is_null($pid) && !is_null($do) ){
            
            $this->_helper->viewRenderer->setNoRender(true);
            
            $consumePhysicians = new Consumer_Model_ConsumersPhysicians;

            if( $do == 'remove' ) {
                $res = $consumePhysicians->remove($id, $pid );
            }

            if( $do == 'assign' ) {
                $res = $consumePhysicians->assign($id, $pid );
            }

            print json_encode( array('success'=>(bool)$res, 'do'=>$do, 'focus'=>$pid, 'consumer'=>$id) );

            return;
        }

    
        if( !is_null($id) ){
        
            $this->view->id = $id;
            $c = new Consumer_Model_Consumer;
            $consumerInfo = $c->findById($id);
            $this->view->assigned =  $c->getConsumerPhysicians();
            
            $physicians = new Default_Model_Physician;
            $this->view->physicians = $physicians->all(true);
            
            
            $this->view->assignedIds = array();
            foreach( $this->view->assigned as $assigned ){
                $this->view->assignedIds[] = $assigned['id'];
            }
            
           
            
        }
        
        
    }


   public function relationAction() {
    
        if($this->xhr) {
            $this->_helper->layout->disableLayout();
        }
    
    
        $found = new Default_Model_Physician;
        $inuse = $found->findAssociations($this->id);
        
        $this->view->id = $this->id;
        $this->view->consumers = $inuse["assingedToConsumer"];
        $this->view->appointments = $inuse['assingedToMedical'];
        
  }  



   public function deleteAction() {
    
    $this->_helper->layout->disableLayout();
    $this->_helper->viewRenderer->setNoRender(true);
    
    $physician = new Default_Model_Physician;
    $physician->deletePhysician($this->id);
    
    $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Physician Removed.") );  
    $this->_helper->redirector->gotoUrl('/physicians');
    
   }


    
}

