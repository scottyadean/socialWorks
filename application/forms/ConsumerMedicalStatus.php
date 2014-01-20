<?php
class Application_Form_ConsumerMedicalStatus extends Main_Forms_Builder {

   public $_id;
   public $customSubmitBtn = false;
   public $formType = 'Add';
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
`consumers_id`,
`exam_id`,
`weight`,
`height`,
`seizure_info`,
`seizure_type`,
`blood_pressure`,
`cholesterol`,
`colon_exam`,
`prostate_exam`,
`osteoporosis_exam`,
`flu_shot`,
`tetanus_booster`,
`pneumococcal_shot`,
`hepatitis_b_series`,
`hepatitis_a`,
`pap_smear`,
`mammogram`,
`summary`



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
                        )))
                                           
                                           ),
                         "height" => array('label'=>'Height'),
                         "seizure_info" => array('label'=>'Seizure Info', 'attributes'=>array('rows'=>'4', 'cols'=>'40')),
                         "seizure_type" => array('label'=> 'Seizure Type'),
                         "blood_pressure"=>array(),
                         "cholesterol"=>array(),
                         "colon_exam"=>array(),
                         "prostate_exam"=>array(),
                         "osteoporosis_exam"=>array(),
                         "flu_shot"=>array(),
                         "tetanus_booster"=>array(),
                        "pneumococcal_shot"=>array(),
                        "hepatitis_b_series"=>array(),
                        "hepatitis_a"=>array(),
                        "pap_smear"=>array(),
                        "mammogram"=>array(),
                        "date"=>array("label"=>'Date', 'attributes'=>array('class'=>'date_widget') ),
                        "summary"=>array('attributes'=>array('rows'=>'4', 'cols'=>'40')));
        // //"achieved"=>array('label'=>'Achieved', 'type'=>'select', 'default'=>'N', 'multiOptions'=>array('N'=>'No','Y'=>'Yes'), 'required'=> false)
                        
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