<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Units_model extends MY_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function search($filter = array())
    {
        $this->db
            ->select("SQL_CALC_FOUND_ROWS units.*", FALSE)
            ->from("units");

        if(!empty($filter["term"]))
        {
            $this->db->like("label", $filter["term"]);
        }

        if(!empty($filter["unit_id"]))
        {
            $this->db->where("unit_id", $filter["unit_id"]);
        }

        if(!empty($filter["base_unit"]))
        {
            $this->db->where("base_unit", $filter["base_unit"]);
        }

        if(!(empty($filter["order_by"]) && (empty($filter["sort"]))))
        {
            $this->db->order_by($filter["order_by"], $filter["sort"]);
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
                case 'unit_id':
                case 'label':
                    $this->db->order_by("units.".$filter["order_by"], $sort);
                    break;
                default:
                    $this->db->order_by('units.unit_id', $sort);
                    break;
            }
        }
        return $this->db
            ->get()
            ->result_array();
    }

    public function list_of_units(){
        return array_column($this->db
            ->select("unit_id, label")
            ->from("units")
            ->where("base_unit", null)
            ->get()
            ->result_array(), "label", "unit_id");
    }

    public function find_convt_for_unit($id){
        return $this->db
            ->select("units.unit_id, units.label, convt")
            ->from("units")
            ->where("base_unit", $id)
            ->get()
            ->result_array();
    }
}