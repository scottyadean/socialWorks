<?php

class Consumer_ExamController  extends Zend_Controller_Action {    
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $params;
   public $format;
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
        $examsModel = new Consumer_Model_ConsumersExams;
        $this->view->physicians = $examsModel->findByConsumerIdAndMapPhysician($this->consumer_id);
        $this->view->consumer_id = $this->consumer_id;
        
    }
    
   
    /**
    * List a all exams
    * @param $id
    * @return<array,json>
    */
   public function createAction(){
    
    $this->_model = new Consumer_Model_ConsumersExams;
    
    $form  = new Application_Form_ConsumerExams;
    $form->customSubmitBtn = $this->xhr;
    $form->build( $this->uri,
                  $this->id,
                  $this->consumer_id);
    
    
      $res = Main_Forms_Handler::onPost($form,
                                        $this->post,
                                        $this->_model,
                                        "createExam",
                                        $this->params,
                                        $this->_helper,
                                        '/exams/index/cid/' . $this->consumer_id,
                                        'New Exam added.',
                                        $this->xhr);  

    if($this->xhr && $this->post && !empty($res)) {
        
        $fvalues = $form->getValues();
        $physician = new Default_Model_Physician;
        $this->consumer_id =  $fvalues['consumer_id'];
        $this->physician_id = $fvalues['physician_id'];
        
        $res = array_merge($res, array('consumer_id'=>$this->consumer_id,
                                       'physician_id'=>$this->physician_id,
                                       'physician'=>$physician->findById($this->physician_id)->toArray()));  
        
        $this->_asJson($res);
        return;
     }
      
    $this->view->form  = $form;
 
   }
    
 
   public function updateAction() {
    
      $this->_model = new Consumer_Model_ConsumersExams;
      
      $data = $this->_model->readExam($this->id)->toArray();
      
      
      $form  = new Application_Form_ConsumerExams;
      $form->customSubmitBtn = $this->xhr;
      $form->build( $this->uri,
                    $this->id,
                    $this->consumer_id);
     
     $form->populate($data);
      
      $res = Main_Forms_Handler::onPost($form,
                                        $this->post,
                                        $this->_model,
                                        "updateExam",
                                        $this->params,
                                        $this->_helper,
                                        '/exams/index/cid/' . $this->consumer_id,
                                        'New Exam updated.',
                                        $this->xhr);  

    if($this->xhr && $this->post && !empty($res)) {
        
        $fvalues = $form->getValues();
        $physician = new Default_Model_Physician;
        $this->consumer_id =  $fvalues['consumer_id'];
        $this->physician_id = $fvalues['physician_id'];
        
        $res = array_merge($res, array('consumer_id'=>$this->consumer_id,
                                       'physician_id'=>$this->physician_id,
                                       'physician'=>$physician->findById($this->physician_id)->toArray()));  
        
        $this->_asJson($res);
        return;
     }
     
     
      if($this->xhr && $this->post) {
        $this->_asJson(array( 'success'=>false, 'id'=>$this->id, 'action'=>'no change', 'message'=>'form not changed', 'errors'=>array() ));
        
      }else{
        $this->view->form  = $form;
         
      }
    
   }



   public function deleteAction() {
      
      if(is_null($this->id)) {
        $this->_redirect('/exams/index/cid/' . $this->consumer_id."/success/false");
      }
      
      $exams   = new Consumer_Model_ConsumersExams;
      $success = $exams->deleteExam($this->id);
      
      if($this->xhr ){
        $this->_asJson(array( 'success'=>$success, 'id'=>$this->id, 'action'=>'delete' ));
        
      }else{
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Exam Removed.") );  
            $this->_redirect('/exams/index/cid/' . $this->consumer_id."/success/".$success);
      }

   }
   
   
   protected function _asJson(array $data) {
        
        
        $this->_helper->viewRenderer->setNoRender(true);
        
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }



    
}
