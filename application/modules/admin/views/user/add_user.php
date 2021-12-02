<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(dirname(__FILE__)."/../inc/header.php");?>
        <title><?php echo MY_SITE_NAME;?> | Add User</title>
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
                                <div class="card-box">
                                    <h4 class="text-dark header-title m-t-0">Add User</h4>
                                    <form role="form" method="POST" action="<?php echo base_url(); ?>admin/user/insert" enctype="multipart/form-data">
                                        <!--NAME AND USERNAME-->
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" data-parsley-pattern="/^[A-Z]+$/i" data-parsley-pattern-message="Please enter character" required="">
                                                <small><?php echo form_error('name'); ?></small>
                                            </div>
                                           <div class="form-group col-lg-6">
                                                <label for="user_name">User Name</label>
                                                <input type="text" name="username" class="form-control" id="username" placeholder="Enter User Name" required="">
                                                <small><?php echo form_error('username'); ?></small>
                                            </div>
                                        </div>
                                        <!-- EMAIL, COUNTRY CODE, MOBILE NUMBER-->
                                        <div class="row">
                                            

                                            <div class="form-group col-lg-6">
                                                <label for="email">Email address</label>
                                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" data-parsley-required-message="Please provide email" required="">
                                                <small><?php if(isset($email)){echo $email;} ?></small>
                                                <small><?php echo form_error('email'); ?></small>
                                            </div>
                                            <div class="form-group col-lg-2 col-md-4 col-xs-4">
                                                <label for="country_code">Country Code</label>
                                                <select id="country_code" name="country_code"  class="btn btn-default dropdown-toggle waves-effect waves-light form-control text-center" required="">
                                                </select>
                                                <small><?php echo form_error('country_code'); ?></small>
                                            </div>
                                            <div class="form-group col-lg-4 col-md-8 col-xs-8">
                                                <label for="phone">Phone Number</label>
                                                <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Phone Number" data-parsley-required-message="Please provide phone no"  minlength="8" maxlength="16" required data-parsley-type="number" required="">
                                                <small><?php if(isset($mobile)){echo $mobile;} ?></small>
                                                <small><?php echo form_error('phone'); ?></small>
                                            </div>
                                            
                                        </div>
                                        <!-- PASSWORD AND CONFIRM PASSWORD-->
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label for="password">Password</label>
                                                <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" required="">
                                                <small><?php echo form_error('password'); ?></small>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="conformpassword">Confirm Password</label>
                                                <input type="password" name="c_password" class="form-control" id="conformpassword" data-parsley-equalto="#password" minlength="4" placeholder="Enter Confirm password"  data-parsley-required-message="Please provide confirm password" required>
                                                <small><?php echo form_error('c_password'); ?></small>
                                            </div>
                                        </div>
                                        <!-- ADDRESS,POSTAL CODE,BIRTH YEAR,PROFILE IMAGE-->
                                        <div class="row">
                                            
                                            <div class="col-lg-6">
                                               <div class="row">
                                                    <div class="form-group col-lg-6">
                                                        <label class="control-label " for="datepicker">Birth Date</label>
                                                        <div class="input-group">
                                                            <input type="text" name="dob" class="form-control datepicker" placeholder="yyyy-mm-dd" id="datepicker" data-parsley-required-message="Please provide DOB" data-parsley-errors-container="#dobError" required="">
                                                            <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                                        </div>
                                                        <span id="dobError"></span>
                                                        <small><?php echo form_error('dob'); ?></small> 
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <label for="country">Country</label>
                                                        <input type="country" name="country" class="form-control" id="country" placeholder="Enter Country" data-parsley-required-message="Please provide country" required="">
                                                        <small><?php if(isset($country)){echo $country;} ?></small>
                                                        <small><?php echo form_error('country'); ?></small>
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <label class="control-label" for="profile_image">Upload Profile image</label>
                                                        <input type="file" id="profile_image" name="profile_image" class="filestyle" data-input="false" id="filestyle-1" tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);" data-parsley-required-message="Please upload profile image" data-parsley-errors-container="#profileError" >
                                                        <span id="profileError"></span>
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
                    url:"<?php echo base_url().'admin/user/get_code'?>",
                    success:function(data){
                        $('#country_code').html(data);
                    }
                });
            });
        </script>
    </body>
</html>