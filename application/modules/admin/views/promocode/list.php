
<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(dirname(__FILE__)."/../inc/header.php");  ?>
        <link href="<?php echo  base_url().'assets/plugins/bootstrap-table/dist/bootstrap-table.min.css'?>" rel="stylesheet" type="text/css" />
        <title><?php echo MY_SITE_NAME;?> | Listing</title>
        <?php include(dirname(__FILE__)."/../inc/style.php"); ?>
    </head>
    <body class="fixed-left">
        <!-- Begin page -->
        <div id="wrapper">
            <!-- Top Bar Start -->
            <?php include(dirname(__FILE__)."/../inc/top_bar.php"); ?>
            <!-- Top Bar End -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include(dirname(__FILE__)."/../inc/left_side_bar.php"); ?>
            <!-- Left Sidebar End -->

            <!-- Right Sidebar -->
            <?php include(dirname(__FILE__)."/../inc/right_side_bar.php"); ?>
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
                                <h4 class="page-title">Discount Code</h4>
                                <p></p>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-lg-12">
	                        		<div class="card-box p-5 m-2">
                                        <div id="morris-area-with-dotted" style="height: 05px;"></div>
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
	                        			<!--===================================================-->
                                        <div class="p-20">
                                            <div class="table-responsive">
                                                <div id="toolbar">
                                                    <a href="<?php echo site_url('admin/promocode/add');?>" class="btn btn-default waves-effect waves-light"><i class="fa fa-plus"></i> Add</a>

                                                    <!-- <a href="<?php echo site_url('admin/user/exportexcel');?>" class="btn btn-default waves-effect waves-light"><i class="fa fa-file-excel-o"></i> Export </a> -->
                                                </div>

                                                <table id="admin_table" data-toggle="table"
                                                    data-toolbar="#toolbar"
                                                    data-url="<?php echo base_url().'admin/promocode/ajax_list';?>"
                                                    data-pagination="true"
                                                    data-side-pagination="server"
                                                    data-search="true"
                                                    data-sort-name="id"
                                                    data-sort-order="desc"
                                                    data-page-list="[5, 10, 20]"
                                                    data-page-size="10"
                                                    data-show-refresh="true"
                                                    class="table-bordered" >
                                                    <thead>
                                                        <tr >
                                                            <th  data-field="id" data-sortable="true" data-order="desc">#</th>
                                                            <th  data-field="code" data-sortable="true"> Code </th>
                                                            <th  data-field="type" data-sortable="true"> Type </th>
                                                            <th  data-field="discount" data-sortable="true"> Discount </th>
                                                            <th  data-field="sdate" data-sortable="true"> Sdate </th>
                                                            <th  data-field="edate" data-sortable="true"> Edate </th>
                                                            <th  data-field="total_used" data-sortable="true"> Total Used </th>
                                                            <th  data-field="is_active" data-sortable="true"> Status </th>
                                                            <th  data-field="action" data-align="center" data-sortable="true" class="text-center"> Action </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <!-- <button class='active label label-table label-success '  id='active' value="1">Active</button>  -->
                                        </div>
	                        		</div>
                        	<!-- end col -->
                         	</div>
                            <!-- col -->
                        </div>
                        <!-- end row -->
                    </div> <!-- container -->
                </div> <!-- content -->
               <?php include(dirname(__FILE__)."/../inc/footer.php")?>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->
        <?php include(dirname(__FILE__)."/../inc/script.php");?>
        <script src="<?php echo  base_url().'assets/plugins/bootstrap-table/dist/bootstrap-table.min.js'?>"></script>
        <script src="<?php echo  base_url().'assets/pages/jquery.bs-table.js'?>"></script>

        <script src="<?php echo  base_url().'assets/plugins/datatables/jquery.dataTables.min.js';?>"></script>
        <script src="<?php echo  base_url().'assets/plugins/datatables/dataTables.bootstrap.js';?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#admin_table").on('click','.active',function(){
                    const tbl_name="tbl_coupon_codes";
                    $.ajax({
                        type:"POST",
                        url:"<?php echo base_url();?>admin/promocode/active_inactive",
                        data:{"id":this.value,"tbl_name":tbl_name},
                        success:function(data){
                            $('#admin_table').bootstrapTable('refresh');
                        }
                    });
                });

                $('#admin_table').on('click', '.delete', function(){
                    const value=this.value;
                    const tbl_name="tbl_coupon_codes";
                    swal({   
                        title: "Are you sure?",   
                        text: "You will not be able to recover this User Details!",   
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonText: "Yes, delete it!",   
                        cancelButtonText: "No, cancel plx!",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm) {     
                            //alert(value);
                            $.ajax({
                                type:"POST",
                                url:"<?php echo base_url();?>admin/promocode/userDelete",
                                data:{"id":value,"tbl_name":tbl_name},
                                success:function(data){
                                    swal("Deleted!", "Your imaginary file has been deleted.", "success");   
                                    //$('#admin_table').bootstrapTable('refresh');
                                    location.reload();
                                }
                            });
                        } else {     
                            swal("Cancelled", "Your imaginary file is safe :)", "error");   
                        } 
                    });
                });
            });
        </script>
        
    </body>
</html>