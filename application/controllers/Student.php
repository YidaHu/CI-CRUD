<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('student_model', 'student');
    }

    public function index()
    {
        $this->load->helper('url');
        $this->load->view('student_view');
    }

    public function ajax_list()
    {
        $list = $this->student->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $student) {
            $no++;
            $row = array();
            $row[] = $student->stuName;
            $row[] = $student->stuNum;
            $row[] = $student->gender;
            $row[] = $student->address;
            $row[] = $student->createTime;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void()" title="Edit" onclick="edit_student(' . "'" . $student->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_student(' . "'" . $student->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->student->count_all(),
            "recordsFiltered" => $this->student->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->student->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'stuName' => $this->input->post('stuName'),
            'stuNum' => $this->input->post('stuNum'),
            'gender' => $this->input->post('gender'),
            'address' => $this->input->post('address'),
            'createTime' => $this->input->post('createTime'),
        );
        $insert = $this->student->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update()
    {
        $data = array(
            'firstName' => $this->input->post('firstName'),
            'lastName' => $this->input->post('lastName'),
            'gender' => $this->input->post('gender'),
            'address' => $this->input->post('address'),
            'dob' => $this->input->post('dob'),
        );
        $this->student->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id)
    {
        $this->student->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

}
