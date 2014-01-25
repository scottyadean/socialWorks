<?php
class Application_Form_Coordinator extends Main_Forms_Builder {

   public $formType = 'Add';
   public $_id;
   public $_agencies;
   public $customSubmitBtn = false;

    public function build( $action = "/coordinators/new",  $id = null, $method= "post" ) {
       
       $this->_id = $id;
       
       if ( !empty( $this->_id )) {
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
      $fields =  array( "fname"=>array('label'=>'First Name', 'required'=> true ), 
                      "lname" => array('label'=>'Last Name', 'required'=> true), 
                      "phone"  => array('required'=> true), 
                      "email"  => array('required'=> false),
                      "agency_id" => array('required'=> true, 
                                    'type'=>'select',
                                    'multiOptions' => $this->_agencies, 'default'=>1));
      
                                          
      if(!empty($this->_id )){
           $fields['id'] = array('type'=>'hidden',
                                'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'),
                                'default'=>$this->_id,
                                'required'=>true);
      }
      
      return $fields;

    }


    public function getCustomFields() {

    if($this->customSubmitBtn) {
      return array();
    }
    
    $custom = array('submit' => array(
                                 'label'=>$this->formType,
                                 'type'=>'submit',
                                 'name'=>'submit',
                                 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'), 
                                 'options' => array('class'=>'btn btn-small btn-primary'),
                                 'ignore'=>true),
                   'cancel' => array(
                                  'label'=>'Cancel',
                                  'type'=>'button',
                                  'name'=>'cancel',
                                  'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'),
                                  'attributes'=>array('onclick'=>"history.back();"),
                                  'options' => array('class'=>'btn btn-small btn-primary'),
                                  'ignore'=>true       
                                 ));

    return $custom;

    }
    
    
}
