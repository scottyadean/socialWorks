<?php

class Default_AuthController extends Zend_Controller_Action
{

    public $request;
    public $reloc;
    public $xhr;
    
    public function init(){
       $this->request = $this->getRequest();
       $this->xhr = $this->request->isXmlHttpRequest();
       $this->reloc = $_SERVER['REQUEST_URI'];
    }

    /*
    * 
    **/
    public function indexAction() {
        
        if( $this->xhr ) {
            $this->_helper->layout->disableLayout();
        }
        
        $form = new Application_Form_Login;
        $form->build($this->reloc);
        
        if( $this->request->isPost($this->reloc)  ) {
                    
            if($form->isValid($this->request->getPost()) && Main_Auth::process($form->getValues())) {
                    
                    $this->reloc = $this->request->getParam('reloc', '/');
                    $reloc = strstr("login", $this->reloc) ? '/' : $this->reloc;
                    
                    $this->_helper->redirector->gotoUrl($reloc);
                    
            }else{
            
                $this->_helper->flashMessenger->addMessage(array('alert alert-error'=>"Incorrect Username or Password") ); 
                $this->_helper->redirector->gotoUrl('/login');
                $form->populate($form->getValues());
        
            }
        
        }

        $this->view->form = $form;
    }
    
    
    public function welcomeAction() {
    
    }


    public function passwordAction() {
    
       $form = new Application_Form_Password();

        if($this->request->isPost() && $form->isValid($this->request->getPost()) ) {

            //get the posted form values.
            $post = $form->getValues();

            //quick qurey to see if this username is in our db. 
            $count = $this->emailPassword($post['username']);

            if($count > 0) {

                //if we found a user look up the user by username
                $user = new Default_Model_User();
                $person = $user->findByUsername($post['username']);

                //create the message that will go to the user. 
                $message  = $person->username.' please click or copy and paste the link below to reset your password';
                $message .= "Please Note: This link will expire.";

                //encrypt the id so we can send it along in the link
                $encryption = new Main_Crypt_Base64Encode();
                $id = $encryption->encode($person->id);
            
                //create a hash to be saved to the user status feild we will find this user 
                //later when they click on the link. the link will be good for a day
                $hash = Main_Salt::getResetPasswordHash( $person->username , date("D") );
                $user->updateUser($person->id,array('status'=>$hash));

                //build the return link with the params in place. 
                $link = SITE_URL."/reset/password/h/{$hash}/u/{$id}";

                print $link;

                //mail the link to the user.
                /*

                $mail = new Zend_Mail();
                $mail->setBodyText("{$message} \n {$link}");
                $mail->setBodyHtml("{$message}<br><a href='{$link}'> password reset </a>");
                $mail->setFrom(SITE_EMAIL, SITE_NAME);
                $mail->addTo($person->email, $person->username);
                $mail->setSubject('Password Reset Link');
                $mail->send();
                */

                //tell the front end we found the user. 
                $this->view->status = '200';

            }else{
                $this->view->status = '404';
            }
        } 


       $this->view->form = $form;  
    }

    public function passwordresetAction() {


        if( $this->request->isPost() ) {

            $password1 = $this->request->getParam('password', false);
            $password2 = $this->request->getParam('passwordverify', false);
            
            $user_data = new Zend_Session_Namespace('user_data');
            $hash = $user_data->hash;
    
            //if password mismatch reset the form with error.
            if($password1 != $password2){
                $this->getPasswordResetInfo();
            }
            
            //create a new salted hash
            $salt = Main_Salt::getRandomSha1Hash();
            $pass = Main_Salt::getSha1Hash($password1, $salt);

            //update the password and remove this reset hash.
            $user = new Default_Model_User();
            $person = $user->findbyHash($hash);

            $user->updateUser($person['id'], array('password'=>$pass,
                                                 'salt'=> $salt,
                                                 'status'=>'_')); 

            //redirect to the log-in page. 
            $this->_helper->redirector->gotoUrl('/login');

        }else{

            $this->getPasswordResetInfo();

        }

    }

    private function getPasswordResetInfo(){

        $hsid = $this->request->getParam('u', false);
        $hash = $this->request->getParam('h', false);
        $this->view->person = null;
        $this->view->form  = null;
        $this->view->hash  = $hash;


        if( $hsid && $hash ) {

           $user_data = new Zend_Session_Namespace('user_data');
           $user_data->hash = $hash;

           $encryption = new Main_Crypt_Base64Encode;
           $id = $encryption->decode($hsid);
           $user = new Default_Model_User();

           if($user->countByField('id', $id) > 0)
                $this->view->person = $user->findbyHash($hash);
           
           if($this->view->person != null)
            $this->view->form = new Application_Form_PasswordReset;
        }
    }



    public function joinAction() {
       $form = new Application_Form_Join();

        if($this->request->isPost() && $form->isValid($this->request->getPost()) ){
            
            
            $user = new Default_Model_User();
            $post = $form->getValues();
            
            $count = $this->emailPassword($post['username']);

            if( $count == 0 ){

            if($user->create($post)){
            
                $this->_helper->redirector->gotoUrl('/welcome');
            
            }else{
            
              $this->_helper->flashMessenger->addMessage(array('error'=>'Username '.$post['username'].' was not created.') );
              $this->_helper->redirector->gotoUrl('/join');
              
            }   
        
            }else{
            
                $this->_helper->flashMessenger->addMessage(array('error'=>'Username '.$post['username'].' is not available.') );
                $this->_helper->redirector->gotoUrl('/join');
            }
        
        }


       $this->view->form = $form;  
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector->gotoUrl('/');
    }

    public function emailPassword($uname) {
        $user = new Default_Model_User();
        return $user->countByField('username', $uname);
    }



}
