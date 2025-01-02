<?php
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use PayPal\Api\Tax;

require_once APPPATH.'/Core/Input.php';
require_once APPPATH.'/Model/TasksModel.php';

class TasksController extends Controller{
    public function process(){
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
           
            $this->getAll();
            
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
    private function getAll(){
        $this->resp->result = 0;
        $data = [];
        try{
            $TaskMd = new TasksModel();
            $query = $TaskMd->getAll();

            $res = $query->get();
            $quantity = count($res);

            $result = $query->get();
            foreach($result as $element)
            {
                $data[] = array(
                    "id" => (int)$element->id,
                    "title" => $element->title,
                    "description" => $element->description,
                    "status" => $element->status,
                    "due_date" =>$element ->due_date,
                    "priority"=> $element -> priority,
                    "created_at" => $element->created_at,
                    "updated_at" => $element->updated_at,
                    "category" => array(
                        "id" => (int)$element->category_id,
                        "name" =>$element->category_name,

                    )
                );
            }
            $this->resp->result = 1;
            $this->resp->quantity = $quantity;
            $this->resp->data = $data;



        }
        catch(Exception $ex)
        {
            $this->resp->msg = $ex->getMessage();
        }
        $this->jsonecho();
        
    }
}
?>