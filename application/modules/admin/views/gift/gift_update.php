<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
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
                                    <h4 class="text-dark header-title m-t-0">Edit Gifticon</h4>
                                    <form role="form" method="POST" action="<?php echo base_url(); ?>admin/gift/update" enctype="multipart/form-data">
                                        <!--NAME AND USERNAME-->
                                        <input type="hidden" name="gift_id" value="<?php echo $gift['id']; ?>">
                                        <div class="row">
                                            <div class="form-group col-lg-8 ">
                                                <label for="offer_text">Business</label>
                                                    <select class="form-control show-tick" style="margin: 0px;" name="business_id" id="business_id" data-parsley-required-message="Please Choose business" required="">
                                                        <?php foreach ($business as $key => $value) 
                                                        {
                                                            if($gift['business_id'] == $value['id'])
                                                            {

                                                            ?>
                                                            <option selected value="<?php echo $value['id']; ?>">
                                                            <?php echo $value['name'].' - '.$value['username'];?>
                                                            </option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $value['id']; ?>">
                                                            <?php echo $value['name'].' - '.$value['username'];?>
                                                            </option>
                                                        <?php
                                                            }
                                                        }   
                                                        ?>
                                                    </select>
                                                <small><?php echo form_error('category_name'); ?></small>
                                            </div>
                                        </div>

                                        <!-- <div class="row">
                                            <div class="form-group col-lg-8 ">
                                                <label for="offer_text">Country</label>
                                                    <select class="form-control show-tick" style="margin: 0px;" name="country_id" id="country_id" data-parsley-required-message="Please Choose country" required="">
                                                        <option value="">Select Country</option>
                                                        <?php foreach ($country as $key => $value) 
                                                        {
                                                           if($gift['country_id'] == $value['id'])
                                                            {

                                                            ?>
                                                            <option selected value="<?php echo $value['id']; ?>">
                                                            <?php echo $value['name'];?>
                                                            </option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $value['id']; ?>">
                                                            <?php echo $value['name'];?>
                                                            </option>
                                                        <?php
                                                            }
                                                        }   
                                                        ?>
                                                    </select>
                                                <small><?php echo form_error('category_name'); ?></small>
                                            </div>
                                        </div> -->

                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label class="control-label" for="datepickertime">Gifticon Type</label>
                                                <div class="form-control">
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" disabled id="gifticon_type" <?php echo ($gift['gifticon_type'] == 0) ? 'checked' : ''; ?>  value="0" name="gifticon_type"  data-parsley-required-message="Please select gifticon type"
                                                        data-parsley-errors-container="#genderError"  >
                                                        <label for="Female">Gifticon</label>
                                                    </div>
                                                    <div class="radio radio-info radio-inline" >
                                                        <input type="radio" disabled id="gifticon_type" <?php echo ($gift['gifticon_type'] == 1) ? 'checked' : ''; ?> value="1" name="gifticon_type"
                                                        data-parsley-errors-container="#genderError"
                                                        data-parsley-required-message="Please select gifticon type" 
                                                        required="" >
                                                        <label for="Male">Giftcard</label>
                                                    </div>
                                                </div>
                                                <span id="genderError"></span>
                                                <small><?php echo form_error('gifticon_type'); ?></small>
                                            </div>
                                        </div>

                                        <?php if ($gift['gifticon_type'] == 1) { ?>

                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label class="control-label" for="datepickertime">Giftcard Format</label>
                                                <div class="form-control">
                                                    <div class="radio radio-info radio-inline">
                                                        <input  type="radio" id="giftcard_format" <?php echo ($gift['giftcard_format'] == 0) ? 'checked' : ''; ?> value="0" name="giftcard_format" data-parsley-errors-container="#genderError"  >
                                                        <label for="Female">Plain Text</label>
                                                    </div>
                                                  <!--   <div class="radio radio-info radio-inline" >
                                                        <input disabled type="radio" id="giftcard_format" <?php echo ($gift['giftcard_format'] == 1) ? 'checked' : ''; ?> value="1" name="giftcard_format" data-parsley-errors-container="#genderError" >
                                                        <label for="Male">QR code</label>
                                                    </div> -->
                                                    <div class="radio radio-info radio-inline" >
                                                        <input  type="radio" id="giftcard_format" <?php echo ($gift['giftcard_format'] == 2) ? 'checked' : ''; ?> value="2" name="giftcard_format" data-parsley-errors-container="#genderError"  >
                                                        <label for="Male">Code 128</label>
                                                    </div>
                                                </div>
                                                <span id="genderError"></span>
                                                <small><?php echo form_error('giftcard_format'); ?></small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div id="showhidefile" class="form-group col-lg-8">
                                                <label class="control-label" for="datepickertime">Upload CSV Codes</label>
                                                <div class="form-control">
                                                    <input class="m-t-5" type="file" id="execel" name="file" class="filestyle" data-input="false" data-parsley-errors-container="#file"> 
                                                    <span id="file"></span>
                                                </div>
                                                <span id="genderError"></span>
                                                <small><?php echo form_error('giftcard_format'); ?></small>
                                            </div>
                                        </div>

                                        <?php } ?>

                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label for="tags">Select Tags</label>
                                                    <br>
                                                    <?php 
                                                    if($tags) { foreach ($tags as $key => $value) 
                                                    {
                                                        if (in_array($value['id'], $gifticons_tags))
                                                        {
                                                     ?>
                                                         <input type="checkbox" checked=""  id="basic_checkbox_<?php echo $key; ?>" name="tags[]" value="<?php echo $value['id']; ?> ">
                                                        <label for="basic_checkbox_<?php echo $key; ?>"><?php echo $value['name']; ?></label>
                                                     <?php    
                                                        }
                                                        else
                                                        {
                                                     ?>   
                                                         <input type="checkbox" id="basic_checkbox_<?php echo $key; ?>" name="tags[]" value="<?php echo $value['id']; ?> ">
                                                        <label for="basic_checkbox_<?php echo $key; ?>"><?php echo $value['name']; ?></label>
                                                     <?php   
                                                        }
                                                     ?>
                                                    
                                                    <?php 
                                                     } 
                                                    } 
                                                    ?>
                                                    <small><?php echo form_error('tags'); ?></small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label class="control-label" for="Name">Name</label>
                                                <input type="text" name="name" value="<?php echo $gift['name']; ?>" required="" class="form-control" placeholder="Name" id="name" >
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('name'); ?></small> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-4">
                                                <label for="normal_price">Normal Price</label>
                                                <input type="text" class="form-control" value="<?php echo $gift['normal_price']; ?>"  name="normal_price" id="normal_price" placeholder="Enter Normal Price" data-parsley-required-message="Please Normal Price" required="">
                                                <small><?php echo form_error('normal_price'); ?></small>
                                            </div>

                                            <div class="form-group col-lg-4">
                                                <label for="coupon_price">Coupon Price</label>
                                                <input type="text" value="<?php echo $gift['coupon_price']; ?>" name="coupon_price" class="form-control" id="coupon_price" placeholder="Enter Coupon Price" data-parsley-required-message="Please Coupon Price" >
                                                <small><?php echo form_error('coupon_price'); ?></small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-4">
                                                <label class="control-label" for="datepicker">Sale Start date</label>
                                                <div class="input-group">
                                                    <input type="text" name="sale_start_date" value="<?php echo $gift['sale_start_date']; ?>" class="form-control datepicker" placeholder="YYYY-MM-DD" id="sale_start_date" data-parsley-required-message="Please select start date" data-parsley-errors-container="#dobError" required="">
                                                    <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('sale_start_date'); ?></small> 
                                            </div>

                                            <div class="form-group col-lg-4">
                                                <label class="control-label" for="datepicker">Sale End date</label>
                                                <div class="input-group">
                                                    <input type="text" name="sale_end_date" value="<?php echo $gift['sale_end_date']; ?>" class="form-control datepicker" placeholder="YYYY-MM-DD" id="sale_end_date" data-parsley-required-message="Please select end date" data-parsley-errors-container="#dobError" required="">
                                                    <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('sale_end_date'); ?></small> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-8">
                                                <label for="sizes">Select Size</label>
                                                    <br>
                                                    <?php 
                                                    if($sizes) { foreach ($sizes as $key => $value) 
                                                    {
                                                        if (in_array($value, $sizedata))
                                                        {
                                                     ?>
                                                         <input type="checkbox" checked=""  id="basic_checkbox_<?php echo $key; ?>" name="size_data[]" value="<?php echo $value; ?> ">
                                                        <label for="basic_checkbox_<?php echo $key; ?>"><?php echo $value; ?></label>
                                                     <?php    
                                                        }
                                                        else
                                                        {
                                                     ?>   
                                                         <input type="checkbox" id="basic_checkbox_<?php echo $key; ?>" name="size_data[]" value="<?php echo $value; ?> ">
                                                        <label for="basic_checkbox_<?php echo $key; ?>"><?php echo $value; ?></label>
                                                     <?php   
                                                        }
                                                     ?>
                                                    
                                                    <?php 
                                                     } 
                                                    } 
                                                    ?>
                                                    <small><?php echo form_error('sizes'); ?></small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div id="div1" class="form-group col-lg-3">
                                                <label class="control-label" for="small_price">Small Price</label>
                                                <div class="input-group">
                                                    <input type="text" name="Small_price" class="form-control" placeholder="Small Price" id="size_small_price" data-parsley-errors-container="#dobError"
                                                    value="<?php if(!empty($gift_size)) { foreach($gift_size as $key => $value) { if(in_array(trim($value['size']),$sizes)) { if(trim($value['size']) == 'Small') { echo $value['price']; } } } } ?>" >
                                                   
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('expiry_date'); ?></small> 
                                            </div>
                                            <div id="div2" class="form-group col-lg-3">
                                                <label class="control-label" for="regular_price">Regular Price</label>
                                                <div class="input-group">
                                                    <input type="text" name="Regular_price" class="form-control" placeholder="Regular Price" id="size_regular_price" data-parsley-errors-container="#dobError" value="<?php if(!empty($gift_size)) { foreach($gift_size as $key => $value) { if(in_array(trim($value['size']),$sizes)) { if(trim($value['size']) == 'Regular') { echo $value['price']; } } } } ?>"  >
                                                   
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('expiry_date'); ?></small> 
                                            </div>
                                            <div id="div3" class="form-group col-lg-3">
                                                <label class="control-label" for="Medium_price">Medium Price</label>
                                                <div class="input-group">
                                                    <input type="text" name="Medium_price" class="form-control" placeholder="Medium Price" id="size_medium_price" data-parsley-errors-container="#dobError" value="<?php if(!empty($gift_size)) { foreach($gift_size as $key => $value) { if(in_array(trim($value['size']),$sizes)) { if(trim($value['size']) == 'Medium') { echo $value['price']; } } } } ?>" >
                                                   
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('medium_price'); ?></small> 
                                            </div>
                                            <div id="div4" class="form-group col-lg-3">
                                                <label class="control-label" for="large_price">Large Price</label>
                                                <div class="input-group">
                                                    <input type="text" name="Large_price" class="form-control" placeholder="Large Price" id="size_large_price" data-parsley-errors-container="#dobError" value="<?php if(!empty($gift_size)) { foreach($gift_size as $key => $value) { if(in_array(trim($value['size']),$sizes)) { if(trim($value['size']) == 'Large') { echo $value['price']; } } } } ?>">
                                                   
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('large_price'); ?></small> 
                                            </div>
                                            
                                        </div>

                                         <div class="row">
                                            <div id="div1" class="form-group col-lg-3">
                                                <label class="control-label" for="Small_price_coupon">Small Coupon Price</label>
                                                <div class="input-group">
                                                    <input type="text" name="Small_price_coupon" class="form-control" placeholder="Small Coupon Price" id="size_small_price_coupon" data-parsley-errors-container="#dobError" value="<?php if(!empty($gift_size)) { foreach($gift_size as $key => $value) { if(in_array(trim($value['size']),$sizes)) { if(trim($value['size']) == 'Small') { echo $value['coupon_price']; } } } } ?>" >
                                                   
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('expiry_date'); ?></small> 
                                            </div>
                                            <div id="div2" class="form-group col-lg-3">
                                                <label class="control-label" for="Regular_price_coupon">Regular Coupon Price</label>
                                                <div class="input-group">
                                                    <input type="text" name="Regular_price_coupon" class="form-control" placeholder="Regular Coupon Price" id="size_regular_price_coupon" data-parsley-errors-container="#dobError" value="<?php if(!empty($gift_size)) { foreach($gift_size as $key => $value) { if(in_array(trim($value['size']),$sizes)) { if(trim($value['size']) == 'Regular') { echo $value['coupon_price']; } } } } ?>" >
                                                   
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('expiry_date'); ?></small> 
                                            </div>
                                            <div id="div3" class="form-group col-lg-3">
                                                <label class="control-label" for="Medium_price">Medium Coupon Price</label>
                                                <div class="input-group">
                                                    <input type="text" name="Medium_price_coupon" class="form-control" placeholder="Medium Coupon Price" id="size_medium_price_coupon" data-parsley-errors-container="#dobError" value="<?php if(!empty($gift_size)) { foreach($gift_size as $key => $value) { if(in_array(trim($value['size']),$sizes)) { if(trim($value['size']) == 'Medium') { echo $value['coupon_price']; } } } } ?>" >
                                                   
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('medium_price'); ?></small> 
                                            </div>
                                            <div id="div4" class="form-group col-lg-3">
                                                <label class="control-label" for="Large_price_coupon">Large Coupon Price</label>
                                                <div class="input-group">
                                                    <input type="text" name="Large_price_coupon" class="form-control" placeholder="Large Coupon Price" id="size_large_price_coupon" data-parsley-errors-container="#dobError" value="<?php if(!empty($gift_size)) { foreach($gift_size as $key => $value) { if(in_array(trim($value['size']),$sizes)) { if(trim($value['size']) == 'Large') { echo $value['coupon_price']; } } } } ?>">
                                                   
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('Large_price_coupon'); ?></small> 
                                            </div>
                                            
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label class="control-label" for="datepickertime">Expiration Type</label>
                                                <div class="form-control">
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="expiration_type" value="0" <?php echo ($gift['expiration_type'] == 0) ? 'checked' : ''; ?> name="expiration_type"  data-parsley-required-message="Please select"
                                                        data-parsley-errors-container="#genderError"  checked="">
                                                        <label for="Female">Specific Date</label>
                                                    </div>
                                                    <div class="radio radio-info radio-inline" >
                                                        <input type="radio" id="expiration_type" value="1" <?php echo ($gift['expiration_type'] == 1) ? 'checked' : ''; ?> name="expiration_type"
                                                        data-parsley-errors-container="#genderError"
                                                        data-parsley-required-message="Please select" 
                                                        required="" >
                                                        <label for="Male">3Months From Purchase</label>
                                                    </div>
                                                    <div class="radio radio-info radio-inline" >
                                                        <input type="radio" id="expiration_type" value="2" <?php echo ($gift['expiration_type'] == 2) ? 'checked' : ''; ?> name="expiration_type"
                                                        data-parsley-errors-container="#genderError"
                                                        data-parsley-required-message="Please select" 
                                                        required="" >
                                                        <label for="Male">6Months From Purchase</label>
                                                    </div>
                                                    <div class="radio radio-info radio-inline" >
                                                        <input type="radio" id="expiration_type" value="3" <?php echo ($gift['expiration_type'] == 3) ? 'checked' : ''; ?> name="expiration_type"
                                                        data-parsley-errors-container="#genderError"
                                                        data-parsley-required-message="Please select" 
                                                        required="" >
                                                        <label for="Male">12Months From Purchase</label>
                                                    </div>
                                                </div>
                                                <span id="genderError"></span>
                                                <small><?php echo form_error('expiration_type'); ?></small>
                                            </div>
                                        </div>

                                        <div class="row">
                                             <div class="form-group col-lg-4">
                                                <label class="control-label" for="datepicker">Discount %</label>
                                                <div class="input-group">
                                                    <input type="text" name="discount" value="<?php echo $gift['discount']; ?>" class="form-control" placeholder="Discount" id="discount" data-parsley-errors-container="#dobError" >
                                                    
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('expiry_date'); ?></small> 
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label class="control-label" for="datepicker">Expiration Date</label>
                                                <div class="input-group">
                                                    <input type="text" name="expiry_date" value="<?php echo $gift['expiry_date']; ?>" class="form-control datepicker" placeholder="YYYY-MM-DD" id="expiry_date" data-parsley-errors-container="#dobError" >
                                                    <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('expiry_date'); ?></small> 
                                            </div>
                                        </div>

                                         <div class="row">
                                            <div class="form-group col-lg-4">
                                                <label class="control-label" for="datepicker">Valid Start date</label>
                                                <div class="input-group">
                                                    <input type="text" name="valid_start_date" value="<?php echo $gift['valid_start_date']; ?>" class="form-control datepicker" placeholder="YYYY-MM-DD" id="valid_start_date" data-parsley-errors-container="#dobError" >
                                                    <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('valid_start_date'); ?></small> 
                                            </div>

                                            <div class="form-group col-lg-4">
                                                <label class="control-label" for="datepicker">Valid End date</label>
                                                <div class="input-group">
                                                    <input type="text" name="valid_end_date" value="<?php echo $gift['valid_end_date']; ?>" class="form-control datepicker" placeholder="YYYY-MM-DD" id="valid_end_date"  data-parsley-errors-container="#dobError" >
                                                    <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                                </div>
                                                <span id="dobError"></span>
                                                <small><?php echo form_error('valid_end_date'); ?></small> 
                                            </div>
                                        </div>

                                         <div class="row">
                                             <div class="form-group col-lg-6">
                                                <label for="available_store">Available Store</label>
                                                <textarea name="available_store" class="form-control" id="available_store"><?php echo $gift['available_store']; ?></textarea>
                                                <small><?php if(isset($available_store)){echo $available_store;} ?></small>
                                                <small><?php echo form_error('available_store'); ?></small>
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <label for="description">Description</label>
                                                <textarea name="description" class="form-control" id="description"><?php echo $gift['description']; ?></textarea>
                                                <small><?php if(isset($description)){echo $description;} ?></small>
                                                <small><?php echo form_error('description'); ?></small>
                                            </div>
                                        </div>  
                                        <div class="row">
                                            <div class="form-group col-lg-4">
                                                <label for="terms">Terms & Condition</label>
                                                <textarea name="terms" class="form-control" id="terms"><?php echo $gift['terms']; ?></textarea>
                                                <small><?php if(isset($terms)){echo $terms;} ?></small>
                                                <small><?php echo form_error('terms'); ?></small>
                                            </div>

                                             <div class="form-group col-lg-4">
                                                <label class="control-label" for="image">Change Product Main image</label>
                                                <input type="file" id="image" name="image" class="filestyle" data-input="false" id="filestyle-1" tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);"  data-parsley-errors-container="#profileError" >
                                                <span id="profileError"></span>
                                                <small><?php echo form_error('image'); ?></small>

                                                <br>
                                                
                                                <img class="img-responsive" src="<?php echo GIFT_IMAGE.$gift['image']; ?>" height="150" width="210"  alt="No image">  

                                            </div>

                                            <div class="form-group col-lg-4">
                                                <label for="sequence">Sequence</label>
                                                <input type="text" value="<?php echo $gift['sequence']; ?>" name="sequence" class="form-control" id="sequence" >
                                                <small><?php echo form_error('sequence'); ?></small>
                                            </div>
                                        </div>
                                        <div class="row">
                                             <div class="form-group col-lg-4">
                                                <label class="control-label" for="image">Product multiple image</label>
                                                <input type="file" id="pimages" multiple="" name="pimages[]" class="filestyle" data-input="false" id="filestyle-1" tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);"  data-parsley-errors-container="#profileError" >
                                                <span id="profileError"></span>
                                                <small><?php echo form_error('image'); ?></small>

                                             </div>
                                             <br>
                                        <?php if(isset($gift_images) && !empty($gift_images) ) { 
                                            foreach ($gift_images as $key => $value) {

                                                ?>
                                                <!-- <?php if($key != 0) { ?> -->
                                                <spam><a href="<?php echo base_url(); ?>admin/gift/deleteImage/<?php echo $value['id']; ?>/<?php echo $value['gifticon_id']; ?>" onclick="return confirm('Are you sure you want to delete this image?')">Delete</a></spam>
                                                
                                                <img style="width: 100px" src="<?php echo GIFT_IMAGE.$value['image']; ?>">
                                                <!-- <?php } ?> -->
                                        <?php } } ?>
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

        <script type="text/javascript">
            $(document).ready(function() {
                $('form').parsley();

                 $('#sale_start_date').datepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose:true
                });


                $('#sale_end_date').datepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose:true
                });


                $('#expiry_date').datepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose:true
                });


                $('#valid_start_date').datepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose:true
                });


                $('#valid_end_date').datepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose:true
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