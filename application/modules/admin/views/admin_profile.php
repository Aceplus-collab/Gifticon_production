<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('./inc/header.php');?>
        <title><?php echo MY_SITE_NAME;?> | Admin Profile</title>
        <?php $this->load->view('./inc/style.php');?>
    </head>
    <body class="fixed-left">
        <!-- Begin page -->
        <div id="wrapper">
            <!-- Top Bar Start -->
            <?php $this->load->view('./inc/top_bar.php')?>
            <!-- Top Bar End -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php $this->load->view('./inc/left_side_bar.php')?>
            <!-- Left Sidebar End -->
            <!-- Right Sidebar -->
            <?php $this->load->view('./inc/right_side_bar.php')?>
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
                                <h4 class="page-title">Admin Profile</h4>
                                <p></p>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-lg-6">
                        		<div class="card-box p-0">
                        			<div class="profile-widget text-center">
			                            <div class="bg-custom bg-profile"></div>
			                            <img src="<?php echo base_url(); ?>assets/images/users/<?php echo $admin['profile_image']; ?>" class="thumb-lg img-circle img-thumbnail" alt="img">
			                            <h4><?php echo $admin['name']; ?></h4>
                                        <div class="container ">
                                            <div class="row text-left m-l-15 ">
                                                <div class="col-lg-3">Name</div>
                                                <div class="col-lg-1">:</div>
                                                <div class="col-lg-4"><?php echo $admin['name']; ?></div>
                                            </div>
                                            <div class="row text-left m-l-15 m-t-15">
                                                <div class="col-lg-3">Email Address</div>
                                                <div class="col-lg-1">:</div>
                                                <div class="col-lg-4"><?php echo $admin['email']; ?></div>
                                            </div>
                                            <div class="row text-left m-l-15 m-t-15">
                                                <div class="col-lg-3">Name</div>
                                                <div class="col-lg-1">:</div>
                                                <div class="col-lg-4"><?php echo $admin['name']; ?></div>
                                            </div>
                                            <div class="row text-left m-l-15 m-t-15"></div>
                                        </div>
			                        </div>
                        		</div>
                         	</div>
                            <!-- col -->
                        </div>
                        <!-- end row -->
                    </div> <!-- container -->
                </div> <!-- content -->
               <?php $this->load->view('./inc/footer.php')?>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->
        <?php $this->load->view('./inc/script.php');?>
    </body>
</html>