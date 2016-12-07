<?php


namespace User;
session_start();

require_once '../lib/db.php';

use Db;
$db = new Db\Database();

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'POST':
        if (!isset($_POST['email'])) {
            $user = new User($_POST['username'], $_POST['password'], $db);
            $user->authentication();
        }
        if (isset($_POST['email'])){
            $user = new User($_POST['username'], $_POST['password'], $db);
            $user->register($_POST['email']);
        }


   /* case 'GET':

    case 'PUT':

    case 'DELETE':*/
}


class User
{
    private $_db;
    private $_username;
    private $_password;
    private $_email;


    public function __construct($username, $password, $db)
    {
        $this->_db=$db;
        $this->_username = $username;
        $this->_password = $password;
    }


    public function authentication()
    {
        $db = $this->_db;
        $db->connect();
        $db->select('users', '*', '', 'users.login="' . $this->_username . '" AND users.password="' . $this->_password . '"');

        $res = $db->getResult();
        if (count($res) == 1) {
            /*print_r($res);*/
            $_SESSION['user_id'] = $res[0]['id'];
            header('Location: ../app.php');
            return $res[0]['id'];
        }

    }

    public function register($email)
    {
        $db = $this->_db;
        $db->connect();

        $this->_email = $email;

        $data= array(
            'login'=>$this->_username,
            'email'=>$this->_email,
            'password'=>$this->_password,
        );

        $db->insert('users',$data);
        header('Location: ../index.php');
    }
}