<?php

class Consumer_CoordinatorController  extends Zend_Controller_Action {    
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $params;
   public $format;
   public $callback;
   
   public function init() {
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->post = $this->getRequest()->isPost();
        $this->params = $this->getRequest()->getParams();
    }
    
  /**
    * List a all Coordinators
    * @return<html,json>
    */
    public function indexAction() {
        $coordinatorModel = new Default_Model_Coordinator;
        $this->view->coordinators = $coordinatorModel->indexCoordinator();
        
        if($this->xhr) {    
            $this->_asJson($this->view->coordinators);
        }
    }

  /**
    * List a all Coordinators
    * @return<html,json>
    */
    public function readAction() {
        $coordinatorModel = new Default_Model_Coordinator;
        $this->view->coordinator = $coordinatorModel->readCoordinator($this->id);
        
        if(isset($this->view->coordinator->id)) {
            $agency = new Default_Model_Agency;
            $this->view->agency = $agency->readAgency($this->view->coordinator->agency_id);
        }
        
        
        if($this->xhr) {
            $this->_helper->layout->disableLayout();
        }
    }
   
    /**
    * Create a new Coordinator
    * @return<html>
    */
   public function newAction(){
    
      $coords = new Default_Model_Coordinator;
      $form  = new Application_Form_Coordinator; 
      $form->build( $this->uri, $this->id);
      $form->customSubmitBtn = $this->xhr; 
      
      $res = Main_Forms_Handler::onPost($form,
                                 $this->post,
                                 $coords,
                                 "createCoordinator",
                                 $this->params,
                                 $this->_helper,
                                 '/coordinator/index',
                                 "Coordinator created successful.",
                                 $this->xhr);

      if($this->xhr && $this->post && !empty($res)) {
         $this->_asJson($res);
         return;
      }
        
        if($this->xhr && $this->post) {
            $this->_asJson(array( 'success'=>false, '
                                 id'=>$this->id, 'action'=>'no change',
                                 'message'=>'form not changed', 'errors'=>array() ));
        }else{
         
            $this->view->form  = $form;
        }
           
   }
    
 
   public function editAction() {
    
      $coords = new Default_Model_Coordinator;
      $form   = new Application_Form_Coordinator;
      $form->customSubmitBtn = $this->xhr; 
      $form->build( $this->uri, $this->id);

      $coordsData = $coords->readCoordinator($this->id)->toArray();
      $form->populate($coordsData);

      $res = Main_Forms_Handler::onPost($form,
                                 $this->post,
                                 $coords,
                                 "updateCoordinator",
                                 $this->params,
                                 $this->_helper,
                                 '/coordinator/index',
                                 "Coordinator updated successful.",
                                 $this->xhr);

      if($this->xhr && $this->post && !empty($res)) {
         $this->_asJson($res);
         return;
      }
        
        if($this->xhr && $this->post) {
            $this->_asJson(array( 'success'=>false, '
                                 id'=>$this->id, 'action'=>'no change',
                                 'message'=>'form not changed', 'errors'=>array() ));
        }else{
         
            $this->view->form  = $form;
        }



   }



   public function deleteAction() {
    
    if($this->xhr && !is_null($this->id)) {    
        $coords  = new Default_Model_Coordinator;
        $success = $coords->deleteCoordinator($this->id);
        $this->_asJson(array( 'success'=>$success, 'id'=>$this->id ));
      }else{
        $this->_helper->flashMessenger->addMessage(array('alert alert-error'=>"No Direct Access to Delete Action") );  
        $this->_redirect('/');
      }
   }


   public function assignAction() {
   
        $cid = $this->getRequest()->getParam('coordinator', null);
        $do  = $this->getRequest()->getParam('do', null);
         
        if( $this->post && !is_null($cid) && !is_null($do) ){
            
            $consumerCoordinators = new Consumer_Model_ConsumersCoordinators;
            
            if( $do == 'remove' ) {
                $res = $consumerCoordinators->remove($this->id, $cid);
            }

            if( $do == 'assign' ) {
                $res = $consumerCoordinators->assign($this->id, $cid);
            }

            
            $this->_asJson(array('success'=>(bool)$res, 'do'=>$do, 'focus'=>$cid, 'consumer'=>$this->id));

            return true;
        }

        if( !is_null($this->id) ){
            $this->_helper->layout->disableLayout();
        
            $this->view->id = $this->id;
            $c = new Consumer_Model_Consumer;
            $consumerInfo = $c->findById($this->id);
            $this->view->assigned =  $c->getConsumerCoordinators();
            
            $coordinators = new Default_Model_Coordinator;
            $this->view->coordinators = $coordinators->indexCoordinator()->toArray();
            
            $this->view->assignedIds = array();
            
            foreach( $this->view->assigned as $assigned ){
                $this->view->assignedIds[] = $assigned['id'];
            } 
           
        }
        
        
    }
  
  
   protected function _asJson(array $data) {
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }

    
}
