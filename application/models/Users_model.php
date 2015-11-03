<?php
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;

/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 10/11/2015
 * Time: 3:12 PM
 * model for users table
 * @property Acl acl
 */


class Users_model extends CI_Model {
    private $TABLENAME = 'users';
    function __construct() {
        if (!property_exists('Users_model', 'acl') || $this->acl == null) {
            $this->acl = new Acl();
        }
    }

    public function can_do_it($resource) {
        return $this->acl->isAllowed('this_user',$resource);
    }

    private function set_permissions($id) {
        $roles = $this->db->from('groups')->where('groups_id',$id)->get()->result();
        $user_role = null;
        foreach($roles as $role) {
            $this->acl->addRole($role->name);
            $user_role = $role->name;
        }
        $this->acl->addRole('this_user', array($user_role));
        $resources = $this->db->list_fields('acl');
        $resources = array_slice($resources,2);
        $permissions = $this->db->from('acl')
                                ->where('acl_groups_id',$id)
                                ->get()->row_array();
        foreach($resources as $resource) {
            $this->acl->addResource($resource);
            if ($permissions[$resource] == 1) {
                $this->acl->allow($user_role, $resource);
            } else {
                $this->acl->deny($user_role, $resource);
            }
        }
    }

    /**
     * select * from users
     *
     * @access public
     * @return all users
     */
    function get_all() {
        // $this -> db -> select('*');
        $this->db->from ( $this->TABLENAME );
        // $this -> db -> limit(1);

        $query = $this->db->get ();
        return $query->result ();
    }


    /**
     * provide login input of email and password
     * return user info if match
     *
     * @access public
     * @param string $email
     * @param string $password
     * @return login user records
     */
    function login($email, $password) {
        $this->db->select ( 'users_id, users_groups_id, email, password' );
        $this->db->from ( $this->TABLENAME );
        $this->db->where ( 'email', $email );
        $this->db->where ( 'password', sha1 ( $password ) );
        // $this -> db -> limit(1);

        $query = $this->db->get();

        if ($query->num_rows () == 1) {
            $this->set_permissions($query->row()->users_groups_id);
            return $query->result ();
        } else {
            return false;
        }
    }

    /**
     * input from form
     * intser the data to users(table)
     *
     * @access public
     * @return null
     */
    function register() {
        $data = array (
            'email' => $this->input->post ( 'email' ),
            'username' => $this->input->post ( 'username' ),
            'users_groups_id' => 12,
            'password' => sha1 ( $this->input->post ( 'password' ) )
        );
        $this->db->insert ( $this->TABLENAME, $data );
    }

    /**
     * select * from users where email = $email
     * return match user
     *
     * @access public
     * @param string $email
     * @return match user
     */
    function getByEmail($email) {
        // $this -> db -> select('id, email');
        $this->db->from ( $this->TABLENAME );
        $this->db->where ( 'email', $email );
        // $this -> db -> limit(1);

        $query = $this->db->get ();

        if ($query->num_rows () == 1) {
            return $query->result ();
        } else {
            return false;
        }
    }

    /**
     * select * from users where id = $id
     * return match user
     *
     * @access public
     * @param string $id
     * @return match user
     */
    function getById($id) {
        // $this -> db -> select('id, email');
        $this->db->from ( $this->TABLENAME );
        $this->db->where ( 'users_id', $id );
        // $this -> db -> limit(1);

        $query = $this->db->get ();

        if ($query->num_rows () == 1) {
            return $query->result ();
        } else {
            return false;
        }
    }

    /**
     * input from form
     * update the data to users(table)
     *
     * @access public
     * @return null
     */
    function update_profile($id, $data) {

        $this->db->where('users_id', $id);
        $this->db->update($this->TABLENAME, $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            //
            return -1;
        }
        else
        {
            return $this->db->affected_rows();
        }

    }
}

