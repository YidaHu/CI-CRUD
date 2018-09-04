<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>"></script>


<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/1.3.3/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/0.4.5/sweetalert2.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/sweetalert2/1.3.3/sweetalert2.min.js"></script>

<div class="row">

    <button class="btn btn-success" onclick="add_student()"><i class="glyphicon glyphicon-plus"></i> Add New Student
    </button>
    <button class="btn btn-info" onclick="printer()"><i class="glyphicon glyphicon-plus"></i> Printer
    </button>

    <br/>
    <br/>
    <!--    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">-->
    <!--        <thead>-->
    <!--        <tr>-->
    <!--            <th>Student Name</th>-->
    <!--            <th>Student Number</th>-->
    <!--            <th>Gender</th>-->
    <!--            <th>Address</th>-->
    <!--            <th>Create Time</th>-->
    <!--            <th style="width:189px;">Action</th>-->
    <!--        </tr>-->
    <!--        </thead>-->
    <!--        <tbody>-->
    <!--        </tbody>-->
    <!---->
    <!---->
    <!--    </table>-->
    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Student Name</th>
            <th>Student Number</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Create Time</th>
            <th style="width:189px;">Action</th>
        </tr>
        </thead>
        <tbody>
        </tbody>


    </table>
</div>


</div>


<script type="text/javascript">

    var save_method; //for save method string
    var table;
    $(document).ready(function () {
        table = $('#table').DataTable({

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('student/ajax_list')?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                },
            ],

        });
    });

    function printer() {
        window.print();
    }

    function add_student() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add New Student'); // Set Title to Bootstrap modal title
    }

    function edit_student(id) {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('student/ajax_edit/')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {

                $('[name="id"]').val(data.id);
                $('[name="stuName"]').val(data.stuName);
                $('[name="stuNum"]').val(data.stuNum);
                $('[name="gender"]').val(data.gender);
                $('[name="address"]').val(data.address);
                $('[name="createTime"]').val(data.createTime);

                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Student'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax
    }

    function save() {
        var url;
        if (save_method == 'add') {
            url = "<?php echo site_url('student/ajax_add')?>";
        }
        else {
            url = "<?php echo site_url('student/ajax_update')?>";
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
                    'Good job!',
                    'Data has been save!',
                    'success'
                )
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    }

    function delete_student(id) {

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false
        }).then(function (isConfirm) {
            if (isConfirm) {

                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('student/ajax_delete')?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                        swal(
                            'Deleted!',
                            'Your file has been deleted.',
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

    function view_student(id) {
        $.ajax({
            url: "<?php echo site_url('student/list_by_id')?>/" + id,
            type: "GET",
            success: function (result) {
                $('#haha').empty().html(result).fadeIn('slow');
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    }


    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,
    });


</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Student Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Student Name</label>
                            <div class="col-md-9">
                                <input name="stuName" placeholder="Student Name" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Student Number</label>
                            <div class="col-md-9">
                                <input name="stuNum" placeholder="Student Number" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Gender</label>
                            <div class="col-md-9">
                                <select name="gender" class="form-control">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Address</label>
                            <div class="col-md-9">
                                <textarea name="address" placeholder="Address" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Date of Create</label>
                            <div class="col-md-9">
                                <input name="createTime" placeholder="yyyy-mm-dd" class="form-control datepicker"
                                       type="text">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>