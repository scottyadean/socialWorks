<?php
class Consumer_NoteController  extends Zend_Controller_Action {
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $params;
   public $consumer_id;
   
   
   protected $_form;
   protected $_model;
   protected $_result;
   
      
   public $indexAction = '/consumer/notes/index/cid/';
   
   public function init() {
        
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->sort = $this->getRequest()->getParam('sort', false);
        $this->post = $this->getRequest()->isPost();
        $this->params = $this->getRequest()->getParams();
        $this->user_id = Zend_Auth::getInstance()->getIdentity()->id;
        $this->consumer_id = $this->getRequest()->getParam('cid', $this->getRequest()->getParam('consumer_id', null));
        
        $this->_model = new Consumer_Model_ConsumersNotes;       
        $this->_form  = new Application_Form_ConsumerNotes;
        
        if( empty($this->consumer_id) || !is_numeric($this->consumer_id) ){
            $this->_helper->flashMessenger->addMessage(array('alert alert-error'=>"Error: A consumer id required to add a note.") );  
            $this->_redirect('/consumers');
            
        }
        
        
        if($this->xhr) {
            
             $this->_helper->layout->disableLayout();
            
        }
    
    }
    
    /**
    * List a all notes
    * @param $cid
    * @return<array,json>
    */
    public function indexAction() {        
        
        $this->view->notes = $this->_model->findByConsumerId($this->consumer_id);
        $this->view->consumer_id = $this->consumer_id;
        
        if( $this->xhr ) {
            $this->_asJson($this->view->notes->toArray());   
        } 
        
    }
    
    /**
    *  get all notes by month
    *  
    */
    public function notesByDateAction() {
      
        $this->view->consumer_id = $this->consumer_id;
        
        $searchDate = $this->getRequest()->getParam('date', date("Y-m-d"));
        
        $year  = $this->getRequest()->getParam('year', false);
        if($year!= false) {
            $searchDate = $year."-"; 
        }
        $month = $this->getRequest()->getParam('month', false);
        if($month!= false) {
            $searchDate = $searchDate.$month."-"; 
        }
        $day   = $this->getRequest()->getParam('day', false); 
        if($day!= false) {
            $searchDate = $searchDate.$day; 
        }
     
    
        $this->view->notes = $this->_model->findByConsumerIdAndUserId($this->consumer_id,
                                                                      $this->user_id,
                                                                      $searchDate);
      
      if( $this->xhr ) {
         $this->_asJson($this->view->notes->toArray());   
      } 
        
      
    }
    
    /**
    * Create a new note
    * @param $cid
    * @return<array,json>
    */
   public function createAction(){
        
        $this->_form->customSubmitBtn = $this->xhr;
        $this->_form->build( $this->uri,
                             $this->consumer_id,
                             $this->user_id,
                             $this->id);
        
       
        
        $this->result = Main_Forms_Handler::onPost($this->_form ,
                                          $this->post,
                                          $this->_model,
                                          "createNote",
                                          $this->params,
                                          $this->_helper,
                                          $this->indexAction . $this->consumer_id,
                                          "Note created.",
                                          $this->xhr);  
    
        $this->_onSubmit();

    }
    
    /**
    * update a new note
    * @param $cid
    * @param $id
    * @return<array,json>
    */
   public function updateAction() {
  
    $this->_form->customSubmitBtn = $this->xhr;  
    $this->_form->build( $this->uri,
                         $this->consumer_id,
                         $this->user_id,
                         $this->id);
      
    $data = $this->_model->readNote($this->id)->toArray();
    $this->_form->populate($data);
    
    $this->result = Main_Forms_Handler::onPost($this->_form,
                                               $this->post,
                                               $this->_model,
                                               "updateNote",
                                               $this->params,
                                               $this->_helper,
                                               $this->indexAction . $this->consumer_id,
                                               "Note updated.",
                                               $this->xhr);
    
    
    $this->_onSubmit();
    
   }


 /**
    * Delete a new note
    * @param $cid
    * @param $id
    * @return<array,json>
    */   
   public function deleteAction() {
    
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        

        $del = $this->_model->deleteNote($this->id);

        if( $this->xhr ) {
            
            $this->_asJson(array('id' => $this->id,
                                'consumer_id' => $this->consumer_id,
                                'message' => 'Note Removed.',
                                'success' => $del));
         }else{
            
            $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Note Removed.") );  
            $this->_redirect($this->indexAction . $this->consumer_id);
         
         }
   }

   protected function _onSubmit() {
    
    if($this->xhr && $this->post && !empty($this->result)) {
            
            $this->result['values'] = $this->_model->readNote( (int)$this->result['id'] )->toArray();
            $this->_asJson($this->result);
            return;
        }
        
        if($this->xhr && $this->post) {
            $this->_asJson(array( 'success'=>false, 'id'=>$this->id, 'action'=>'no change', 'message'=>'form not changed', 'errors'=>array() ));
        }else{        
            $this->view->form  = $this->_form;
        }
    
   }
   
    protected function _asJson(array $data) {
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }

    
}
