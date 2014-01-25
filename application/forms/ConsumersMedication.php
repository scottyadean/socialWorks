<?php
class Application_Form_ConsumersMedication extends Main_Forms_Builder {


   public $formType = 'Add';
   public $row_id;
   public $customSubmitBtn = false;
   public $consumerId;
   public $physicianId;
   public $pharmaceuticalId;
   public $_physicians;
   public $_pharmaceuticals;   

   public function build(  $action = "/new",
                            $id = null,
                            $consumer_Id,
                            $physician_Id = null,
                            $pharmaceutical_id = null,
                            $method = "post" ) {
       
       
      $this->row_id = $id;
      $this->consumerId = $consumer_Id;
      $this->physicianId = $physician_Id;
      $this->pharmaceuticalId = $pharmaceutical_id;
       
       if ( !empty( $this->row_id )) {
            $this->formType = 'Update';
       }

       $this->setName("consumers_pharmaceuticals_form");
       $this->getData();
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('consumers_pharmaceuticals', $this->getFields());
       $this->formElementsFromArray($this->getCustomFields());
       $this->createElements();

    }

    public function getFields() {
   /*
   `consumer_id`,
   `pharmaceutical_id`,
   `physician_id`,
   `frequency`,
   `unit`,
   `strength`,
   `side_effects`
   */
       return  array("consumer_id" => array('default'=>$this->consumerId, 'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                     "pharmaceutical_id" => array('default'=>$this->pharmaceuticalId, 'label'=>'Pharmaceutical', 'type'=>'select', 'multiOptions'=>$this->_pharmaceuticals ),
                     "physician_id" => array('default'=>$this->physicianId, 'label'=>'Physician', 'type'=>'select', 'multiOptions'=>$this->_physicians ),
                     "frequency" => array('label'=>'Frequency', 'required'=> false), 
                     "unit"=>array('label'=>'Unit', 'required'=> true),
                     "reason"=>array(),
                     "strength"=>array('label'=>'Strength (Mg)', 'required'=> true ),
                     "side_effects" => array('label'=>'Side Effects', 'attributes'=>array('rows'=>'4', 'cols'=>'8', 'class'=>'textarea-standard-size'))
                      );
    }

   public function getData() {
        
        $consumer = new Consumer_Model_Consumer;
        $consumerInfo = $consumer->findById($this->consumerId);
        $consumerUsers = $consumer->getConsumerUsers();
        $consumerPhysicians = $consumer->getConsumerPhysicians();
        $pharmaceuticals = new Default_Model_Pharmaceutical;
        
        $consumerPharmaceuticals = $pharmaceuticals->indexPharmaceutical()->toArray();
        
        $this->_pharmaceuticals = array();
        foreach( $consumerPharmaceuticals as $rx ) {
            $this->_pharmaceuticals[$rx['id']] = $rx['name'];
        }

        $this->_physicians = array();
        foreach( $consumerPhysicians as $dr ) {
            $this->_physicians[$dr['id']] = $dr['name'];
        }
        
    }
    
    public function getCustomFields() {
     
      if($this->customSubmitBtn){
 
          $custom = array(); 
       
      }else{
       
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
                                   
       }                        
       
       if(!empty($this->row_id )) {
     
            $custom['id'] = array('type'=>'hidden',
                                  'name'=>'id',
                                  'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'),
                                  'default'=>$this->row_id,
                                  'required'=>true);
          }
     
     return $custom;
    }    
}
