<?php
class Reports_IspController extends Zend_Controller_Action {

    public $steps = array('Start', 'Info', 'Medical', 'SIRs', 'Goals', 'Summary', 'Finalize');
    public $formName = 'Isp Report';
    public $id;
    public $userId;
    public $progressStep = 0;
    
    protected $_action;
    protected $_consumerModel;
    protected $_sessionInfo;
    
    public function init() {
        
        $this->id = $this->getRequest()->getParam('id', false);
        $this->userId = Zend_Auth::getInstance()->getIdentity()->id;
        $this->progressStep = round( ( 100 / count($this->steps) ), 2);
        $this->_consumerModel = new Consumer_Model_ConsumersUsers;  
        $this->_sessionInfo = new Zend_Session_Namespace('ISP_REPORT_'.$this->id); 
        $this->_action = $this->getRequest()->getParam('action', 'index');
        
        if($this->_action != 'index' && ( !$this->id || !is_numeric($this->id))) {
            $this->_helper->flashMessenger->addMessage(array('alert alert-error'=>"Please select a consumer to continue.") );
            $this->_forward('index');
        }
        
        $this->_setStandardViewValues($this->_action);
         
    }
    
    /* Step 1 let the user selected a client 
    */    
    public function indexAction() {
        
        $this->view->activeStep = 0;
        $this->view->percentComplete = $this->progressStep;
        $this->view->consumers = $this->_consumerModel->getByUserId($this->userId);
        $this->view->id = $this->id;
        
    }
    
    /* Step 2 Info
     * Get the genreal info form the user
     * We start collecting form data here
     * and saving it in a session until the
     * final step when the user creates and saves
     * the isp report to the db.
    */
    public function infoAction() {
        $userModel = new Default_Model_User;
        $this->view->user = $userModel->findById($this->userId);
        
        $consumer = new Consumer_Model_Consumer;
        $this->view->consumer = $consumer->findById($this->id);
        $this->view->coordinator = $consumer->getConsumerCoordinators(); 
        
        $this->view->activeStep = 1;
        $this->view->percentComplete = $this->progressStep * 2;
    }
    
    /*
    * Step 3 Medical
    * get any medical info needed for the client
    */
    public function medicalAction() {
        
        if( $this->getRequest()->isPost() ) {
           $this->_setInSession( $this->getRequest()->getPost(), 'info'); 
        }
         
        $consumer = new Consumer_Model_Consumer;
        $this->view->consumer = $consumer->findById($this->id);
        $this->view->insurance = $consumer->getConsumerInsurance(); 
        
        $examsModel = new Consumer_Model_ConsumersExams;
        $this->view->physicians = $examsModel->findByConsumerIdAndMapPhysician($this->id);     
        
        $medsModel = new Consumer_Model_ConsumersPharmaceuticals;
        $this->view->medications = $medsModel->findByConsumerIdAndMapPhysician($this->id);
        
        $allergiesModel = new Consumer_Model_ConsumersAllergies;
        $this->view->allergies = $allergiesModel->getByConsumerId($this->id);
        
        $hospitalized = new Consumer_Model_ConsumersHospitalized;
        $this->view->hospitalized = $hospitalized->getByConsumerId($this->id);
        
        $crudModel = new Default_Model_Crud; 
        $crudModel->setDbName('consumers_medical_status');
        $this->view->medicalStatus = $crudModel->_index(array('consumer_id = ?' => $this->id));
         
         $this->view->appointments = $consumer->getConsumerAppointments();
         
        $this->view->activeStep = 2;
        $this->view->percentComplete = $this->progressStep * 3;
        
    }

    
   /*
    * Step 3 SIRs
    * getSerious Incident Reports 
    */
    public function sirsAction() {
        
        if( $this->getRequest()->isPost() ) {
           $this->_setInSession( $this->getRequest()->getPost(), 'medical'); 
        }
        
        $crudModel = new Default_Model_Crud; 
        $crudModel->setDbName('consumers_sirs');
        $this->view->sirs = $crudModel->_index(array('consumer_id = ?' => $this->id));
        
        $this->view->activeStep = 3;
        $this->view->percentComplete = $this->progressStep * 4;
    }

    
    /*
    * Step 5 Goals
    * get goal info needed for the client
    */
    public function goalsAction() {
        
        if( $this->getRequest()->isPost() ) {
           $this->_setInSession( $this->getRequest()->getPost(), 'sirs'); 
        }
        
        $consumer = new Consumer_Model_Consumer;
        $this->view->consumer = $consumer->findById($this->id);
        $this->view->goals = $consumer->getConsumerGoals();
        
        $this->view->activeStep = 4;
        $this->view->percentComplete = $this->progressStep * 5;
    }

    /*
    * Step 6 Summary
    * get any medical info needed for the client
    */
    public function summaryAction() {
        
        if( $this->getRequest()->isPost() ) {
           $this->_setInSession( $this->getRequest()->getPost(), 'goals'); 
        }
        
        $this->view->activeStep = 5;
        $this->view->percentComplete = $this->progressStep * 6;
    }


  /*
    * Step 6 Finalize
    * get any medical info needed for the client
    */
    public function finalizeAction() {
        if( $this->getRequest()->isPost() ) {
           $this->_setInSession( $this->getRequest()->getPost(), 'summary'); 
        }
        
        $this->view->activeStep = 5;
        $this->view->percentComplete = $this->progressStep * 6;
        
        
         $this->_helper->viewRenderer->setNoRender(true);
         $this->_helper->layout->disableLayout();
        
      header("Content-type: application/vnd.ms-word");
       header("Content-Disposition: attachment;Filename=document_name.doc");

echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body>";
echo "<b>My first document</b>";
echo "</body>";
echo "</html>";
        
        
    }
    
    
    protected function _setStandardViewValues($namespace = 'default') {
        
        $this->view->formName = $this->formName;
        $this->view->steps = $this->steps;
        $this->view->id = (int)$this->id;
        $this->view->consumer = $this->_consumerModel->findByConsumerIdAndUserId($this->id, $this->userId);
        
        
        $storedData = $this->_getInSession($namespace);
        
        if(is_array($storedData)){
            foreach( $storedData as $k=>$v) {
                $this->view->$k = is_array($v) ? $v :  strip_tags($v);        
            }
        }
    }
    
    
    protected function _setInSession($data, $namespace) {
        $this->_sessionInfo->$namespace = $data;
    }
    
    protected function _getInSession($namespace) {
        if (isset($this->_sessionInfo->$namespace)) {
            return $this->_sessionInfo->$namespace;
        }
    }

    
    
    
}    
