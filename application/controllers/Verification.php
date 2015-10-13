<?php

/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 10/11/2015
 * Time: 3:16 PM
 */

class Verification extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('users_model','',TRUE);
        $this->load->library('form_validation');
        $this->load->helper('form');
    }

    function login()
    {

        $this->load->view('login_view');
    }

    function register()
    {
        $this->load->view('login_view');
    }

    public function verify(){
        //This method will have the credentials validation

        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
        if($this->form_validation->run() == FALSE)
        {
            //Field validation failed.  User redirected to login page
            redirect('verification/login', 'refresh');
        }
        else
        {
            //Go to private area
            //redirect('users/user_profile', 'refresh');

            /* USE CASE FOR ACL SYSTEM*/
            $data = array('data' => array(
                'acl_view' => $this->users_model->can_do_it('acl_view'),
                'acl_update_profile' => $this->users_model->can_do_it('acl_update_profile'),
                'acl_view_profile' => $this->users_model->can_do_it('acl_view_profile'),
                'acl_create_project' => $this->users_model->can_do_it('acl_create_project'),
                'acl_update_project' => $this->users_model->can_do_it('acl_update_project'),
                'acl_update_check' => $this->users_model->can_do_it('acl_update_check'),
                'acl_update_acl' => $this->users_model->can_do_it('acl_update_acl')
            ));
            $this->load->view('welcome_message',$data);
        }
    }

    function check_database($password)
    {
        //Field validation succeeded.  Validate against database
        $email = $this->input->post('email');

        //query the database
        $result = $this->users_model->login($email, $password);
        if($result)
        {
            //session
            $sess_array = array();
            foreach($result as $row)
            {
                $sess_array = array(
                    'users_id' => $row->users_id,
                    'email' => $row->email
                );
                $this->session->set_userdata('logged_in', $sess_array);
            }
            return true;
        }
        else
        {
            $this->form_validation->set_message('check_database', 'Invalid email or password');
            return false;
        }
    }
    public function signup() {
        $this->load->library ( 'form_validation' );
        // error message, validation rules
        $this->form_validation->set_rules ( 'email', 'Your Email', 'trim|required|valid_email' );
        $this->form_validation->set_rules ( 'username', 'User Name', 'trim|required' );
        $this->form_validation->set_rules ( 'password', 'Password', 'trim|required|min_length[4]|max_length[32]' );
        $this->form_validation->set_rules ( 'con_password', 'Password Confirmation', 'trim|required|matches[password]' );

        if ($this->form_validation->run () == FALSE) {
            redirect ( 'verification/login', 'refresh' );
        } else {
            $this->users_model->register();
            // query the database search by email
            $result = $this->users_model->getByEmail($this->input->post('email'));
            //session
            $sess_array = array ();
            foreach ( $result as $row ) {
                $sess_array = array (
                    'users_id' => $row->users_id,
                    'email' => $row->email
                );
                $this->session->set_userdata ( 'logged_in', $sess_array );
            }
            // Go to private area
            redirect ( 'users/user_profile', 'refresh' );
        }
    }

    public function logout()
    {
        $sess_array  = array(
            'users_id' => '',
            'email' => '',
        );
        $this->session->unset_userdata('logged_in');
        $this->session->sess_destroy();
        redirect('verification/login', 'refresh');
    }

}

?>