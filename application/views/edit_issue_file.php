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
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/1.3.3/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/sweetalert2/0.4.5/sweetalert2.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/sweetalert2/1.3.3/sweetalert2.min.js"></script>
<style type="text/css">
    #buangLine {
        border: none;
        background-color: transparent;
        resize: none;
        outline: none;
    }
</style>
<style type="text/css">
    .imagePreview {
        width: 100%;
        height: 300px;
    }

    .imagePreview img {
        max-width: 100%;
        max-height: 300px;
        display: block;
        margin: auto;
    }
</style>

<div class="box">
    <!-- Horizontal Form -->
    <div class="box-header with-border">
        <div class="col-md-6">
            <h3 class="box-title">题目变更</h3>
        </div>
        <div class="col-md-6">
      <span class="pull-right">
        <button class="btn btn-default" id="btnBack"><i class="fa fa-arrow-left"> </i> Back</button>
      </span>
        </div>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <section class="content">
            <!-- form start -->
            <form class="form-horizontal" id="dataCryo" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">题目 : </label>
                    <div class="col-sm-6">
                        <h5><?php echo $output->issue_title; ?></h5>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">选项数 : </label>
                    <div class="col-sm-6">
                        <h5><?php echo $output->choices; ?></h5>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">答案 : </label>
                    <div class="col-sm-6">
                        <h5><?php echo $output->correct_number; ?></h5>
                    </div>
                </div>
            </form>
            <form class="form-horizontal" action="title/issue_upload" id="issue_form" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" value="<?php echo $output->id; ?>" name="issue_id"/>
                <div class="box-body">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <div id="question_img" class="imagePreview">
                                <img alt="问题">
                            </div>
                            <div class="input-group">
                                <label class="input-group-btn">
                  <span class="btn btn-primary">
                    问题 File<input type="file" name="question_file" style="display:none" class="uploadFile">
                  </span>
                                </label>
                                <input type="text" class="form-control" readonly="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div id="answer_img" class="imagePreview">
                                <img alt="回答">
                            </div>
                            <div class="input-group">
                                <label class="input-group-btn">
                  <span class="btn btn-primary">
                    答案 File<input type="file" name="answer_file" style="display:none" class="uploadFile">
                  </span>
                                </label>
                                <input type="text" class="form-control" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success center-block" type="submit" id="upload"><i
                                    class="glyphicon glyphicon-plus"></i>题目
                        </button>
                    </div>
            </form>
            <p></p>
        </section>
    </div>
</div>

<script>
    $(document).ready(function () {
        var get_issue = "title/issue_image/";
        var id = '<?php echo $output->id; ?>';
        var rr = Math.random().toString(36).slice(-12);
        $('#question_img').children('img').attr('src', get_issue + "q/" + id + "?r=" + rr);
        $('#answer_img').children('img').attr('src', get_issue + "a/" + id + "?r=" + rr);
        $('#btnBack').unbind('click').click(function () {
            $.ajax({
                url: "<?php echo base_url();?>welcome/Load_Title",
                success: function (result) {
                    $('#haha').empty().html(result).fadeIn('slow');
                }
            });
        });
    })
</script>

<script>
    $(document).on('change', ':file', function () {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.parent().parent().next(':text').val(label);

        // プレビューのセット
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
        if (/^image/.test(files[0].type)) { // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
            reader.onloadend = function () { // set image data as background of div
                input.parent().parent().parent().prev('.imagePreview').children('img').attr('src', this.result);
            }
        }
    });
</script>

<script>
    $('#upload').click(function (event) {
        event.preventDefault();
        //
        var form = document.getElementById("issue_form");
        var FD = new FormData(form);

        // Check input!!

        var XHR = new XMLHttpRequest();
        XHR.open("POST", "title/issue_upload");
        XHR.send(FD);

        $('#main_area').empty().load("welcome/Load_Title").fadeIn('slow');
    });
</script>
