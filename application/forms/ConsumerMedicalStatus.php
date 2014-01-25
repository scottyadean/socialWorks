<?php
class Application_Form_ConsumerMedicalStatus extends Main_Forms_Builder {

   public $_id;
   public $customSubmitBtn = false;
   public $formType = 'Add';
   public $_gender = "male";
   public $insuranceTypes = array();
   
   public function build( $action = "/medical/status/create",
                          $id = null,
                          $method = "post" ) {
       
       $this->_id = $id;
       
       if(!is_null($this->_id)) {    
            $this->formType = 'Update';
       }
       
       $this->setName("consumers-medical-status");
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('consumers_medical_status', $this->getFields());
       
       if($this->customSubmitBtn == false){
            $this->formElementsFromArray($this->getCustomFields());
       }
       
       $this->createElements();
    }

  /**
'required'=> false, 'attributes'=>array('rows'=>'4', 'cols'=>'8', 'class'=>'textarea-standard-size')
'attributes'=>array('rows'=>'4', 'cols'=>'8', 'class'=>'textarea-standard-size')
   */
    public function getFields() {

         $fields = array("consumer_id" => array('required'=> true,  'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                          
                          
                          "weight" => array('label'=>'Weight', 'required'=> true,                      
                            'validators' => array(
                               array('Float', false, array( 'messages' => "Invalid entry, numbers only ex. 149")),
                               array('notEmpty', true, array(
                                       'messages' => array(
                                           'isEmpty' => 'Weigth can\'t be empty'
                                       )
                               )))),
                         
                         
                         "date" => array('attributes'=>array('class'=>'date_widget')),
                        
                        "height" => array(),
                        "seizure_info" => array('attributes'=>array('rows'=>'4', 'cols'=>'40', 'class'=>'textarea-standard-size')),
                        "seizure_type" => array(),
                        "blood_pressure" => array(),
                        "cholesterol" => array('label'=>'Cholesterol Date', 'attributes'=>array('class'=>'date_widget')),
                        "cholesterol_results" => array(),
                        "colon_exam" => array(),
                        "prostate_exam" => array(),
                        "osteoporosis_exam" => array(),
                        "flu_shot" => array(),
                        "tetanus_booster" => array(),
                        "pneumococcal_shot" => array(),
                        "hepatitis_b_series" => array(),
                        "hepatitis_a" => array(),
                        "pap_smear" => array(),
                        "pap_smear_date" => array('attributes'=>array('class'=>'date_widget')),
                        "mammogram" => array(),
                        "summary"=>array('attributes'=>array('rows'=>'4', 'cols'=>'40','class'=>'textarea-standard-size')));
          
          if( $this->gender == '' )
          
          
                      
           if( !empty( $this->_id ) ) {
              
              $fields['id'] = array('default'=>$this->_id, 'type'=>'hidden', 'required'=> true,
                                    'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'));       
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
                                  'attributes'=>array('onclick'=>"window.history.back()"),
                                  'options' => array('class'=>'btn btn-small btn-primary'),
                                  'ignore'=>true       
                                 ));
    return $custom;
    
    
    }   
}