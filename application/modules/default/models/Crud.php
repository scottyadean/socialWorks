<?php
class Default_Model_Crud extends Zend_Db_Table_Abstract {

    protected $_name = '_not_set_';
    protected $_primary = 'id';

    public function setPrimary(  $primary ) {
        $this->_primary = $primary;
    }

    public function setDbName(  $name ) {
        $this->_name = trim($name);
        return $name;
    }
    
    public function getInfo() {
        
        return $this->info();
        
    }
    
    
    public function cleanData($data) {
        
       $info = $this->getInfo();
       return array_intersect_key($data, $info['metadata']);  
        
    }
    
    
    public function crudData($ex) {
        
       if( !is_array($ex)) {
            $ex = explode(",",$ex);
       }
        
       $f = $this->getInfo();
       $v = array();

       foreach( $f['metadata'] as $k=>$m ){
        $feilds[] = $k;
        
        if(!in_array((string)$k, $ex )){
              $v[] = $k;
             
         }
       
       }
       
       $d = new stdClass;
       $d->fields = $feilds;
       $d->excluded = $ex;
       $d->visible = $v;
       $d->name = $this->_name;
       $d->primary = $this->_primary;
       $d->results = $this->_index();
       
       return $d;
    }
    
    
    public function _index() {
      return $this->fetchAll($this->select());    
    }
    
    public function _create($data) {
        if( isset($data['id']) ) {
            unset($data['id']);     
       }
       

       return $this->insert($this->cleanData($data));
    }

    public function _read($id) {
       return  $this->find((int)$id)->current();
    }
    
    public function _update($data) {
        $where = array('id = ?' => (int)$data['id']);
        return $this->update($this->cleanData($data), $where);
    }
        

    public function _delete($id) {
       $found = $this->find((int)$id)->current()->toArray();
       if(count($found) > 0) {
            $where = array('id = ?' => (int)$id);
           return  $this->delete($where);
       }
        return false;
    }

    public function _findByColValue($find = array( "id = ?" , 0 )) {
       $select = $this->select();
       
       foreach( $find as $k=>$v ) {
        $select->where("{$k}", $v);
       }
       
       
       return $this->fetchRow($select);
    }
    
    public function countByField($field, $val) {
       $select = $this->select()->where("{$field} = ?", $val);
       $select->from($this->_name,'COUNT(id) AS num');
       return $this->fetchRow($select)->num;
    }

    
}