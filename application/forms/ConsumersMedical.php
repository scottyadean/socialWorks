<?php
class Application_Form_ConsumersMedical extends Main_Forms_Builder {

   public $_id;
   public $_consumer_id;
   public $customSubmitBtn = false;
   public $physicians = array();
   public $formType = 'Add';
    
    public function build( $action = "/appointment/new",
                           $consumer_id= null,
                           $id = null,
                           $method= "post" ) {
       
       $this->_id = $id;
       $this->_consumer_id = $consumer_id;
       
       $this->setName("consumer-med-appontment-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->getData();
       $this->formElementsFromTable('consumers_appointments', $this->getFields());
       $this->formElementsFromArray($this->getCustomFields());
       $this->createElements();
    }

    public function getData() {
        
        $consumer = new Consumer_Model_Consumer;
        $consumerInfo = $consumer->findById($this->_consumer_id);
        $consumerUsers = $consumer->getConsumerUsers();
        $consumerPhysicians = $consumer->getConsumerPhysicians();
        
        foreach( $consumerPhysicians as $dr ) {
            $this->physicians[$dr['id']] = $dr['name'];
        }
        
    }
    
    public function getFields() {
  
       $fields = array("hospital"=>array('required'=>true),
                       "accompanying"=>array('requried'=>false),
                       "user_id" => array('type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')), 
                       "consumer_id"=>array('default'=>$this->_consumer_id, 'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                       "physician_id"=>array( 'label'=>'physician', 'type'=>'select', 'multiOptions'=>$this->physicians,  ),
                       "date"=>array( 'label'=>'Date & Time of Appointment', 'attributes'=>array('class'=>'date_widget'), 'requried'=>true),
                       "ampm"=>array( 'label'=>'AM - PM', 'requried'=>true, 'type'=>'select', 'multiOptions'=>array('am'=>'AM.', 'pm'=>'PM.'),  ),
                       "time"=>array( 'label'=>'Time', 'requried'=>true),
                       "description"=>array('required'=> false, 'attributes'=>array('rows'=>'4', 'cols'=>'8')),
                       "info"=>array('label'=>'Appointment Information', 'attributes'=>array('rows'=>'4', 'cols'=>'8')),
                       "special"=>array('label'=>'Special Instructions/Orders', 'attributes'=>array('rows'=>'4', 'cols'=>'8')),
                       "medications"=>array('label'=>'Medication changes/new', 'attributes'=>array('rows'=>'4', 'cols'=>'8')),
                       "referrals"=>array('label'=>'Physician Referrals', 'attributes'=>array('rows'=>'4', 'cols'=>'8')),
                       "next"=>array("label"=>'Next Appointment', 'attributes'=>array('class'=>'date_widget')),
                      );
      
      
      if( isset( $this->_id ) ) {
              
              $fields['id'] = array('default'=>$this->_id, 'type'=>'hidden',
                                    'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'));       
           }
      
      return $fields;
      
    }


    public function getCustomFields() {

      if($this->customSubmitBtn) {
         return array();
      }
         
         $custom = array('submit' => array('label'=>$this->formType,
                                    'type'=>'submit',
                                    'name'=>'submit',
                                    'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'), 
                                    'options' => array('class'=>'btn btn-small btn-primary'),
                                    'ignore'=>true),
                 'cancel' => array('label'=>'Done',
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
