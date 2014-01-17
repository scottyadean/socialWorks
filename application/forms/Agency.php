<?php
class Application_Form_Agency extends Main_Forms_Builder {

   public $formType = 'Add';
   public $_aid;
   public $_agencies;

    public function build( $action = "/agency/new",  $id = null, $method= "post" ) {
       
       $this->_aid = $id;
       
       if ( !empty( $this->_aid )) {
            $this->formType = 'Edit';
       }
     
       $this->setName("agency-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('agencies', $this->getFields());
       $this->formElementsFromArray($this->getCustomFields());
       $this->createElements();

    }
    
    public function getFields() {
    return  array( "name"=>array('label'=>'Agency Name', 'required'=> true ), 
                   "address" => array('label'=>'Address', 'required'=> true),
                   "city" => array('label'=>'City', 'required'=> true),
                   "state" => array('required'=> true, 
                                    'type'=>'select', 
                                    'multiOptions' => Main_Forms_Data::AmericaStates(), 'default'=>'CA'),
                   "zip" => array('label'=>'Zip', 'required'=> true),
                   "phone"  => array('required'=> true), 
                   "email"  => array('required'=> false));
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
                                 
                                 
    if(!empty($this->_aid )){
        $custom['id'] = array('type'=>'hidden',
                              'name'=>'id',
                              'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'),
                              'default'=>$this->_aid,
                              'required'=>true);
    }                             
                                 


    return $custom;


    }
    
    
}
