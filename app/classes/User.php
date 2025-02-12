<?php

class User {
    private $id;
    private $username;


    public function __construct($row) {
        // Assuming $row is an associative array with keys that match column names
        $this->id = $row['id'] ?? null;
        $this->username = $row['username'] ?? null;
    }

    public function set_id ($id){
        $this-> id = $id;
    }
    function get_id() {
        return $this-> id;
    }

    public function set_username ($username){
        $this-> username = $username;
    }
    function get_username() {
        return $this-> username;
    }


}