<?php

class Tools_AsyncController extends Zend_Controller_Action {
    
    protected $_user;
    
    public function init() {
        
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        if(  !$this->getRequest()->isXmlHttpRequest() ) {
            throw new Exception('bad request'); exit;
        }
        
        
        $this->_user = Zend_Auth::getInstance()->getIdentity();
        
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
    
    
    public function chatAction() {
        
        $offsetId = $lastId = $this->getRequest()->getParam('offset', 0);
        $response = Default_Model_Temp::selectAllByAccountId($this->_user->account_id, $offsetId);
        $lastId = Default_Model_Temp::countTemp($this->_user->account_id);
        
        $this->getResponse()
            ->setHeader('Content-type', 'application/json')
            ->setBody(json_encode(array("messages" => $response, "offset"=>$lastId))); 

    }


    public function chatInputAction() {
        //`pointer`, `status`, `data`, `action`, `account_id`
        $txt = trim($this->getRequest()->getParam('msg', null));
        $txt = strip_tags($txt, "<p><hr><strong><a><br><3>");
        $txt = $this->chatSmiles($txt);
        
        $txt = $this->isUrl( $txt );
        
        
        $data = array( "pointer"    => $this->_user->username,
                       "account_id" => $this->_user->account_id,
                       "status"     => 'public',
                       "action"     => 'chat',
                       "data"       => $txt);
        
        $response = false;
        
        if( !empty( $txt ) ) {
            $response = Default_Model_Temp::insertTemp($data);    
        }
        
        $this->getResponse()
            ->setHeader('Content-type', 'application/json')
            ->setBody(json_encode(array('success' => $response,
                                        'data'=>$data,
                                        'txt'=>$txt )));
        
    }

    public function chatSmiles($txt) {
        
        $smiles = $this->smiles();    
        foreach($smiles as $k=>$s){
            $txt = str_replace($k, "<i>$s</i>", $txt);
        }
    
        return $txt;
        
        
    }
    
    public function isUrl($txt) {
    
        if( filter_var($txt, FILTER_VALIDATE_URL) ) {
            
            $txt = "<a href='{$txt}' target='_blank' >{$txt}</a>";
            
        }    
        
        return $txt;
    }
    
    public function smiles() {
        
        return array(   "%:)" => "&#x263B;",
                        ":)" => "&#x263A;",
                       ":("  => "&#x2639;",
                       ":x"  => "&#x2620;",
                       "(69)"=> "&#x262F;",
                       "/|\\" => "&#x262E;",
                       "(*)" => "&#x2741;",
                       "-8-" => "&#x2603;",
                       "^^^" => "&#x2655;",
                       ":-/"  =>"&#x30C5;",
                       "./)" => "&#x270D;",
                       "<3>"   => "&#x2661;");
        
    }
    
    
    
}    