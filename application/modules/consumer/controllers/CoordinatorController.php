<?php

class Consumer_CoordinatorController  extends Zend_Controller_Action {    
   
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $format;
   public $callback;
   
   public function init() {
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->post = $this->getRequest()->isPost();
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
    
    if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {

           if( $lastid = $coords->createCoordinator($form->getValues())){
               $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Coordinator added.") );  
               $this->_redirect('/coordinator/index');
           }
       }
     
     $this->view->form  = $form;
   }
    
 
   public function editAction() {
    
      $coords = new Default_Model_Coordinator;
      $form   = new Application_Form_Coordinator; 
      $form->build( $this->uri, $this->id);

      $coordsData = $coords->readCoordinator($this->id)->toArray();
      $form->populate($coordsData);

     if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
             if( $lastid = $coords->updateCoordinator($form->getValues())){
                 $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Coordinator updated.") );  
                 $this->_redirect('/coordinator/index');
             }
         }
         
      $this->view->form  = $form;
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
