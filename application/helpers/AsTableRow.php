<?php
class Zend_View_Helper_AsTableRow extends Zend_View_Helper_Abstract
{
    function AsTableRow($rows){

        $html = "<tr>";
        foreach($rows as $td)
            $html .= "<td>{$td}</td>";
        $html .= "</tr>";

        return $html;
    }
}

