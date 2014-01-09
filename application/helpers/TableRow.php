<?php
class Zend_View_Helper_TableRow extends Zend_View_Helper_Abstract
{
    function TableRow($rows, $opts=""){

        $html = "\n<tr {$opts}>";
        
        foreach($rows as $td){
        
            $html .= "\n<td>{$td}</td>";
        
        }
        
        $html .= "\n</tr>";

        return $html;
    }
}

