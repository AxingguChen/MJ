<?php
/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 11/2/2015
 * Time: 10:51 PM
 */

class Projects_model extends CI_Model
{
    private $TABLENAME = 'projects';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get total count of projects table
     *
     * @access public
     * @return integer count
     */
    public function get_all_count()
    {
        $query = $this->db->count_all($this->TABLENAME);
        return $query;
    }

    /**
     * Get all records of projects
     *
     * @access public
     * @param integer $offset
     * @param integer $limit
     * @param string $order
     * @param string $direction
     * @return array records
     */
    public function get_all($offset = 0, $limit = 0, $order = 'projects.projects_id', $direction = 'desc')
    {
        $this->db->select('*');
        $this->db->from($this->TABLENAME);
        $this->db->join('users', "users.users_id = $this->TABLENAME.projects_users_id");
        if (strlen($order) > 0)
        {
            $this->db->order_by($order, $direction);
        }
        if ($limit > 0)
        {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get a record of projects which id
     * is $id
     *
     * @access public
     * @param integer $id
     * @return array records
     */
    public function get_by_id($id)
    {
        if ($id <= 0)
        {
            return -1;
        }
        $this->db->select('*');
        $this->db->from($this->TABLENAME);
        $this->db->join('users', "users.users_id = $this->TABLENAME.projects_users_id");
        $this->db->where("$this->TABLENAME.projects_id", $id);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get all records of projects by specific user id
     *
     * @access public
     * @param integer $user_id
     * @return array records
     */
    public function get_by_users_id($users_id)
    {
        if ($users_id <= 0)
        {
            return -1;
        }
        $this->db->select('*');
        $this->db->from($this->TABLENAME);
        $this->db->where("$this->TABLENAME.projects_users_id", $users_id);
        $query = $this->db->get();
        return $query->result();
    }

}

?>