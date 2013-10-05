<?php 
class Base_Functions_Sessions
{
   

    public static function temp_value($value ='') {
        $temp_value = new Zend_Session_Namespace('temp_values');
           
        if (!isset($temp_value->values))
            $temp_value->values = array();

        if(isset($value) && $value == ':get')
            return  $temp_value->values;


        $temp_value->values = $value;
         
        return  $temp_value->values;

    }
    
    public static function cumulative_value($name, $value) {
        $values = new Zend_Session_Namespace($name);
        
        if (!isset($values->values))
            $values->values = array();

        if($value == ':get')
            return $values->values;

        $values->values[] = $value;
        
        return $values->values;

    }
    

    public static function update_session_value_by_name($name, $value)
    {
        $values = new Zend_Session_Namespace($name);
        
        if (!isset($values->values))
            $values->values = array();

        $values->values = $value;
        
        return true;
    }    
    
    
    
} 
