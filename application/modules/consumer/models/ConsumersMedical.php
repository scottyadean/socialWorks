<?php
class Consumer_Model_ConsumersMedical extends Zend_Db_Table_Abstract 
{
    protected $_name = 'consumers_medicals';
    protected $_primary = 'id';
 
 
     public function findAll() {
       $select = $this->select();   
       return $this->fetchAll($select);
    }
    
 
     public function findByConsumerId($id) {
       $select = $this->select()->where('consumer_id = ?', $id);
       return $this->fetchAll($select);
    }
    
    public function findByColumn($column, $value) {    
        $select = $this->select();
        $select->where( $column.' = ?', $value );
        return $this->fetchAll($select);
    }
 
 
    
}