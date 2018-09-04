<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= base_url() . 'uploads/nophoto.jpg'; ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>
                    <?php
                    if (isset($_SESSION['s_name'])) {
                        echo $_SESSION['s_name'];
                    } else {
                        echo '';
                    }
                    ?>
                </p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">导航栏</li>

            <li><a href=""><i class="fa fa-dashboard"></i>主页</a></li>

            <li class="treeview">
                <a href="#" style="text-decoration:none">
                    <i class="fa fa-files-o"></i>
                    <span>数据分析</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a class="ayam" href="<?php echo base_url(); ?>welcome/Load_SchoolProfile"><i
                                    class="fa fa-circle-o"></i>Teacher & Student</a></li>
                    <li><a class="ayam" href="<?php echo base_url(); ?>welcome/Load_Subjek"><i
                                    class="fa fa-circle-o"></i>Subject of Year</a></li>

                </ul>
            </li>

            <li class="treeview">
                <a href="#" style="text-decoration:none">
                    <i class="fa fa-users"></i>
                    <span>信息管理</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a class="ayam" href="<?php echo base_url(); ?>welcome/Load_Teacher"><i
                                    class="fa fa-circle-o"></i>Teacher
                        </a>
                    </li>
                    <li>
                        <a class="ayam" href="<?php echo base_url(); ?>welcome/Load_Student"><i
                                    class="fa fa-circle-o"></i>Student
                        </a>
                    </li>
                    <li>
                        <a class="ayam" href="<?php echo base_url(); ?>welcome/Load_Title"><i
                                    class="fa fa-circle-o"></i>题目管理
                        </a>
                    </li>
                </ul>
            </li>


            <li>
                <a class="ayam" href="<?php echo base_url(); ?>welcome/Load_Activity"><i class="fa fa-users"></i>展示
                </a>
            </li>

            <li>
                <a class="ayam" href="<?php echo base_url(); ?>welcome/load_Notes"><i class="fa fa-user-md"></i>关于
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<script type="text/javascript">


    $(document).on('click', '.ayam', function () {

        var href = $(this).attr('href');
        $('#haha').empty().load(href).fadeIn('slow');
        return false;

    });


</script>


<script type="text/javascript">

    $('.apam').removeClass('active');

</script>


<script>


    $(document).ready(function () {

        $("body").on("click", ".ayam", function () {

            $('.ayam').each(function (a) {
                $(this).removeClass('selectedclass')
            });
            $(this).addClass('selectedclass');
        });

    })


</script>


<style type="text/css">


    li a.selectedclass {
        color: red !important;
        font-weight: bold;
    }

</style>