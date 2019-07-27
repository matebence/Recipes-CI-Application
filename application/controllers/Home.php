<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = array(
            "title" => lang("title_home")
        );

        $this->load->view("home/index", $data);
    }
}