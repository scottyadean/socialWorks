<?php
class Application_Form_ConsumersMedical extends Main_Forms_Builder {


   public $id;
   public $consumer;
   public $physicians = array();
   public $formType = 'Add';
    

    public function build( $action = "/consumer/medical/appointment/",  $consumer= null, $id = null, $method= "post" ) {
       
       
       $this->id = $id;
       $this->consumer = $consumer;
       
       
       $this->setName("consumer-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->getData();
       $this->formElementsFromTable('consumers_medicals', $this->getFields());
       $this->formElementsFromArray($this->getCustomFields());
       $this->createElements();

    }

    public function getData() {
        
        $consumer = new Consumer_Model_Consumer;
        $consumerInfo = $consumer->findById($this->consumer);
        $consumerUsers = $consumer->getConsumerUsers();
        $consumerPhysicians = $consumer->getConsumerPhysicians();
        
        foreach( $consumerPhysicians as $dr ) {
            
            $this->physicians[$dr['id']] = $dr['name'];
            
        }
        
        
    }
  
  
    public function getFields() {
  
  
       return  array(
                     "accompanying"=>array('requried'=>true),
                     "user_id" => array('type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')), 
                     "consumer_id"=>array('default'=>$this->consumer, 'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                     "physician_id"=>array( 'label'=>'physician', 'type'=>'select', 'multiOptions'=>$this->physicians,  ),
                     "date"=>array( 'label'=>'Date & Time of Appointment' ),
                     "description"=>array('required'=> true, 'attributes'=>array('rows'=>'4', 'cols'=>'8')),
                     "bp"=>array('label'=>'Blood Pressure'), 
                     "heart" => array('label'=>'Heart Rate'),
                     "weight" => array('label'=>'Weight'),
                     "info"=>array('label'=>'Appointment Information', 'attributes'=>array('rows'=>'4', 'cols'=>'8')),
                     "special"=>array('label'=>'Special Instructions/Orders', 'attributes'=>array('rows'=>'4', 'cols'=>'8')),
                     "medications"=>array('label'=>'Medication changes/new', 'attributes'=>array('rows'=>'4', 'cols'=>'8')),
                     "referrals"=>array('label'=>'Physician Referrals', 'attributes'=>array('rows'=>'4', 'cols'=>'8')),
                     "next"=>array("label"=>'Next Appointment'),
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
                                  'label'=>'Cancel',
                                  'type'=>'button',
                                  'name'=>'cancel',
                                  'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'),
                                  'attributes'=>array('onclick'=>"window.history.back()"),
                                  'options' => array('class'=>'btn btn-small btn-primary'),
                                  'ignore'=>true       
                                 )
                                 );
                                 
                                 
    return $custom;


    }
    
    
}
