<?php
class Zend_View_Helper_JsHelper extends Zend_View_Helper_Abstract
{  
    function jsHelper() {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $file_uri = 'media/js/' . $request->getControllerName() . '/' . $request->getActionName() . '.js';

        if (file_exists($file_uri)) {
            $this->view->headScript()->appendFile('/' . $file_uri);
        }
    }
}