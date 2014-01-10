<?php

class Consumer_ExamController  extends Zend_Controller_Action {    
   
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
        $examsModel = new Consumer_Model_ConsumersExams;
        $this->view->physicians = $examsModel->findByConsumerIdAndMapPhysician($this->consumer_id);
        $this->view->consumer_id = $this->consumer_id;
        
    }
    
   
    /**
    * List a all exams
    * @param $id
    * @return<array,json>
    */
   public function newAction(){
    
    $exams = new Consumer_Model_ConsumersExams;
    
    $form  = new Application_Form_ConsumerExams;
    $form->customSubmitBtn = $this->xhr;
    $form->build( $this->uri,
                  $this->consumer_id,
                  $this->physician_id,
                  $this->id);
    
   if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {

           if( $lastid = $exams->createExam($form->getValues())){
               
               if( $this->xhr ) {
                  
                  $physician = new Default_Model_Physician;
                  
                  $this->_asJson(array( 'success'=>true,
                                        'msg'=>'New Exam added.',
                                        'id'=>$lastid,
                                        'action'=>'new',
                                        'consumer_id'=>$this->consumer_id,
                                        'physician_id'=>$this->physician_id,
                                        'values'=>$form->getValues(),
                                        'physician'=>$physician->findById($this->physician_id)->toArray()));
               
                  return;
               }
               
               $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Exam added.") );  
               $this->_redirect('/consumer/exam/index/cid/' . $this->consumer_id);
                  
           }
       
       }
    
     
    $this->view->exams = $exams->findByConsumerId($this->consumer_id); 
    $this->view->form  = $form;
    
 
   }
    
 
   public function editAction() {
    
      $exams = new Consumer_Model_ConsumersExams;
      $form  = new Application_Form_ConsumerExams;
      $form->customSubmitBtn = $this->xhr;
      $form->build( $this->uri,
                    $this->consumer_id,
                    $this->physician_id,
                    $this->id);

        $examData = $exams->readExam($this->id)->toArray();
      
        if(isset($examData['date']) && $examData['date'] !='') {
            $strDate = strtotime( $examData['date'] );
            $examData['date'] = date('m/d/Y', $strDate );
        } 
      
        $form->populate($examData);
      
     if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
  
             if($exams->updateExam($form->getValues())){
              
               if( $this->xhr ) {
                  
                  $physician = new Default_Model_Physician;
                  
                  $this->_asJson(array( 'success'=>true,
                                        'msg'=>'Exam updated.',
                                        'id'=>$this->id,
                                        'action'=>'edit',
                                        'consumer_id'=>$this->consumer_id,
                                        'physician_id'=>$this->physician_id,
                                        'values'=>$form->getValues(),
                                        'physician'=>$physician->findById($this->physician_id)->toArray()));
               
                  return;
               }
              
              
              
                 $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Exam updated.") );  
                 $this->_redirect('/consumer/exam/index/cid/' . $this->consumer_id);
             }
         
         }
      
      $this->view->form  = $form;
    
   }



   public function deleteAction() {
    
      $exams   = new Consumer_Model_ConsumersExams;
      $success = $exams->deleteExam($this->id);
   
      if($this->xhr && !is_null($this->id)) {    
        
        $this->_asJson(array( 'success'=>$success, 'id'=>$this->id ));
        
      }else{
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Exam Removed.") );  
        $this->_redirect('/consumer/exam/index/cid/' . $this->consumer_id."/success/".$success);
        
      }
           
    
   }
   
   
   protected function _asJson(array $data) {
        
        
        $this->_helper->viewRenderer->setNoRender(true);
        
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }



    
}
