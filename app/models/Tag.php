<?php

Class Tag{

    private $db;
    public function __construct(){
        $this->db = new Database();
    }

    public function getAllTags(){
        $this->db->query("SELECT * FROM tags");
        return $this->db->resultSet();
    }
}