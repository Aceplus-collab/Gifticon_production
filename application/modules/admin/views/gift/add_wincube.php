
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
                                <h4 class="page-title">Import from WinCube</h4>
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
                                                <!-- <div id="toolbar">
                                                    <a href="<?php echo site_url('admin/gift/add');?>" class="btn btn-default waves-effect waves-light"><i class="fa fa-plus"></i> Add</a>
                                                    <a href="<?= site_url('admin/gift/add_wincube');?>" class="btn btn-primary waves-effect waves-light m-l-5"><i class="fa fa-plus"></i> Import from WinCube</a>
                                                </div> -->

                                                <table id="goods_table" data-toggle="table"
                                                    data-toolbar="#toolbar"
                                                    data-url="<?php echo base_url().'admin/gift/ajax_wincube_goods';?>"
                                                    data-pagination="true"
                                                    data-side-pagination="client"
                                                    data-search="true"
                                                    data-sort-name="goods_id"
                                                    data-sort-order="ASC"
                                                    data-page-list="[5, 10, 20]"
                                                    data-page-size="10"
                                                    data-page-number="<?php echo ($this->session->userdata('gift_curr_page') != NULL) ? $this->session->userdata('gift_curr_page') : 1; ?>"
                                                    data-show-refresh="true"
                                                    data-click-to-select="true"
                                                    class="table-bordered" >
                                                    <thead>
                                                        <tr >
                                                            <th data-field="selected" data-checkbox="true"></th>
                                                            <th data-field="goods_id" data-sortable="true" data-order="desc" class="text-center">#</th>
                                                            <th data-field="category1" data-sortable="true" class="text-center">Cat. 1</th>
                                                            <th data-field="category2" data-sortable="true" class="text-center">Cat. 2</th>
                                                            <th data-field="affiliate" data-sortable="true" class="text-center">Affiliate</th>
                                                            <th data-field="affiliate_category" data-sortable="true" class="text-center">Affiliate Cat.</th>
                                                            <th data-field="goods_img_html" data-sortable="false" class="text-center">Image</th>
                                                            <th data-field="goods_nm" data-sortable="true" class="text-center">Name</th>
                                                            <th data-field="normal_sale_price" data-sortable="true" class="text-center">Normal Price</th>
                                                            <th data-field="sale_price" data-sortable="true" class="text-center">Coupon Price</th>
                                                            <th data-field="period_end" data-sortable="true" class="text-center">End Date</th>
                                                            <th data-field="limit_date" data-sortable="true" class="text-center">Limit Days</th>
                                                            <!-- <th data-field="desc" data-sortable="true" class="text-center">Description</th> -->
                                                            <th data-field="delivery_url" data-sortable="true" class="text-center">Delivery URL</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <div class="m-t-20">
                                                    <button id="confirm_add" disabled class="btn btn-purple waves-light">Add Selected Items</button>
                                                </div>
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
                const $table = $('#goods_table')
                const $button = $('#confirm_add')

                $table.click(function () {
                    const selections = $table.bootstrapTable('getSelections')
                    if (selections.length) {
                        $button.prop('disabled', false)
                    } else {
                        $button.prop('disabled', true)
                    }
                })

                $button.click(async function () {
                    // swal('Confirm', 'getSelections: ' + JSON.stringify($table.bootstrapTable('getSelections'), null, 2))
                    let selections = $table.bootstrapTable('getSelections')
                    for (let item of selections) {
                        delete item.goods_img_html
                        delete item.selected
                        if (item.goods_img && !item.goods_img.startsWith('https')) {
                            item.goods_img = item.goods_img.replace('http', 'https')
                        }
                    }
                    if (selections.length) {
                        const response = await fetch('<?= base_url() ?>admin/gift/ajax_wincube_import', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(selections),
                            credentials: 'include',
                        })
                        const res = await response.json()
                        console.info(res)
                        if (response.ok) {
                            swal('Successful', `Imported ${res.affected_rows} items.`)
                        } else {
                            swal('Error', 'Something went wrong during the import. Try again?')
                        }
                    } else {
                        swal('Hmm', 'Looks like there is nothing new to import.')
                    }
                })
            });
        </script>
        
    </body>
</html>