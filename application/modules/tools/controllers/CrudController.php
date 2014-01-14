<?php

class Tools_CrudController extends Zend_Controller_Action {
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $page;
   public $dbname;
   
   public $indexUrl;
   public $createUrl;
   public $updateUrl;
   
   protected $_db;
   protected $_crud;
   protected $_form;
   protected $_model;
   protected $_colRef;
   
   public function init() {
       
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->post = $this->getRequest()->isPost();   
        $this->page = $this->getRequest()->getParam('page', 1);
        $this->params = $this->getRequest()->getParams();
        $this->dbname = $this->getRequest()->getParam("crudDb" , "error"); 

        $this->_setViewParams();
       
    }
    
    public function indexAction() {
        $this->view->crud = $this->_curd;
        $count = $this->_model->_count($this->_colRef);
        $paginate = new Base_Template_Paginate(3, $count, $this->page);
        $this->_model->page_limit = $paginate->get_limit();
        $this->_model->page_offset = $paginate->get_offset();
        $this->view->count = $count;
        $this->view->paginate = $paginate->links($this->indexUrl.'/page');
        
        $this->view->results = $this->_model->_index($this->_colRef);
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
                   $this->_redirect($this->indexUrl.'/page/'.$this->page);
                   
               }
           }
    

        $form->populate($this->params);
        $this->view->form  = $form;
       }
       
    
    public function readAction() {
        $this->view->crud = $this->_curd;
        $this->view->result = $this->_model->_read($this->id);
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
           $this->_redirect($this->indexUrl.'/page/'.$this->page);
   
          }
          
           $this->view->form  = $form;
        
    }
    
    
    
   public function deleteAction() {
    if($this->xhr && !is_null($this->id)) {    
      
        $success = $this->_model->_delete($this->id);
        $this->_asJson(array( 'success'=>$success, 'id'=>$this->id ));
      
      } else {
        
        $this->_helper->flashMessenger->addMessage(array('alert alert-error'=>"No Direct Access to Delete Action") );  
        $this->_redirect($this->indexUrl);
      }
      
   }

   
    public function templateAction() {
      $this->_helper->layout->disableLayout();
      $this->view->crud = $this->_curd;
      $this->getResponse()->setHeader('Content-type', 'text/plain');
      
   }
   
    
    protected function _setViewParams() {
              
        $model  = $this->getRequest()->getParam("crudModel" , "Default_Model_Crud");
        $from   = $this->getRequest()->getParam("crudFrom" , "Application_Form_".ucwords($this->dbname));
        $fields = $this->getRequest()->getParam("crudExcluded" , array());
        $rurl   = $this->getRequest()->getParam("crudRedirect" ,$this->uri);
        $name   = $this->getRequest()->getParam("crudDisplayName" , "");
        $colref = $this->getRequest()->getParam("crudRef" , false);
        $paging = $this->getRequest()->getParam("crudPaging" , null);
        
        foreach( $this->params as $k=>$p ) {
            $rurl = str_replace(":{$k}",$p, $rurl);
            $colref = str_replace(":{$k}",$p, $colref);
        }
        
        $this->indexUrl    = str_replace(":curd", "index",  $rurl);
        $this->updateUrl   = str_replace(":curd", "update", $rurl);
        $this->createUrl   = str_replace(":curd", "create", $rurl);
        $this->deleteUrl   = str_replace(":curd", "delete", $rurl);
        $this->templateUrl = str_replace(":curd", "template", $rurl);
        
               
        $this->_model = new $model;
        $this->_db = $this->_model->setDbName($this->dbname);
        $this->_curd = $this->_model->crudData($fields);
        $this->_form = $from;
        $this->_colRef = $colref;
        
        $this->view->layout = true;
        $this->view->indexUrl  = $this->indexUrl;
        $this->view->updateUrl = $this->updateUrl;
        $this->view->createUrl = $this->createUrl;
        $this->view->deleteUrl = $this->deleteUrl;
        $this->view->templateUrl  = $this->templateUrl;
        $this->view->page      = $this->page;
        $this->view->paging    = empty($paging) ? true : false;
        
        $this->view->displayName = $name;        
        
        if($this->xhr) {
            $this->_helper->layout->disableLayout();
            $this->view->layout = false;
        }
        
         $wheres = array();
         if($this->_colRef) {
            
            $where = explode(",",$this->_colRef);  
            
            foreach($where as $k=>$v) {
             $cols = explode("=>",$v);
             $wheres["{$cols[0]} = ?"] = $cols[1]; 
            }
         
        }
        
        $this->_colRef = $wheres;
        
        
    }
    
    
    protected function _asJson(array $data) {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }
    
}    