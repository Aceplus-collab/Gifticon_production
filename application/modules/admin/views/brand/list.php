
<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(dirname(__FILE__)."/../inc/header.php");?>
        <link href="<?php echo  base_url().'assets/plugins/bootstrap-table/dist/bootstrap-table.min.css'?>" rel="stylesheet" type="text/css" />
        <title>Gifticon</title>
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
                            <div class="col-sm-12">
                                <h4 class="page-title">Brand List</h4>
                                <p></p>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="col-lg-12">
	                        		<div class="card-box p-5 m-2">
                                        <!-- <div id="morris-area-with-dotted" style="height: 05px;"></div> -->
                                        
                                        <?php if($this->session->flashdata('msg')){
                                            echo '<br><div class="alert alert-danger">'.$this->session->flashdata('msg').'</div>'; 
                                        } ?>
                                        <?php if(isset($error_msg) && $error_msg != ''){
                                            echo '<br><div class="alert alert-danger">'.$error_msg.'</div>'; 
                                        } ?>
                                        <?php if($this->session->flashdata('suc')){
                                            echo '<br><div class="alert alert-success">'.$this->session->flashdata('suc').'</div>'; 
                                        } ?>
	                        			<!--===================================================-->
                                        <div class="p-20">
                                            <div class="table-responsive">
                                                <div id="toolbar">
                                                    <a href="<?php echo site_url('admin/brand/add');?>" class="btn btn-default waves-effect waves-light"><i class="fa fa-plus"></i> Add</a>
                                                </div>

                                                <table id="category_table" data-toggle="table"
                                                    data-toolbar="#toolbar"
                                                    data-url="<?php echo base_url().'admin/brand/ajax_list';?>"
                                                    data-pagination="true"
                                                    data-side-pagination="server"
                                                    data-search="true"
                                                    data-sort-name="id"
                                                    data-sort-order="ASC"
                                                    data-page-list="[5, 10, 20]"
                                                    data-page-number="<?php echo ($this->session->userdata('brand_curr_page') != NULL) ? $this->session->userdata('brand_curr_page') : 1; ?>"
                                                    data-page-size="10"
                                                    data-show-refresh="true"
                                                    class="table-bordered" >
                                                    <thead>
                                                        <tr >
                                                            <th  data-field="id" data-sortable="true" data-order="desc" class="text-center">#</th>
                                                            <th  data-field="merchant_id" data-sortable="true" class="text-center"> Merchant id </th>
                                                            <th  data-field="image" data-sortable="true" class="text-center"> Image </th>
                                                            <th  data-field="name" data-sortable="true" class="text-center"> Name </th>
                                                            <th  data-field="username" data-sortable="true" class="text-center"> User Name </th>
                                                            <th  data-field="email" data-sortable="true" class="text-center"> Email </th>
                                                            <th  data-field="phone" data-sortable="true" class="text-center"> Phone </th>
                                                            <th  data-field="description" data-sortable="true" class="text-center"> Description </th>
                                                            <th  data-field="website" data-sortable="true" class="text-center"> Website </th>
                                                            <th  data-field="commision_rate" data-sortable="true" class="text-center"> Commision Rate </th>
                                                            <th  data-field="sequence" data-sortable="true" class="text-center"> Sequence </th>
                                                            <th  data-field="purchased" data-sortable="false"> Purchased History </th>

                                                            <th  data-field="is_active" data-sortable="true" class="text-center"> Status </th>
                                                            <th  data-field="update_date" data-sortable="true" class="text-center"> Update Date </th>
                                                            
                                                            <th  data-field="action" data-align="center" data-sortable="true" class="text-center"> Action </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            
                                        </div>
	                        		</div>
                        	<!-- end col -->
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
        <script src="<?php echo  base_url().'assets/plugins/bootstrap-table/dist/bootstrap-table.min.js'?>"></script>
        <script src="<?php echo  base_url().'assets/pages/jquery.bs-table.js'?>"></script>

        <script src="<?php echo  base_url().'assets/plugins/datatables/jquery.dataTables.min.js';?>"></script>
        <script src="<?php echo  base_url().'assets/plugins/datatables/dataTables.bootstrap.js';?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#category_table").on('click','.active',function(){
                    const tbl_name="tbl_businesses";
                    $.ajax({
                        type:"POST",
                        url:"<?php echo base_url();?>admin/brand/active_inactive",
                        data:{"id":this.value,"tbl_name":tbl_name},
                        success:function(data){
                            $('#category_table').bootstrapTable('refresh');
                        }
                    });
                });

                $('#category_table').on('click', '.delete', function(){
                    const value=this.value;
                    const tbl_name="tbl_businesses";
                    swal({   
                        title: "Are you sure?",   
                        text: "You will not be able to recover this Brand!",   
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
                                url:"<?php echo base_url();?>admin/brand/Delete",
                                data:{"id":value,"tbl_name":tbl_name},
                                success:function(data){
                                    swal("Deleted!", "Your selected brand has been deleted.", "success");   
                                    //location.reload();
                                    document.location.href = '<?php echo base_url().'admin/brand/listing/1' ?>';
                                }
                            });
                        } else {     
                            swal("Cancelled", "Brand is safe :)", "error");   
                        } 
                    });
                });
            });
        </script>
        
    </body>
</html>