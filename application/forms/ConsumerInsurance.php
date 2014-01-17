<?php
class Application_Form_ConsumerInsurance extends Main_Forms_Builder {

   public $_id;
   public $customSubmitBtn = false;
   //public $consumerId;
   public $formType = 'Add';
   public $insuranceTypes = array();
   
   public function build( $action = "/consumer/insurance/new/",
                          $id = null,
                          $method = "post" ) {
       
       $this->_id = $id;
       
       if(!is_null($this->_id)) {    
            $this->formType = 'Update';
       }
       
      
       $this->setName("consumer-insurance-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->getData();
       $this->formElementsFromTable('consumers_insurance', $this->getFields());
       
       if($this->customSubmitBtn == false){
            $this->formElementsFromArray($this->getCustomFields());
       }
       
       $this->createElements();
    }

    public function getData() {

            $this->insuranceTypes = array('medical'=>'medical',
                                           'vision'=>'vision',
                                           'dental'=>'dental');     
    }
  
  /**
   * `id`,
   * `consumer_id`,
   * `type`,
   * `medical_number`,
   * `medicare_number`,
   * `insurance_info`
   */
    public function getFields() {

         $fields = array("consumer_id" => array('required'=> true,  'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                         "type" => array('default'=>'medical', 'label'=>'Insurance Type', 'required'=> true, 'type'=>'select', 'multiOptions'=>$this->insuranceTypes ),
                         "medical_number"=>array('label'=>'Medical Number', 'required'=> true), 
                         "medicare_number"=>array('label'=>'Medicare Number', 'required'=> true ),
                         "insurance_info"=>array('label'=>'Info','required'=> false, 'attributes'=>array('rows'=>'4', 'cols'=>'8')));
        
           if( isset( $this->_id ) ) {
              
              $fields['id'] = array('default'=>$this->_id, 'type'=>'hidden', 'required'=> true,
                                    'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'));       
           }
           
           return $fields;
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
                                  'attributes'=>array('onclick'=>"window.history.back()"),
                                  'options' => array('class'=>'btn btn-small btn-primary'),
                                  'ignore'=>true       
                                 ));
    return $custom;
    
    }   
}