<?php

class Database
{
    private $db;

    function __construct()
    {
        $this->db = mysqli_connect("db", "root", "secret", "tictactoeDB");
    }

    function real_escape_string($data)
    {
        return mysqli_real_escape_string($this->db, $data);
    }

    function checkDataUsers($data)
    {
        $user_check_query = "SELECT * FROM users WHERE login='$data' LIMIT 1";
        $result = mysqli_query($this->db, $user_check_query);
        return mysqli_fetch_assoc($result);
    }

    function setPasswordUser($login, $password)
    {
        $password = md5($password);

        $query = "INSERT INTO users (login, password) 
                  VALUES('$login', '$password')";
        mysqli_query($this->db, $query);
    }

    function getUserByLoginPassword($login, $password)
    {
        $password = md5($password);
        $query = "SELECT * FROM users WHERE login='$login' AND password='$password'";
        return mysqli_query($this->db, $query);
    }

    function query($data)
    {
        return $this->db->query($data);
    }
}
