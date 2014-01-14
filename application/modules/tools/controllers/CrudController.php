<?php

class Tools_CrudController extends Zend_Controller_Action {
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   
   
   protected $_db;

   protected $_crud;
   protected $_form;
   protected $_model;
      
   
   public function init() {
       
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->post = $this->getRequest()->isPost();   
        
        $model  = $this->getRequest()->getParam("crudModel" , "Default_Model_Crud");
        $from   = $this->getRequest()->getParam("crudFrom" , "");
        $dbname = $this->getRequest()->getParam("crudDb" , "error"); 
        $fields = $this->getRequest()->getParam("crudExcluded" , array());
        
        $this->_model = new $model;
        $this->_db = $this->_model->setDbName($dbname);
        $this->_curd = $this->_model->crudData($fields);
        $this->_form = $from;
        
        $this->view->displayName = $this->getRequest()->getParam("crudDisplayName" , "");
        $this->view->layout = true;
        
        if($this->xhr) {
            $this->_helper->layout->disableLayout();
            $this->view->layout = false;
        }
       
    }
    
        
    public function indexAction() {
        
        $this->view->crud = $this->_curd;
        
    }
    
    
    
    public function createAction() {
        
        $form  = new $this->_form; 
        $form->customSubmitBtn = $this->xhr; 
        $form->build( $this->uri, $this->id);
    
        if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
               if( $lastid = $this->_model->_create($form->getValues())){
                   
                   if($this->xhr) {
                   
                         $this->_asJson(array('id'=>$lastid,
                                              'success'=>true,
                                              'values'=>(array)$form->getValues(),
                                              'action'=>'new',
                                              'msg'=>'New record added.'));
                   
                      return;
                   }
                   
                   $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New record added.") );  
                   $this->_forward('index');
                   
               }
           }
    

         
         $this->view->form  = $form;
       }
       
       
    
    public function updateAction() {
        
        
        
        $form  = new $this->_form; 
        $form->customSubmitBtn = $this->xhr; 
        $form->build( $this->uri, $this->id);
        
        $data = $this->_model->_read($this->id)->toArray();
        
        $form->populate($data);

        if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
       
           $this->_model->_update($form->getValues());
           
            if($this->xhr) {
                  
               $this->_asJson(array('id'=>$this->id,
                                    'success'=>true,
                                    'values'=>(array)$form->getValues(),
                                    'action'=>'update',
                                    'msg'=>'Record updated.'));
               
               return;
            }
                 
           $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Record updated.") );  
           $this->_forward('index');
   
          }
          
     
           $this->view->form  = $form;
        
    }
    
    
    protected function _asJson(array $data) {
        
        $this->_helper->viewRenderer->setNoRender(true);
        
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }
    
    
}    