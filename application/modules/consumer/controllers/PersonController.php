<?php
class Consumer_PersonController extends Zend_Controller_Action {    

    public $id;
    public $xhr;
    public $uri;
    public $post;
    public $type;
    public $format;
    public $callback;
    
    protected $_model;
    
    public function init() {
    
        $this->_model = new Default_Model_Pharmaceutical;
        $this->id = $this->getRequest()->getParam('id', null);
        $this->xhr = $this->getRequest()->isXmlHttpRequest();
        $this->uri = $this->getRequest()->getRequestUri();
        $this->sort = $this->getRequest()->getParam('sort', false);
        $this->post = $this->getRequest()->isPost();
        $this->type = $this->getRequest()->getParam('type', null);
        $this->format = $this->getRequest()->getParam('format', false);
        $this->callback = $this->getRequest()->getParam('callback', null);
    
    }
    
    
    public function assigneesAction() {
    
        $pid = $this->getRequest()->getParam('person', null);
        $do  = $this->getRequest()->getParam('do', null);
         
        if( $this->post && !is_null($pid) && !is_null($do) ){
            
            $consumerPersons = new Consumer_Model_ConsumersPersons;
            
            if( $do == 'remove' ) {
                $res = $consumerPersons->remove($this->id, $pid);
            }

            if( $do == 'assign' ) {
                $res = $consumerPersons->assign($this->id, $pid);
            }

            
            $this->_asJson(array('success'=>(bool)$res,
                                 'do'=>$do,
                                 'focus'=>$pid,
                                 'type'=>$this->type,
                                 'consumer'=>$this->id));

            return true;
        }

        if( !is_null($this->id) ){
            
            $this->_helper->layout->disableLayout();
            $this->view->id = $this->id;
            
            $c = new Consumer_Model_Consumer;
            $consumerInfo = $c->findById($this->id);
            $this->view->assigned =  $c->getConsumerPersons( );
            
            $p = new Default_Model_People;
            $this->view->people = $p->_index(array('type = ?'=>$this->type))->toArray();
            
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