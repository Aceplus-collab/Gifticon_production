<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(dirname(__FILE__)."/../inc/header.php");?>
        <title><?php echo MY_SITE_NAME; ?> | FAQ</title>
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
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                          <?php if($this->session->flashdata('error_msg')){
                              echo '<div class="alert alert-danger">'.$this->session->flashdata('error_msg').'</div>'; 
                          } ?>
                          <?php if(isset($error_msg) && $error_msg != ''){
                              echo '<div class="alert alert-danger">'.$error_msg.'</div>'; 
                          } ?>
                          <?php if($this->session->flashdata('succ_msg')){
                              echo '<div class="alert alert-success">'.$this->session->flashdata('succ_msg').'</div>'; 
                          } ?>
                          <h4 class="page-title" style="padding-left:10px;">FAQ</h4>
                          <br>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title text-center m-b-20"><b><?php echo $data['title'];?></b></h4>
                            
                            <?php echo form_open('admin/page/faq',array('id'=>'edit_faq', 'name'=>'edit_faq', 'class' => 'form-horizontal', 'method' => 'post', 'enctype'=>'multipart/form-data')); ?>

                                <input type="hidden" name="id" value="<?php echo $data['id'];?>">

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <textarea id="contents" name="contents" class="form-control" placeholder="Enter about us contents"><?php if(isset($data['contents'])) echo $data['contents']; ?></textarea>
                                        <?php echo form_error('contents'); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-sm-12 text-center">
                                        <button name="edit_faq" type="submit" value="update" class="btn btn-app waves-effect waves-light">Update</button>
                                        <a onclick="history.go(-1)" class="btn btn-default waves-effect waves-light m-l-5">Back</a>
                                    </div>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div> 
           
        </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->
        <?php include(dirname(__FILE__)."/../inc/script.php");?>

       <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    if($("#contents").length > 0){
        tinymce.init({
            selector: "textarea#contents",
            theme: "modern",
            height:300,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking",
                "save table contextmenu directionality emoticons paste textcolor"
            ],
            toolbar: 'undo redo | styleselect | bold italic | fontselect | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview fullpage | forecolor backcolor emoticons',
            style_formats: [
                {title: 'Header 1', format: 'h1'},
                {title: 'Header 2', format: 'h2'},
                {title: 'Header 3', format: 'h3'},
                {title: 'Header 4', format: 'h4'},
                {title: 'Header 5', format: 'h5'},
                {title: 'Header 6', format: 'h6'}
            ],
            image_title: true, 
            // enable automatic uploads of images represented by blob or data URIs
            automatic_uploads: true,
            // add custom filepicker only to Image dialog
            file_picker_types: 'image',
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('name', 'editor_image');
                input.setAttribute('accept', 'image/*');

                input.onchange = function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                  
                    reader.onload = function () {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        // call the callback and populate the Title field with the file name
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };
                input.click();
            },
        });    
    }  
});
</script>
    </body>
</html>