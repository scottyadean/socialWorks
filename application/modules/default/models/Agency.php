<?php
class Default_Model_Agency extends Zend_Db_Table_Abstract {

    protected $_name = 'agencies';
    protected $_primary = 'id';

    public function indexAgency() {
      $select = $this->select();
      return $this->fetchAll($select);    
    }
    
    public function createAgency($data) {
       return $this->insert($data);
    }

    public function readAgency($id) {
       return  $this->find($id)->current();
    }
   
    public function updateAgency($data) {
        $where = array('id = ?' => (int)$data['id']);
        return $this->update($data, $where);
    }
        
    public function deleteAgency($id) {
       $found = $this->find($id)->current()->toArray();
       if(count($found) > 0) {
            $where = array('id = ?' => (int)$id);
           return  $this->delete($where);
       }
        return false;
    }

    public function countByField($field, $val) {
       $select = $this->select()->where("{$field} = ?", $val);
       $select->from($this->_name,'COUNT(id) AS num');
       return $this->fetchRow($select)->num;
    }

}