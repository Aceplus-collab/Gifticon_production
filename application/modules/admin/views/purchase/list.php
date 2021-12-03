
<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(dirname(__FILE__)."/../inc/header.php");?>
        <link href="<?php echo  base_url().'assets/plugins/bootstrap-table/dist/bootstrap-table.min.css'?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo  base_url().'assets/css/purchase-list.css'?>" rel="stylesheet" type="text/css" />
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
                                        <div class="alert_div">

                                        </div>
                                        <div class="p-20">
                                            <div class="table-responsive">
                                                <button class="exchange_rate">Exchange Rate Test</button>
                                                <div id="toolbar">
                                                    <a href="#" class="excel_export_btn btn btn-default waves-effect waves-light">
                                                    <i class="fa fa-file"></i>    
                                                    Export Purchase List
                                                    </a>
                                                </div>

                                                <table id="category_table" data-toggle="table"
                                                    data-filter-from-date="true"
                                                    data-filter-to-date="true"
                                                    data-filter-country="true"
                                                    data-toolbar="#toolbar"
                                                    data-url="<?php echo base_url().'admin/purchase/purchase_ajax_list';?>"
                                                    data-country-list-url="<?php echo base_url().'admin/purchase/country_ajax_list';?>"
                                                    data-pagination="true"
                                                    data-side-pagination="server"
                                                    data-search="true"
                                                    data-sort-name="id"
                                                    data-sort-order="ASC"
                                                    data-page-list="[5, 10, 20]"
                                                    data-page-size="10"
                                                    data-show-refresh="true"
                                                    class="table-bordered purchase_table" >
                                                    <thead>
                                                        <tr >
                                                            <th  data-field="id" data-sortable="true" data-order="desc" class="text-center">#</th>
                                                            <!-- <th  data-field="purchase_id" data-sortable="false" class="text-center"> Purchase ID </th> -->
                                                            <th  data-field="country" data-sortable="true" class="text-center"> Country </th>
                                                            <th  data-field="purchase_date" data-sortable="true" class="text-center"> Purchase date </th>
                                                            <th  data-field="username" data-sortable="true" class="text-center"> User Name </th>
                                                            <th  data-field="gift_name" data-sortable="true" class="text-center"> Gift Name </th>
                                                            <th  data-field="business_name" data-sortable="true" class="text-center"> Business Name </th>

                                                            <th  data-field="scanner_id" data-sortable="true" class="text-center"> Scanner ID </th>
                                                            
                                                            <th  data-field="gift_image" data-sortable="true" class="text-center"> Gift Image </th>
                                                            
                                                            
                                                            <th  data-field="gifticon_type" data-sortable="true" class="text-center"> Gifticon Type </th>
                                                            <th  data-field="giftcard_format" data-sortable="true" class="text-center"> Giftcard format </th>
                                                            <th  data-field="currency" data-sortable="false" class="text-center"> Currency </th>
                                                            <th  data-field="price" data-sortable="true" class="text-center"> Price </th>
                                                            <th  data-field="normal_price" data-sortable="true" class="text-center"> Normal Price </th>
                                                            <th  data-field="coupon_discount_amount" data-sortable="true" class="text-center"> Coupon Amount </th>
                                                            <th  data-field="is_redeem" data-sortable="true" class="text-center"> Redeem Status </th>
                                                            <th  data-field="redeem_date" data-sortable="true" class="text-center"> Redeem date </th>
                                                            <th  data-field="giftto_user_name" data-sortable="false"> giftto username </th>
                                                            <th  data-field="giftfrom_user_name" data-sortable="false"> giftfrom username </th>
                                                            <th  data-field="voucher_status" data-sortable="false" class="text-center voucher-status"> Voucher Status </th>
                                                            <th  data-field="wincube_id" data-sortable="false" class="text-center action-btn"> Action </th>
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
        <script src="<?php echo  base_url().'assets/plugins/bootstrap-table/dist/bootstrap-table.js'?>"></script>
        <script src="<?php echo  base_url().'assets/pages/jquery.bs-table.js'?>"></script>

        <script src="<?php echo  base_url().'assets/plugins/datatables/jquery.dataTables.min.js';?>"></script>
        <script src="<?php echo  base_url().'assets/plugins/datatables/dataTables.bootstrap.js';?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.excel_export_btn').on('click', function(event){
                    event.preventDefault();
                    let toDate = $('.to-date').find('input').val();
                    let fromDate = $('.from-date').find('input').val();
                    let country = $('.country').find('select').val();
                    let search = $('.search').find('input').val();
                    let url = "<?php echo base_url();?>admin/purchase/ajax_excel_export";
                    let parama = `?search=${search}&fromDate=${fromDate}&toDate=${toDate}&country=${country}&sort=id&order=ASC&limit=-1&offset=0&export_excel=true`
                    $.ajax({
                        type:"GET",
                        url:url + parama,
                        success:function(data){
                            window.open(url + parama,'_blank');
                        }
                    });
                });
                $(document).on("click",".cancel-voucher-excel-btn", function () {
                    $('.alert_div').empty()
                    if (confirm("Are you sure to Voucher cancel!")) {
                        let _that = $(this);
                        let wincube_id = $(this).attr('data-wincube-id');
                        let purchase_id = $(this).attr('data-purchase-id');
                        $.ajax({
                            type:"POST",
                            url:"<?php echo base_url();?>admin/purchase/ajax_voucher_cancel",
                            data: {"wincube_id": wincube_id, "purchase_id":purchase_id},
                            success:function(data){
                                console.log(data, 'data')
                                let res = JSON.parse(data);
                                console.log(res, 'res')
                                if(res['success'])
                                {
                                    $('.alert_div').append('<br><div class="alert alert-success">'+res.success+'</div>')
                                    _that.parent('.action-btn').prev('.voucher-status').find('span').text("Cancelled");
                                    _that.parent('.action-btn').append('<span> - </span>');
                                    _that.remove();
                                }else{
                                    $('.alert_div').append('<br><div class="alert alert-warning">'+res.error+'</div>')
                                }
                            }
                        });
                    }
                });

                $(".exchange_rate").on('click', function(){
                    $.ajax({
                        type:"GET",
                        url:"<?php echo base_url();?>admin/exchangeRate/exchangeRateSave",
                        success:function(data){
                            console.log(data, )
                        }
                    });
                })
                $(document).on("click",".recancel-voucher-excel-btn", function () {
                    $('.alert_div').empty()
                    let _that = $(this);
                    let wincube_id = $(this).attr('data-wincube-id');
                    let purchase_id = $(this).attr('data-purchase-id');
                    $.ajax({
                        type:"POST",
                        url:"<?php echo base_url();?>admin/purchase/ajax_voucher_recancel",
                        data: {"wincube_id": wincube_id, "purchase_id":purchase_id},
                        success:function(data){
                            let res = JSON.parse(data);
                            if(res['success'])
                            {
                                $('.alert_div').append('<br><div class="alert alert-success">'+res.success+'</div>')
                            }
                            _that.text('Cancel Voucher');
                            _that.parent('.action-btn').prev('.voucher-status').find('span').text("--");
                            _that.removeClass('recancel-voucher-excel-btn');
                            _that.addClass('cancel-voucher-excel-btn');
                        }
                    });
                });
            });
        </script>
        
    </body>
</html>
