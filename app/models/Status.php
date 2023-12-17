<?php

Class Status{

    private $db;
    public function __construct(){
        $this->db = new Database();
    }

    public function getAllStatus() {
        $this->db->query("SELECT * FROM status");
        return $this->db->resultSet();
    }
}