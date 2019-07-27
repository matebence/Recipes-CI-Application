<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Recipes_model extends MY_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert($id, $data = array())
    {
        $this->db->trans_begin();
        $this->db->insert("recipes", $data["recipe"]);

        $recipe_id = $this->db->insert_id();
        if($recipe_id){
            if((!empty($data["ingredient"]["ingredients_id"])) && (strcmp($data["ingredient"]["ingredients_id"][0],"x") < 0)){
                for($i = 0; $i < count($data["ingredient"]["ingredients_id"]); $i++) {
                    $recipe_ingredient_items = array(
                        "recipe_id" => addslashes($recipe_id),
                        "ingredient_id" => addslashes($data["ingredient"]["ingredients_id"][$i]),
                        "quantity" => addslashes($data["ingredient"]["ingredients_quantity"][$i]),
                        "unit_id" => addslashes($data["ingredient"]["ingredients_units"][$i]),
                    );
                    $this->db->insert("recipe_ingredient_items", $recipe_ingredient_items);
                }
            }

            if((!empty($data["subrecipe"]["subrecipes_id"])) && (strcmp($data["subrecipe"]["subrecipes_id"][0],"x") < 0)) {
                for($i = 0; $i < count($data["subrecipe"]["subrecipes_id"]); $i++) {
                    $recipe_subrecipe_items = array(
                        "recipe_id" => addslashes($recipe_id),
                        "subrecipe_id" => addslashes($data["subrecipe"]["subrecipes_id"][$i]),
                        "quantity" => addslashes($data["subrecipe"]["subrecipes_quantity"][$i]),
                        "unit_id" => addslashes($data["subrecipe"]["subrecipes_units"][$i]),
                    );
                    $this->db->insert("recipe_subrecipe_items", $recipe_subrecipe_items);
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function update($id, $data = array())
    {
        $this->db->trans_begin();

        $this->db->where("recipe_id", $id);
        $success = $this->db->update("recipes", $data["recipe"]);

        if($success){
            if((!empty($data["ingredient"]["ingredients_id"])) && (strcmp($data["ingredient"]["ingredients_id"][0],"x") < 0)){
                $this->db->where("recipe_id", addslashes($id));
                $this->db->delete("recipe_ingredient_items");

                for($i = 0; $i < count($data["ingredient"]["ingredients_id"]); $i++) {
                    $recipe_ingredient_items = array(
                        "recipe_id" => addslashes($id),
                        "ingredient_id" => addslashes($data["ingredient"]["ingredients_id"][$i]),
                        "quantity" => addslashes($data["ingredient"]["ingredients_quantity"][$i]),
                        "unit_id" => addslashes($data["ingredient"]["ingredients_units"][$i]),
                    );
                    $this->db->insert("recipe_ingredient_items", $recipe_ingredient_items);
                }
            }

            if((!empty($data["subrecipe"]["subrecipes_id"])) && (strcmp($data["subrecipe"]["subrecipes_id"][0],"x") < 0)) {
                for($i = 0; $i < count($data["subrecipe"]["subrecipes_id"]); $i++) {
                    $this->db->where("recipe_id", addslashes($id));
                    $this->db->where("subrecipe_id", addslashes($data["subrecipe"]["subrecipes_id"][$i]));
                    $this->db->delete("recipe_subrecipe_items");

                    if($data["subrecipe"]["subrecipes_units"][$i] != 0){
                        $recipe_subrecipe_items = array(
                            "recipe_id" => addslashes($id),
                            "subrecipe_id" => addslashes($data["subrecipe"]["subrecipes_id"][$i]),
                            "quantity" => addslashes($data["subrecipe"]["subrecipes_quantity"][$i]),
                            "unit_id" => addslashes($data["subrecipe"]["subrecipes_units"][$i]),
                        );
                        $this->db->insert("recipe_subrecipe_items", $recipe_subrecipe_items);
                    }
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return FALSE;
        } else {
            $this->db->trans_commit();
            $this->recalculate_recipes_price_by_recipe_change($id);

            return TRUE;
        }
    }

    public function search($filter = array())
    {
        $this->db
            ->distinct()
            ->select("SQL_CALC_FOUND_ROWS recipes.name as name, recipes.unit_id, recipes.*", FALSE)
            ->from("recipe_ingredient_items")
            ->join("ingredients", "recipe_ingredient_items.ingredient_id = ingredients.ingredient_id", "left")
            ->join("recipes", "recipe_ingredient_items.recipe_id = recipes.recipe_id", "left");

        if(!empty($filter["term"]))
        {
            $this->db->like("recipes.name", $filter["term"]);
        }

        if(!empty($filter["recipe_id"]))
        {
            $this->db->where("recipe_id", $filter["recipe_id"]);
        }

        if(!empty($filter["type"]))
        {
            $this->db->where("recipes.type", $filter["type"]);
        }

        if(!(empty($filter["order_by"]) && (empty($filter["sort"]))))
        {
            $this->db->order_by($filter["order_by"], $filter["sort"]);
        }

        if(!empty($filter["offset"]) && ($filter["offset"] > -1))
        {
            $this->db->offset($filter["offset"]);
        }

        if((!empty($filter["per_page"])) && ($filter["per_page"] > -1))
        {
            $this->db->limit($filter["per_page"]);
        }

        if(!empty($filter["order_by"]))
        {
            $sort = 'asc';
            if (!empty($filter["sort"]) && in_array($filter["sort"], ['asc', 'desc']))
            {
                $sort = $filter["sort"];
            }

            switch ($filter["order_by"])
            {
                case 'recipe_id':
                case 'name':
                    $this->db->order_by("recipes.".$filter["order_by"], $sort);
                    break;
                default:
                    $this->db->order_by('recipes.recipe_id', $sort);
                    break;
            }
        }
        return $this->db
            ->get()
            ->result_array();
    }

    public function list_of_recipes($type){
        return array_column($this->db
            ->select("recipe_id, name")
            ->from("recipes")
            ->where("type", $type)
            ->get()
            ->result_array(), "name", "recipe_id");
    }

    public function recipes_detail($id, $type){
        return $this->db
            ->select("1 AS quantity, recipes.unit_id, units.label, price")
            ->from("recipes")
            ->join("units","units.unit_id = recipes.unit_id","left")
            ->where("type", $type)
            ->where("recipe_id", $id)
            ->get()
            ->result_array();
    }

    public function list_subrecipes_by_recipe($id, $type){
        return $this->db
            ->select("recipes.name, recipes.recipe_id, recipe_subrecipe_items.quantity, recipe_subrecipe_items.unit_id, units.label, recipes.price, units.convt, units.base_unit, u.label as base_unit_label")
            ->from("recipe_subrecipe_items")
            ->join("recipes","recipes.recipe_id = recipe_subrecipe_items.subrecipe_id","left")
            ->join("units","units.unit_id = recipe_subrecipe_items.unit_id","left")
            ->join("units u","u.unit_id = units.base_unit","left")
            ->where("type", $type)
            ->where("recipe_subrecipe_items.recipe_id", $id)
            ->get()
            ->result_array();
    }
}