<?php
/**
 * Created by PhpStorm.
 * User: huyida
 * Date: 2018/8/29
 * Time: 14:13
 */

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }

    public function index()
    {
        $this->load->view('login');
    }

    function check()
    {
        $this->load->model('login_model');
        $user = $this->login_model->u_select($_POST['u_name']);
        //调用User_test模型的u_select方法查询提交的用户名的信息
        if ($user) {
            // 如果此用户存在
            if ($user[0]->upw == $_POST['u_pw']) {
                // 如果提交的密码与正确密码一致，则创建session
                $arr = array('s_id' => $user[0]->uid);
                $_SESSION["s_id"] = $user[0]->uid;
                $_SESSION["s_name"] = $user[0]->uname;
                redirect(site_url() . 'home/index/');
//                echo 'pw right';
//                echo $_SESSION["s_id"];

            } else {
                echo 'pw wrong';
            }
        } else {
            echo 'name wrong';
        }
    }

    function is_login()
    {
        if ($_SESSION["s_id"]) {
            // 如果能取得这个ID的session，就意味着处于登录状态
            echo "logined";
        } else {
            echo 'no login';
        }
    }

    function logout()
    {
        // 删除此ID是session
        unset($_SESSION['s_id']);
    }
}