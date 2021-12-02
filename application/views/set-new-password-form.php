<!DOCTYPE html>
<html>
<head>
	<title>Phanziety | Change Password</title>	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/custom.css">
	<style>
		.parsley-required,.parsley-equalto,.parsley-minlength
		{
			color: red;
			list-style: none;
			padding-left: 0px;
		}
	</style>
	<?php
	if($user_detail)
	{
		?>
		<link rel="shortcut icon" href="<?php echo PROFILE_IMAGE.$user_detail['profile_image']; ?>">
		<?php
	}
	else
	{	?>
		<link rel="shortcut icon" href="<?php echo base_url().LOGO;?>">
		<?php
	}
	?>
</head>
<body>

	<div class="container" >  
		<?php
		if($user_detail)
		{
			?>
			<div class="row m-t-50  text-center" >			
				<div class="col-md-12" >
					<img src="<?php echo PROFILE_IMAGE.$user_detail['profile_image']; ?>" style="height: 200px; width:200px" >
				</div>
				<div class="col-md-12 text-white " >
					<h3><?php echo $user_detail['name']; ?></h3>
				</div>
			</div>
			<form role="form" method="POST" id="new-password" action="<?php echo base_url() ?>forgotpassword/saveNewPassword" data-parsley-validate>
				<div class="row" >
					<div class="col-md-6 col-md-offset-3" >
						<?php
						if($this->session->flashdata("error_message"))
						{
							?>
							<div class="alert alert-danger">					
								<?php echo $this->session->flashdata("error_message"); ?>
							</div>
							<?php
						}
						else if($this->session->flashdata("success_message"))
						{
							?>
							<div class="alert alert-success">					
								<?php echo $this->session->flashdata("success_message"); ?>
							</div>
							<?php	
						}
						?>
					</div>
				</div>
				<?php
				if(!isset($user_detail['is_success']))
				{
					?>
					<div class="row m-t-20" >
						<div class="col-md-6 col-md-offset-3 text-left " >
							<input type="hidden" name="forgot_pass" value="<?php echo $user_detail['forgot_pass'] ?>" >
							<input type="hidden" name="user_id" value="<?php echo $user_detail['id'] ?>">
						</div>
						<div class="row" style="margin-top:10px;">
								<center style="margin-bottom:20px; " >
									<h2>
										Set your new password
									</h2>
								</center>
								<div class="col-md-6 col-md-offset-3">
									<input type="password" minlength="4" class="form-control" name="newpassword" id="newpassword" placeholder="New Password" data-parsley-required-message="Please provide New password"  required=""/>
								</div>
								<div class="col-md-6 col-md-offset-3" style="margin-top:10px;">
									<input type="password" minlength="4" class="form-control" name="confirmpassword" placeholder="Confirm Password" data-parsley-equalto="#newpassword" minlength="4" data-parsley-required-message="Please provide confirm password" data-parsley-equalto-message="newpassword and confirm password must be same" required/>
								</div>
							</div>
							<div class="row	" style="margin-top:10px;">
								<div class="col-md-3 col-md-offset-3 text-left "> 
									
									<input class="btn btn-primary" type="submit" value="Change"></input>
								</div>
							</div>					
					</div>
					<?php
				}
				?>
			</form>
			<?php
		}
		else
		{
			?>
			<center class="text-white" >
				<h3 style="color:green;">Your password is changed</h3>
			</center>
			<?php
		}
		?>
	</div>	
	<script src="<?php  echo base_url() ?>assets/js/jquery.min.js"></script> 
	<script type="text/javascript" src="<?php  echo base_url() ?>assets/js/jquery.validate.min.js" ></script>
	<script src="<?php echo base_url().'assets/js/parsley.min.js'; ?>"></script>
    <script src="<?php echo base_url().'assets/js/parsley.js'; ?>"></script>
	<script type="text/javascript">
		$(document).ready(function() {
		 	$('form').parsley();
		 });
		/*$("#new-password").validate({
			rules:{
				newpassword:{
					required:true,
					minlength:"4",
				},
				confirmpassword:{
					required:true,
					equalTo: "#newpassword",
				}				
			},
			messages:{
				newpassword:{
					required:<?php echo "Please Enter Newpassword"; ?>,
					minlength:<?php echo "length of Newpassword must be 4 or more"; ?>,
				},
				confirmpassword:{
					required:<?php echo "please Enter Confirm password"; ?>,
					equalTo: <?php echo "Newpassword and ConfirmPassword must be same"; ?>,
				}
			},
		});*/

	</script>
</body>
</html>