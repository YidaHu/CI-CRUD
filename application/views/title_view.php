<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>"></script>


<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/1.3.3/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/0.4.5/sweetalert2.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/sweetalert2/1.3.3/sweetalert2.min.js"></script>

<div class="box">
    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title">题目管理</h3>
        </div>
        <!--
              <div class="col-md-6">
                <span class="pull-right">
                  <button class="btn btn-default" id = "btnLink"><i class="far fa-arrow-alt-circle-up"> </i> Issues</button>
                </span>
              </div>
        -->
    </div><!-- /.box-header -->
    <div class="box-body">
        <!-- Main content -->
        <section class="content">
            <!-- Page Content Here -->
            <div class="row">
                <button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i>添加题目
                </button>
                <br/>
                <br/>
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>题目</th>
                        <th>选项数</th>
                        <th>答案</th>
                        <th>用户</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </section><!-- /.content -->
    </div><!-- /.box-body -->
</div><!-- /.box -->


<script type="text/javascript">

    var save_method; //for save method string
    var table;
    $(document).ready(function () {
        $('#btnLink').unbind('click').click(function () {
            $.ajax({
                url: "title/regist_issues",
                success: function (result) {
                    $('#haha').empty().html(result).fadeIn('slow');
                }
            });
        });

        table = $('#table').DataTable({

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('title/ajax_list/issue_master')?>",
                "type": "POST"
            },
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
            ],
            // "pageLength": 10,
            //Set column definition initialisation properties.
            //Set column definition initialisation properties.
            // "columns": [
            //     {
            //         "data": "issue_title",
            //     },
            //     {
            //         "data": "choices",
            //         "orderable": false,
            //         "width": "4em"
            //     },
            //     {
            //         "data": "correct_number",
            //         "orderable": false,
            //         "width": "3em"
            //     },
            //     {
            //         "data": "user_id",
            //         "orderable": false,
            //         "width": "4em"
            //     },
            //     {
            //         "data": "id",
            //         "width": "14em",
            //         "orderable": false,
            //         "searchable": false,
            //         "render": function (data) {
            //             return btn_regist(data) + btn_edit(data) + btn_delete(data);
            //         }
            //     },
            // ],

        });
    });

    function btn_regist(id) {
        return ' <a class="btn btn-sm btn-success" href="javascript:void(0)" title="题目" onclick="edit_issue(' + id + ')"><i class="glyphicon glyphicon-picture"></i> 登録</a> ';
    }

    function btn_edit(id) {
        return ' <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="编辑" onclick="edit_data(' + id + ')"><i class="glyphicon glyphicon-pencil"></i> 編集</a> ';
    }

    function btn_delete(id) {
        return ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="删除" onclick="delete_data(' + id + ')"><i class="glyphicon glyphicon-trash"></i> 削除</a> ';
    }

    function add_data() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('添加题目'); // Set Title to Bootstrap modal title
    }

    function edit_data(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('title/ajax_edit/issue_master/')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('[name="id"]').val(data.id);
                $('[name="issue_title"]').val(data.issue_title);
                $('[name="choices"]').val(data.choices);
                $('[name="correct_number"]').val(data.correct_number);
                $('[name="user_id"]').val(data.user_id);

                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('编辑题目'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_issue(id) {
        $.ajax({
            url: "<?php echo site_url('title/edit_issue_data/')?>/" + id,
            type: "GET",
            success: function (result) {
                console.log(result);
                $('#haha').empty().html(result).fadeIn('slow');
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    }

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax
    }

    function save() {
        var url;
        if (save_method == 'add') {
            url = "<?php echo site_url('title/ajax_add/issue_master')?>";
        } else {
            url = "<?php echo site_url('title/ajax_update/issue_master')?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data) {
                //if success close modal and reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
                swal(
                    '题目',
                    '成功',
                    'success'
                )
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }

    function delete_data(id) {

        swal({
            title: '删除',
            text: "删除",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '删除',
            closeOnConfirm: false
        }).then(function (isConfirm) {
            if (isConfirm) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('title/ajax_delete/issue_master/')?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                        swal(
                            '删除成功',
                            '删除成功',
                            'success'
                        );
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    }
                });
            }
        })
    }
</script>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Issue Master Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">题目</label>
                            <div class="col-md-9">
                                <input name="issue_title" placeholder="题目" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">选项数</label>
                            <div class="col-md-9">
                                <input name="choices" placeholder="选项数" class="form-control cv-number"
                                       type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">答案</label>
                            <div class="col-md-9">
                                <input name="correct_number" placeholder="答案" class="form-control cv-number"
                                       type="text">
                            </div>
                        </div>
                        <input type="hidden" value="<?php
                        if (isset($_SESSION['s_name'])) {
                            echo $_SESSION['s_name'];
                        } else {
                            echo '';
                        }
                        ?>" name="user_id"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">确定</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>