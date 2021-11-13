<?php
include_once('database/database.php');

class User
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    function getUsers()
    {
        return $this->db->query("SELECT * FROM users");
    }

    function createUser($login, $password)
    {
        $password = md5($password);
        return $this->db->query("INSERT INTO users (login, password) VALUES('$login', '$password')");
    }

    function getUserByID($id)
    {
        return $this->db->query("SELECT * FROM users WHERE ID = $id LIMIT 0,1");
    }

    function updateUser($id, $login, $password)
    {
        $password = md5($password);
        return $this->db->query("UPDATE users SET login = '$login', password = '$password' WHERE ID = $id");
    }

    function deleteUser($id)
    {
        return $this->db->query("DELETE FROM users WHERE ID = $id");
    }
}
