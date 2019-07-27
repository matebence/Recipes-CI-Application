<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends MY_controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("Units_model");
    }

    public function load_units_by_base_unit($unit_id)
    {
        if ($this->input->is_ajax_request()) {
            if (!(is_array($unit_id)) && (is_numeric($unit_id))) {
                echo json_encode($this->Units_model->search(array("base_unit" => addslashes($unit_id))));
            }
        }
    }

    public function load_convt_for_unit($base_unit_id){
        if ($this->input->is_ajax_request()) {
            if (!(is_array($base_unit_id)) && (is_numeric($base_unit_id))) {
                echo json_encode($this->Units_model->find_convt_for_unit(addslashes($base_unit_id)));
            }
        }
    }
}