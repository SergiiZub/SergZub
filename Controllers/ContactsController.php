<?php
namespace Controllers;

use Core\Controller;
use Classes\Session;
use Models\Message;

class ContactsController extends Controller
{
    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Message();
    }

    public function index(){
        if ($_POST){
            if ($this->model->save($_POST)){
                Session::setFlash('Thank you! Your message was send successfully! ');
            }
        }
    }
    
    public function admin_index(){
        $this->data = $this->model->getList();
    }
}