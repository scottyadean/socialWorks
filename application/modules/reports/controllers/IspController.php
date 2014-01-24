<?php
class Reports_IspController extends Zend_Controller_Action {

    public $steps = array('Start', 'Info', 'Medical', 'SIRs', 'Goals', 'Programs', 'Summary', 'Finalize');
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
        $this->view->persons = $consumer->getConsumerPersons();   
        
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
    * Step 4 SIRs
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
    * Step 5 products
    * get programs 
    */
    public function programsAction() {
        
        if( $this->getRequest()->isPost() ) {
           $this->_setInSession( $this->getRequest()->getPost(), 'goals'); 
        }
        
        $programModel = new Consumer_Model_ConsumerPrograms;
        $this->view->programs = $programModel->_index(array("consumer_id = ?" => $this->id));
      
        $this->view->activeStep = 5;
        $this->view->percentComplete = $this->progressStep * 6;
    }    
        

    /*
    * Step 7 Summary
    * get any medical info needed for the client
    */
    public function summaryAction() {
        
        if( $this->getRequest()->isPost() ) {
           $this->_setInSession( $this->getRequest()->getPost(), 'programs'); 
        }
       
        $userModel = new Default_Model_User;
        $this->view->user = $userModel->findById($this->userId);
        
        $this->view->activeStep = 6;
        $this->view->percentComplete = $this->progressStep * 7;
    }


  /*
    * Step 8 Finalize
    * get any medical info needed for the client
    */
    public function finalizeAction() {
       
       $word = $this->getRequest()->getParam('word', false); 
        
       if( $this->getRequest()->isPost() ) {
            $this->_setInSession( $this->getRequest()->getPost(), 'summary'); 
        }
       
       if($word){
        $this->_helper->layout->disableLayout();
        
        
         
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=document_name.doc");
        
        }
        
        $userModel = new Default_Model_User;
        $this->view->user = $userModel->findById($this->userId);
        
        $consumer = new Consumer_Model_Consumer;
        $this->view->consumer = $consumer->findById($this->id);
        $this->view->coordinator = $consumer->getConsumerCoordinators(); 
        $this->view->persons = $consumer->getConsumerPersons();   
        $this->view->insurance = $consumer->getConsumerInsurance(); 
        $this->view->goals = $consumer->getConsumerGoals();
        
        $medsModel = new Consumer_Model_ConsumersPharmaceuticals;
        $this->view->medications = $medsModel->joinOnPharmaId($this->id);
        
        $allergiesModel = new Consumer_Model_ConsumersAllergies;
        $this->view->allergies = $allergiesModel->getByConsumerId($this->id);
        
        $hospitalized = new Consumer_Model_ConsumersHospitalized;
        $this->view->hospitalized = $hospitalized->getByConsumerId($this->id);
        
        
        $crudModel = new Default_Model_Crud; 
        $crudModel->setDbName('consumers_medical_status');
        $this->view->medicalStatus = $crudModel->_index(array('consumer_id = ?' => $this->id));
         
        $crudModel = new Default_Model_Crud; 
        $crudModel->setDbName('consumers_sirs');
        $this->view->sirs = $crudModel->_index(array('consumer_id = ?' => $this->id)); 
       
        $programs = new Consumer_Model_ConsumerPrograms;
        $this->view->programs = $programs->mapDirectorTitle($this->id);
        
        
        $keys = array('summary', 'programs','goals','sirs', 'medical', 'info');
        $data = array();
        foreach($keys as $namespace) {
            $data["_{$namespace}_"] = $this->_getInSession($namespace);
        }
        
        if(isset($data["_medical_"])
           &&  isset($data["_medical_"]["js-drag-selected-exams"])
           && !empty($data["_medical_"]["js-drag-selected-exams"])){
            $exams = new Consumer_Model_ConsumersExams;
            $ids = explode(",", $data["_medical_"]["js-drag-selected-exams"]);
            $this->view->exams = $exams->findByIds($ids);
        }
        
        $this->view->data = $data;
        $this->view->word = $word;
        
        
        $this->view->activeStep = 7;
        $this->view->percentComplete = $this->progressStep * 8;
        
        
        
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
