<?php


class Consumer_IndexController extends Zend_Controller_Action {

   public $user_id;
   public $consumer_id;

   public function init() {
       $this->user_id = Zend_Auth::getInstance()->getIdentity()->id;
       $this->view->user_id = $this->user_id;
       $this->consumer_id =  $id = $this->getRequest()->getParam('id', 0);
   }


    public function indexAction() {
        $consumers = new Consumer_Model_Consumer;
        $this->view->consumers = $consumers->findAll();
    }
    
    public function viewAction() {

       

        if( $this->consumer_id  != 0 ){
        
             
            $consumer = new Consumer_Model_Consumer;
            
            $this->view->consumer =$consumer->findById($this->consumer_id);
            $this->view->users =  $consumer->getConsumerUsers();
            $this->view->physicians = $consumer->getConsumerPhysicians();
            $this->view->coordinators = $consumer->getConsumerCoordinators();
            $this->view->insurance = $consumer->getConsumerInsurance();
            $this->view->goals = $consumer->getConsumerGoals();
            $this->view->appointments = $consumer->getConsumerAppointments();
            $this->view->persons = $consumer->getConsumerPersons();            
           
            //$hospitalized = new Consumer_Model_ConsumersHospitalized;
            //$this->view->hospitalized = $hospitalized->getByConsumerId($this->consumer_id);
            
            $hospitalizedModel = new Default_Model_Crud; 
            $hospitalizedModel->setDbName('consumers_hospitalized');
            $this->view->hospitalized = $hospitalizedModel->_index(array('consumer_id = ?' => $this->consumer_id));
            
            
            $medsModel = new Consumer_Model_ConsumersPharmaceuticals;
            $this->view->medications = $medsModel->findByConsumerIdAndMapPhysician($this->consumer_id);
            
            $allergiesModel = new Default_Model_Crud; 
            $allergiesModel->setDbName('consumers_allergies');
            $this->view->allergies = $allergiesModel->_index(array('consumer_id = ?' => $this->consumer_id));

            //$allergiesModel = new Consumer_Model_ConsumersAllergies;
            //$this->view->allergies = $allergiesModel->getByConsumerId($this->consumer_id);
            
            $examsModel = new Consumer_Model_ConsumersExams;
            $this->view->physiciansExams = $examsModel->findByConsumerIdAndMapPhysician($this->consumer_id); 
            
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

