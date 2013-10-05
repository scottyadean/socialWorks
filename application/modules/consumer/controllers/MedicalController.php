<?php

class Consumer_MedicalController extends Zend_Controller_Action {


    public $id;
    public $sort;
    public $post;
    public $format;
    public $callback;

    public function init() {
    
        $this->id = $this->getRequest()->getParam('consumer', $this->getRequest()->getParam('id', null));
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->sort = $this->getRequest()->getParam('sort', false);
        $this->post = $this->getRequest()->isPost();
        $this->format = $this->getRequest()->getParam('format', false);
        $this->callback = $this->getRequest()->getParam('callback', null);
        
    }

    public function indexAction() {

        if($this->xhr) {
            $this->_helper->layout->disableLayout();
        }
        
        if($this->format == 'json') {
            $this->_helper->viewRenderer->setNoRender(true);
        }    
        
        if( $this->id != null ) {
            
            $meds = new Consumer_Model_ConsumersMedical;
            $res = $meds->findByConsumerId($this->id);
            
        }
        
        
        $this->view->render =  $this->getRequest()->getParam('render', false);
        $this->view->records = $res;
        
       
    }
    
    public function newAction() {
       
        $form = new Application_Form_ConsumersMedical;
       
        if( $this->xhr ) {
             $this->_helper->layout->disableLayout();
        }    
        
        if( $this->post && $form->isValid($this->getRequest()->getPost()) ) {
            
            if(Main_Auth::process($form->getValues())){
               
               $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Medical appointment added.") );  
               $this->_helper->redirector->gotoUrl($this->uri);
            
            }
        
        }

       $form->build($this->uri, $this->id);
       $this->view->form = $form;
       
    }



    
}
    