<?php
class Application_Form_Coordinator extends Main_Forms_Builder {

   public $formType = 'Add';
   public $_cid;
   public $_agencies;

    public function build( $action = "/coordinators/new",  $id = null, $method= "post" ) {
       
       $this->_cid = $id;
       
       if ( !empty( $this->_cid )) {
            $this->formType = 'Edit';
       }
     
       $this->setName("coordinators-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->getData();
       $this->formElementsFromTable('coordinators', $this->getFields());
       $this->formElementsFromArray($this->getCustomFields());
       $this->createElements();

    }

    public function getData() {
         $agency = new Default_Model_Agency;
         $keyValues = array();
         
         foreach( $agency->indexAgency() as $k=>$v  ) {
            $keyValues[$v['id']] = $v['name'];            
         }
         
         $this->_agencies = $keyValues;
    }
    
    public function getFields() {
   
      /*
         `id`,
         `agency_id`,
         `fname`,
         `lname`,
         `phone`,
         `email`,
      */
       return  array( "fname"=>array('label'=>'First Name', 'required'=> true ), 
                      "lname" => array('label'=>'Last Name', 'required'=> true), 
                      "phone"  => array('required'=> true), 
                      "email"  => array('required'=> false),
                      "agency_id" => array('required'=> true, 
                                    'type'=>'select',
                                    'multiOptions' => $this->_agencies, 'default'=>1)
                      
                      );
    }


    public function getCustomFields() {

    $custom = array('submit' => array(
                                 'label'=>$this->formType,
                                 'type'=>'submit',
                                 'name'=>'submit',
                                 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'), 
                                 'options' => array('class'=>'btn btn-small btn-primary'),
                                 'ignore'=>true),
                   'cancel' => array(
                                  'label'=>'Done',
                                  'type'=>'button',
                                  'name'=>'cancel',
                                  'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'),
                                  'attributes'=>array('onclick'=>"history.back();"),
                                  'options' => array('class'=>'btn btn-small btn-primary'),
                                  'ignore'=>true       
                                 )
                                 );
                                 
                                 
    if(!empty($this->_cid )){
        $custom['id'] = array('type'=>'hidden',
                              'name'=>'id',
                              'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'),
                              'default'=>$this->_cid,
                              'required'=>true);
    }                             
                                 


    return $custom;


    }
    
    
}
