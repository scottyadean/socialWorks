<?php


class Media_IndexController extends Zend_Controller_Action {


    public $id;
    public $type;
    public $post;
    public $callback;
    
    public function init() {
    
        $this->id = $this->getRequest()->getParam('id', null);
        $this->type = $this->getRequest()->getParam('type', null);
        $this->post = $this->getRequest()->isPost();
        $this->callback = $this->getRequest()->getParam('callback', null);
        
    
    }
    

    public function indexAction() {
        $this->_helper->layout->disableLayout();
        
    }
    

    public function getAction() {
     
     
        $this->_helper->layout->disableLayout();
        $img = new Media_Model_Image;
        $this->_helper->viewRenderer->setNoRender(true);
        $res = $img->findbyIdAndType( $this->id, $this->type );
        
        if($res == null){
            $res = $img->findbyIdAndType( 0, 'c');
        }
        
        header("Content-type: image/jpg");
        print base64_decode( $res['img'] );
        exit;
        
  
    }
    
    
    public function createAction(){
        
        $this->_helper->layout->disableLayout();
        $form = new Application_Form_Image;
        
        if( $this->callback != null ){
            
            $path = "/image/db/create/".$this->id.'/'.$this->type.'/callback/'.$this->callback;
        
        }else{
            
            $path = "/image/db/create/".$this->id.'/'.$this->type;
        }
        
        $form->build($path);
        $upload = new Zend_File_Transfer_Adapter_Http();
        
        
        
        if( $this->post  ){
            
            
            $upload->addValidator('MimeType', false, array('image/gif', 'image/jpeg'));
            $upload->addValidator('Size',false,array('max' => '4MB','bytestring' => false));
            
            //check if its a valid post. 
            if($upload->isValid()) {   
                
                    
                    $output = Base_Functions_Assets::saveImageTo($upload, 'img', 'db', 200);
                    $img = new Media_Model_Image;
                    $img->create($this->id, $this->type , $output, 'jpg' , true );
                 
                   
                    if( $this->callback != null ){
                    
                       $this->_helper->viewRenderer->setNoRender(true);
                       print "<script>parent.".$this->callback."(true, 'New Image Added'); </script>"; 
                       return;
                            
                    }else{
                        
                       $this->_helper->flashMessenger->addMessage(array('alert alert-success'=>"New Image Added.") ); 
                        
                    }
                    
                    
            }else{
                
                if( $this->callback != null ){
                    $this->_helper->viewRenderer->setNoRender(true);
                    print "<script>parent.".$this->callback."(false, '".implode('<br />',$upload->getErrors())."'); </script>"; 
                    return;
                
                }else{
                    $this->_helper->flashMessenger->addMessage(array('alert alert-error'=>implode('<br />',$upload->getErrors())) ); 
                }    
            }  
            
            
        }
    
        $this->view->id = $this->id;
        $this->view->type = $this->type;
        $this->view->form = $form;
    }
    
    
    public function updateAction() {
    
    
    
    }
    
    
    
    public function deleteAction() {
    
    
    
    }
    
    
 }   
