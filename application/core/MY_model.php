<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_model extends CI_Model
{
    protected $table_name = '';
    protected $primary_key = '_id';

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('inflector');

        if (!$this->table_name) {
            $this->table_name = strtolower(plural(get_class($this)));
        }
    }

    public function insert($id, $data = array())
    {
        $this->db->trans_begin();
        $this->db->insert($this->table_name("_model"), $data);

        $success = $this->db->insert_id();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return FALSE;
        } else {
            $this->db->trans_commit();

            if ($success) {
                return $success;
            } else {
                return FALSE;
            }
        }
    }

    public function update($id, $data = array())
    {
        $this->db->trans_begin();

        $this->db->where(singular($this->table_name("_model")) . $this->primary_key, $id);
        $success = $this->db->update($this->table_name("_models"), $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return FALSE;
        } else {
            $this->db->trans_commit();

            if ($success) {
                return $success;
            } else {
                return FALSE;
            }
        }
    }

    public function delete($id, $data = array())
    {
        $this->db->trans_begin();

        $this->db->where(singular($this->table_name("_model")) . $this->primary_key, $id);
        $success =  $this->db->delete($this->table_name("_models"));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return FALSE;
        } else {
            $this->db->trans_commit();

            if ($success) {
                return $success;
            } else {
                return FALSE;
            }
        }
    }

    public function total_results()
    {
        return $this->db
            ->query("SELECT FOUND_ROWS() as total")
            ->row()
            ->total;
    }

    public function recalculate_recipes_price_by_ingredient_change($id){
        $stored_procedure = "CALL ingredient_change_listener(?)";
        return $this->db->query($stored_procedure, array("ingredient_id" => $id));
    }

    public function recalculate_recipes_price_by_recipe_change($id){
        $stored_procedure = "CALL recipe_change_listener(?)";
        $this->db->query($stored_procedure, array("subrecipe_id" => $id));
    }

    private function table_name($string_for_remove)
    {
        return substr($this->table_name, 0, strpos($this->table_name, $string_for_remove));
    }
}