<?php
class Application_Form_People extends Main_Forms_Builder {

   public $_id;
   public $customSubmitBtn = false;
   public $formLabel = 'Add';
   public $_types;
   
   public function build( $action = "/person/create",
                          $id = null,
                          $method = "post" ) {
       
       $this->_id = $id;
       
       if(!is_null($this->_id)) {    
            $this->formLabel = 'Update';
       }
       

       $this->setName("people-form");
       $this->getData();
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('people', $this->getFields());
       
       if($this->customSubmitBtn == false){
            $this->formElementsFromArray($this->getCustomFields());
       }
       
       $this->createElements();
    }

    public function getData() {

            $this->_types = array('payee'=>'Payee',
                                  'emergy_contact'=>'Emergy contact',
                                  'father'=>'Father',
                                  'bother'=>'Brother',
                                  'sister'=>"Sister",
                                  'mother'=>'Mother',
                                  'uncle'=>'Uncle',
                                  'aunt'=>'Aunt',
                                  'relative'=>'Relative',
                                  'gardian'=>'Gardian',
                                  'significant_other'=>'Significant other',
                                  'friend'=>'Friend');     
    }
  
  /**
   *`id`,
   *`consumer_id`,
   *`type`,
   *`fname`,
   *`lname`,
   *`employer`,
   *`email`,
   *`phone`,
   *`cell`,
   *`address`,
   *`city`,
   *`state`,
   *`zip`
   **/
    public function getFields() {

         $fields = array("consumer_id" => array('required'=> true,  'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                         "type" => array('default'=>'medical', 'label'=>'Type', 'required'=> true, 'type'=>'select', 'multiOptions'=>$this->_types ),
                         "fname"=>array('label'=>'First Name', 'required'=> true), 
                         "lname"=>array('label'=>'Last Name', 'required'=> true ),
                         "email"=>array('label'=>'Email', 'required'=> false ),
                         "phone"=>array('label'=>'Phone', 'required'=> true),
                         "cell"=>array('label'=>'Cell', 'required'=> false),
                         "address"=>array('label'=>'Address', 'required'=> false),
                         "city"=>array('label'=>'City', 'required'=> false),
                         "state"=>array('label'=>'State', 'required'=> false),
                         "zip"=>array('label'=>'Zip', 'required'=> false),
                         "info"=>array('label'=>'Info','required'=> false, 'attributes'=>array('rows'=>'4', 'cols'=>'8'))
                         
                         );
        
           if( isset( $this->_id ) ) {
              
              $fields['id'] = array('default'=>$this->_id, 'type'=>'hidden', 'required'=> true,
                                    'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'));       
           }
           
           return $fields;
    }


    public function getCustomFields() {
    $custom = array('submit' => array(
                                 'label'=>$this->formLabel,
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
                                  'attributes'=>array('onclick'=>"window.history.back()"),
                                  'options' => array('class'=>'btn btn-small btn-primary'),
                                  'ignore'=>true       
                                 ));
    return $custom;
    
    }   
}