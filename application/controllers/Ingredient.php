<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ingredient extends MY_controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("Ingredients_model");
        $this->load->model("Units_model");
    }

    public function index()
    {
        $data = array(
            "title" => lang("title_ingredients"),
            "ingredient" => true
        );
        $filter = $this->_prepare_filter('ingredients', TRUE);

        $data['filter'] = $filter;
        $data['results'] = $this->Ingredients_model->search($filter);
        $data['total_results'] = $this->Ingredients_model->total_results();
        $data['ingredient_units'] = array("x" => "Zvoľte si jednotku") + $this->Units_model->list_of_units();

        $this->load->view("ingredient/index", $data);
    }

    public function create()
    {
        $ingredient = array();
        if (($this->_validate_input_for_ingredients()) && ($this->input->is_ajax_request())) {

            if ((!is_array($this->input->post("ingredient_name"))) ||
                (!is_array($this->input->post("ingredient_price"))) ||
                (!is_array($this->input->post("ingredient_quantity"))) ||
                (!is_array($this->input->post("ingredient_unit")))) {

                $new_ingredient = array(
                    "name" => addslashes($this->input->post("ingredient_name")),
                    "price" => addslashes($this->input->post("ingredient_price")),
                    "quantity" => addslashes($this->input->post("ingredient_quantity")),
                    "unit_id" => addslashes($this->input->post("ingredient_unit"))
                );

                $success = $this->Ingredients_model->insert(null, $new_ingredient);
                if ($success) {
                    $ingredient["inserted"] = $success;
                    echo json_encode($ingredient);
                } else {
                    echo json_encode($ingredient);
                }
            } else {
                echo json_encode($ingredient);
            }
        } else {
            echo json_encode(array("error" => true, "validation" => validation_errors_array()));
        }
    }

    public function update($ingredient_id)
    {
        $ingredient = array();
        if (($this->_validate_input_for_ingredients()) && ($this->input->is_ajax_request()) && (is_numeric($ingredient_id))) {

            if ((!is_array($this->input->post("ingredient_name"))) ||
                (!is_array($this->input->post("ingredient_price"))) ||
                (!is_array($this->input->post("ingredient_quantity"))) ||
                (!is_array($this->input->post("ingredient_unit")))) {

                $edit_ingredient = array(
                    "name" => addslashes($this->input->post("ingredient_name")),
                    "price" => addslashes($this->input->post("ingredient_price")),
                    "quantity" => addslashes($this->input->post("ingredient_quantity")),
                    "unit_id" => addslashes($this->input->post("ingredient_unit"))
                );

                $success = $this->Ingredients_model->update(addslashes($ingredient_id), $edit_ingredient);
                if ($success) {
                    $ingredient["updated"] = $success;
                    echo json_encode($ingredient);
                } else {
                    echo json_encode($ingredient);
                }
            } else {
                echo json_encode($ingredient);
            }
        } else {
            echo json_encode(array("error" => true, "validation" => validation_errors_array()));
        }
    }

    public function delete($ingredient_id)
    {
        $ingredient = array();
        if (($this->input->is_ajax_request())) {

            if (is_numeric($ingredient_id)) {
                $success = $this->Ingredients_model->delete(addslashes($ingredient_id));
                if ($success) {
                    $ingredient["deleted"] = $success;
                    echo json_encode($ingredient);
                } else {
                    echo json_encode($ingredient);
                }
            } else {
                echo json_encode($ingredient);
            }
        } else {
            echo json_encode($ingredient);
        }
    }

    public function load_all_ingredients($term)
    {
        if ($this->input->is_ajax_request()) {
            if(is_string($term)){
               echo json_encode($this->Ingredients_model->search(array("term" => addslashes($term))));
            }
        }
    }

    public function load_ingredients_detail($ingredient_id)
    {
        if ($this->input->is_ajax_request()) {
            if (is_numeric($ingredient_id)) {
                echo json_encode($this->Ingredients_model->ingredients_detail(addslashes($ingredient_id)));
            }else{
                echo json_encode(array());
            }
        }
    }

    public function load_recipe_ingredient_items($recipe_id){
        if ($this->input->is_ajax_request()) {
            if (is_numeric($recipe_id)) {
                echo json_encode($this->Ingredients_model->list_ingredients_by_recipe(addslashes($recipe_id)));
            }
        }
    }

    private function _validate_input_for_ingredients()
    {
        $this->form_validation->set_rules('ingredient_name',
            'Ingredient name',
            'required|max_length[255]|regex_match[/^[a-ž ]+$/i]',
            array('required' => lang("validation_rule_ingredient_name_required"),
                'max_length' => lang("validation_rule_ingredient_name_max"),
                'regex_match' => lang("validation_rule_ingredient_name_regex")));
        $this->form_validation->set_rules('ingredient_price',
            'Ingredient price',
            'required|numeric',
            array('required' => lang('validation_rule_ingredient_price_required'),
                'numeric' => lang("validation_rule_ingredient_price_numeric")));
        $this->form_validation->set_rules('ingredient_quantity',
            'Ingredient quantity',
            'required|numeric',
            array('required' => lang('validation_rule_ingredient_quantity_required'),
                'numeric' => lang("validation_rule_ingredient_quantity_numeric")));
        $this->form_validation->set_rules('ingredient_unit',
            'Ingredient unit',
            'required|integer',
            array('required' => lang("validation_rule_ingredient_unit_required"),
                'integer' => lang("validation_rule_ingredient_unit_integer")));

        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }
}