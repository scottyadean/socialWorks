<?php

class Consumer_MedicationController  extends Zend_Controller_Action {    
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $format;
   public $params;
   public $callback;
   public $consumer_id;
   public $physician_id;
   
   protected $_model;
   
   public function init() {
        $this->id = $this->getRequest()->getParam('id', null);
        $this->consumer_id = $this->getRequest()->getParam('cid', $this->getRequest()->getParam('consumer_id', 0));
        $this->physician_id = $this->getRequest()->getParam('physician_id', null);     
        
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->sort = $this->getRequest()->getParam('sort', false);
        $this->post = $this->getRequest()->isPost();
        $this->params = $this->getRequest()->getParams();
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
    * @return<array,json>
    */
   public function createAction(){
    $this->_model = new Consumer_Model_ConsumerMedications;
    
    $form  = new Application_Form_ConsumersMedication;
    $form->customSubmitBtn = $this->xhr;
    $form->build( $this->uri,
                  $this->id,
                  $this->consumer_id);
    
      $res = Main_Forms_Handler::onPost($form,
                                  $this->post,
                                  $this->_model,
                                  "createMedication",
                                  $this->params,
                                  $this->_helper,
                                  '/consumers/medications/index/cid/' . $this->consumer_id,
                                  "Medication created successfully.",
                                  $this->xhr);  

    if($this->xhr && $this->post && !empty($res)) {
        
        $fvalues = $form->getValues();
        $pharam = new Default_Model_Pharmaceutical;
        $physician = new Default_Model_Physician;
        
        $this->consumer_id =  $fvalues['consumer_id'];
        $this->physician_id = $fvalues['physician_id'];
        $this->pharmaceutical_id = $fvalues['pharmaceutical_id'];
        
              
        $res = array_merge($res, array('consumer_id'=>$this->consumer_id,
                        'physician_id'=>$this->physician_id,
                        'pharmaceutical'=>$pharam->readPharmaceutical($this->pharmaceutical_id)->toArray(),
                        'physician'=>$physician->findById($this->physician_id)->toArray()));  
        
        $this->_asJson($res);
        return;
     }
      
    
    //$this->renderScript('/medication/form.phtml'); 
    $this->view->form  = $form;
    
 
   }
    
   public function updateAction() {
    
      $this->_model = new  Consumer_Model_ConsumerMedications;
      $data =  $this->_model->readMedication($this->id)->toArray();
      
      $this->consumer_id = $data['consumer_id'];
      $this->physician_id = $data['physician_id'];
      $this->pharmaceutical_id = $data['pharmaceutical_id'];
      
      $form  = new Application_Form_ConsumersMedication;
      $form->customSubmitBtn = $this->xhr;
      $form->build( $this->uri,
                    $this->id,
                    $this->consumer_id,
                    $this->physician_id,
                    $this->pharmaceutical_id
                    );
      $form->populate($data);


      
      $res = Main_Forms_Handler::onPost($form,
                                  $this->post,
                                  $this->_model,
                                  "updateMedication",
                                  $this->params,
                                  $this->_helper,
                                  '/consumers/medications/index/cid/' . $this->consumer_id,
                                  "Medication updated successfully.",
                                  $this->xhr);  
      
        if($this->xhr && $this->post && !empty($res)) {
            
            $fvalues = $form->getValues();
            $pharam_id = $fvalues['pharmaceutical_id']; 
            $pharam = new Default_Model_Pharmaceutical;
            $physician = new Default_Model_Physician;
                  
            $res = array_merge($res, array('consumer_id'=>$this->consumer_id,
                            'physician_id'=>$this->physician_id,
                            'pharmaceutical'=>$pharam->readPharmaceutical($pharam_id)->toArray(),
                            'physician'=>$physician->findById($this->physician_id)->toArray()));  
            
            $this->_asJson($res);
            return;
         }
      
      $this->view->form  = $form;
      $this->renderScript('/medication/form.phtml');
    
   }



   public function deleteAction() {
    
   
      if($this->xhr && !is_null($this->id)) {    
        
        $this->_model  = new Consumer_Model_ConsumerMedications;
        $success = $this->_model->deleteMedication($this->id);
        
        $this->_asJson(array( 'success'=>$success, 'id'=>$this->id ));
      
      } else {
        
        $this->_helper->flashMessenger->addMessage(array('alert alert-error'=>"No Direct Access to Delete Action") );  
        $this->_redirect($this->indexUrl);
      
      }
            
   }
   
   protected function _asJson(array $data) {
        $this->_helper->viewRenderer->setNoRender(true);        
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }
   
}