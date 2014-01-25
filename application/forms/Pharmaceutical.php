<?php
class Application_Form_Pharmaceutical extends Main_Forms_Builder {

   public $formLabel = 'Add';
   public $_id;
   public $customSubmitBtn = false;

    public function build( $action = "/pharmaceutical/new",
                          $id = null, $method = "post" ) {
       
      $this->_id = $id;
       
       if ( !empty( $this->_id )) {
            $this->formLabel = 'Update';
       }
     
       $this->setName("pharmaceutical-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('pharmaceuticals', $this->getFields());
       $this->formElementsFromArray($this->getCustomFields());
       $this->createElements();

    }

    public function getFields() {

       return  array( "maker" => array('label'=>'Drug Manufacturer', 'required'=> false), 
                      "name"=>array('label'=>'Drug Name', 'required'=> true),
                      "site"=>array('label'=>'Web Site', 'required'=> true ),
                      "blood_level_monitoring"=> array('label'=>'Requires periodic blood level monitoring', 'value'=>1, 'type'=>'checkbox'),
                      "side_effects" => array('label'=>'Side Effects', 'attributes'=>array('rows'=>'4', 'cols'=>'8', 'class'=>'textarea-standard-size'))
                      );
    
               if( !empty( $this->_id ) ) {
              
                $fields['id'] = array('default'=>$this->_id, 'type'=>'hidden', 'required'=> true,
                                    'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'));       
               }
    }

    public function getCustomFields() {
            
        if($this->customSubmitBtn) {
            return array();
        }
    
        return array('submit' => array(
                                    'label'=>$this->formLabel,
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
    
    
}
