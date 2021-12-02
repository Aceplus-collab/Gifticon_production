<div id="change-password-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog"> 
                <form id="change-password-form" method="POST"  data-parsley-validate>
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                            <h4 class="modal-title">Change Password</h4> 
                        </div> 
                        <div class="modal-body"> 
                            <div class="row"> 
                                <div class="col-md-12"> 
                                    <div class="form-group"> 
                                        <label for="old-password" class="control-label">Old password</label> 
                                        <input required type="password" id="old-password" name="oldpassword" class="form-control" placeholder="Enter old password" /> 
                                    </div> 
                                    <div class="form-group"> 
                                        <label for="new-password" class="control-label">New password</label> 
                                        <input required type="password" id="new-password" name="newpassword" class="form-control" placeholder="Enter you new password" /> 
                                    </div>
                                    <div class="form-group"> 
                                        <label for="confirm-password"  class="control-label">Confirm Password</label> 
                                        <input required type="password" data-parsley-equalto="#new-password" id="confirm-password" name="confirmpassword" class="form-control" placeholder="Re-enter password" /> 
                                    </div>
                                </div> 

                            </div>   
                        </div> 
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button> 
                            <!-- <button type="button" class="save-change btn btn-info waves-effect waves-light">Save</button>  -->
                            <input type="submit" class="save-change btn btn-info  waves-light" name="ChangePassword" value="SAVE">
                        </div> 
                    </div>
                </form> 
            </div>
        </div>     
        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo  base_url().'assets/js/jquery.min.js';?>"></script>
        <script src="<?php echo  base_url().'assets/js/bootstrap.min.js';?>"></script>
        <script src="<?php echo  base_url().'assets/js/detect.js';?>"></script>
        <script src="<?php echo  base_url().'assets/js/fastclick.js';?>"></script>
        <script src="<?php echo  base_url().'assets/js/jquery.slimscroll.js';?>"></script>
        <script src="<?php echo  base_url().'assets/js/jquery.blockUI.js';?>"></script>
        <script src="<?php echo  base_url().'assets/js/waves.js';?>"></script>
        <script src="<?php echo  base_url().'assets/js/wow.min.js';?>"></script>
        <script src="<?php echo  base_url().'assets/js/jquery.nicescroll.js';?>"></script>
        <script src="<?php echo  base_url().'assets/js/jquery.scrollTo.min.js';?>"></script>

        <script src="<?php echo  base_url().'assets/plugins/peity/jquery.peity.min.js';?>"></script>
        <script src="<?php echo base_url().'assets/js/parsley.min.js'; ?>"></script>
        <script src="<?php echo base_url().'assets/js/parsley.js'; ?>"></script>
        <!-- jQuery  -->
        <script src="<?php echo  base_url().'assets/plugins/waypoints/lib/jquery.waypoints.js';?>"></script>
        <script src="<?php echo  base_url().'assets/plugins/counterup/jquery.counterup.min.js';?>"></script>

        <script src="<?php echo  base_url().'assets/plugins/sweetalert/dist/sweetalert.min.js';?>"></script>
        <script src="<?php echo  base_url().'assets/pages/jquery.sweet-alert.init.js';?>"></script>

        <script src="<?php echo  base_url().'assets/plugins/morris/morris.min.js';?>"></script>
        <script src="<?php echo  base_url().'assets/plugins/raphael/raphael-min.js';?>"></script>

        <script src="<?php echo  base_url().'assets/plugins/jquery-knob/jquery.knob.js';?>"></script>

        <script src="<?php echo  base_url().'assets/pages/jquery.dashboard.js';?>"></script>

        <script src="<?php echo  base_url().'assets/js/jquery.core.js';?>"></script>
        <script src="<?php echo  base_url().'assets/js/jquery.app.js';?>"></script>
        <script src="<?php echo base_url().'assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js';?>"></script>
        <script src="<?php echo base_url().'assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js';?>"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-filestyle/src/bootstrap-filestyle.min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function() {
                $('#datepicker').datepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    autoclose:true,
                    endDate: "today",
                });
                $(".save-change").on("click",function(){
                    
                    if($("#change-password-form").parsley().validate()){
                        $.ajax({
                            url : "<?php base_url().'admin/ChangePassword'?>",
                            data  : $("#change-password-form").serialize(),
                            method : "POST",
                            success:function(response){
                                const _result = JSON.parse(response);
                                if(_result.code == "1"){
                                    $.Notification.notify('success',notification.position,"Change Password", _result.message);
                                    $("#change-password-modal").modal("toggle");
                                } else {
                                    $.Notification.notify('warning',notification.position,"Change Password", _result.message);
                                }
                            }
                        });
                    }
                });
            });
            //$('.selectpicker').selectpicker();
            $(":file").filestyle({input: false});
        </script>

            