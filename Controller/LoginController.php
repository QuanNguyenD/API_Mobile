<?php
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
require_once APPPATH.'/Core/Input.php';
class LoginController extends Controller{
    public function process()
    {
        
        $jwt = null;
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $jwt =$headers['Authorization'];
        }
        if (!$jwt && isset($_COOKIE['accessToken'])) {
            $jwt = $_COOKIE['accessToken'];
        }
        
            
        if ($jwt) {
            $this->resp->result = 1;
            $this->resp->msg = "You already logged in";
            $this->jsonecho();
        } else {
            $this -> login();
        }


        
            
        
    }
    private function login(){

        $this->resp->result = 0;
        $password = Input::post("password");

        $data = [];
        $payload = [];
        $msg = [];
        $jwt = "";

        if( !$password )
        {
            $this->resp->msg = "Password can not be emptyy !";
            $this->jsonecho();
        }
        $this->loginByUser();

    }

    private function loginByUser(){
        $this->resp->result = 0;
        $password = Input::post("password");
        $email = Input::post("email");

        if( !$email )
        {
            $this->resp->msg = "Email can not be empty !";
            $this->jsonecho();
        }
        $User = Controller::model("User", $email);
        if( !$User->isAvailable()
            || 
            !password_verify($password, $User->get("password")) 
            )
        {
            $this->resp->msg = "The email or password you entered is incorrect !";
            $this->jsonecho();
        }
        $data = array(
            "id"    => (int)$User->get("id"),
            "email" => $User->get("email"),
            "username" => $User->get("username"),
            "active" => (int)$User->get("active"),
            "created_at" => $User->get("created_at"),
            "updated_at" => $User->get("updated_at"),
            
        );

        $payload = $data;
        $payload["hashPass"] = md5($User->get("password"));
        $payload["iat"] = time();
        $jwt = Firebase\JWT\JWT::encode($payload, EC_SALT, 'HS256');

        $this->resp->result = 1;
        $this->resp->msg = "Congratulations, User ".$User->get("name")." ! You have been logged in successfully.";
        $this->resp->accessToken = $jwt;
        $this->resp->data = $data;
        $this->jsonecho();


    }

}


?>