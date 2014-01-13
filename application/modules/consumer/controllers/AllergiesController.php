<?php

class Consumer_AllergiesController  extends Zend_Controller_Action {    
   
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
        $this->view->layout  = true;
    }
    
  /**
    * List a all Allergies
    * @return<html>
    */
    public function indexAction() {
        
        $allergiesModel = new Consumer_Model_ConsumersAllergies;
        $this->view->allergies = $allergiesModel->getByConsumerId($this->consumer_id);
        
        if( $this->xhr ) {
            $this->_helper->layout->disableLayout();
            $this->view->layout = false;
         }
    }    
        

  /**
    * List a Allergy
    * @return<html,json>
    */
    public function readAction() {
        
        if($this->xhr) {
            $this->_helper->layout->disableLayout();
        }
        
        $allergiesModel = new Consumer_Model_ConsumersAllergies;
        $this->view->allergy = $allergiesModel->readAllergy($this->id);
    }
   
    /**
    * Create a new allergy
    * @return<html>
    */
   public function newAction(){
    
    $allergy = new Consumer_Model_ConsumersAllergies;
    $form  = new Application_Form_ConsumersAllergies; 
    $form->customSubmitBtn = $this->xhr; 
    $form->build( $this->uri, $this->consumer_id);
    
    if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
           if( $lastid = $allergy->createAllergy($form->getValues())) {
               if($this->xhr) {
               
                     $this->_asJson(array('id'=>$lastid,
                                          'success'=>true,
                                          'values'=>(array)$form->getValues(),
                                          'action'=>'new',
                                          'msg'=>'New Allergy info added.'));
               
                  return;
               }
               
               $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Allergy info added.") );  
               $this->_redirect('/consumers/allergies/index/cid/'.$this->consumer_id);
               
           }
       }

        if($this->xhr) {
            $this->_helper->layout->disableLayout();
        }
     
     $this->view->form  = $form;
   }
    
 
   public function editAction() {
    
    $allergy = new Consumer_Model_ConsumersAllergies;
    $allergyData = $allergy->readAllergy($this->id)->toArray();
    
    $this->consumer_id = $allergyData['consumer_id'];
    
    $form  = new Application_Form_ConsumersAllergies;
    $form->customSubmitBtn = $this->xhr; 
    $form->build( $this->uri,
                 $this->consumer_id,
                 (int)$this->id);
    
    
     if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
    
         $allergy->updateAllergy($form->getValues());
        
        
         if($this->xhr) {
            $this->_asJson(array('id'=>$this->id,
                                 'success'=>true,
                                 'values'=>(array)$form->getValues(),
                                 'action'=>'update',
                                 'msg'=>'Allergy info updated.'));
            return;
         }
              

        $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Allergy info updated.") );  
        $this->_redirect('/consumers/allergies/index/cid/'.$this->consumer_id);

             
             
         }

        if($this->xhr) {
            $this->_helper->layout->disableLayout();
        }
     
       
      $form->populate($allergyData);
      $this->view->form  = $form;
   }

   public function deleteAction() {
    if($this->xhr && !is_null($this->id)) {    
      
        $insurance  = new Consumer_Model_ConsumersAllergies;
        $success = $insurance->deleteAllergy($this->id);
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
