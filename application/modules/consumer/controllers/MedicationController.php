<?php

class Consumer_MedicationController  extends Zend_Controller_Action {    
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $format;
   public $callback;
   public $consumer_id;
   public $physician_id;
   
   public function init() {
        $this->id = $this->getRequest()->getParam('id', null);
        $this->consumer_id = $this->getRequest()->getParam('cid', $this->getRequest()->getParam('consumer_id', 0));
        $this->physician_id = $this->getRequest()->getParam('physician_id', null);     
        
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->sort = $this->getRequest()->getParam('sort', false);
        $this->post = $this->getRequest()->isPost();
        $this->format = $this->getRequest()->getParam('format', false);
        $this->callback = $this->getRequest()->getParam('callback', null);
        $this->view->layout = true;
        
         if( $this->xhr ) {
            $this->_helper->layout->disableLayout();
            $this->view->layout = false;
         }
    }
    
    
    public function indexAction() {
        $medsModel = new Consumer_Model_ConsumersPharmaceuticals;
        $this->view->physicians = $medsModel->findByConsumerIdAndMapPhysician($this->consumer_id);
        $this->view->consumer_id = $this->consumer_id;
        
    }
    
    /**
    * Creat a new medication
    * @param $id
    * @return<array,json>
    */
   public function newAction(){
    
    $exams = new Consumer_Model_ConsumersPharmaceuticals;
    
    $form  = new Application_Form_ConsumersMedication;
    $form->customSubmitBtn = $this->xhr;
    $form->build( $this->uri,
                  $this->id,
                  $this->consumer_id);
    
   if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {

           if( $lastid = $exams->createPharmaceutical($form->getValues())){
               
               if( $this->xhr ) {
                  
                  $physician = new Default_Model_Physician;
                  
                  $this->_asJson(array( 'success'=>true,
                                        'msg'=>'New Medication added.',
                                        'id'=>$lastid,
                                        'action'=>'new',
                                        'consumer_id'=>$this->consumer_id,
                                        'physician_id'=>$this->physician_id,
                                        'values'=>$form->getValues(),
                                        'physician'=>$physician->findById($this->physician_id)->toArray()));
               
                  return;
               }
               
               $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Medication added.") );  
               $this->_redirect('/consumers/medications/index/cid/' . $this->consumer_id);
                  
           }
       
       }
    
     
    $this->view->exams = $exams->findByConsumerId($this->consumer_id); 
    $this->view->form  = $form;
    
 
   }
    
   public function editAction() {
    
      $meds = new Consumer_Model_ConsumersPharmaceuticals;
      $medsData = $meds->readPharmaceutical($this->id)->toArray();
      
      $this->consumer_id = $medsData['consumer_id'];
      $this->physician_id = $medsData['physician_id'];
      $this->pharmaceutical_id = $medsData['pharmaceutical_id'];
      
      $form  = new Application_Form_ConsumersMedication;
      $form->customSubmitBtn = $this->xhr;
      
      $form->build( $this->uri,
                    $this->id,
                    $this->consumer_id,
                    $this->physician_id,
                    $this->pharmaceutical_id
                    );
      
     if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
     if($meds->updatePharmaceutical($form->getValues())) {         
               if( $this->xhr ) {
                  
                  $fvalues = $form->getValues();
                  
                  $pharam_id = $fvalues['pharmaceutical_id']; 
                  $pharam = new Default_Model_Pharmaceutical;
                  $physician = new Default_Model_Physician;
                  
                  $this->_asJson(array( 'success'=>true,
                                        'msg'=>'Medication updated.',
                                        'id'=>$this->id,
                                        'action'=>'edit',
                                        'consumer_id'=>$this->consumer_id,
                                        'physician_id'=>$this->physician_id,
                                        'values'=>$fvalues,
                                        'pharmaceutical_info'=>$pharam->readPharmaceutical($pharam_id)->toArray(),
                                        'physician'=>$physician->findById($this->physician_id)->toArray()));  
                  return;
               }
              
              
                 $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Medication updated.") );  
                 $this->_redirect('/consumers/medications/index/cid/' . $this->consumer_id);
             }
         
         }
      
      $form->populate($medsData);
      $this->view->form  = $form;
    
   }



   public function deleteAction() {
    
      $exams   = new Consumer_Model_ConsumersPharmaceuticals;
      $success = $exams->deletePharmaceutical($this->id);
   
      if($this->xhr && !is_null($this->id)) {    
        
        $this->_asJson(array( 'success'=>$success, 'id'=>$this->id ));
        
      }else{
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Medication Removed.") );  
        $this->_redirect('/consumers/medications/index/cid/' . $this->consumer_id."/success/".$success);
        
      }
            
   }
   
   protected function _asJson(array $data) {
        $this->_helper->viewRenderer->setNoRender(true);        
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }
   
}