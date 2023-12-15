<?php

Class Users extends Controller{
    private $userModel;
    public function __construct()
    {
       $this->userModel = $this->model('User');
      

    }
    public function index()
    {
        echo 'index';
    }

    public function register(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'full_name' => trim($_POST['full_name']) ,
                'email'=> trim($_POST['email']),
                'password'=> trim($_POST['password']),
                
            ];
            $emailExist = $this->userModel->findUserByEmail($data['email']);
            if($emailExist){
                echo json_encode(["message"=>"Email already exists"]);
            }else{
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                $registerUser = $this->userModel->register($data);
                if($registerUser){
                    $token = JwtAuth::createToken(
                        [
                            'email' =>$data['email'] ,
                            'full_name' =>$data['full_name'],
                        
                        ]);
                    echo json_encode(['token' => $token]);
                }
            }
            
        }
    }

    public function login(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'email'=> trim($_POST['email']),
                'password'=> trim($_POST['password']),
                
            ];
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
        
    }
}