<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Ingredients_model extends MY_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update($id, $data = array())
    {
        parent::update($id, $data);
        return $this->recalculate_recipes_price_by_ingredient_change($id);
    }

    public function search($filter = array())
    {
        $this->db
            ->select("SQL_CALC_FOUND_ROWS units.label as label, units.base_unit as base_unit, ingredients.*", FALSE)
            ->from("ingredients")
            ->join("units", "units.unit_id=ingredients.unit_id", "left");

        if(!empty($filter["term"]))
        {
            $this->db->like("name", $filter["term"]);
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
                case 'ingredient_id':
                case 'name':
                        $this->db->order_by("ingredients.".$filter["order_by"], $sort);
                    break;
                default:
                    $this->db->order_by('ingredients.ingredient_id', $sort);
                    break;
            }
        }

        return $this->db
            ->get()
            ->result_array();
    }

    public function list_of_ingredients(){
        return array_column($this->db
            ->select("ingredient_id, name")
            ->from("ingredients")
            ->get()
            ->result_array(), "name", "ingredient_id");
    }

    public function ingredients_detail($id){
        return $this->db
            ->select("1 AS quantity, ingredients.unit_id, units.label, price")
            ->from("ingredients")
            ->join("units","units.unit_id=ingredients.unit_id","left")
            ->where("ingredient_id", $id)
            ->get()
            ->result_array();
    }

    public function list_ingredients_by_recipe($id){
        return $this->db
            ->select("ingredients.name, ingredients.ingredient_id, recipe_ingredient_items.quantity, recipe_ingredient_items.unit_id, units.label, ingredients.price, units.convt, units.base_unit, u.label as base_unit_label")
            ->from("recipe_ingredient_items")
            ->join("ingredients","ingredients.ingredient_id = recipe_ingredient_items.ingredient_id","left")
            ->join("units","units.unit_id = recipe_ingredient_items.unit_id","left")
            ->join("units u","u.unit_id = units.base_unit","left")
            ->where("recipe_id", $id)
            ->get()
            ->result_array();
    }
}