<?php
class Zend_View_Helper_OnOff extends Zend_View_Helper_Abstract
{  
    function OnOff($value, $on='Y', $off='N') {
        
        return strtolower($value) == strtolower($on) ? $this->on($value) : $this->off($value);
        
    }
    
    
    function on($v) {
        
        return "<i class='ico ok' title='{$v}'> </i>";
        
    }
   
    function off($v) {
        
        return "<i class='ico fail' title='{$v}'> </i>";     
    }
   
    
}