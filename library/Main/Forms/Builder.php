<?php

/*
array(14) { ["SCHEMA_NAME"]=> NULL
            ["TABLE_NAME"]=> string(7) "clients"
            ["COLUMN_NAME"]=> string(5) "fname"
            ["COLUMN_POSITION"]=> int(2)
            ["DATA_TYPE"]=> string(7) "varchar" ["DEFAULT"]=> NULL ["NULLABLE"]=> bool(false) ["LENGTH"]=> string(2) "70" ["SCALE"]=> NULL ["PRECISION"]=> NULL ["UNSIGNED"]=> NULL ["PRIMARY"]=> bool(false) ["PRIMARY_POSITION"]=> NULL ["IDENTITY"]=> bool(false) } 

*/

class Main_Forms_Builder extends Zend_Form {

    public $field;
    public $elements = array();

    public function formElementsFromTable($tableName, array $fields) {
        
        $meta = Zend_Db_Table::getDefaultAdapter()->describeTable($tableName);
        
        foreach( $meta as $k=>$m ) {
        
            $col = $m['COLUMN_NAME'];
            
                    
            if( array_key_exists($col, $fields)) {
                $this->field = $fields[$col];
                $this->elements[$col] = array('meta'=>$m);
                $this->addElementValues($col, $m);
            }
        }
        
        return $this->elements;
    }
    
    
    public function formElementsFromArray(array $fields) {

        foreach( $fields as $k=>$f){
            
            $this->field = $f;
            $meta = isset( $f['meta'] ) && !empty($f['meta']) ? $f['meta'] : array('DATA_TYPE'=>'varchar');
            $this->elements[$k] = array();
            $this->addElementValues($k, $meta);
            
        }
    
   
    }
   
    public function appendHtml($e) {
        
        
        $html = array();
        
        if( isset($e['attributes'])){
            foreach( $e['attributes'] as $attr=>$val ) {
                $html[$attr] = $val;
            }
       }
        
        $element =  $this->createElement('hidden', 'html' ,array(
        'required' => false,
        'ignore' => true,
        'autoInsertNotEmptyValidator' => false,
        'decorators' => array(
            array(
                'HtmlTag', $html
            )
        )
    ));
              

 
        $element->clearValidators();
        $this->addElement($element);
       
        
    }
    
    public function createElements() {
        foreach( $this->elements as $e ) {

        $element =  $this->createElement($e['type'], $e['name'], array(
            'filters'    => $e['filters'],
            'validators' => $e['validators'],
            'required'   => $e['required'],
            'label'      => $e['label'],
            'ignore'     => $e['ignore']
        ));

        if( isset($e['disableDecorator']) ){
            
            if(is_array($e['disableDecorator'])){ 
            
                foreach(  $e['disableDecorator'] as $disableDecorator){
                   $element->removeDecorator($disableDecorator);
                }
            
            }else{
                $element->removeDecorator($e['disableDecorator']);
            }
        }

        if( isset( $e['multiOptions'] ) ) {
            $element->addMultiOptions($e['multiOptions']);
        }
        
        //if we have a enum field and no mulitoption override use the data in the enum.
        if(isset($e['meta']['DATA_TYPE']) && !isset( $e['multiOptions'] ) && strstr($e['meta']['DATA_TYPE'], 'enum') ) {
            $element->addMultiOptions($this->enumToArray($e['meta']['DATA_TYPE']));
        }
        
        
        if( $e['required'] == true ) {
            if(isset($e['attributes']['class'])) {
                $e['attributes']['class'] = $e['attributes']['class'].' __required';     
            }else{
                $e['attributes']['class'] = '__required'; 
            }
        }
        
        foreach( $e['attributes'] as $attr=>$val ) {
            $element->setAttrib($attr, $val);
        }
        
        if( isset( $e['decorators'] ) ) {
            $element->setDecorators($e['decorators']);
        }
        
        
        if (isset( $e['options'] ))  {
            $element->setOptions($e['options']);
        }
        
        
        if( isset( $e['default'] )) {
             $element->setValue($e['default']);
        }
        
        
         $this->addElement($element);

        }
    
    }
    
   
    public function addElementValues($col, $meta) {
        
        $this->setValue('name', $col,  $col);
        $this->setValue('type', $col, $this->metaToType($meta['DATA_TYPE']));
        $this->setValue('label', $col,  $this-> prettyText($col));
        $this->setValue('ignore', $col, false);
        $this->setValue('default', $col, null, true);
        $this->setValue('options', $col, null, true);
        $this->setValue('required', $col,  false);
        $this->setValue('attributes', $col,  array());
        $this->setValue('decorators', $col,  null, true);    
        $this->setValue('multiOptions', $col, null, true);
        $this->setValue('disableDecorator', $col, false, true);
        
        
        $this->setFilters($col, $meta);
        $this->setValidators($col, $meta);
        
    
    }
    

    public function setFilters($col, $meta) {
       
        if( isset( $this->field['filters'] ) ) {
            
            $this->elements[$col]['filters'] =  $this->field['filters'];
            return;
        }
       
        if(isset( $meta['DATA_TYPE'])
        && $meta['DATA_TYPE'] == 'varchar') {
            
            $this->elements[$col]['filters'] =  array('StringTrim');
            return;
        }
        
        
        $this->elements[$col]['filters'] = array();
    }
    
    public function setValidators($col, $meta) {
       
       if( isset( $this->field['validators'] ) ) {
            $this->elements[$col]['validators'] =  $this->field['validators'];
            return;    
        }
        
        
        $this->elements[$col]['validators'] = array();
        
        if( isset($meta['LENGTH']) && (int)$meta['LENGTH'] > 0 ) {
            $this->elements[$col]['validators'] = array( array('StringLength', false, array(0, (int)$meta['LENGTH'])));
        }
        
    }

    
    public function setValue($key, $col, $default = false, $doNotSet = false) {
       
       if( $this->isFieldValueSet( $key, $col ) ) {
            return;
        }     
    
        if( $doNotSet ) {
            return;
        }
         
        $this->elements[$col][$key] = $default;
    
    }
    
    public function isFieldValueSet($key, $col) {
    
        if( isset( $this->field["{$key}"] ) ) {        
            $this->elements[$col]["{$key}"] = $this->field["{$key}"];
            return true;
        } 
        
        return false;
    }
    
    
    public function prettyText($str) {
        
        return ucwords(str_replace("_", " ", $str));
    }
    

    public function metaToType($type) {
        
        $t = strtolower($type);
        
        if( strstr($t,"enum") ) {
            $t = 'enum';
        }
        
        
        switch($t){
            
        case"varchar":    
            return 'text';
        break;
        
        case "text":
            return 'textarea';
        break;
            
        case"enum":
            return "select";
        break;    
            
        default:
            return 'text';
        break;
            
        }
        
        
    }
    
    
    
    public function enumToArray($list) {
    
    
        preg_match('/^enum\((.*)\)$/', $list, $matches);
        $i = 1;
        foreach( explode(',', $matches[1]) as $value )
        {
             $enum[$value] = trim( $value, "'" );
             $i++;
        }
        
        return $enum;
    }
    
    
 
}




