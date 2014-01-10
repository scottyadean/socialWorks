<?php

/** 
* Columns:
* id	int(11) AI PK
* consumers_id	int(11)
* note Text
*
*/
class Consumer_Model_ConsumersNotes extends Zend_Db_Table_Abstract 
{
    protected $_name = 'consumers_notes';
    protected $_primary = 'id';    
    protected $_referenceMap = array(
                        'Consumer' => array(
                            'columns' => array('consumer_id'),
                            'refColumn' => 'id',
                            'refTableClass' => 'Consumer_Model_Consumer'));
                   
    public function createNote($data) {
        
        $data = $this->_cleanData($data);
        return $this->insert($data);
    }
    
    public function readNote($id) {
        return $this->find($id)->current();
    }
    
    public function updateNote($data) {
        $data = $this->_cleanData($data);
        $where = array('id = ?' => (int)$data['id']);
        return $this->update($data, $where);
    }
    
    public function deleteNote($id) {     
        return $this->delete(array('id = ?' => (int)$id));
    }
   
    public function findByConsumerId($consumer_id) {
        
          $select = $this->select()->where( 'consumer_id = ?', (int)$consumer_id );
          return $this->fetchAll($select);
    }

    public function findByConsumerIdAndUserId($consumer_id, $user_id, $searchDate=false) {
        
        $select = $this->select()->where( 'consumer_id = ?', (int)$consumer_id );
        
        if($searchDate) {
            $select->where('created LIKE ?', "{$searchDate}%");
        }
        
        
        return $this->fetchAll($select);
        
    }
    

    
   public function countNote($id) {
   
       $select = $this->select();
       $select->from($this->_name, array("num"=>"COUNT(id)"));
       $select->where("id = ?", $id);
       $result = $this->fetchRow($select);
       //return the int count.
       return (int)$result["num"];
   }
  
  
  protected function _cleanData($data) {
    
        if(isset($data['created'])) {
            $timestamp = strtotime($data['created']." ".date("H:i:s"));
            $data['created'] = date("Y-m-d H:i:s", $timestamp);
        }
        if(array_key_exists('created',$data) && empty($data['created'])){
            unset($data['created']);
        }
        
    return $data;
  }
  
  
}
