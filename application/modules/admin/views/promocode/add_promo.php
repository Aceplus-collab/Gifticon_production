<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(dirname(__FILE__)."/../inc/header.php");?>
        <title><?php echo MY_SITE_NAME;?> | Add Coupon code</title>
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
                                    <h4 class="text-dark header-title m-t-0">Add Coupon code</h4>
                                    <form role="form" method="POST" action="<?php echo base_url(); ?>admin/promocode/insert" enctype="multipart/form-data">
                                        <!--NAME AND USERNAME-->
                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label for="name">Code</label>
                                                <input type="text" name="code" class="form-control" id="code" placeholder="Enter coupon code" required="">
                                                <small><?php echo form_error('name'); ?></small>
                                            </div>
                                           
                                        </div>

                                        <div class="row">
                                        <div class="form-group col-lg-8">
                                               <label class="control-label" for="datepickertime">Type</label>
                                                <div class="form-control">
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="type" value="percentage" name="type"  data-parsley-required-message="Please select type"
                                                        data-parsley-errors-container="#genderError"  checked="">
                                                        <label for="percentage">Percentage</label>
                                                    </div>
                                                    <div class="radio radio-info radio-inline" >
                                                        <input type="radio" id="type" value="flat" name="type"
                                                        data-parsley-errors-container="#genderError"
                                                        data-parsley-required-message="Please select type" 
                                                        required="" >
                                                        <label for="flat">Flat</label>
                                                    </div>
                                                </div>
                                                <span id="genderError"></span>
                                                <small><?php echo form_error('type'); ?></small>
                                            </div>
                                        </div> 

                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label for="name">Discount</label>
                                                <input type="text" name="discount" class="form-control" id="discount" placeholder="Enter coupon discount Ex: 5 or 10" required="">
                                                <small><?php echo form_error('name'); ?></small>
                                            </div>
                                           
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-4">
                                                <label class="control-label" for="datepicker">Start date</label>
                                                <div class="input-group">
                                                    <input type="text" name="sdate" class="form-control datepicker" placeholder="YYYY-MM-DD" id="sdate" data-parsley-required-message="Please select start date" data-parsley-errors-container="#dobError" required="">
                                                    <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('sdate'); ?></small> 
                                            </div>

                                            <div class="form-group col-lg-4">
                                                <label class="control-label" for="datepicker">End date</label>
                                                <div class="input-group">
                                                    <input type="text" name="edate" class="form-control datepicker" placeholder="YYYY-MM-DD" id="edate" data-parsley-required-message="Please select end date" data-parsley-errors-container="#dobError" required="">
                                                    <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('edate'); ?></small> 
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

                $('#sdate').datepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose:true
                });


                $('#edate').datepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose:true
                });
            
                
            });
        </script>
    </body>
</html>