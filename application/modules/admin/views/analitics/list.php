
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
                                <h4 class="page-title">App Analitics</h4>
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
                                                   <form name="statusform" action="<?php echo base_url(); ?>admin/analitics/listing" method="POST">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="input-group">
                                                                    <input type="text" name="searchdate" class="form-control datepicker" id="datepicker" value="<?php echo $searchdate; ?>" >
                                                                    <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-2">
                                                                <button class="btn btn-primary btn-sm" ><i class="glyphicon glyphicon-search"></i> Search</button>
                                                                <a href="<?php echo base_url(); ?>admin/analitics/listing" class="btn btn-success btn-sm" > Today</a>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </form>

                                                </div>

                                            <table class="table m-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th>Count</th>
                                                </tr>   
                                            </thead>
                                            <tbody>
                                                <?php                                               
                                                if(!empty($analitics_data))
                                                {   
                                                   
                                                    foreach ($analitics_data['title'] as $key => $row) 
                                                    {  
                                                        ?>
                                                        <tr>
                                                            <th scope="row">
                                                                <?php echo $key+1; ?>
                                                            </th>
                                                            <td><?php echo $row; ?></td>
                                                            <td><?php echo $analitics_data['valuedata'][$key]; ?></td>
                                                        </tr>                   
                                                        <?php
                                                    }

                                                    
                                                }
                                                else
                                                {
                                                    ?>
                                                    <tr>
                                                        <td scope="row" style="text-align: center;font-size: 1.7em;" colspan="10" >
                                                            No Order Found
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
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