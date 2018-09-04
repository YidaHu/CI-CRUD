<?php
/**
 * Created by PhpStorm.
 * User: huyida
 * Date: 2018/8/29
 * Time: 14:25
 */

class Login_model extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // 继承父类的构造函数
        $this->load->database(); // 连接数据库
    }

    function u_insert($arr)
    {
        $this->db->insert('user', $arr);
    }

    function u_update($id, $arr)
    {
        $this->db->where('uid', $id);
        $this->db->update('user', $arr);
    }

    function u_del($id)
    {
        $this->db->where('uid', $id);
        $this->db->delete('user');
    }

    function u_select($name)
    {
        $this->db->where('uname', $name);
        $this->db->select('*');
        $query = $this->db->get('user');
        return $query->result();
    }
}