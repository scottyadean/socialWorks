<?php

class Utils_AsyncController extends Zend_Controller_Action {
    
    public function init() {
        
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        if(  !$this->getRequest()->isXmlHttpRequest() ) {
            throw new Exception('bad request'); exit;
        }
        
    }
    
    public function indexAction() {
        
        print "index list";
        
    }
    
    
    public function distanceAction() {
        
       $params = array('units' => 'imperial',
                        'sensor'=>'false',
                        'mode'=>'driving',
                        'origins'=> $this->getRequest()->getParam('start', null),
                        'destinations'=>$this->getRequest()->getParam('end',null));
        
        $url = 'http://maps.googleapis.com/maps/api/distancematrix/json' . '?' . http_build_query($params);
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        
        $this->getResponse()
        ->setHeader('Content-type', 'application/json')
        ->setBody($response);
        
        
    }
    
}    