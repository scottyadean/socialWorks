<?php
class Main_Forms_Crud {
    /*
    * _programs
    * curd object. 
    */
    protected $_programs = array("db" => "programs",
                                "url" => "/programs/:crud",
                                "form" => "Application_Form_Programs",
                                "title" => "Programs",
                                "excluded_from_list" => array('id','director_id','city','state','zip','county','email','fax'),
                                "excluded_from_view"=> array('id'));
    /*
    * _sirs
    * curd object. 
    */
    protected $_sirs = array("db" => "consumers_sirs",
                            "url" => "/sir/:crud/consumer_id/:consumer_id",
                            "form" => "Application_Form_ConsumerSir",
                            "title" => "Serious Incident Reports",
                            "where" => "consumer_id=>:consumer_id",
                            "excluded_from_list" => array('id','consumer_id'),
                            "excluded_from_view"=> array('id'));
    /*
    * _medical
    * curd object. 
    */
    protected $_medical = array("db" => "consumers_medical_status",
                                "url" => "/medical/status/:crud/consumer_id/:consumer_id",
                                "form" => "Application_Form_ConsumerMedicalStatus",
                                "title" => "Medical Status",
                                "where" => "consumer_id=>:consumer_id",
                                "excluded_from_list" => "id,consumer_id,exam_id,colon_exam,prostate_exam,osteoporosis_exam,tetanus_booster,pneumococcal_shot,hepatitis_b_series,hepatitis_a,pap_smear,mammogram,summary",
                                "excluded_from_view"=> array('id'));
    /*
    * _goals
    * curd object. 
    */
    protected $_goals = array("db" => "consumers_goals",
                            "url" => "/goals/:crud/consumer_id/:consumer_id",
                            "form" => "Application_Form_ConsumerGoals",
                            "title" => "Goals",
                            "onCreate"=>"Consumer_Model_ConsumersGoals::ClearData",
                            "onUpdate"=>"Consumer_Model_ConsumersGoals::ClearData",
                            "where" => "consumer_id=>:consumer_id",
                            "excluded_from_list" => "id,consumer_id",
                            "excluded_from_view"=> array('id'));
    /*
    * _allergies
    * curd object. 
    */
    protected $_allergies = array("db" => "consumers_allergies",
                            "url" => "/allergies/:crud/consumer_id/:consumer_id",
                            "form" => "Application_Form_ConsumersAllergies",
                            "title" => "Allergies",
                            "where" => "consumer_id=>:consumer_id",
                            "excluded_from_list" => "id,consumer_id",
                            "excluded_from_view"=> array('id'));     

    /*
    * _hospitalized
    * curd object. 
    */
    protected $_hospitalized = array("db" => "consumers_hospitalized",
                                    "url" => "/hospitalized/:crud/consumer_id/:consumer_id",
                                    "form" => "Application_Form_ConsumersHospitalized",
                                    "title" => "Hospitalized Info",
                                    "where" => "consumer_id=>:consumer_id",
                                    "excluded_from_list" => "id,consumer_id",
                                    "excluded_from_view"=> array('id'));
    /*
    * _insurance
    * curd object. 
    */
    protected $_insurance = array("db" => "consumers_insurance",
                                "url" => "/insurance/:crud/consumer_id/:consumer_id",
                                "form" => "Application_Form_ConsumerInsurance",
                                "title" => "Insurance Info",
                                "where" => "consumer_id=>:consumer_id",
                                "excluded_from_list" => "id,consumer_id,physician_id",
                                "excluded_from_view"=> array('id'));
    /*
    * _person
    * curd object. 
    */
    protected $_person = array("db" => "people",
                                "url" => "/person/:crud/consumer_id/:consumer_id",
                                "form" => "Application_Form_People",
                                "title" => "People",
                                "excluded_from_list" => "id,address,city,state,zip,cell",
                                "excluded_from_view"=> array('id'));
    
    /*
     * return a protected property by name
    */
    public function getProperty($property) {
        
        if(property_exists ( $this ,$property )) {
            
            return $this->$property;
            
        }else{
            
            
            return false;
        }
        
        
    }
    
    
    
    
}