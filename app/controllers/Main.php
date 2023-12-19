<?php

Class Main extends Controller{

    private $priotityModel;
    private $statusModel;
    private $tagModel;
    private $ticketModel;

    public function __construct(){
       
        $this->priotityModel = $this->model('Priority');
        $this->statusModel = $this->model('Status');
        $this->tagModel = $this->model('Tag');
        $this->ticketModel = $this->model('Ticket');
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

    public function newTicket(){
        AuthMiddleware::authenticate();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $postData = file_get_contents("php://input");
            $data = json_decode($postData, true);
            if ($data !== null && !empty($data['title']) && !empty($data['description'])
                 && !empty($data['priority'])  && !empty($data['creatordId']) && !empty($data['assignedTo']) && !empty($data['tags']) 
                ) {
                    $addedTicket =$this->ticketModel->addTicket($data);
                    if($addedTicket){
                        echo json_encode(['message' => 'Ticket Added Succe']);
                    }
                    else{
                        echo json_encode(['error' => 'Addition Failed']);
                    } 
                
            }
            else {
               
                http_response_code(400); 
                echo json_encode(['error' => 'Invalid JSON payload']);
            }
        }
       
    }

    public function allTickets() {
        AuthMiddleware::authenticate();
        $ticketDetails = $this->ticketModel->getAllTickets();
        
       
        echo json_encode($ticketDetails);

    }

    public function ticketAssigned($userdId) {
        AuthMiddleware::authenticate();
        $ticketDetails = $this->ticketModel->getTicketAssignedTo($userdId);
        
       
        echo json_encode($ticketDetails);

    }
    public function incompleteTicket($userdId){
        AuthMiddleware::authenticate();
        $numIncoTicket = $this->ticketModel->getIncompleteTicket($userdId);
        echo json_encode($numIncoTicket);
    }

    public function myTicket($userdId){
        AuthMiddleware::authenticate();
        $ticketsDetails = $this->ticketModel->getMyTickets($userdId);
        echo json_encode($ticketsDetails);
    }

    public function editTicketStatus($statusId,$ticketId){
        $decodeToken = AuthMiddleware::authenticate();
        if($_SERVER['REQUEST_METHOD'] == 'PUT'){
            
            $userId = $decodeToken->id;
            $updtTicket = $this->ticketModel->editStatus($statusId, $ticketId,$userId);
            if($updtTicket){
                echo json_encode(['message' => 'Ticket updated Succe']);
            }else{
                echo json_encode(['error' => 'Update Failed']);
            }
        }else{
            http_response_code(400); 
            echo json_encode(['error' => 'Bad Request']);
        }
    }

    public function deleteTicket($ticketId){
        $decodeToken = AuthMiddleware::authenticate();
        if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
            
            $userId = $decodeToken->id;
            $deleteTick = $this->ticketModel->delete($ticketId,$userId);
            if($deleteTick){
                echo json_encode(['message' => 'Ticket deleted Succe']);
            }else{
                echo json_encode(['error' => 'Delete Failed']);
            }
        }else{
            http_response_code(400); 
            echo json_encode(['error' => 'Bad Request']);
        }
    }

    public function ticketById($ticketId){
        AuthMiddleware::authenticate();
        $ticket = $this->ticketModel->getTicketById($ticketId);
        echo json_encode($ticket);
    }

    public function addComment($ticketId){
        $decodeToken = AuthMiddleware::authenticate();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $postData = file_get_contents("php://input");
            $data = json_decode($postData, true);

            if(!empty($data['comment'])){
                $userId = $decodeToken->id;
                
                $comment = $this->ticketModel->insertIntoComment($ticketId , $userId ,$data['comment']);
                if($comment){
                    echo json_encode(['message' => true]);
                }else{
                    echo json_encode(['message' => false]);
                }
            }else{
                http_response_code(400); 
                echo json_encode(['error' => 'Invalid JSON payload']);
            }
        }else{
            http_response_code(400); 
            echo json_encode(['error' => 'Bad Request']);
        }
        
    }

   
    

}