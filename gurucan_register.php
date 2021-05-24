<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

/* 
step1: create user 
step2: login user
*/

defined('_JEXEC') or die;

class plgSystemGurucan_Register extends JPlugin
{
    function __construct(&$subject, $params){
        parent::__construct($subject, $params);
    }

    public function onAfterInitialise(){
            
            $app = JFactory::getApplication();
            
            //get gurucan user data
            $gurucanUserData = $this->getGurucanUserData();

            if(!$gurucanUserData){
                return;
            }

            //register user
            $user = new JUser;
            $user_data = $this->getJoomlaUserData($gurucanUserData);
            $user->bind($user_data);
            $user->save();

            //login user
            $credentials = $this->getUserCredentials($user);
            $app->login($credentials);        
      
    }

    private function getGurucanUserData(){ 
        
        if($_GET['token'] == '3899dcbab79f92af727c2190bbd8abc5' && $_SERVER['REQUEST_METHOD'] === 'POST'){
            // $g_user = json_decode("{\"influencer\":\"606338f8892d842f698e3d0f\",\"action\":\"sign_up\",\"source\":{\"_id\":\"60a79c2dc01abe07fee375f3\",\"tags\":[],\"status\":\"not_validated\",\"name\":\"test2\",\"email\":\"test2@test.com\",\"influencer\":\"606338f8892d842f698e3d0f\",\"avatar\":\"https:\/\/icotar.com\/initials\/test2.png?bg=39948095\",\"influencersData\":{}},\"source_type\":\"user\"}");
            $g_user = json_decode(file_get_contents('php://input'));
            
            if($g_user->source->_id){
                return Array(
                    "name" => $g_user->source->name,
                    "email" => $g_user->source->email
                );
            }else{
                return false;
            }
            
        }else{
            return false;
        }

    }

    private function getJoomlaUserData($gurucanUserData){
        $rand = rand();
        return Array(
            "name" => $gurucanUserData['name'],
            "username" => $gurucanUserData['name'],
            "email" => $gurucanUserData['email'],
            "password" => $gurucanUserData['email'],
            "activation" => 0,
            "block" => 0,
            "groups" => Array(
                2
            )
        );
    }

    private function getUserCredentials($user){
        return Array(
            'username' => $user->username,
		    'password' => $user->email,
		    'secretkey' => ''
        );
    }
   
}