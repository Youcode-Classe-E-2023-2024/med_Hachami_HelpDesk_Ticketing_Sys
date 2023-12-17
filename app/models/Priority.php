<?php

Class Priority{

    private $db;
    public function __construct(){
        $this->db = new Database();
    }

    public function getAllPriorities(){
        $this->db->query("SELECT * FROM priority");
        return $this->db->resultSet();
    }
}