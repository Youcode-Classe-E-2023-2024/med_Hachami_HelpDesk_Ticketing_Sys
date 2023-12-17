<?php

Class Main extends Controller{

    private $priotityModel;
    private $statusModel;
    private $tagModel;

    public function __construct(){
       
        $this->priotityModel = $this->model('Priority');
        $this->statusModel = $this->model('Status');
        $this->tagModel = $this->model('Tag');
    }
    public function index(){
        return;
    }

    public function allPriorities(){
        AuthMiddleware::authenticate();
        $priorities = $this->priotityModel->getAllPriorities();
        echo json_encode($priorities);

    }


    public function allStatus(){
        
    
        AuthMiddleware::authenticate();
        $status = $this->statusModel->getAllStatus();
        echo json_encode($status);
    }

    public function allTags(){
        AuthMiddleware::authenticate();
        $tags = $this->tagModel->getAllTags();
        echo json_encode($tags);

    }


}