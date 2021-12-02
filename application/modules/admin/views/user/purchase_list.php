
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
                                <h4 class="page-title">Purchase List</h4>
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
                                               

                                                <table id="category_table" data-toggle="table"
                                                    data-toolbar="#toolbar"
                                                    data-url="<?php echo base_url().'admin/user/purchase_ajax_list/'.$user_id;?>"
                                                    data-pagination="true"
                                                    data-side-pagination="server"
                                                    data-search="true"
                                                    data-sort-name="id"
                                                    data-sort-order="ASC"
                                                    data-page-list="[5, 10, 20]"
                                                    data-page-size="10"
                                                    data-show-refresh="true"
                                                    class="table-bordered" >
                                                    <thead>
                                                        <tr >
                                                            <th  data-field="id" data-sortable="true" data-order="desc" class="text-center">#</th>
                                                            <th  data-field="username" data-sortable="true" class="text-center"> User Name </th>
                                                            <th  data-field="gift_name" data-sortable="true" class="text-center"> Gift Name </th>
                                                            <th  data-field="business_name" data-sortable="true" class="text-center"> Business Name </th>
                                                            <th  data-field="scanner_id" data-sortable="true" class="text-center"> Scanner ID </th>
                                                            
                                                            <th  data-field="gift_image" data-sortable="true" class="text-center"> Gift Image </th>
                                                            
                                                            
                                                            <th  data-field="gifticon_type" data-sortable="true" class="text-center"> Gifticon Type </th>
                                                            <th  data-field="giftcard_format" data-sortable="true" class="text-center"> Giftcard format </th>
                                                            <th  data-field="price" data-sortable="true" class="text-center"> Price </th>
                                                            <th  data-field="normal_price" data-sortable="true" class="text-center"> Normal Price </th>
                                                            <th  data-field="coupon_discount_amount" data-sortable="true" class="text-center"> Coupon Amount </th>
                                                            <th  data-field="is_redeem" data-sortable="true" class="text-center"> Redeem Status </th>
                                                            <th  data-field="purchase_date" data-sortable="true" class="text-center"> Purchase date </th>
                                                            <th  data-field="redeem_date" data-sortable="true" class="text-center"> Redeem date </th>
                                                            <th  data-field="giftto_user_name" data-sortable="false"> giftto username </th>
                                                            <th  data-field="giftfrom_user_name" data-sortable="false"> giftfrom username </th>

                                                            <th  data-field="sent_sms_number" data-sortable="false"> sent sms number </th>

                                                            <th  data-field="edited_name" data-sortable="false"> edited name </th>

                                                            <th  data-field="image_name" data-sortable="false"> Gif </th>

                                                            <th  data-field="txt_color" data-sortable="false">Text color</th>

                                                            <th  data-field="bg_color" data-sortable="false">Background color</th>

                                                            <th  data-field="user_notes" data-sortable="false">user notes</th>
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
                                    location.reload();
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