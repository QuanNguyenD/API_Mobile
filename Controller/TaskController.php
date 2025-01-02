<?php
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
require_once APPPATH.'/Core/Input.php';
require_once APPPATH.'/Model/TasksModel.php';

class TaskController extends Controller{
    public function process($id = null){
        $jwt = null;
            $headers = getallheaders();
            if (isset($headers['Authorization'])) {
                $jwt =$headers['Authorization'];
            }
            if (!$jwt && isset($_COOKIE['accessToken'])) {
                $jwt = $_COOKIE['accessToken'];
            }
            if(!isset($jwt)){
                header("Location: " . APPURL . "/login");
                exit;
            }
            
        

        $request_method = Input::method();
        
        if($request_method === 'GET')
        {
            if ($id !== null) {
                //$this->getById($id); // Truyền $id vào phương thức getById
            } else {
                echo json_encode(["message" => "ID is required"]);
            }
        }
        elseif($request_method ==='PUT'){
            // $decoded = JWT::decode($jwt, new Key(EC_SALT, 'HS256'));
            // if($decoded->role !="admin"){
            //     $this->resp->msg = "You are not admin & you can't do this action !";
            //     $this->jsonecho();
            // }
            //$this->update($id);
        }
        else if( $request_method === 'POST')
        {
            // $decoded = JWT::decode($jwt, new Key(EC_SALT, 'HS256'));
            // if($decoded->role !="admin"){
            //     $this->resp->msg = "You are not admin & you can't do this action !";
            //     $this->jsonecho();
            // }
            //$this->add($id);
        }
        elseif($request_method ==='DELETE'){
            // $decoded = JWT::decode($jwt, new Key(EC_SALT, 'HS256'));
            // if($decoded->role !="admin"){
            //     $this->resp->msg = "You are not admin & you can't do this action !";
            //     $this->jsonecho();
            // }
            //$this->delete($id);

        }
        
    }
}
?>