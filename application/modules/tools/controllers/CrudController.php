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
        
        $this->_setViewParams();
       
    }
    
    public function indexAction() {
      
        $this->view->crud = $this->_curd;
        $count = $this->_model->_count($this->_colRef);
        $paginate = new Base_Template_Paginate(50, $count, $this->page);
        $this->_model->page_limit = $paginate->get_limit();
        $this->_model->page_offset = $paginate->get_offset();
        $this->view->count = $count;
        $this->view->paginate = $paginate->links($this->indexUrl.'/page');
        $this->view->results = $this->_model->_index($this->_colRef);
    
         if($this->xhr) {
         $this->_asJson(array('results'=> $this->view->results,
                              'count'=>$this->view->count,
                              'limit'=>$this->_model->page_limit,
                              'offset'=>$this->_model->page_offset,
                              'paginate'=>$this->view->paginate,
                              'link'=>$this->indexUrl.'/page'));
         return;
      }
    
    
    }
    
    
    public function createAction() {
        
        $this->form->build( $this->uri, $this->id);
        
        $res = Main_Forms_Handler::onPost($this->form,
                                     $this->post,
                                     $this->_model,
                                     "_create",
                                     $this->params,
                                     $this->_helper,
                                     $this->indexUrl.'/page/'.$this->page,
                                     $this->view->displayName." created.",
                                     $this->xhr);  
      
      if($this->xhr && $this->post && !empty($res)) {
         $this->_asJson($res);
         return;
      }
      
      $this->view->form  = $this->form;
   }
       
    public function readAction() {
        $this->view->crud = $this->_curd;
        $this->view->result = $this->_model->_read($this->id);
    }       
    
    public function updateAction() {

        $this->form->build( $this->uri, $this->id);        
        $data = $this->_model->_read((int)$this->id)->toArray();
        $this->form->populate($data);

        $res = Main_Forms_Handler::onPost($this->form,
                                          $this->post,
                                          $this->_model,
                                          "_update",
                                          $this->params,
                                          $this->_helper,
                                          $this->indexUrl.'/page/'.$this->page,
                                          $this->view->displayName." update successful.",
                                          $this->xhr);  
         
         if($this->xhr && $this->post && !empty($res)) {
            $this->_asJson($res);
            return;
         }
        
        if($this->xhr && $this->post) {
            $this->_asJson(array( 'success'=>false, 'id'=>$this->id, 'action'=>'no change', 'message'=>'form not changed', 'errors'=>array() ));
        }else{        
            $this->view->form  = $this->form;
        }
          
        
        
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
         
      $ref =  $this->getRequest()->getParam("crudRef" , false);
      
      //get the values for this curd ref.
      $crud = new Main_Forms_Crud;
      $c = $crud->getProperty($ref);
      
      //set the db name
      $this->dbname = isset($c['db']) ? $c['db'] : null;
      
      //create a instance of the model.
      $model  = isset($c['model']) ? $c['model'] : "Default_Model_Crud";
      $this->_model = new $model;
      $this->_db = $this->_model->setDbName($this->dbname);
      $fields = isset($c['excluded_from_list']) ? $c['excluded_from_list'] :  array();
      $this->_curd = $this->_model->crudData($fields);
      $this->_form = $c['form'];
      $rurl   = isset($c['url']) ? $c['url'] : $this->uri;
      $name   = isset($c['title']) ? $c['title'] :  "";
      $colref = isset($c['where']) ? $c['where'] : false;
      $paging = isset($c['paging']) ? $c['paging'] : null;
        
      $this->_model->onCreate = isset($c['onCreate']) ? $c['onCreate'] : null;
      $this->_model->onUpdate = isset($c['onUpdate']) ? $c['onUpdate'] : null;  
 
 
      foreach( $this->params as $k=>$p ) {
         $rurl = str_replace(":{$k}",$p, $rurl);
         $colref = str_replace(":{$k}",$p, $colref);
      }
      
      $this->_colRef = $colref;
      $this->indexUrl    = str_replace(":crud", "index",  $rurl);
      $this->updateUrl   = str_replace(":crud", "update", $rurl);
      $this->createUrl   = str_replace(":crud", "create", $rurl);
      $this->deleteUrl   = str_replace(":crud", "delete", $rurl);
      $this->templateUrl = str_replace(":crud", "template", $rurl);
      
      $this->form  = new $this->_form;
      $this->form->customSubmitBtn = $this->xhr;
      
      $this->view->displayName = $name;        
      $this->view->layout = true;
      $this->view->indexUrl  = $this->indexUrl;
      $this->view->updateUrl = $this->updateUrl;
      $this->view->createUrl = $this->createUrl;
      $this->view->deleteUrl = $this->deleteUrl;
      $this->view->templateUrl  = $this->templateUrl;
      $this->view->page      = $this->page;
      $this->view->paging    = empty($paging) ? true : false;
        
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