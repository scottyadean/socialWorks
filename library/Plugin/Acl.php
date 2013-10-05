<?php

class Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
  
    private $_acl = null;
    private $_fbAuth = false;
 
    public function __construct(Zend_Acl $acl) {   
        $this->_acl = $acl;
    }
 
    public function preDispatch(Zend_Controller_Request_Abstract $request) {   
        
        
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        
        try{
        
            $auth = Zend_Auth::getInstance();
            $role = isset($auth->getIdentity()->role) ? Main_Acl::Roles($auth->getIdentity()->role) : 'guest';
        
        }catch(Exception $e){
            
            $role = Main_Acl::Roles(0);            
        }
        

        $resource = "{$module}-{$controller}";
    
        //print "roll: $role, /$module/$controller/$action | ";

        if(!$this->_acl->isAllowed($role, $resource, 'view')) {
          $reloc =  $request->getHeader('referer');
          //If the user has no access we send him elsewhere by changing the request
          $request->setModuleName('default')->setControllerName('auth')->setActionName('index')->setParams(array('reloc'=>urlencode($reloc)));
        }


    }
}

