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
        <title><?php echo MY_SITE_NAME;?> | Admin Change Password</title>
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
                                <h4 class="page-title">Change Password</h4>
                                <p></p>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-lg-6 col-xs-12 col-sm-12 ">
                                <?php if($this->session->flashdata('msg')){
                                    echo '<div class="alert alert-danger">'.$this->session->flashdata('msg').'</div>'; 
                                } ?>
                                <?php if(isset($error_msg) && $error_msg != ''){
                                    echo '<div class="alert alert-danger">'.$error_msg.'</div>'; 
                                } ?>
                                <?php if($this->session->flashdata('suc')){
                                    echo '<div class="alert alert-success">'.$this->session->flashdata('suc').'</div>'; 
                                } ?>
                        		<div class="card-box p-0">
                        			<div class="profile-widget text-center">
			                            <div class="bg-custom bg-profile"></div>
			                            <img src="<?php echo base_url().'assets/img/icon.png';?>" class="thumb-lg img-circle img-thumbnail" alt="img">
			                            <h4><?php echo 'Gifticon Admin'; ?></h4>
                                        <form role="form" method="POST" action="<?php echo base_url(); ?>admin/savePassword" >
                                            <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                                            <div class="container ">
                                                <div class="row text-left m-l-5 ">
                                                    <div class="col-lg-4 col-xs-12"><label for="old_password">Old Password</label></div>
                                                    <div class="col-lg-1 ">:</div>
                                                    <!-- <div class="col-lg-4"><?php echo $admin['name']; ?></div> -->
                                                    <div class="input-group col-lg-6 col-xs-11">
                                                        <input type="password" name="old_password" class="form-control" id="old_password" placeholder="Old Password" data-parsley-required-message="Please Enter Old Password"  minlength="4" autocomplete="off" required="">
                                                        <small><?php echo form_error('old_password'); ?></small>
                                                    </div>
                                                </div>
                                                <div class="row text-left m-l-5 m-t-15">
                                                    <div class="col-lg-4"><label for="new_password">New Password</label></div>
                                                    <div class="col-lg-1">:</div>
                                                    <div class="input-group col-lg-6">
                                                        <input type="password" name="new_password" class="form-control" id="new_password" placeholder="New Password" data-parsley-required-message="Please Enter New Password" minlength="4" 
                                                      minlength="4" autocomplete="off" required="" >
                                                        <small><?php echo form_error('new_password'); ?></small>
                                                    </div>
                                                </div>
                                                <div class="row text-left m-l-5 m-t-15">
                                                    <div class="col-lg-4"><label for="c_password">Confirm Password</label></div>
                                                    <div class="col-lg-1">:</div>
                                                    <div class="input-group col-lg-6">
                                                        <input type="password" name="c_password" class="form-control" id="c_password" data-parsley-equalto="#new_password" minlength="4" placeholder="Confirm password"  data-parsley-required-message="Please Enter confirm password" autocomplete="off" required="">
                                                        <small><?php echo form_error('c_password'); ?></small>
                                                    </div>
                                                </div>
                                                <div class="row text-left m-t-15">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-6">
                                                        <input type="submit" class="btn btn-purple waves-light" value="Change Password" name="submit">
                                                    </div>
                                                </div>
                                                <div class="row text-left m-l-15 m-t-15"></div>
                                            </div>
                                        </form>
			                        </div>
                        		</div>
                         	</div>
                            <!-- col -->
                        </div>
                        <!-- end row -->
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
        <script type="text/javascript">
             $(document).ready(function() {
                $('form').parsley();
            });
        </script>
    </body>
</html>