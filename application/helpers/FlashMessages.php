<?php

class Zend_View_Helper_FlashMessages extends Zend_View_Helper_Abstract
{
    public function flashMessages()
    {
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
        $output = '';
       
        if (!empty($messages)) {
            $output .= '<ul id="flash-messages">';
       
            if(is_array($messages) ){
                foreach ($messages as $message) {
                    $output .= '<li class="' . key($message) . '">' . current($message) . '</li>';
                }
            }else{
            
                $output .= '<li class="message">' . current($messages) . '</li>';
            }
       
            $output .= '</ul>';
        }
       
        return $output;
    }
}
