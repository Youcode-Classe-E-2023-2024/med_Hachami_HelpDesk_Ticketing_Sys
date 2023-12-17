<?php

Class User{
    private $db;
    public function __construct(){
        $this->db = new Database();
    }

    public function register($data){
        $this->db->query("INSERT INTO user (full_name , email , password) VALUES (:full_name , :email , :password) ");
        $this->db->bind(':full_name' , $data['full_name'] );
        $this->db->bind(':email' , $data['email'] );
        $this->db->bind(':password' , $data['password'] );

        return $this->db->execute();
    }

    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM user WHERE email = :email');
        $this->db->bind(':email', $email);
    
        $row = $this->db->single();
    
        return ($row) ? true : false;
    }

    public function getAllUsers(){
      $this->db->query('SELECT * FROM user');
      return $this->db->resultSet();
    }

    public function login($email, $password){
        $this->db->query('SELECT * FROM user WHERE email = :email ');
        $this->db->bind(':email', $email);
    
        $row = $this->db->single();
        
        if($row){
         
          $hashed_password = $row->password;
          if(password_verify($password, $hashed_password)){
            $this->db->query('SELECT id,email,full_name FROM user WHERE email = :email ');
            $this->db->bind(':email', $email);
            return $this->db->single();
          } else {
            return false;
          }
        }else{
          return false;
        }
        
      }
}