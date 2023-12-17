<?php

Class Ticket{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function addTicket($data){
        $this->db->query("INSERT INTO ticket (title , description , priority , tag , creatordId) 
                            VALUES (:title, :description, :priority , :tag , :creatordId)"); 
        $this->db->bind(':title',$data['title']);
        $this->db->bind(':description',$data['description']);
        $this->db->bind(':priority',$data['priority']);
        $this->db->bind(':tag',$data['tag']);
        $this->db->bind(':creatordId',$data['creatordId']);
        $insertIntoTicket = $this->db->execute();
        if($insertIntoTicket){
            $this->db->query("SELECT id FROM ticket ORDER BY id DESC LIMIT 1;");
            $lastTicketId = $this->db->single()->id;
            foreach($data['assignedTo'] as $userId){
                $this->db->query("INSERT INTO ticketassignment (ticketId, userId) VALUES (:ticketId, :userId)");
                $this->db->bind(':ticketId',$lastTicketId);
                $this->db->bind(':userId',$userId);
                $assigning = $this->db->execute();

            }  
            return $assigning ;   
        }else{
            return false ;
        }
        
    }
}