<?php

Class Ticket{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function addTicket($data){
        $lastTicketId = '';
        $this->db->query("INSERT INTO ticket (title , description , priority , creatordId) 
                            VALUES (:title, :description, :priority ,:creatordId)"); 
        $this->db->bind(':title',$data['title']);
        $this->db->bind(':description',$data['description']);
        $this->db->bind(':priority',$data['priority']);
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
            foreach ($data['tags'] as $tagId) {
                $this->db->query("INSERT INTO ticket_tag(ticket_id, tag_id) VALUES (:ticket_id, :tag_id) ");
                $this->db->bind(':ticket_id',$lastTicketId);
                $this->db->bind(':tag_id',$tagId);
                $ticketTag = $this->db->execute();
            }
            return $assigning && $ticketTag ;   
        }else{
            return false ;
        }
        
    }

    public function getAllTickets(){
        $this->db->query("SELECT
        DISTINCT ticket.id as tickeId,
        ticket.title as ticketTitle,
        ticket.description as ticketDesc,
        ticket.createdAt as ticketCreatedAt,
        user.id as creatordId,
        user.full_name as creatorName,
        user.imgUrl as creatorImg,
        priority.Name as priority,
        status.Name as status,
        GROUP_CONCAT(DISTINCT tags.Name) as tags,
        (
            SELECT GROUP_CONCAT( assignedUser.imgUrl)
            FROM ticketassignment
            LEFT JOIN user as assignedUser ON ticketassignment.userId = assignedUser.id
            WHERE ticketassignment.ticketId = ticket.id
        ) as assignedUserImg
    FROM
        ticket
    JOIN priority ON ticket.priority = priority.id
    JOIN status ON ticket.status = status.id
    INNER JOIN ticketassignment ON ticket.id = ticketassignment.ticketId
    INNER JOIN user ON ticket.creatordId = user.id
    INNER JOIN ticket_tag ON ticket.id = ticket_tag.ticket_id
    INNER JOIN tags ON ticket_tag.tag_id = tags.id
    GROUP BY
        tickeId,
        ticketTitle,
        ticketDesc,
        ticketCreatedAt,
        userId,
        creatorName,
        priority,
        status;
    ");
        return $this->db->resultSet();
        
    }

    public function getTicketAssignedTo($userId){
        $this->db->query("
        SELECT
        DISTINCT ticket.id as tickeId,
        ticket.title as ticketTitle,
        ticket.description as ticketDesc,
        ticket.createdAt as ticketCreatedAt,
        user.id as creatordId,
        user.full_name as creatorName,
        user.imgUrl as creatorImg,
        priority.Name as priority,
        status.Name as status,
        GROUP_CONCAT(DISTINCT tags.Name) as tags,
        (
            SELECT GROUP_CONCAT( assignedUser.imgUrl)
            FROM ticketassignment
            LEFT JOIN user as assignedUser ON ticketassignment.userId = assignedUser.id
            WHERE ticketassignment.ticketId = ticket.id 
        ) as assignedUserImg
    FROM
        ticket
    JOIN priority ON ticket.priority = priority.id
    JOIN status ON ticket.status = status.id
    INNER JOIN ticketassignment ON ticket.id = ticketassignment.ticketId
    INNER JOIN user ON ticket.creatordId = user.id
    INNER JOIN ticket_tag ON ticket.id = ticket_tag.ticket_id
    INNER JOIN tags ON ticket_tag.tag_id = tags.id
    AND ticketassignment.userId=:userId 
    GROUP BY
        tickeId,
        ticketTitle,
        ticketDesc,
        ticketCreatedAt,
        userId,
        creatorName,
        priority,
        status;
        
        ");
        $this->db->bind(':userId',$userId);
        return $this->db->resultSet();
    }

    public function assignedTo($ticketId){
        $this->db->query("SELECT user.id , user.full_name, user.email  , ticket.id as tickeId FROM ticketassignment , ticket, user 
        WHERE ticket.id = ticketassignment.ticketId AND ticketassignment.userId = user.id AND ticketassignment.ticketId = :tickedId");
        $this->db->bind(':tickedId' , $ticketId);
        return $this->db->resultSet();
    }

    public function getIncompleteTicket($userId){
        $this->db->query("SELECT ticket.id FROM 
        `ticketassignment` , user , ticket
        WHERE ticketassignment.ticketId = ticket.id
        AND user.id = ticketassignment.userId
        AND ticket.status <>4 AND user.id = :userId");
        $this->db->bind(':userId', $userId);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getMyTickets($userId){
        $this->db->query("
        SELECT
        DISTINCT ticket.id as tickeId,
        ticket.title as ticketTitle,
        ticket.description as ticketDesc,
        ticket.createdAt as ticketCreatedAt,
        user.id as creatordId,
        user.full_name as creatorName,
        user.imgUrl as creatorImg,
        priority.Name as priority,
        status.Name as status,
        GROUP_CONCAT(DISTINCT tags.Name) as tags,
        (
            SELECT GROUP_CONCAT( assignedUser.imgUrl)
            FROM ticketassignment
            LEFT JOIN user as assignedUser ON ticketassignment.userId = assignedUser.id
            WHERE ticketassignment.ticketId = ticket.id 
        ) as assignedUserImg
    FROM
        ticket
    JOIN priority ON ticket.priority = priority.id
    JOIN status ON ticket.status = status.id
    INNER JOIN ticketassignment ON ticket.id = ticketassignment.ticketId
    INNER JOIN user ON ticket.creatordId = user.id
    INNER JOIN ticket_tag ON ticket.id = ticket_tag.ticket_id
    INNER JOIN tags ON ticket_tag.tag_id = tags.id
    AND creatordId=:userId
    GROUP BY
        tickeId,
        ticketTitle,
        ticketDesc,
        ticketCreatedAt,
        userId,
        creatorName,
        priority,
        status;
        
        ");
        $this->db->bind(':userId',$userId);
        return $this->db->resultSet();
    }

   
}