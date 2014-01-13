<?php

class Consumer_MedicalController extends Zend_Controller_Action {


    public $id;
    public $xhr;
    public $uri;
    public $sort;
    public $post;
    public $format;
    public $callback;
    public $consumer_id;

    public function init() {
        
        $this->consumer_id = $this->getRequest()->getParam('consumer_id', $this->getRequest()->getParam('cid', 0));
        $this->id = $this->getRequest()->getParam('id', 0);
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

        $model = new Consumer_Model_ConsumersMedical;
        $this->view->appointments = $model->findByConsumerId($this->consumer_id);
        $this->view->consumer_id = $this->consumer_id;
               
    }
    
    
   public function newAction(){
    
    $model= new Consumer_Model_ConsumersMedical;
    $form = new Application_Form_ConsumersMedical;
    $form->customSubmitBtn = $this->xhr;
    $form->build($this->uri,
                 $this->consumer_id);
    
    if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
        
        if( $lastid = $model->createAppointment($form->getValues())){               
               if( $this->xhr ) {
                
                $this->_asJson(array('success'=>true,
                                     'msg'=>'New Appointment added.',
                                     'id'=>$lastid,
                                     'action'=>'new',
                                     'consumer_id'=>$this->consumer_id,
                                     'values'=>$form->getValues()));
                  return;
               }
               
               $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Appointment added.") );  
               $this->_redirect('/appointment/index/cid/' . $this->consumer_id);
                  
           }
       
       }
    
    
    $this->view->form  = $form;
    
 
   }
    
    public function editAction() {
        
        $model = new Consumer_Model_ConsumersMedical;
        $data = $model->readAppointment($this->id)->toArray();
        $this->consumer_id = $data['consumer_id'];
        $form = new Application_Form_ConsumersMedical;
        $form->customSubmitBtn = $this->xhr;
        $form->build($this->uri, $this->consumer_id, $this->id);
        $form->populate($data);  
        
        if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
            if($model->updateAppointment($form->getValues())){
               if( $this->xhr ) {      
                  $this->_asJson(array( 'success'=>true,
                                        'msg'=>'Appointment updated.',
                                        'id'=>$this->id,
                                        'action'=>'edit',
                                        'consumer_id'=>$this->consumer_id,
                                        'values'=>$form->getValues()));
                  return;
               }
                 $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Appointment updated.") );  
                 $this->_redirect('/appointment/index/cid/' . $this->consumer_id);
             }
         
         }
        
        
       $this->view->form = $form;
       
    }

    
   public function deleteAction() {
    
      $model   = new Consumer_Model_ConsumersMedical;
      $success = $model->deleteAppointment($this->id);
   
      if($this->xhr && !is_null($this->id)) {    
        $this->_asJson(array( 'success'=>$success, 'id'=>$this->id ));
      }else{
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Appointment Removed.") );  
        $this->_redirect('/appointment/index/cid/' . $this->consumer_id."/success/".$success);
        
      }
   }       
   
   protected function _asJson(array $data) {
        
        
        $this->_helper->viewRenderer->setNoRender(true);
        
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }
    
}
    