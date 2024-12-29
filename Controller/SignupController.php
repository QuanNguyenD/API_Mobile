<?php
require_once APPPATH.'/Core/Input.php';
class SignupController extends Controller{
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
            $this->signup();
        }
    }
    private function signup(){


        $this->resp->result = 0;
        $required_fields  = [
            "email", 
            "password", 
            "username",
        ];

        foreach ($required_fields as $field) 
        {
            if (!Input::post($field)) 
            {
                $this->resp->msg = "Missing field: ".$field;
                $this->jsonecho();
            }
        }
        $email = Input::post("email");
        $password = Input::post("password");
        $username = Input::post("username");
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->resp->msg = "Email is not correct format. Try again !";
            $this->jsonecho();
        }
        //email duplication
        $User = Controller::model("User", $email);
        if( $User->isAvailable() )
        {
            $this->resp->msg = "This email is used by someone. Try another !";
            $this->jsonecho();
        }
        //password filter
        if (mb_strlen($password) < 6) 
        {
            $this->resp->msg = "Password must be at least 6 character length!";
            $this->jsonecho();
        }

        try 
        {
            $Doctor = Controller::model("User");
            $Doctor->set("email", strtolower($email))
                    ->set("password", password_hash($password, PASSWORD_DEFAULT))
                    ->set("username", $username)
                    ->set("created_at", date("Y-m-d H:i:s"))
                    ->set("updated_at", date("Y-m-d H:i:s"))
                    ->save();

            $this->resp->result = 1;
            $this->resp->msg = "User account is created successfully !";
            $this->resp->data = array(
                "id" => (int)$Doctor->get("id"),
                "email" => $Doctor->get("email"),
                "username" => $Doctor->get("username"),
                "created_at" => $Doctor->get("created_at"),
                "updated_at" => $Doctor->get("updated_at"),
            );

            $data = [
                "email" => strtolower($email),
                "username" => $username,
                "password" => $password
            ];

            //MyEmail::signup($data);
        } 
        catch (\Exception $ex) 
        {
            $this->resp->msg = $ex->getMessage();
        }
        $this->jsonecho();






    }





}



?>