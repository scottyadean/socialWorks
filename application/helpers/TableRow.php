<?php
class Zend_View_Helper_TableRow extends Zend_View_Helper_Abstract
{
    function TableRow($rows, $attrs="", $template = false){

        
    
        $html = "\n<tr {$attrs}>";
        
        
        foreach($rows as $td){
            $html .= "\n<td>{$td}</td>";
        }
        
        $html .= "\n</tr>";

        return $html;
    }
}

