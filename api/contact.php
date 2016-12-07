<?php


namespace Contact;
session_start();

require_once '../lib/db.php';

use Db;

$db = new Db\Database();

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        if (isset($_GET['search'])){
            $contact = new Contact($_SESSION['user_id'], $db);
            $contact->getContact($_GET['search']);
        }


    /* case 'GET':

     case 'PUT':

     case 'DELETE':*/
}

class Contact
{
    private $_db;
    private $_user_id;
    public $_firstname;
    public $_email;

    public function __construct($user_id,$db)
    {
        $this->_user_id = $user_id;
        $this->_db = $db;
    }

    public function add($firstname, $email)
    {

        $this->_firstname = $firstname;
        $this->_email = $email;

        $data = array(
            'firstname' => $this->_firstname,
            'email' => $this->_email,
            'user_id' => $this->_user_id
        );


        $db->connect();
        $db->insert('persons', $data);

    }

    public function getContact($keyword)
    {

        $this->_firstname = $keyword;
        $db = $this->_db;
        $db->connect();
        $db->select('persons', '*', '', 'persons.firstname="' . $this->_firstname . '" AND persons.user_id="' . $this->_user_id . '"');

        $data = $db->getResult();
        header('Content-Type: application/json');
        echo json_encode($data)

        /*    $data = /** whatever you're serializing **/;
        /*header('Content-Type: application/json');
        echo json_encode($data);*/
    }
}
