<?php
class Consumer_Model_ConsumersPharmaceuticals extends Zend_Db_Table_Abstract {

    protected $_name = 'consumers_pharmaceuticals';
    protected $_primary = 'id';
    
    protected $_referenceMap = array(
                              'ConsumerUser' => array(
                              'columns' => array('consumer_id'),
                              'refTableClass'=>'Consumer_Model_Consumer',
                              'refColumns' => array('id')
                             ),

                              'PharmaceuticalConsumer' => array(
                              'columns'=>array('pharmaceutical_id'),
                              'refTableClass'=>'Default_Model_Pharmaceutical',
                              'refColumns' => array('id')
                             )
                              
                              );
                             
    public function assign($consumer_id, $pharmaceutical_id, array $extenedData = array()) {
            
       $count = $this->count( $consumer_id, $pharmaceutical_id );
       
       if( $count == 0 ){
        $data = array('consumer_id'=>$consumer_id,
                      'pharmaceutical_id'=>$pharmaceutical_id);
        
        if( count($extenedData) > 0 ){
            array_merge($data, $extenedData); 
        }

        return $this->insert($data);
       }

       return false;

    }
    
    
    public function remove($consumer_id, $pharmaceutical_id) {
            
       $count = $this->count( $consumer_id, $pharmaceutical_id );
      
       if( $count > 0 ){
      
           return $this->delete(array('consumer_id = ?' => $consumer_id, 'pharmaceutical_id = ?' =>$pharmaceutical_id));
       }

       return false;

    }
   
   
     public function findById($pharmaceutical_id) {
     
           $select = $this->select();
           $select->where( 'pharmaceutical_id = ?', $pharmaceutical_id );
    
           return $this->fetchAll($select);
    
    }
   
    
   public function count($consumer_id, $pharmaceutical_id) {
   
       $select = $this->select();
       $select->from($this->_name, array("num"=>"COUNT(consumer_id)", "count_id"=>"consumer_id"));
       $select->where("consumer_id = ?", $consumer_id)->where( 'pharmaceutical_id = ?', $pharmaceutical_id );
       $result = $this->fetchRow($select);
     
       //return the int count.
       return (int)$result["num"];
      
   }
    
    public function createPharmaceutical($data) {
        
        if(isset($data['id'])) {
            unset($data['id']);
        }
        
        return $this->insert($data);
    }
    
    public function readPharmaceutical($id) {
        return $this->find((int)$id)->current();
    }
    public function updatePharmaceutical($data) {
        
        $where = array('id = ?' => (int)$data['id']);
        return $this->update($data, $where);
    }
    
    public function deletePharmaceutical($id) {     
        return $this->delete(array('id = ?' => (int)$id));
    }
   
    public function findByConsumerId($consumer_id, $where=array()) {
        
          $select = $this->select()->where( 'consumer_id = ?', (int)$consumer_id );
          
          if(is_array($where)) {
            
            foreach($where as $k=>$w) {
                $select->where( "{$k}", $w );
            }
            
          }
          
          return $this->fetchAll($select);
    }

    public function findByPhysicianId($physician_id) {
          $select = $this->select();
          $select->where( 'physician_id = ?', $physician_id );
          return $this->fetchAll($select);
    }
   
    public function findByConsumerAndPhysicianId($consumer_id, $physician_id) {
       $select = $this->select();
       $select->where("consumer_id = ?", $consumer_id)->where( 'physician_id = ?', $physician_id );
       return $this->fetchRow($select); 
    }
    
    
    public function findByConsumerIdAndMapPhysician($consumer_id) {
        
        $consumer = new Consumer_Model_Consumer;
        $consumerInfo = $consumer->findById($consumer_id);
        $consumerUsers = $consumer->getConsumerUsers();    
        $physicians = $consumer->getConsumerPhysicians();
        
  
        $pharmaceuticals = new Default_Model_Pharmaceutical;
        $pharma = $pharmaceuticals->indexPharmaceutical()->toArray();
       
        $meds = $this->findByConsumerId($consumer_id, array( 'physician_id > ?' => 0 ))->toArray();
        foreach($meds as $key=>$med ) {
            
             
            foreach( $physicians as $k=>$p ) {
                if( $med['physician_id'] == $p['id'] ){
                    $meds[$key]['physician'] = $p;
                    continue;
                }
            }
            
            foreach($pharma as $kh=>$ph ) {
               
                if( $med['pharmaceutical_id'] == $ph['id'] ){
                    $meds[$key]['pharmaceutical'] = $ph;
                    continue;
                }
            }
               
        }
        
        return $meds; 
    }
    
   public function countPharmaceutical($id) {
   
       $select = $this->select();
       $select->from($this->_name, array("num"=>"COUNT(id)"));
       $select->where("id = ?", $id);
       $result = $this->fetchRow($select);
       //return the int count.
       return (int)$result["num"];
   }
  

      
}
