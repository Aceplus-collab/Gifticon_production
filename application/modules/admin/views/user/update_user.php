<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(dirname(__FILE__)."/../inc/header.php");?>
        <title><?php echo MY_SITE_NAME;?> | Update User</title>
        <?php include(dirname(__FILE__)."/../inc/style.php");?>
    </head>
    <body class="fixed-left">
        <!-- Begin page -->
        <div id="wrapper">
            <!-- Top Bar Start -->
            <?php include(dirname(__FILE__)."/../inc/top_bar.php");?>
            <!-- Top Bar End -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include(dirname(__FILE__)."/../inc/left_side_bar.php");?>
            <!-- Left Sidebar End -->
            <!-- Right Sidebar -->
            <?php include(dirname(__FILE__)."/../inc/right_side_bar.php");?>
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
                          <div class="col-lg-12">
                            <?php if($this->session->flashdata('msg')){
                                            echo '<div class="alert alert-danger">'.$this->session->flashdata('msg').'</div>'; 
                                        } ?>
                                        <?php if(isset($error_msg) && $error_msg != ''){
                                            echo '<div class="alert alert-danger">'.$error_msg.'</div>'; 
                                        } ?>
                                        <?php if($this->session->flashdata('suc')){
                                            echo '<div class="alert alert-success">'.$this->session->flashdata('suc').'</div>'; 
                                        } ?>

                                <button class="btn btn-sm btn-default" onclick="goBack()" >Go Back</button>
                                <br>        
                                <div class="card-box">
                                    <h4 class="text-dark header-title m-t-0">Update User</h4>
                                    <form role="form" method="POST" action="<?php echo base_url(); ?>admin/user/update" enctype="multipart/form-data">
                                        <!--NAME AND USERNAME-->
                                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $user['id']; ?>">
                                        <div class="row"> 
                                            <div class="form-group col-lg-6">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" data-parsley-pattern="/^[A-Z]+$/i" data-parsley-pattern-message="Please enter character" value="<?php echo $user['name']; ?>" required="">
                                                <small><?php echo form_error('name'); ?></small>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="user_name">User Name</label>
                                                <input type="text" name="username" class="form-control" id="username" placeholder="Enter User Name" value="<?php echo $user['username']; ?>" required="">
                                                <small><?php echo form_error('username'); ?></small>
                                            </div>
                                        </div>
                                        <!-- EMAIL, COUNTRY CODE, MOBILE NUMBER-->
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label for="email">Email address</label>
                                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" data-parsley-required-message="Please provide email" value="<?php echo $user['email']; ?>" required="">
                                                <small><?php if(isset($email)){echo $email;} ?></small>
                                                <small><?php echo form_error('email'); ?></small>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label for="country_code">Country Code</label>
                                                <select id="country_code" name="country_code"  class="btn btn-default dropdown-toggle waves-effect waves-light form-control text-center" required="">
                                                </select>
                                                <small><?php echo form_error('country_code'); ?></small>
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="phone">Phone Number</label>
                                                <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone Number" data-parsley-required-message="Please provide phone no"  minlength="8" maxlength="16" required data-parsley-type="number" value="<?php echo $user['phone']; ?>" required="">
                                                <small><?php if(isset($mobile)){echo $mobile;} ?></small>
                                                <small><?php echo form_error('phone'); ?></small>
                                            </div>
                                            
                                        </div>
                                        <!-- PASSWORD AND CONFIRM PASSWORD-->
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label for="password">Change Password</label>
                                                <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" >
                                                <small><?php echo form_error('password'); ?></small>
                                            </div>
                                        </div>
                                        <!-- ADDRESS,POSTAL CODE,BIRTH YEAR,PROFILE IMAGE-->
                                        <div class="row">
                                            
                                            <div class="col-lg-6">
                                               <div class="row">
                                                    <div class="form-group col-lg-6">
                                                        <label class="control-label " for="datepicker">Birth Date</label>
                                                        <div class="input-group">
                                                            <input type="text" name="dob" class="form-control datepicker" placeholder="yyyy-mm-dd" value="<?php echo $user['dob']; ?>" id="datepicker" data-parsley-required-message="Please provide DOB" data-parsley-errors-container="#dobError" required="">
                                                            <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                                        </div>
                                                        <span id="dobError"></span>
                                                        <small><?php echo form_error('dob'); ?></small> 
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <label for="country">Country</label>
                                                        <input type="country" name="country" class="form-control" id="country" placeholder="Enter Country" value="<?php echo $user['country']; ?>" data-parsley-required-message="Please provide country" required="">
                                                        <small><?php if(isset($country)){echo $country;} ?></small>
                                                        <small><?php echo form_error('country'); ?></small>
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <label class="control-label" for="profile_image">Upload Profile image</label>
                                                        <input type="file" id="profile_image" name="profile_image" class="filestyle" data-input="false" id="filestyle-1" tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);" data-parsley-required-message="Please upload profile image" data-parsley-errors-container="#profileError" >
                                                        <span id="profileError"></span>
                                                        <img src="<?php echo PROFILE_IMAGE.$user['profile_image']; ?>" class="thumb-lg img-circle img-thumbnail" alt="profile-image">
                                                        <small><?php echo form_error('profile_image'); ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row m-t-0">
                                    </div>
                                        <!-- <button type="submit" class="btn btn-purple waves-effect waves-light">Submit</button> -->
                                        <input type="submit" class="btn btn-purple waves-light" value="submit">
                                    </form>
                                    <div id="morris-area-with-dotted" style="height: 05px;"></div>
                                </div>
                          </div>

                            <!-- col -->
                        </div>
                        <!-- end row -->
                    </div> <!-- container -->
                </div> <!-- content -->
               <?php include(dirname(__FILE__)."/../inc/footer.php");?>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->
        <?php include(dirname(__FILE__)."/../inc/script.php");?>

        <script type="text/javascript">
            $(document).ready(function() {
                $('form').parsley();
            
                $.ajax({
                    type:"POST",
                    data:{country_code:'<?php echo $user['country_code']; ?>'},
                    url:"<?php echo base_url().'admin/user/get_code'?>",
                    success:function(data){
                        $('#country_code').html(data);
                    }
                });
            });
        </script>



<script>
function goBack() {
    window.history.back();
}
</script> 
    </body>
</html>