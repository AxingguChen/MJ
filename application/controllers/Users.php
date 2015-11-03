<?php
/**
 * Created by PhpStorm.
 * User: jackie
 * Date: 11/2/2015
 * Time: 11:23 PM
 */

class Users extends CI_Controller {
    function __construct() {
        parent::__construct ();
        $this->load->model ( 'users_model', '', TRUE );
        //$this->load->model ( 'acl_model', '', TRUE );
    }

    // access
    // index.php/users/view_user
    function view_user() {
        // get session data
//        if ($this->session->userdata ( 'logged_in' )) {
//            $session_data = $this->session->userdata ( 'logged_in' );
            // get user data by id
            $data ['rows'] = $this->users_model->getById ( '1' );
        //print all
           print_r($data);
           // $this->load->view ( 'user_profile_view', $data );
 /*       } else {
            redirect ( 'verification/login', 'refresh' );
        }*/
    }

        // access
        // index.php/users/view_user
        function view_users(){
            //TODO acl
            $data['rows'] = $this->users_model->get_all();
            print_r($data);
        }

        // access
        // index.php/users/user_users
        function update_user() {
            // get session data
            if ($this->session->userdata ( 'logged_in' )) {
                $session_data = $this->session->userdata ( 'logged_in' );

                // getting data from form table
                $tmparray = $this->input->post ( NULL, TRUE );
                $this->users_model->update_profile ( $session_data ['users_id'], $tmparray );

                // get user data by id
                $data ['rows'] = $this->users_model->getById ( $session_data ['users_id'] );
                $this->load->view ( 'user_profile_view', $data );
            } else {
                redirect ( 'verification/login', 'refresh' );
            }
        }

}

?>