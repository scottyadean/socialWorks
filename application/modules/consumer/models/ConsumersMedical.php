<?php
class Consumer_Model_ConsumersMedical extends Zend_Db_Table_Abstract 
{
    protected $_name = 'consumers_medicals';
    protected $_primary = 'id';
  
    protected $_referenceMap = array(
                     'Consumer' => array(
                     'columns' => array('consumer_id'),
                     'refColumn' => 'id',
                     'refTableClass' => 'Consumer_Model_Consumer'));
 
    
    public function createAppointment($data) {
       if(isset($data['id'])) {
            unset($data['id']);
       }
       return $this->insert($data);
    }
 
    public function readAppointment($id) {
       
       return $this->find((int)$id)->current();
    }
 
   
   public function updateAppointment($data) {
       
       $where = array('id = ?' => (int)$data['id']);
       return $this->update($data, $where);
    
    }

    public function deleteAppointment($id) {     
        return $this->delete(array('id = ?' => (int)$id));
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