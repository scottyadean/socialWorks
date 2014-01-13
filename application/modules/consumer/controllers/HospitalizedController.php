<?php

class Consumer_HospitalizedController  extends Zend_Controller_Action {    
   
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
    * List a all Hospitalized
    * @return<html>
    */
    public function indexAction() {
        
        if( $this->xhr ) {
            $this->_helper->layout->disableLayout();
            $this->view->layout = false;
         }
         
        $model = new Consumer_Model_ConsumersHospitalized;
        $this->view->hospitalized = $model->getByConsumerId($this->consumer_id);
        $this->view->consumer_id = $this->consumer_id;
    }     
        

  /**
    * List a Hospital
    * @return<html,json>
    */
    public function readAction() {
        
        if($this->xhr) {
            $this->_helper->layout->disableLayout();
            $this->view->layout = false;
        }
        
        $model = new Consumer_Model_ConsumersHospitalized;
        $this->view->hospital = $model->readHospital($this->id);
    }
   
    /**
    * Create a new hospital
    * @return<html>
    */
   public function newAction(){
    
    $model = new Consumer_Model_ConsumersHospitalized;
    $form  = new Application_Form_ConsumersHospitalized; 
    $form->customSubmitBtn = $this->xhr; 
    $form->build( $this->uri, $this->consumer_id);
    
    if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
           if( $lastid = $model->createHospital($form->getValues())) {
               if($this->xhr) {
               
                     $this->_asJson(array('id'=>$lastid,
                                          'success'=>true,
                                          'values'=>(array)$form->getValues(),
                                          'action'=>'new',
                                          'msg'=>'New Hospital info added.'));
               
                  return;
               }
               
               $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Hospital info added.") );  
               $this->_redirect('/consumer/hospitalized/index/cid/'.$this->consumer_id);
               
           }
       }

        if($this->xhr) {
            $this->view->layout = false;
            $this->_helper->layout->disableLayout();
        }
     
     $this->view->form  = $form;
   }
    
 
   public function editAction() {
    
    $model = new Consumer_Model_ConsumersHospitalized;
    $hospitalData = $model->readHospital($this->id)->toArray();
    
    $this->consumer_id = $hospitalData['consumer_id'];
    
    $form  = new Application_Form_ConsumersHospitalized;
    $form->customSubmitBtn = $this->xhr; 
    $form->build( $this->uri,
                 $this->consumer_id,
                 (int)$this->id);
    
    
     if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
    
         $model->updateHospital($form->getValues());
        
        
         if($this->xhr) {
            $this->_asJson(array('id'=>$this->id,
                                 'success'=>true,
                                 'values'=>(array)$form->getValues(),
                                 'action'=>'update',
                                 'msg'=>'Hospital info updated.'));
            return;
         }
              

        $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Hospital info updated.") );  
        $this->_redirect('/consumer/hospitalized/index/cid/'.$this->consumer_id);

             
         }

        if($this->xhr) {
            $this->view->layout = false;
            $this->_helper->layout->disableLayout();
        }
     
       
      $form->populate($hospitalData);
      $this->view->form  = $form;
   }

   public function deleteAction() {
    if($this->xhr && !is_null($this->id)) {    
      
        $insurance  = new Consumer_Model_ConsumersHospitalized;
        $success = $insurance->deleteHospital($this->id);
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
