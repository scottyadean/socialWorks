<?php

class Consumer_ProgramController  extends Zend_Controller_Action {    
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $params;
   public $format;
   public $callback;
   public $consumer_id;
   
   protected $_model;
   
   public function init() {
        
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->sort = $this->getRequest()->getParam('sort', false);
        $this->post = $this->getRequest()->isPost();
        $this->params = $this->getRequest()->getParams();
        $this->format = $this->getRequest()->getParam('format', false);
        $this->callback = $this->getRequest()->getParam('callback', null);
        $this->consumer_id = $this->getRequest()->getParam('cid', $this->getRequest()->getParam('consumer_id', 0));
        
        $this->view->layout = true;
        
         if( $this->xhr ) {
            $this->_helper->layout->disableLayout();
            $this->view->layout = false;
         }
    }
    
    
    public function indexAction() {
      $this->_model = new Consumer_Model_ConsumerPrograms;
      $this->view->programs = $this->_model->_index(array("consumer_id = ?" => $this->consumer_id));
      $this->view->consumer_id = $this->consumer_id;   
    
    }
    
   
    /**
    * Create
    * @param $id
    * @return<array,json>
    */
   public function createAction(){
    
    $this->_model = new Consumer_Model_ConsumerPrograms;
    $form  = new Application_Form_ConsumerPrograms;
    $form->customSubmitBtn = $this->xhr;
    $form->build( $this->uri, $this->id, $this->consumer_id);
    
      $res = Main_Forms_Handler::onPost($form,
                                        $this->post,
                                        $this->_model,
                                        "_create",
                                        $this->params,
                                        $this->_helper,
                                        '/consumer/programs/index/cid/' . $this->consumer_id,
                                        'New Program added for consumer.',
                                        $this->xhr);  

    if($this->xhr && $this->post && !empty($res)) {
        
        $fvalues = $form->getValues();
        
        $crud = new Default_Model_Crud;
        $crud->setDbName('programs');
        
        $program = $crud->_read($fvalues['program_id'])->toArray();
        $this->consumer_id =  $fvalues['consumer_id'];
        
        $dcrud = new Default_Model_Crud;
        $dcrud->setDbName('people');
        $director = $dcrud->_read($program['director_id']);
        
        $res = array_merge($res, array('consumer_id'=>$this->consumer_id,
                                        'director'=>$director,
                                        'program'=>$program));  
        $this->_asJson($res);
        return;
     }
      
    $this->view->form  = $form;
 
   }
    
 
   public function updateAction() {
    
      $this->_model = new Consumer_Model_ConsumerPrograms;
      $data = $this->_model->_read($this->id)->toArray();
      $form  = new Application_Form_ConsumerPrograms;
      $form->customSubmitBtn = $this->xhr;
      $form->build( $this->uri,
                    $this->id,
                    $this->consumer_id);
     
     $form->populate($data);
      
      $res = Main_Forms_Handler::onPost($form,
                                        $this->post,
                                        $this->_model,
                                        "_update",
                                        $this->params,
                                        $this->_helper,
                                        '/consumer/programs/index/cid/' . $this->consumer_id,
                                        'Program updated.',
                                        $this->xhr);  

    if($this->xhr && $this->post && !empty($res)) {
        
       $fvalues = $form->getValues();
        
        $crud = new Default_Model_Crud;
        $crud->setDbName('programs');
        
        $program = $crud->_read($fvalues['program_id'])->toArray();
        $this->consumer_id =  $fvalues['consumer_id'];
        
        $dcrud = new Default_Model_Crud;
        $dcrud->setDbName('people');
        $director = $dcrud->_read($program['director_id'])->toArray();
        
        $res = array_merge($res, array('consumer_id'=>$this->consumer_id,
                                        'director'=>$director,
                                        'program'=>$program));
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
      
      
      
      if($this->xhr ){
     
        $this->_model = new Consumer_Model_ConsumerPrograms;
        $success = $this->_model->_delete($this->id);
        $this->_asJson(array( 'success'=>$success, 'id'=>$this->id, 'action'=>'delete' )); 
      }

   }
   
   
   protected function _asJson(array $data) {
        
        
        $this->_helper->viewRenderer->setNoRender(true);
        
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }



    
}
