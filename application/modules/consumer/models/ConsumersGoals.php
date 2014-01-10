<?php

/** 
* Columns:
* id	int(11) AI PK
* consumers_id	int(11)
* goal	text
*
*/

class Consumer_Model_ConsumersGoals extends Zend_Db_Table_Abstract 
{
    protected $_name = 'consumers_goals';
    protected $_primary = 'id';    
    protected $_referenceMap = array(
                        'Consumer' => array(
                            'columns' => array('consumer_id'),
                            'refColumn' => 'id',
                            'refTableClass' => 'Consumer_Model_Consumer'));
                   
    public function createGoal($data) {
        return $this->insert($data);
    }
    
    public function readGoal($id) {
        return $this->find($id)->current();
    }
    
    public function updateGoal($data) {
        $where = array('id = ?' => (int)$data['id']);
        return $this->update($data, $where);
    }
    
    public function deleteGoal($id) {     
        return $this->delete(array('id = ?' => (int)$id));
    }
   
    public function findByConsumerId($consumer_id) {
        $select = $this->select()->where( 'consumer_id = ?', (int)$consumer_id );
        return $this->fetchAll($select);
    }

   public function countGoal($id) {
   
       $select = $this->select();
       $select->from($this->_name, array("num"=>"COUNT(id)"));
       $select->where("id = ?", $id);
       $result = $this->fetchRow($select);
       //return the int count.
       return (int)$result["num"];
   }
  
}
