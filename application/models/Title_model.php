<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: huyida
 * Date: 2018/8/31
 * Time: 11:49
 */

class Title_model extends CI_Model
{
    private $table = '';
    private $uid;
    private $cmax = 3; // 連続正解のしきい値
    public $column = array();
    public $label = array();
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
        $this->search = '';
    }

    public function set_table( $table_name )
    {
        $ret = TRUE;
        if ($this->db->table_exists($table_name)){
            $this->table = $table_name;

            $this->db->select( 'column_name,column_comment' );
            $this->db->where( 'table_name', $this->table );
            $query = $this->db->get('information_schema.columns');

            $this->label = array();
            foreach( $query->result_array() as $c ) {
                $this->label[$c["column_name"]] = $c["column_comment"];
            }
//			if( array_key_exists('id', $this->label) ) unset($this->label['id']);
            $this->column = array_keys($this->label);
        } else {
            $ret = FALSE;
        }
        return $ret;
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column as $item)
        {
            if($_POST['search']['value'])
                ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
            $column[$i] = $item;
            $i++;
        }

        if(isset($_POST['order']))
        {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($field, $filter_str)
    {
//        $this->_get_datatables_query();
//        if($_POST['length'] != -1)
//            $this->db->limit($_POST['length'], $_POST['start']);
//        if(in_array($field,$this->column) && $filter_str){
//            $this->db->where($field, $filter_str);
//        }
//        $query = $this->db->get();
//        return $query->result_array();
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($field=null, $filter_str=null)
    {
        $this->_get_datatables_query();
        if(in_array($field,$this->column) && $filter_str){
            $this->db->where($field, $filter_str);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($field=null, $filter_str=null)
    {
        $this->db->from($this->table);
        if(in_array($field,$this->column) && $filter_str){
            $this->db->where($field, $filter_str);
        }
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $query = $this->db->where('id',$id)->get();
        return $query->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function get_by_id_view($id)
    {
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $query = $this->db->get();
        if($query->num_rows() > 0) {
            $results = $query->row();
        }
        return $results;
    }

    public function get_issue_data($id)
    {
        $this->db->from('issue_data');
        $this->db->where('issue_id',$id);
        $query = $this->db->get();
        if($query->num_rows() == 0) {
            $new_data = array( 'issue_id' => $id);
            $this->db->insert('issue_data',$new_data);
            $this->db->from('issue_data');
            $this->db->where('issue_id',$id);
            $query = $this->db->get();
        }
        $results = $query->row();
        return $results;
    }

    public function update_issue($issue_id = NULL,$q_type,$a_type,$q_path,$a_path)
    {
        $ret = '  RET:';
        if(!is_null($issue_id)){
            $field_data = array();
            if($q_path){
                $field_data['question_type'] = $q_type;
                $field_data['question_data'] = file_get_contents( $q_path );
                $this->db->where('issue_id',$issue_id);
                $this->db->update('issue_data',$field_data);
                unlink($q_path);
                $ret .= ' Question regist:';
            }

            $field_data = array();
            if($a_path){
                $field_data['answer_type'] = $a_type;
                $field_data['answer_data'] = file_get_contents( $a_path );
                $this->db->where('issue_id',$issue_id);
                $this->db->update('issue_data',$field_data);
                unlink($a_path);
                $ret .= ' Answer regist:';
            }
        }
        return $ret;
    }

    public function write_log($issue_id,$choice)
    {
        $tt = now('Asia/Tokyo');
        $adate = mdate('%Y-%m-%d %H:%i:%s',$tt);
        $is_correct = ($this->get_by_id($issue_id)->correct_number == $choice);
        if($is_correct){
            $ct = $this->get_correct_times($issue_id) + 1;
            if($ct > $this->cmax) $ct=$this->cmax;
        } else {
            $ct = 0;
        }
        $record = array(
            'user_id' => $this->uid,
            'group_id' => '',
            'answer_date' => $adate,
            'issue_id' => $issue_id,
            'answer_number' => $choice,
            'correct_times' => $ct
        );
        $this->db->insert('result_log', $record);
//		return $this->db->insert_id();
    }

    public function get_correct_times($issue_id)
    {
        $this->db->where('user_id',$this->uid);
        $this->db->where('issue_id',$issue_id);
        $this->db->order_by('answer_date', 'DESC');
        $this->db->select('correct_times');
        $query = $this->db->get('result_log',1);
        $r = 0;
        if($query->num_rows()){
            $r = $query->row()->correct_times;
        }
        return $r;
    }

    public function daily_issue()
    {
        $this->db->select('id');
        $query = $this->db->get('issue_view');
        $id_list = array_column($query->result_array(),'id');
        return $id_list;
    }

    public function dbtest()
    {
        $this->db->where('user_id',$this->uid);
        $this->db->where('issue_id',39);
        $this->db->order_by('answer_date', 'DESC');
        $this->db->select('correct_times');
        $query = $this->db->get('result_log',1);
        $r = 0;
        if($query->num_rows()){
            $r = $query->row()->correct_times;
        }
        return $r;
    }

    public function db_version()
    {
        return $this->db->version();
    }


}