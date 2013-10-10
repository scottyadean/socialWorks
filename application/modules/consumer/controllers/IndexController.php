<?php


class Consumer_IndexController extends Zend_Controller_Action {


    public function indexAction() {
        $consumers = new Consumer_Model_Consumer;
        $this->view->consumers = $consumers->findAll();
    }
    
    public function viewAction() {
    
        $id = $this->getRequest()->getParam('id', null);
    
        if( !is_null($id) ){
        
            $consumer = new Consumer_Model_Consumer;
            $consumerInfo = $consumer->findById($id);
            $consumerUsers = $consumer->getConsumerUsers();
            $consumerPhysicians = $consumer->getConsumerPhysicians();
            $consumerPharamchicals = $consumer->getConsumerPharamchicals();
            
            
            $this->view->consumer = $consumerInfo;
            $this->view->users = $consumerUsers;
            $this->view->physicians = $consumerPhysicians;
            $this->view->pharamchicals  = $consumerPharamchicals;
            
        }
    
    }
    
 
    public function newAction() {
    
      $this->request = $this->getRequest();
      $form = new Application_Form_Consumer;
      $form->build();
    
      if( $this->request->isPost() && $form->isValid($this->request->getPost()) ) {
            $consumerModel = new Consumer_Model_Consumer;
            if($consumerModel->create($form->getValues())){
               $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>' Consumer Added Successfully. ') ); 
               $this->_helper->redirector->gotoUrl('/consumers');
            }
        }
            
        $this->view->form = $form;

    }
    
    
    public function editAction() {
      $this->request = $this->getRequest();
      $id =  $this->request->getParam('id', null);
      $consumers = new Consumer_Model_Consumer;
      $consumerData = $consumers->findById($id);
      $form = new Application_Form_Consumer;
      $form->build('/consumer/edit/'.$id,$id);
      $form->populate( $consumerData->toArray() );
      
      
      if( $this->request->isPost() && $form->isValid($this->request->getPost()) ) {
            
               $consumerModel = new Consumer_Model_Consumer;
            
               //var_dump($form->getValues()); exit;
            
              if($consumerModel->updateConsumer($form->getValues(),$id)){
               $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>' Consumer Updated Successfully. ') ); 
               $this->_helper->redirector->gotoUrl('/consumer/edit/'.$id);
             
            }
        
        }
            
        $this->view->form = $form;

    }    

    
   public function assignedAction() {
        $id = Zend_Auth::getInstance()->getIdentity()->id;
        $consumer = new Consumer_Model_ConsumersUsers;  
        $this->view->consumers = $consumer->getByUserId($id);
        $this->view->checkin = true;
        $this->renderScript('index/index.phtml');
    } 
    
    
    
    
    
    

    public function assigneesAction() {
        
        $this->_helper->layout->disableLayout();
        $id = $this->getRequest()->getParam('id', null);
        $uid = $this->getRequest()->getParam('user', null);
        $do  = $this->getRequest()->getParam('do', null);
         
        if( $this->getRequest()->isPost() && !is_null($uid) && !is_null($do) ){
            
            $this->_helper->viewRenderer->setNoRender(true);
            
            $consumerUsers = new Consumer_Model_ConsumersUsers;

            if( $do == 'remove' ) {
                $res = $consumerUsers->remove($id, $uid);
            }

            if( $do == 'assign' ) {
                $res = $consumerUsers->assign($id, $uid);
            }

            print json_encode( array('success'=>(bool)$res, 'do'=>$do, 'focus'=>$uid, 'client'=>$id) );

            return;
        }

    
        if( !is_null($id) ){
        
            $this->view->id = $id;
            $c = new Consumer_Model_Consumer;
            $consumerInfo = $c->findById($id);
            $this->view->assigned =  $c->getConsumerUsers();
            
            $users = new Default_Model_User;
            $this->view->users = $users->all(true);
            
            
            $this->view->assignedIds = array();
            foreach( $this->view->assigned as $assigned ){
                $this->view->assignedIds[] = $assigned['id'];
            }
            
           
            
        }
        
        
    }



}

