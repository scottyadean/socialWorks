<?php
class Zend_View_Helper_TableFoot extends Zend_View_Helper_Abstract
{
    function TableFoot($content=""){
        
       return  "\n</tbody>\n<tfoot>{$content}\n</tfoot>\n</table>";
        
    }
}

