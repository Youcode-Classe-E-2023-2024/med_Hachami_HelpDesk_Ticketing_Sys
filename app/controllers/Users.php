<?php

Class Users extends Controller{
    private $userModel;
    public function __construct()
    {
       $this->userModel = $this->model('User');
      

    }
    public function index()
    {
        echo json_encode("hello");
    }

    public function register(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $postData = file_get_contents("php://input");
            $data = json_decode($postData, true);
            
            
            if ($data !== null) {
                
                $emailExist = $this->userModel->findUserByEmail($data['email']);
                if($emailExist){
                    echo json_encode(["message_1"=>"Email already exists"]);
                }else{
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    $registerUser = $this->userModel->register($data);
                    if($registerUser){
                        $token = JwtAuth::createToken(
                            [
                                'email' =>$data['email'] ,
                                'full_name' =>$data['full_name'],
                            
                            ]);
                        echo json_encode(['message_2' => 'You registered successfully']);
                    }
                }
                
            } else {
                
                http_response_code(400); 
                echo json_encode(['error' => 'Invalid JSON payload']);
            }
           
            
        }
    }

    public function login(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $postData = file_get_contents("php://input");
            $data = json_decode($postData, true);
            if ($data !== null) {
                // var_dump($data);
                $chekUser = $this->userModel->findUserByEmail($data['email']);
                if($chekUser){
                    $logUser = $this->userModel->login($data['email'] , $data['password']);
                    if($logUser){
                        // var_dump($logUser);
                        $token = JwtAuth::createToken(
                            $logUser);
                        echo json_encode(['token' => $token]);
                    }else{
                        echo json_encode(["message"=>"Email or password is incorrect"]);
                    }
                }else{
                    echo json_encode(["message"=>"Email or password is incorrect"]);
                }
            }
            else {
               
                http_response_code(400); 
                echo json_encode(['error' => 'Invalid JSON payload']);
            }
        }
        
    }

    public function allUsers(){
        AuthMiddleware::authenticate();
        $users =  $this->userModel->getAllUsers();
        echo json_encode($users);
    }


}