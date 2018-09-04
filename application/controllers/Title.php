<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); //防止直接通过文件路径访问

class Title extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->load->helper('url');
        $this->load->model('title_model', 'target');

    }

    public function index()
    {
//        $this->load->helper('url');
        $this->load->view('title_view');
    }

    public function ajax_list($table_name, $match = null, $filter_str = null)
    {
        if ($this->target->set_table($table_name)) {
            $list = $this->target->get_datatables($match, $filter_str);
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $title) {
                $no++;
                $row = array();
                $row[] = $title->issue_title;
                $row[] = $title->choices;
                $row[] = $title->correct_number;
                $row[] = $title->user_id;

                //add html for action
                $row[] = '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="题目" onclick="edit_issue(' . "'" . $title->id . "'" . ')"><i class="glyphicon glyphicon-picture"></i> 题目
                        <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="编辑" onclick="edit_data(' . "'" . $title->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> 编辑</a>
                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="删除" onclick="delete_data(' . "'" . $title->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i> 删除</a>';


//                $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void()" title="Edit" onclick="edit_student(' . "'" . $student->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
//				  <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_student(' . "'" . $student->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

                $data[] = $row;
            }

            // output for jquery.dataTables, bootstrap
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->target->count_all($match, $filter_str),
                "recordsFiltered" => $this->target->count_filtered($match, $filter_str),
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        }
    }

    public function ajax_edit($table_name, $id)
    {
        if ($this->target->set_table($table_name)) {
            $data = $this->target->get_by_id($id);
            echo json_encode($data);
        }
    }

    public function ajax_add($table_name)
    {
        if ($this->target->set_table($table_name)) {
            $data = array();
            foreach ($this->target->column as $col) {
                $data[$col] = $this->input->post($col);
            }
            $insert = $this->target->save($data);
            $output = array(
                "status" => TRUE,
                "insert" => $insert,
                "data" => $data
            );
            echo json_encode(array($output));
        }
    }

    public function ajax_update($table_name)
    {
        if ($this->target->set_table($table_name)) {
            $data = array();
            foreach ($this->target->column as $col) {
                $data[$col] = $this->input->post($col);
            }
            $this->target->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_delete($table_name, $id)
    {
        if ($this->target->set_table($table_name)) {
            $this->target->delete_by_id($id);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function ajax_chk_answer($issue_id, $choice, $mode = null)
    {
        $this->target->set_table('issue_master');
        $issue = $this->target->get_by_id($issue_id);
        $result = array(
            "choice" => $choice,
            "correct" => $issue->correct_number,
            "result" => ($issue->correct_number == $choice)
        );

        // 回答データをログテーブルに書き込む
        if ($mode != 'view') {
            $this->target->write_log($issue_id, $choice);
        }

        echo($issue->correct_number == $choice);
    }

    public function list_by_id($table_name, $id)
    {
        if ($this->target->set_table($table_name)) {
            $data['output'] = $this->target->get_by_id_view($id);
            $this->load->view('view_Detail', $data);
        }
    }

    public function edit_issue_data($id)
    {
        $this->target->set_table('issue_master');
        $data['output'] = $this->target->get_by_id_view($id);

        // issue_data が存在しない場合は追加する
        $row = $this->target->get_issue_data($id);
        if (!$row) {
            show_error('No issue data. Check FOREIGN KEY for issue_data.');
        }
        if (!file_exists(APPPATH . '/views/login.php')) {
            // Whoops, we don't have a page for that!
            show_404();
        }
        $this->load->view('edit_issue_file', $data);
//        $this->load->view('login');
    }

    public function issue_image($qa, $id)
    {
        $row = $this->target->get_issue_data($id);
        switch ($qa) {
            case "q":
                $ext = $row->question_type;
                $img = $row->question_data;
                break;
            case "a":
                $ext = $row->answer_type;
                $img = $row->answer_data;
                break;
            default:
                $ext = 'jpeg';
                $img = null;
        }

        if (!$img) {
            $ext = 'jpeg';
            $img = file_get_contents('/var/www/nps-master/images/nophoto.jpg');
        }

        $this->output->set_content_type($ext)->set_output($img);
    }

    public function regist_issues_batch()
    {
        // 使用頻度の低い処理なので、動作確認レベルで完成としてコードのチューニングと経過表示は省略
        $q_files = array();
        $a_files = array();
        $cmd = array();
        $choices = 2;
        $path = BASEPATH;
        $path = str_replace('system', 'uploads', $path);
        $output['path'] = $path;

        $filenames = $this->get_file_name($path);
        foreach ($filenames as $fname) {
            $t = substr($fname, 0, 1);
            if ($t == 'Q') array_push($q_files, $fname);
        }
        sort($q_files);

        $this->target->set_table('issue_master');
        foreach ($q_files as $fname) {
            $q_path = $path . $fname;
            $a_path = $path . 'A' . substr_replace($fname, '', 0, 1);
            $type = '.' . pathinfo($q_path, PATHINFO_EXTENSION);
            $title = pathinfo($q_path, PATHINFO_FILENAME);
            $ans = substr($title, -1, 1);
            $title = substr_replace($title, '', -2, 2);
            $title = substr_replace($title, '', 0, 1);
            $lbuf = $title . ' : ' . $ans . ' : ' . $type . ' : ' . $q_path . ' : ' . $a_path . '<br />';
            array_push($cmd, $lbuf);
            $data_buf = array(
                'issue_title' => $title,
                'choices' => $choices,
                'correct_number' => $ans,
                'user_id' => $this->target->userid()
            );
            //    $id = $this->target->save( $data_buf );
            //    $this->target->get_issue_data($id);
            //    $this->target->update_issue($id,$type,$type,$q_path,$a_path);
        }
        $output['cmd'] = $cmd;
        $this->load->view('regist_batch', $output);
    }

    private function get_file_name($dir)
    {
        // ディレクトリの存在確認&ハンドルの取得
        if (is_dir($dir) && $handle = opendir($dir)) {

            $files = array();
            // ループ処理(ディレクトリを読み込んでエントリがfalseでない間)
            while (($file = readdir($handle)) !== false) {
                // ファイルタイプがfileの場合のみ処理する(ディレクトリとかでない場合)
                if (filetype($path = $dir . $file) == "file") {
                    array_push($files, $file);
                }
            }
            return $files;
        }
    }

    public function issue_upload()
    {
        if ($issue_id = $this->input->post('issue_id')) {
            $config['allowed_types'] = 'gif|bmp|jpg|jpeg|png';
            $config['file_name'] = 'issue' . md5(uniqid() . mt_rand());
            $config['upload_path'] = './uploads/';
            $config['max_size'] = 10000;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('question_file')) {
                $question_data = $this->upload->data();
                $q_ext = $question_data['file_ext'];
                $q_path = $question_data['full_path'];
            } else {
                $q_ext = '';
                $q_path = '';
            }

            if ($this->upload->do_upload('answer_file')) {
                $answer_data = $this->upload->data();
                $a_ext = $answer_data['file_ext'];
                $a_path = $answer_data['full_path'];
            } else {
                $a_ext = '';
                $a_path = '';
            }

            echo $this->target->update_issue($issue_id, $q_ext, $a_ext, $q_path, $a_path);
        } else {
            show_error('(issue_upload)not submit !');
        }
    }
}