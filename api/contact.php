<?php


namespace Contact;
session_start();

require_once '../lib/db.php';

use Db;

$db = new Db\Database();

$method = $_SERVER['REQUEST_METHOD'];/*$contact = new Contact('2', $db);
$contact->getAll()*/;

switch ($method) {
    case 'GET':
        $contact = new Contact($_SESSION['user_id'], $db);
        var_dump($_GET);
        if (!isset($_GET['keyword'])) {
            $contact->getAll();
        } else {
            $contact->getContact($_GET['keyword']);

        }
        break;
    case 'POST':
        $contact = new Contact($_SESSION['user_id'], $db);
        $contact->addContact($_POST['firstname'], $_POST['email']);
//            var_dump($_POST);
        break;
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

    public function __construct($user_id, $db)
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
        $this->_email = $keyword;
        $db = $this->_db;
        $db->connect();
//        $db->select('persons', '*', '', 'persons.user_id="' . $this->_user_id . '" AND (persons.email="' . $this->_email . '" OR persons.firstname="' . $this->_firstname . '")', '', '');
        $db->select('persons', '*', '', 'persons.user_id =" ' . $this->_user_id . '" AND (persons.email LIKE "%' . $this->_email . '%" OR persons.firstname LIKE "%' . $this->_firstname . '%")', '', '');

        $data = $db->getResult();
        header('Content-Type: application/json');
        echo json_encode($data)/*    $data = /** whatever you're serializing **/
        ;
        /*header('Content-Type: application/json');
        echo json_encode($data);*/
    }

    public function addContact($firstname, $email)
    {
        $this->_firstname = $firstname;
        $this->_email = $email;
        $db = $this->_db;
        $db->connect();

        $data = array(
            'user_id' => $this->_user_id,
            'firstname' => $this->_firstname,
            'email' => $this->_email,

        );

        $db->insert('persons', $data);
    }

    public function getAll()
    {
        $db = $this->_db;
        $db->connect();
//        $db->select('persons', '*', '', 'persons.user_id="2"','','');
        $db->select('persons', '*', '', 'persons.user_id="' . $this->_user_id . '"', '', '');
        $data = $db->getResult();
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
