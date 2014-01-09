<?php

class Consumer_InsuranceController  extends Zend_Controller_Action {    
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $format;
   public $callback;
   public $consumer_id;
   
   public function init() {
    
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->post = $this->getRequest()->isPost();
        $this->consumer_id = $this->getRequest()->getParam('cid', $this->getRequest()->getParam('consumer_id', 0));
        
    }
    
  /**
    * List a all Coordinators
    * @return<html,json>
    */
    public function indexAction() {
        
        $insuranceModel = new Consumer_Model_ConsumersInsurance;
        $this->view->insurance = $insuranceModel->getByConsumerId($this->consumer_id);
        
        if($this->xhr) {    
            $this->_asJson($this->view->insurance);
        }
    }

  /**
    * List a all Coordinators
    * @return<html,json>
    */
    public function readAction() {
        
        if($this->xhr) {
            $this->_helper->layout->disableLayout();
        }
        
        $insuranceModel = new Consumer_Model_ConsumersInsurance;
        $this->view->insurance = $insuranceModel->readInsurance($this->id);
    }
   
    /**
    * Create a new Coordinator
    * @return<html>
    */
   public function newAction(){
    
    $insurance = new Consumer_Model_ConsumersInsurance;
    $form  = new Application_Form_ConsumerInsurance; 
    $form->build( $this->uri, $this->consumer_id);
    
    if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
           if( $lastid = $insurance->createInsurance($form->getValues())){
               $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New insurance info added.") );  
               $this->_redirect('/consumer/insurance/index/cid/'.$this->consumer_id);
           }
       }
     
     $this->view->form  = $form;
   }
    
 
   public function editAction() {
    
    $insurance = new Consumer_Model_ConsumersInsurance;
    $insuranceData = $insurance->readInsurance($this->id)->toArray();
    $this->consumer_id = $insuranceData['consumer_id'];
    $form  = new Application_Form_ConsumerInsurance;
    $form->build( $this->uri,
                 $this->consumer_id,
                 (int)$this->id);
    $form->populate($insuranceData);

     if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
    
        $insurance->updateInsurance($form->getValues());
        $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Insurance info updated.") );  
        $this->_redirect('/consumer/insurance/index/cid/'.$this->consumer_id);

             
             
         }
         
      $this->view->form  = $form;
   }

   public function deleteAction() {
    if($this->xhr && !is_null($this->id)) {    
      
        $insurance  = new Consumer_Model_ConsumersInsurance;
        $success = $insurance->deleteInsurance($this->id);
        $this->_asJson(array( 'success'=>$success, 'id'=>$this->id ));
      
      } else {
        
        $this->_helper->flashMessenger->addMessage(array('alert alert-error'=>"No Direct Access to Delete Action") );  
        $this->_redirect('/');
      }
   }

   protected function _asJson(array $data) {
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }
}
