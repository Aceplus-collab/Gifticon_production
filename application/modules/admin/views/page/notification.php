<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(dirname(__FILE__)."/../inc/header.php");?>
        <title><?php echo MY_SITE_NAME; ?> | Notification</title>
        <?php include(dirname(__FILE__)."/../inc/style.php");?>

    <link href="<?php echo base_url() ?>assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />

    <link href="<?php echo base_url() ?>assets/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />

    <style type="text/css">
        
        .ms-container {
            width: 943px !important;
        }
    </style>
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
            <div class="content-page">
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="page-title">Notification</h4>
                            <p class="text-muted page-title-alt">
                                Notification to send Users
                            </p>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if($this->session->flashdata('errorindi')){
                                        echo '<div class="alert alert-danger">'.$this->session->flashdata('errorindi').'</div>'; 
                                    } ?>
                                    <?php if(isset($error_msg) && $error_msg != ''){
                                        echo '<div class="alert alert-danger">'.$error_msg.'</div>'; 
                                    } ?>
                                    <?php if($this->session->flashdata('succUser')){
                                        echo '<div class="alert alert-success">'.$this->session->flashdata('succUser').'</div>'; 
                                    } ?>
                                    <h4 class="m-t-0 m-b-30 header-title"><b>Send Notifications To Users</b></h4>
                                    <?php if($AllUsers)
                                    {
                                        ?>
                                        <form method="POST" class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url(); ?>admin/home/sendNotiUsers" > 
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div id="myRadioGroup" class="form-group">
                                                        UPDATE &nbsp;<input type="radio" checked name="type" value="update" />
                                                        EVENT &nbsp;<input type="radio"  name="type" value="event" />
                                                    </div>

                                                    <div id="div1" class="form-group">
                                                     <label for="exampleInputEmail1">Notification Message</label>

                                                     <textarea id="textarea123"  name="message" class="form-control" maxlength="240" rows="2" placeholder="Write your messasge with maximum 240 characters"></textarea>
                                                    </div>

                                                    <div class="well">
                                                        <div class="form-group"> 
                                                            <select name="users[]" class="multi-select" multiple="" id="my_multi_select_custom" >
                                                                <option disabled value="">Please selcect users</option>
                                                                <?php foreach ($AllUsers as $key => $value)
                                                                {
                                                                    ?>
                                                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name'].'-'.$value['username']; ?> - <?php echo $value['email']; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="checkbox checkbox-primary col-lg-2 col-lg-offset-1">
                                                        <input id="select_custom"  type="checkbox">
                                                        <label for="select_custom">
                                                            <strong> Select All</strong>
                                                        </label>
                                                 </div>
                                                </div> 
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="col-sm-4"></label>
                                                        <div class="col-sm-8">
                                                            <button type="submit"  class="btn btn-purple waves-effect waves-light">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                              </div>
                                            </form>
                                            <?php 
                                        }
                                        else
                                        { 
                                            ?>
                                            <h4 class="text-center text-muted"><b>Register but not order users not found</b></h4>
                                            <?php 
                                        } 
                                        ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if($this->session->flashdata('errorindi')){
                                        echo '<div class="alert alert-danger">'.$this->session->flashdata('errorindi').'</div>'; 
                                    } ?>
                                    <?php if(isset($error_msg) && $error_msg != ''){
                                        echo '<div class="alert alert-danger">'.$error_msg.'</div>'; 
                                    } ?>
                                    <?php if($this->session->flashdata('succUser')){
                                        echo '<div class="alert alert-success">'.$this->session->flashdata('succUser').'</div>'; 
                                    } ?>
                                    <h4 class="m-t-0 m-b-30 header-title"><b>Notification to send Business</b></h4>
                                    <?php if($AllUsers)
                                    {
                                        ?>
                                        <form method="POST" class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url(); ?>admin/home/sendNotiBusiness" > 
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div id="myRadioGroup" class="form-group">
                                                        UPDATE &nbsp;<input type="radio" checked  name="type" value="UPDATE" />
                                                        EVENT &nbsp;<input type="radio"  name="type" value="EVENT" />
                                                    </div>

                                                    <div id="div1" class="form-group">
                                                     <label for="exampleInputEmail1">Notification Message</label>

                                                     <textarea id="textarea123"  name="message" class="form-control" maxlength="240" rows="2" placeholder="Write your messasge with maximum 240 characters"></textarea>
                                                    </div>

                                                    <div class="well">
                                                        <div class="form-group"> 
                                                            <select name="users[]" class="multi-select" multiple="" id="my_multi_select_business" >
                                                                <option disabled value="">Please selcect users</option>
                                                                <?php foreach ($AllBusiness as $key => $value)
                                                                {
                                                                    ?>
                                                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name'].'-'.$value['username']; ?> - <?php echo $value['email']; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="checkbox checkbox-primary col-lg-2 col-lg-offset-1">
                                                        <input id="select_business"  type="checkbox">
                                                        <label for="select_business">
                                                            <strong> Select All</strong>
                                                        </label>
                                                 </div>
                                                </div> 
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="col-sm-4"></label>
                                                        <div class="col-sm-8">
                                                            <button type="submit"  class="btn btn-purple waves-effect waves-light">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                              </div>
                                            </form>
                                            <?php 
                                        }
                                        else
                                        { 
                                            ?>
                                            <h4 class="text-center text-muted"><b>Register but not order users not found</b></h4>
                                            <?php 
                                        } 
                                        ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 

          
                    
                    
                
            </div> 
            <?php include(dirname(__FILE__)."/../inc/footer.php"); ?>
        </div>
            
        </div>
        <!-- END wrapper -->
        <?php include(dirname(__FILE__)."/../inc/script.php");?>

        <script src="<?php echo base_url() ?>assets/plugins/parsleyjs/dist/parsley.min.js"></script>

        <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/multiselect/js/jquery.multi-select.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url() ?>assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>
       
<script type="text/javascript">
$(document).ready(function () {
   


    $('#my_multi_select_custom').multiSelect({
        selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search by name,username,email'>",
        selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search by name,username,email'>",
        afterInit: function (ms) {
            var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
            .on('keydown', function (e) {
                if (e.which === 40) {
                    that.$selectableUl.focus();
                    return false;
                }
            });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
            .on('keydown', function (e) {
                if (e.which == 40) {
                    that.$selectionUl.focus();
                    return false;
                }
            });
        },
        afterSelect: function () {
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function () {
            this.qs1.cache();
            this.qs2.cache();
        }
    });

    $('#select_custom').change(function() {
        if($(this).is(':checked')) {
            $("#my_multi_select_custom").multiSelect('select_all');  
        }
        else
        {      
           $("#my_multi_select_custom").multiSelect('deselect_all');
        }
        
    });

    $('#my_multi_select_business').multiSelect({
        selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search by name,username,email'>",
        selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search by name,username,email'>",
        afterInit: function (ms) {
            var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
            .on('keydown', function (e) {
                if (e.which === 40) {
                    that.$selectableUl.focus();
                    return false;
                }
            });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
            .on('keydown', function (e) {
                if (e.which == 40) {
                    that.$selectionUl.focus();
                    return false;
                }
            });
        },
        afterSelect: function () {
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function () {
            this.qs1.cache();
            this.qs2.cache();
        }
    });

    $('#select_business').change(function() {
        if($(this).is(':checked')) {
            $("#my_multi_select_business").multiSelect('select_all');  
        }
        else
        {      
           $("#my_multi_select_business").multiSelect('deselect_all');
        }
        
    });

});
</script>
    </body>
</html>