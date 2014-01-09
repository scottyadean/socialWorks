<?php
class Consumer_NoteController  extends Zend_Controller_Action {
   public $id;
   public $xhr;
   public $uri;
   public $post;
   public $format;
   public $callback;
   public $consumer_id;
      
   public $indexAction = '/consumer/notes/index/';
   
   public function init() {
        
        $this->id = $this->getRequest()->getParam('id', null);
        $this->user_id = Zend_Auth::getInstance()->getIdentity()->id;
        $this->consumer_id = $this->getRequest()->getParam('cid', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->sort = $this->getRequest()->getParam('sort', false);
        $this->post = $this->getRequest()->isPost();
        $this->format = $this->getRequest()->getParam('format', false);
        $this->callback = $this->getRequest()->getParam('callback', null);
    
        if( empty($this->consumer_id) || !is_numeric($this->consumer_id) ){
            $this->_helper->flashMessenger->addMessage(array('alert alert-error'=>"Error: A consumer id required to add a note.") );  
            $this->_redirect('/consumers');
            
        }
    
    }
    
    /**
    * List a all notes
    * @param $cid
    * @return<array,json>
    */
    
    public function indexAction() {
        
        $notesModel = new Consumer_Model_ConsumersNotes;
        $this->view->notes = $notesModel->findByConsumerId($this->consumer_id);
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
      
        $notesModel = new Consumer_Model_ConsumersNotes;
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
     
    
        $this->view->notes = $notesModel->findByConsumerIdAndUserId($this->consumer_id,
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
   public function newAction(){
        
        $form  = new Application_Form_ConsumerNotes;
        
        $note = new Consumer_Model_ConsumersNotes;
        $form->build( $this->uri,
                      $this->consumer_id,
                      $this->user_id,
                      $this->id);
        if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
         
            if( $lastid = $note->createNote($form->getValues())){
                
               if( $this->xhr ) {
               
                  $values = $form->getValues();
                  $values['id'] = $lastid;
                  $values['created'] = isset($values['created']) ? $values['created'] : 'just now';
                  $this->_asJson(array('success'=>true,
                                       'msg'=>'New Note added.',
                                       'values'=>$values,
                                       'id'=>$lastid));   
               
               }else{
                  $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Note added.") );  
                  $this->_redirect($this->indexAction . $this->consumer_id);
               } 
                
                
            }
        }
     
        $this->view->form  = $form;
    }
    
    /**
    * update a new note
    * @param $cid
    * @param $id
    * @return<array,json>
    */
   public function editAction() {
    
    $form  = new Application_Form_ConsumerNotes;
    $note = new Consumer_Model_ConsumersNotes;
      
      $form->build( $this->uri,
                    $this->consumer_id,
                    $this->user_id,
                    $this->id);

    $notesData = $note->readNote($this->id)->toArray();
    $form->populate($notesData);
      
     if( $this->post && $form->isValid($this->getRequest()->getPost())  ) {
  
             if( $lastid = $note->updateNote($form->getValues())){
               
               
                 if( $this->xhr ) {
               
                  $values = $form->getValues();
                  $values['created'] = isset($values['created']) ? $values['created'] : 'updated';
                  $this->_asJson(array('success'=>true,
                                       'msg'=>'Note updated.',
                                       'values'=>$values,
                                       'id'=>$this->id));   
               
               }else{
                 
                 $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"Note updated.") );  
                 $this->_redirect($this->indexAction . $this->consumer_id);
                 
               } 
               
               
               
               
             }
         }
      
         $this->view->form  = $form;
    
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
        
        $exams = new Consumer_Model_ConsumersNotes;
        $del = $exams->deleteNote($this->id);

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


    protected function _asJson(array $data) {
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->getResponse()->setHeader('Content-type', 'application/json')
                            ->setBody(json_encode($data));
    }

    
}
