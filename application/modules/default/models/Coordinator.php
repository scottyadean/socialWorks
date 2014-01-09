<?php
class Default_Model_Coordinator extends Zend_Db_Table_Abstract {

    protected $_name = 'coordinators';
    protected $_primary = 'id';

    public function indexCoordinator() {
      $select = $this->select();
      return $this->fetchAll($select);    
    }
    
    public function createCoordinator($data) {
       return $this->insert($data);
    }

    public function updateCoordinator($data) {
        $where = array('id = ?' => (int)$data['id']);
        return $this->update($data, $where);
    }
        
    public function readCoordinator($id) {
       return  $this->find($id)->current();
    }
    
    public function deleteCoordinator($id) {
       $found = $this->find($id)->current()->toArray();
       if(count($found) > 0) {
            $where = array('id = ?' => (int)$id);
           return  $this->delete($where);
       }
        return false;
    }

    public function findByCoordinatorName($lname) {
       $select = $this->select()->where("lname Like %?%", $lname);
       return $this->fetchRow($select);
    }
    
    public function countByField($field, $val) {
       $select = $this->select()->where("{$field} = ?", $val);
       $select->from($this->_name,'COUNT(id) AS num');
       return $this->fetchRow($select)->num;
    }

    public function findAssociations($id) {    
        $assingedToConsumer = new Consumer_Model_ConsumersCoordinators;
        $assingedConsumer = $assingedToConsumer->findById($id)->toArray();
        return $assingedConsumer;
    }
}