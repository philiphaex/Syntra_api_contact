<?php


namespace Contact;
session_start();

require_once '../lib/db.php';

use Db;
$db = new Db\Database();

class Contact{
    private $_db;
    private $_user_id;
    public $_firstname;
    public $_email;

    public function __construct($db)
    {
        $this->_user_id=$user_id = $_SESSION['user_id'];
        $this->_db=$db;
    }

    public function add($firstname, $email)
    {
        
        $this->_firstname=$firstname;
        $this->_email=$email;

        $data= array(
            'firstname'=>$this->_firstname,
            'email'=>$this->_email,
            'user_id'=>$this->_user_id
        );

        
        $db->connect();
        $db->insert('persons',$data);
        
    }
    public function getContact($keyword){

        $this->_firstname =$keyword;
        $db = $this->_db;
        $db->connect();
        $db->select('persons', '*', '', 'persons.firstname="' . $this->_fi . '" AND users.email="' . $this->_password . '"')
        
       
        /*    $data = /** whatever you're serializing **/;
        /*header('Content-Type: application/json');
        echo json_encode($data);*/
    }
}
