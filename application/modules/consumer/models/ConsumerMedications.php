<?php
/** 
* `id`, `consumer_id`, `allergy`, `info`
*
*/
class Consumer_Model_ConsumerMedications extends Zend_Db_Table_Abstract 
{
    protected $_name = 'consumers_pharmaceuticals';
    protected $_primary = 'id'; 
    protected $_referenceMap = array(
                        'Consumer' => array(
                            'columns' => array('consumer_id'),
                            'refColumn' => 'id',
                            'refTableClass' => 'Consumer_Model_Consumer'));

                            
    public function indexMedication() {
        return $this->fetchAll();
    }                         
                            
    public function createMedication($data) {
        
        if(isset($data['id'])) {
            unset($data['id']);
        }
        
        return $this->insert($data);
    }
    
    public function readMedication($id) {
        
        return $this->find($id)->current();
    }
    
    public function updateMedication($data) {
        
        $where = array('id = ?' => (int)$data['id']);
        return $this->update($data, $where);
    }
    
    public function deleteMedication($id) {     
        
        return $this->delete(array('id = ?' => (int)$id));
    }
   
    public function getByConsumerId($consumer_id) {
        
        $select = $this->select()->where( 'consumer_id = ?', (int)$consumer_id );
        return $this->fetchAll($select);
    }

    
   public function countMedication($id) {
   
       $select = $this->select();
       $select->from($this->_name, array("num"=>"COUNT(id)"));
       $select->where("id = ?", $id);
       $result = $this->fetchRow($select);
       return (int)$result["num"];
   }
  

}