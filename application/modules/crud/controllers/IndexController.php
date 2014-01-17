<?php
class Crud_IndexController extends Zend_Controller_Action {


    public $model;
    public $dir;
    
    public $dataDir;
    public $dirFound;
    public $dirWrite;


    public function init() {
        $this->dir = APPLICATION_PATH."/../src/build";
        $this->dirFound = is_dir($this->dir);
        $this->dirWrite = is_writable($this->dir);
        $this->dataDir = APPLICATION_PATH."/../src/data";
    }


    public function indexAction() {

        $this->view->dir = $this->dir;
        $this->view->dirFound = $this->dirFound;
        $this->view->dirWrite = $this->dirWrite; 
        
    }
        
    public function createAction() {

            if( $this->getRequest()->isPost() ) {
                
                    $this->model = $this->getRequest()->getParam('model', null);
                
                    if( !empty( $this->model ) && !is_dir($this->dir. "/". $this->model)) {
                                                
                        try{
                        
                            mkdir($this->dir. "/". $this->model);
                            
                        }catch(Exception $e) {
                            
                            throw new Exception( "Unable to create product dir. ". $e->getMessage() );
                        }
                    }
                
                $this->createData();
                
            }
    
    }
    
    
    private function createData() {
                
        mkdir($this->dir. "/". $this->model."/models");
        mkdir($this->dir. "/". $this->model."/views");
        mkdir($this->dir. "/". $this->model."/contorllers");
        mkdir($this->dir. "/". $this->model."/views/scripts");
        
        file_put_contents($this->dir. "/". $this->model."/views/scripts/index.phtml", $this->createPage('index'));
        file_put_contents($this->dir. "/". $this->model."/views/scripts/create.phtml", $this->createPage('create'));
        file_put_contents($this->dir. "/". $this->model."/views/scripts/read.phtml", $this->createPage('read'));
        file_put_contents($this->dir. "/". $this->model."/views/scripts/update.phtml", $this->createPage('update'));
        file_put_contents($this->dir. "/". $this->model."/views/scripts/delete.phtml", $this->createPage('delete'));
        
        
    }
    
    
    
    private function createPage($page="icrud") {
            
            $data = "";
        
            switch( $page ) {
                
                case "index":
                case "read":
                case "create":   
                    $data = file_get_contents( $this->dataDir. "/{$page}.phtml");
                    $data = str_replace("{model}", $this->model, $data);
                    $data = str_replace("{abr}", substr($this->model, 0, 1), $data);
                break;
                
            }
            
            return $data;
        
        
    }
    
    
    
    
   
}
