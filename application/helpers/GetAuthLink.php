<?php
class Zend_View_Helper_GetAuthLink extends Zend_View_Helper_Abstract
{
   function GetAuthLink()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $username = $auth->getIdentity()->fname;
            return "<a href='/my/account' class='lite btn btn-small btn-primary'>".ucwords($username)."</a> 
                    <a href='/logout' class='btn btn-small'>Logout</a>";

        } 

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        
        if($controller == 'auth' && $action == 'index'){
            return '';
        }
        
        
        return "<a href='/login' class='btn btn-primary btn-small'>Login</a>";
    }

}
