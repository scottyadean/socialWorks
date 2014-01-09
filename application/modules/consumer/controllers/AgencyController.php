<?php

class Consumer_AgencyController  extends Zend_Controller_Action {    
   
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
        $this->post = $this->getRequest()->isPost();
    }
    
  /**
    * List a all Coordinators
    * @return<html,json>
    */
    public function indexAction() {
        $agencyModel = new Default_Model_Agency;
        $this->view->agencies = $agencyModel->indexAgency();
        
        if($this->xhr) {    
            $this->_asJson($this->view->agencies);
        }
    }
    
   
    /**
    * Create a new Coordinator
    * @return<html>
    */
   public function newAction(){
    
    $agency = new Default_Model_Agency;
    $form  = new Application_Form_Agency; 
    $form->build( $this->uri, $this->id);
    
    if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {

           if( $lastid = $agency->createAgency($form->getValues())){
               $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Agency added.") );  
               $this->_redirect('/agency/index');
           }
       }
     
     $this->view->form  = $form;
    
   }
    
 
   public function editAction() {
    
      $agency = new Default_Model_Agency;
      $form   = new Application_Form_Agency; 
      $form->build( $this->uri, $this->id);
      $agencyData = $agency->readAgency($this->id)->toArray();
      $form->populate($agencyData);

     if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
             if( $lastid = $agency->updateAgency($form->getValues())){
                 $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Agency updated.") );  
                 $this->_redirect('/agency/index');
             }
         }
         
      $this->view->form  = $form;
   }



   public function deleteAction() {
    
    if($this->xhr && !is_null($this->id)) {    
        $agency  = new Default_Model_Agency;
        $success = $agency->deleteAgency($this->id);
        $this->_asJson(array( 'success'=>$success, 'id'=>$this->id ));
      }else{
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
