<?php
class Default_Model_People extends Zend_Db_Table_Abstract {

    protected $_name = 'people';
    protected $_primary = 'id';

    
    public function _index(array $where = array()) {
        $select = $this->select();
        if(  count($where) > 0  ){
        
            foreach( $where as $k=>$w) {
                
                $select->where( "{$k}", $w );
                
            }    
            
        }
        
      return $this->fetchAll($select);    
    }
    
    public function _create($data) {
        if( isset($data['id']) ) {
            unset($data['id']);     
       }
       
       return $this->insert($data);
    }

    public function _read($id) {
       return  $this->find((int)$id)->current();
    }
    
    public function _update($data) {
        $where = array('id = ?' => (int)$data['id']);
        return $this->update($data, $where);
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