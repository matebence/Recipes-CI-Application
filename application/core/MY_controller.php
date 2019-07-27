<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->lang->load('information', 'slovak');

        $this->load->helper('html');
        $this->load->helper('language');
        $this->load->helper('form');
    }

    public function _prepare_filter($param = 'table', $reset_pagination = FALSE)
    {
        $default = array(
            'offset' => 0,
            'per_page' => 5,
            'term' => '',
            'type' => '',
            'order_by' => '',
            'sort' => ''
        );

        $filters = $default;

        if (session_id() == '') session_start();
        $stored_filter = isset($_SESSION[$param]) ? $_SESSION[$param] : array();

        foreach ($filters as $filter_type => $filter_value) {
            if (isset($_REQUEST[$filter_type]) && is_scalar($_REQUEST[$filter_type])) {
                $filters[$filter_type] = addslashes($_REQUEST[$filter_type]);
            } elseif (isset($stored_filter[$filter_type])) {
                $filters[$filter_type] = addslashes($stored_filter[$filter_type]);
            }
        }

        if ($reset_pagination && !isset($_REQUEST['offset'])) $filters['offset'] = 0;

        if (isset($_REQUEST['reset']) && $_REQUEST['reset'] == 1) {
            $filters = $default;
        }

        $_SESSION[$param] = $filters;
        return $filters;
    }
}