<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Recipe extends MY_controller
{
    const RECIPE_TYPE = "recipe";
    const SUBRECIPE_TYPE = "subrecipe";

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Recipes_model");
        $this->load->model("Ingredients_model");
        $this->load->model("Units_model");
    }

    public function index()
    {
        $data = array(
            "title" => lang("title_recipes"),
            "ingredient" => true
        );
        $filter = $this->_prepare_filter('recipes', TRUE);
        $filter["type"] = self::RECIPE_TYPE;

        $data['filter'] = $filter;
        $data['results'] = $this->Recipes_model->search($filter);
        $data['total_results'] = $this->Recipes_model->total_results();
        $data['recipe_ingredients'] = array("x" => "Zvoľte si Ingredienciu") + $this->Ingredients_model->list_of_ingredients();
        $data['recipe_subrecipes'] = array("x" => "Zvoľte si subrecept") + $this->Recipes_model->list_of_recipes(self::SUBRECIPE_TYPE);

        $this->load->view("recipe/index", $data);
    }

    public function subrecipes()
    {
        $data = array(
            "title" => lang("title_subrecipes"),
            "ingredient" => true
        );
        $filter = $this->_prepare_filter('recipes', TRUE);
        $filter["type"] = self::SUBRECIPE_TYPE;

        $data['filter'] = $filter;
        $data['results'] = $this->Recipes_model->search($filter);
        $data['total_results'] = $this->Recipes_model->total_results();
        $data['recipe_ingredients'] = array("x" => "Zvoľte si Ingredienciu") + $this->Ingredients_model->list_of_ingredients();
        $data['recipe_subrecipes'] = array("x" => "Zvoľte si subrecept") + $this->Recipes_model->list_of_recipes(self::SUBRECIPE_TYPE);
        $data['subrecipe_units'] = array("x" => "Zvoľte si jednotku") + $this->Units_model->list_of_units();

        $this->load->view("recipe/index", $data);
    }

    public function create($type){
        $recipe = array();
        if (($this->_validate_input_for_recipes()) && ($this->input->is_ajax_request())) {

            if ((!is_array($this->input->post("recipe_name"))) ||
                (!is_array($this->input->post("recipe_serving"))) ||
                (is_array($this->input->post("recipe_ingredients"))) ||
                (is_array($this->input->post("ingredients_quantity"))) ||
                (is_array($this->input->post("ingredients_units"))) ||
                (is_array($this->input->post("ingredient_price"))) ||
                (is_array($this->input->post("recipe_subrecipes"))) ||
                (is_array($this->input->post("subrecipes_quantity"))) ||
                (is_array($this->input->post("subrecipe_units"))) ||
                (is_array($this->input->post("subrecipe_price")))) {

                $new_recipe = array(
                    "recipe" => array(
                        "name" => addslashes($this->input->post("recipe_name")),
                        "serving" => addslashes($this->input->post("recipe_serving")),
                        "price" => addslashes($this->_caculate_total_price($this->input->post("ingredient_price"), $this->input->post("subrecipe_price"))),
                        "unit_id" => addslashes($this->input->post("recipe_unit"))
                    ),
                    "ingredient" => array(
                        "ingredients_id" => $this->input->post("recipe_ingredients"),
                        "ingredients_quantity" => $this->input->post("ingredients_quantity"),
                        "ingredients_units" => $this->input->post("ingredients_units"),
                        "ingredients_price" => $this->input->post("ingredient_price"),
                    ),
                    "subrecipe" => array(
                        "subrecipes_id" => $this->input->post("recipe_subrecipes"),
                        "subrecipes_quantity" => $this->input->post("subrecipes_quantity"),
                        "subrecipes_units" => $this->input->post("subrecipe_units"),
                        "subrecipes_price" => $this->input->post("subrecipe_price"),
                    ),
                );

                if(strcmp($type, "recipe") == 0){
                    $new_recipe["recipe"]["type"] = addslashes(Recipe::RECIPE_TYPE);
                    $new_recipe["recipe"]["unit_id"] = addslashes(10);
                }else{
                    $new_recipe["recipe"]["type"] = addslashes(Recipe::SUBRECIPE_TYPE);
                    $new_recipe["recipe"]["unit_id"] = addslashes($this->input->post("recipe_unit"));
                }

                $success = $this->Recipes_model->insert(null, $new_recipe);
                if ($success) {
                    $recipe["inserted"] = $success;
                    echo json_encode($recipe);
                } else {
                    echo json_encode($recipe);
                }
            } else {
                echo json_encode($recipe);
            }
        } else {
            echo json_encode(array("error" => true, "validation" => validation_errors_array()));
        }
    }

    public function update($type, $recipe_id){
        $recipe = array();
        if (($this->_validate_input_for_recipes()) && ($this->input->is_ajax_request()) && (is_numeric($recipe_id))) {

            if ((!is_array($this->input->post("recipe_name"))) ||
                (!is_array($this->input->post("recipe_serving"))) ||
                (is_array($this->input->post("recipe_ingredients"))) ||
                (is_array($this->input->post("ingredients_quantity"))) ||
                (is_array($this->input->post("ingredients_units"))) ||
                (is_array($this->input->post("ingredient_price"))) ||
                (is_array($this->input->post("recipe_subrecipes"))) ||
                (is_array($this->input->post("subrecipes_quantity"))) ||
                (is_array($this->input->post("subrecipe_units"))) ||
                (is_array($this->input->post("subrecipe_price")))) {

                $edit_recipe = array(
                    "recipe" => array(
                        "name" => addslashes($this->input->post("recipe_name")),
                        "serving" => addslashes($this->input->post("recipe_serving")),
                        "price" => addslashes($this->_caculate_total_price($this->input->post("ingredient_price"), $this->input->post("subrecipe_price"))),
                        "unit_id" => addslashes($this->input->post("recipe_unit"))
                    ),
                    "ingredient" => array(
                        "ingredients_id" => $this->input->post("recipe_ingredients"),
                        "ingredients_quantity" => $this->input->post("ingredients_quantity"),
                        "ingredients_units" => $this->input->post("ingredients_units"),
                        "ingredients_price" => $this->input->post("ingredient_price"),
                    ),
                    "subrecipe" => array(
                        "subrecipes_id" => $this->input->post("recipe_subrecipes"),
                        "subrecipes_quantity" => $this->input->post("subrecipes_quantity"),
                        "subrecipes_units" => $this->input->post("subrecipe_units"),
                        "subrecipes_price" => $this->input->post("subrecipe_price"),
                    ),
                    "deleted" => array(
                        "subrecipes_id" => $this->input->post("recipe_subrecipes_deleted"),
                        "ingredients_id" => $this->input->post("recipe_ingredients_deleted"),
                    )
                );

                if(strcmp($type, "recipe") == 0){
                    $new_recipe["recipe"]["type"] = addslashes(Recipe::RECIPE_TYPE);
                    $new_recipe["recipe"]["unit_id"] = addslashes(10);
                }else{
                    $new_recipe["recipe"]["type"] = addslashes(Recipe::SUBRECIPE_TYPE);
                    $new_recipe["recipe"]["unit_id"] = addslashes($this->input->post("recipe_unit"));
                }

                $success = $this->Recipes_model->update(addslashes($recipe_id), $edit_recipe);
                if ($success) {
                    $recipe["updated"] = $success;
                    echo json_encode($recipe);
                } else {
                    echo json_encode($recipe);
                }
            } else {
                echo json_encode($recipe);
            }
        } else {
            echo json_encode(array("error" => true, "validation" => validation_errors_array()));
        }
    }

    public function delete($recipe_id){
        $recipe = array();
        if (($this->input->is_ajax_request())) {

            if (is_numeric($recipe_id)) {
                $success = $this->Recipes_model->delete(addslashes($recipe_id));
                if ($success) {
                    $recipe["deleted"] = $success;
                    echo json_encode($recipe);
                } else {
                    echo json_encode($recipe);
                }
            } else {
                echo json_encode($recipe);
            }
        } else {
            echo json_encode($recipe);
        }
    }

    public function load_subrecipes_detail($recipe_id){
        if ($this->input->is_ajax_request()) {
            if (is_numeric($recipe_id)) {
                echo json_encode($this->Recipes_model->recipes_detail(addslashes($recipe_id), self::SUBRECIPE_TYPE));
            }else{
                echo json_encode(array());
            }
        }
    }

    public function load_recipe_subrecipe_items($recipe_id){
        if ($this->input->is_ajax_request()) {
            if (is_numeric($recipe_id)) {
                echo json_encode($this->Recipes_model->list_subrecipes_by_recipe(addslashes($recipe_id), self::SUBRECIPE_TYPE));
            }
        }
    }

    private function _caculate_total_price($ingredients, $subrecipes){
        if(($ingredients != null) && ($subrecipes != null)){
            return (array_sum($ingredients)+array_sum($subrecipes));
        }else if(($ingredients != null) && ($subrecipes == null)){
            return (array_sum($ingredients));
        }else {
            return 0;
        }
    }

    private function _validate_input_for_recipes(){
        $this->form_validation->set_rules('recipe_name',
            'Recipe name',
            'required|max_length[255]|regex_match[/^[a-ž ]+$/i]',
            array('required' => lang("validation_rule_recipe_name_required"),
                'max_length' => lang("validation_rule_recipe_name_max"),
                'regex_match' => lang("validation_rule_recipe_name_regex")));
        $this->form_validation->set_rules('recipe_serving',
            'Recipe amount',
            'required|numeric',
            array('required' => lang('validation_rule_recipe_serving_required'),
                'numeric' => lang("validation_rule_recipe_serving_numeric")));

        if(!empty($_POST["recipe_ingredients"])) {
            foreach($this->input->post('recipe_ingredients') as $ind=>$val)
            {
                $this->form_validation->set_rules("recipe_ingredients[".$ind."]",
                    "Recipe ingredients",
                    'required|numeric',
                    array('required' => lang('validation_rule_recipe_ingredients_required'),
                        'numeric' => lang("validation_rule_recipe_ingredients_numeric")));
                $this->form_validation->set_rules("ingredients_quantity[".$ind."]",
                    "Ingredients quantity",
                    'required|numeric',
                    array('required' => lang('validation_rule_ingredients_quantity_required'),
                        'numeric' => lang("validation_rule_ingredients_quantity_numeric")));
                $this->form_validation->set_rules("ingredients_units[".$ind."]",
                    "Ingredients units",
                    'required|numeric',
                    array('required' => lang('validation_rule_ingredients_units_required'),
                        'numeric' => lang("validation_rule_ingredients_units_numeric")));
                $this->form_validation->set_rules("ingredient_price[".$ind."]",
                    "Ingredients price",
                    'required|numeric',
                    array('required' => lang('validation_rule_ingredients_price_required'),
                        'numeric' => lang("validation_rule_ingredients_price_numeric")));
            }
        }

        if(!empty($_POST["recipe_subrecipes"])){
            foreach($this->input->post('recipe_subrecipes') as $ind=>$val)
            {
                $this->form_validation->set_rules("recipe_subrecipes[".$ind."]",
                    "Recipe subrecipes",
                    'alpha_numeric',
                    array('alpha_numeric' => lang("validation_rule_recipe_subrecipes_alpha_numeric")));
                $this->form_validation->set_rules("subrecipes_quantity[".$ind."]",
                    "Subrecipe quantity",
                    'numeric',
                    array('numeric' => lang("validation_rule_subrecipes_quantity_numeric")));
                $this->form_validation->set_rules("subrecipe_units[".$ind."]",
                    "Subrecipes units",
                    'alpha_numeric',
                    array('alpha_numeric' => lang("validation_rule_subrecipes_units_alpha_numeric")));
                $this->form_validation->set_rules("subrecipe_price[".$ind."]",
                    "Subrecipes prices",
                    'numeric',
                    array('alpha_numeric' => lang("validation_rule_subrecipes_prices_numeric")));
            }
        }

        if ($this->form_validation->run() == true) {
            return true;
        } else {
            return false;
        }
    }
}