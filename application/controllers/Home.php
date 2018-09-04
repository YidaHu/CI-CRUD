<?php defined('BASEPATH') OR exit('No direct script access allowed');

class home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        session_start();
    }

    public function index()
    {
        // 载入CI的session库
        if (isset($_SESSION['s_id'])) {
            // 如果能取得这个ID的session，就意味着处于登录状态
            $this->load->view('admin_mainmenu');
        } else {
            $this->load->view('login');
        }

    }

    public function load_RegisterForm()
    {
        $this->load->view('register');
    }

    public function Load_Teacher()
    {
        $this->load->view('person_view');
    }

    public function logout()
    {
//        $this->session->sess_destroy();
        if (isset($_SESSION['s_id'])) {
            unset($_SESSION['s_id']);
        }
        redirect(site_url() . 'login');
    }


    public function load_Admin_menu()
    {
        $this->load->view('admin/admin_mainmenu');
    }


}
