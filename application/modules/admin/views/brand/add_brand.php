<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(dirname(__FILE__)."/../inc/header.php");?>
        <title><?php echo MY_SITE_NAME;?></title>
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
                                    <h4 class="text-dark header-title m-t-0">Add Brand</h4>
                                    <form role="form" method="POST" action="<?php echo base_url(); ?>admin/brand/insert" enctype="multipart/form-data">
                                        <!--NAME AND USERNAME-->
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label for="name">Merchant Id</label>
                                                <input type="text" name="merchant_id" class="form-control" id="merchant_id" placeholder="Enter Mrchant Id" required="">
                                                <small><?php echo form_error('merchant_id'); ?></small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" required="">
                                                <small><?php echo form_error('name'); ?></small>
                                            </div>
                                           <div class="form-group col-lg-6">
                                                <label for="user_name">User Name</label>
                                                <input type="text" name="username" class="form-control" id="username" placeholder="Enter User Name" required="">
                                                <small><?php echo form_error('username'); ?></small>
                                            </div>
                                        </div>

                                         <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label for="user_name">Country</label>
                                                  <select name="country_ids[]" class="select2 select2-multiple" multiple="multiple" multiple data-placeholder="Choose ...">
                                                <optgroup label="Country">
                                                    <?php if($country) { foreach ($country as $key => $value) { ?>
                                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                                    <?php } } ?>
                                                </optgroup>
                                                </select>
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
                                            <div class="form-group col-lg-6">
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
                                            
                                            <div class="form-group col-lg-6">
                                                <label class="control-label" for="datepicker">Website</label>
                                                <input type="text" name="website" class="form-control datepicker" placeholder="Website" id="website" >
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('website'); ?></small> 
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="commision_rate">Comission Rate</label>
                                                <input type="text" name="commision_rate" class="form-control" id="commision_rate" placeholder="Enter Country" data-parsley-required-message="Please provide commision rate" required="">
                                                <small><?php if(isset($commision_rate)){echo $commision_rate;} ?></small>
                                                <small><?php echo form_error('commision_rate'); ?></small>
                                            </div>
                                                    
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label for="tags">Select Tags</label>
                                                    <br>
                                                    <?php if($tags) { foreach ($tags as $key => $value) {
                                                        # code...
                                                     ?>
                                                    <input type="checkbox" id="basic_checkbox_<?php echo $key; ?>" name="tags[]" value="<?php echo $value['id']; ?> ">
                                                    <label for="basic_checkbox_<?php echo $key; ?>"><?php echo $value['name']; ?></label>
                                                    <?php } } ?>
                                                    <small><?php echo form_error('tags'); ?></small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label for="occasion">Select Occasion</label>
                                                    <br>
                                                    <?php if($occasion) { foreach ($occasion as $key => $value) {
                                                        # code...
                                                     ?>
                                                    <input type="checkbox" id="basic_checkbox_<?php echo $key; ?>" name="occasion[]" value="<?php echo $value['id']; ?> ">
                                                    <label for="basic_checkbox_<?php echo $key; ?>"><?php echo $value['name']; ?></label>
                                                    <?php } } ?>
                                                    <small><?php echo form_error('occasion'); ?></small>
                                            </div>
                                        </div>

                                         <div class="row">

                                             <div class="form-group col-lg-6">
                                                <label for="description">Description</label>
                                                <textarea name="description" class="form-control" id="description"></textarea>
                                                <small><?php if(isset($description)){echo $description;} ?></small>
                                                <small><?php echo form_error('description'); ?></small>
                                            </div>
                                            
                                            <div class="form-group col-lg-6">
                                                <label class="control-label" for="image">Upload Brand image</label>
                                                <input type="file" id="image" name="image" class="filestyle" data-input="false" id="filestyle-1" tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);" data-parsley-required-message="Please upload image" data-parsley-errors-container="#profileError" >
                                                <span id="profileError"></span>
                                                <small><?php echo form_error('image'); ?></small>

                                            </div>

                                           
                                        </div>
                                        <div class="row m-t-0">
                                        </div>
                                        
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

        <script src="<?php echo base_url(); ?>assets/plugins/select2/select2.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('form').parsley();

                 $(".select2").select2();
            
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