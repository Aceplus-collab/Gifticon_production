<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
$data=$this->session->userdata();
        if(!isset($data['id'])){
            redirect("admin/Home/login");
        }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(dirname(__FILE__)."/inc/header.php");?>
        <title><?php echo MY_SITE_NAME;?> | Dashboard</title>
        <?php include(dirname(__FILE__)."/inc/style.php");?>
    </head>
    <body class="fixed-left">
        <!-- Begin page -->
        <div id="wrapper">
            <!-- Top Bar Start -->
            <?php include(dirname(__FILE__)."/inc/top_bar.php");?>
            <!-- Top Bar End -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include(dirname(__FILE__)."/inc/left_side_bar.php");?>
            <!-- Left Sidebar End -->
            <!-- Right Sidebar -->
            <?php include(dirname(__FILE__)."/inc/right_side_bar.php");?>
            <!-- /Right-bar -->
             <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title">Dashboard</h4>
                                <p></p>
                            </div>
                        </div>
                        <br>
                        <?php if($this->session->flashdata('msg')){
                            echo '<div class="alert alert-danger">'.$this->session->flashdata('msg').'</div>'; 
                        } ?>
                        <?php if(isset($error_msg) && $error_msg != ''){
                            echo '<div class="alert alert-danger">'.$error_msg.'</div>'; 
                        } ?>
                        <?php if($this->session->flashdata('suc')){
                            echo '<div class="alert alert-success">'.$this->session->flashdata('suc').'</div>'; 
                        } ?>
                        <div class="row">
                            <a href="<?php echo base_url(); ?>admin/user/listing">
                                <div class="col-md-4 col-xl-4">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated">
                                        <div class="bg-icon bg-icon-info pull-left">
                                            <i class="md md-person text-info"></i>
                                        </div>
                                        <div class="text-right">
                                        	<?php 
                                        	$total_user = $this->db->get_where('tbl_user',array('is_delete'=>0))->num_rows(); ?>
                                            <h3 class="text-dark"><b class="counter"><?php echo $total_user; ?></b></h3>
                                            <p class="text-muted">Total User</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div> <!-- end col-->
                            </a>

                            <a href="<?php echo base_url(); ?>admin/brand/listing">
                                <div class="col-md-4 col-xl-4">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated">
                                        <div class="bg-icon bg-icon-success pull-left">
                                            <i class="glyphicon glyphicon-tasks text-success"></i>
                                        </div>
                                        <div class="text-right">
                                        	<?php 
                                        	$total_brands = $this->db->get_where('tbl_businesses',array('is_delete'=>0))->num_rows(); ?>
                                            <h3 class="text-dark"><b class="counter"><?php echo $total_brands; ?></b></h3>
                                            <p class="text-muted">Total Brands / Business</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div> <!-- end col-->
                            </a>

                             <a href="<?php echo base_url(); ?>admin/gift/listing">
                                <div class="col-md-4 col-xl-4">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated">
                                        <div class="bg-icon bg-icon-success pull-left">
                                            <i class="glyphicon glyphicon-gift text-danger"></i>
                                        </div>
                                        <div class="text-right">
                                            <?php 
                                            $total_gifts = $this->db->get_where('tbl_gifticons',array('is_delete'=>0))->num_rows(); ?>
                                            <h3 class="text-dark"><b class="counter"><?php echo $total_gifts; ?></b></h3>
                                            <p class="text-muted">Total Gifticons</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div> <!-- end col-->
                            </a>
                        </div>

                        

                    </div> <!-- container -->
                </div> <!-- content -->
               <?php include(dirname(__FILE__)."/inc/footer.php");?>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->
        <?php include(dirname(__FILE__)."/inc/script.php");?>
    </body>
</html>