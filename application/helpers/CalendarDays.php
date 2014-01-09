<?php
class Zend_View_Helper_CalendarDays extends Zend_View_Helper_Abstract
{
    
    /*
    * @param $month<int> two digit month
    * @param $year<int> four digit year
    */
    function CalendarDays($month, $year, $day, $customClass = "") {
       
        $timestamp = mktime(0,0,0,$month,1,$year);
        $maxday = date("t",$timestamp);
        $thismonth = getdate ($timestamp);
        $startday = $thismonth['wday'];
        $html = '';
        
        for ($i=0; $i<($maxday+$startday); $i++) {
            
            if(($i % 7) == 0 ) {
                $html .= "<tr>";
            }
            
            if($i < $startday){
                $html .= "<td></td>";
            }else{
                
                $d = $dd = ($i - $startday + 1);
                
                if( $dd < 10 ) {
                    $dd = "0".$d;
                }
                $today = $day == $dd ? 'highlight' : ''; 
                $html .= "<td data-day='{$dd}' class='{$customClass} {$today}'>{$d}</td>";
            }
            
            if(($i % 7) == 6 ) {
                $html .= "</tr>";
            }
            
        }
        return $html;       
       
    }
}


