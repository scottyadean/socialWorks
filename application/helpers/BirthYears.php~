<?php

//date in YYYY-MM-DD format


class Zend_View_Helper_BirthYears extends Zend_View_Helper_Abstract
{
    function BirthYears($birthday){

        $bday = new DateTime($birthday);
        $today = new DateTime(date('F.j.Y', time())); 
        $diff = $today->diff($bday);
        return $diff->y;
 
    }
         
         
    
}

