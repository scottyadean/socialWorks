<?php

/** 
Columns:
   * `id`,
   * `consumer_id`,
   * `type`,
   * `medical_number`,
   * `medicare_number`,
   * `insurance_info`
*
*/

class Consumer_Model_ConsumersInsurance extends Zend_Db_Table_Abstract 
{
    protected $_name = 'consumers_insurance';
    protected $_primary = 'id';    
    protected $_referenceMap = array(
                        'Consumer' => array(
                            'column' => 'consumer_id',
                            'refColumn' => 'id',
                            'refTableClass' => 'Consumer_Model_Consumer'));
                   

    public function indexInsurance() {
      $select = $this->select();
      return $this->fetchAll($select);    
    }

    public function createInsurance($data) {
        
        if(isset($data['id'])) {
            unset($data['id']);
        }
        
        return $this->insert($data);
    }
    
    public function readInsurance($id) {
        return $this->find((int)$id)->current();
    }
    
    public function updateInsurance($data) {
        
        $where = array('id = ?' => (int)$data['id']);
        return $this->update($data, $where);
    }
    
    public function deleteInsurance($id) {     
        return $this->delete(array('id = ?' => (int)$id));
    }
   
    public function getByConsumerId($consumer_id) {
        $select = $this->select()->where( 'consumer_id = ?', (int)$consumer_id );
        return $this->fetchAll($select);
    }
    
   public function countInsurance($id) {
   
       $select = $this->select();
       $select->from($this->_name, array("num"=>"COUNT(id)"));
       $select->where("id = ?", $id);
       $result = $this->fetchRow($select);
       return (int)$result["num"];
   }
}
