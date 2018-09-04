<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); //防止直接通过文件路径访问

class Signin extends CI_Controller
{// 类名首字母大写，继承CI_Controller类

    public function index()
    {
        $this->load->view('signin'); //载入signin视图
    }

    function regist()
    {
        $this->load->model('login_model'); //载入我们之前创建的User_test模型，首字母不用大小
        $arr = array('uname' => $_POST['u_name'], 'upw' => $_POST['u_pw']);
        //获取提交的表单内容，=>左边是数据表里面的键名，=>右边是通过name获取的表单值
        $this->login_model->u_insert($arr); //调用user_test的u_insert方法插入数据
    }
}