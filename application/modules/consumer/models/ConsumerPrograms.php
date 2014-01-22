<?php
class Consumer_Model_ConsumerPrograms extends Zend_Db_Table_Abstract {

    public $page_limit = null;
    public $page_offset;

    protected $_name = 'consumers_programs';
    protected $_primary = 'id';
    
    public function setPrimary(  $primary ) {
        $this->_primary = $primary;
    }

    public function setDbName(  $name ) {
        $this->_name = trim($name);
        return $name;
    }
    
    public function getInfo() {
        return $this->info();
    }
    
    public function cleanData($data) {
       $info = $this->getInfo();
       return array_intersect_key($data, $info['metadata']);  
    }
    
    public function _index($where) {
        
        $select = $this->select()->from('consumers_programs')
                 ->setIntegrityCheck(false)
                  ->joinLeft(array('p'=>'programs'),
                             'consumers_programs.program_id = p.id', array('title'));
        
        if( $where && is_array($where) ) {
            
            foreach($where as $k=>$v) {
                $select->where("{$k}", $v);
            }
        }
        
        
        if( !empty($this->page_limit)  ) {
            $select->limit($this->page_limit, $this->page_offset);
        }
        
      return $this->fetchAll($select);    
    }
    
    public function _create($data) {
        if( isset($data['id']) ) {
            unset($data['id']);     
       }
       
       return $this->insert($this->cleanData($data));
    }

    public function _read($id) {
        
       $select =  $this->select()->from(array('c'=>'consumers_programs'))
                                ->setIntegrityCheck(false)
                                ->joinLeft(array('p'=>'programs'),
                                'c.program_id = p.id', array('title')); 
        
        $select->where('c.id = ?', $id);
       return  $this->fetchRow($select);
       //find($id)->current();
    }

    
    public function _update($data) {
        $where = array('id = ?' => (int)$data['id']);
        return $this->update($this->cleanData($data), $where);
    }
        

    public function _delete($id) {
            $where = array('id = ?' => (int)$id);
           return  $this->delete($where);
    }


    
    public function _count($where) {
       $select = $this->select();
        
        if( $where && is_array($where) ) {
            foreach($where as $k=>$v){
                $select->where("{$k}", $v);
            }
        }
       
       $select->from($this->_name,'COUNT(id) AS num');
       return $this->fetchRow($select)->num;
    }

    
}