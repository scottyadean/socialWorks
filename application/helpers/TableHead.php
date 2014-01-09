<?php
class Zend_View_Helper_TableHead extends Zend_View_Helper_Abstract
{
    function TableHead(array $ths, $class="", $id=""){
        
        if( $id != "" ) {
            
            $id = "id='{$id}'";
            
        }
        
        $html  = "<table class='{$class}' {$id}>";
        $html .= "\n<thead>";
        $html .= "\n<tr>";
        
        foreach($ths as $th){
            
            $html .= "\n<th>".ucwords($th)."</th>";
        
        }
        
        $html .= "\n</tr>";
        $html .= "\n</thead>";
        $html .= "\n<tbody>";
        return $html;
    }
}

