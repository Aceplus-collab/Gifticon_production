<!DOCTYPE html>
<html>

<head>
	<title>Change password</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/custom.css">
	<style>
		.error {
			color: red;
		}
	</style>
</head>

<body>

	<div class="container">
		<div class="row">
			<?php
			if($this->session->flashdata("error_msg"))
			{
				?>
				<div class="col-md-6 col-md-offset-3" style="margin-top:70px;" >
					<div class="alert alert-danger">
						<?php echo $this->session->flashdata("error_msg"); ?>
					</div>
				</div>
				<?php
			}
			else if($this->session->flashdata("success_msg"))
			{
				?>
				<div class="col-md-6 col-md-offset-3" style="margin-top:70px;" >
					<div class="alert alert-success">
						<?php echo $this->session->flashdata("success_msg"); ?>
					</div>
				</div>
				<?php	
			}
			?>
		</div>
		<?php 
		if ($result['forgot_pass_time'] >= date('Y-m-d H:i:s',strtotime('-1 hours',strtotime($result['forgot_pass_time'])))) {

			if($result['is_expier'] == "0")
			{
			?>
	
		<form method="POST" id="new-password" action="<?php echo base_url() ?>verification/change_password">				
			<div class="row" style="margin-top:70px;">
				<center style="margin-bottom:30px; " >
					<h2>
						Set your new password
					</h2>
				</center>
				<div class="col-md-6 col-md-offset-3">
					<input type="password" minlength="4" class="form-control" name="newpassword" id="newpassword" placeholder="New Password"
					/>
				</div>
				<div class="col-md-6 col-md-offset-3" style="margin-top:10px;">
					<input type="password" minlength="4" class="form-control" name="confirmpassword" placeholder="Confirm Password" />
				</div>
			</div>
			<div class="row	" style="margin-top:10px;">
				<div class="col-md-3 col-md-offset-3 text-left ">
					<input type="hidden" name="user_id" value="<?php echo $result['id'] ?>">
					
					<button class="btn btn-primary" type="submit">Change</button>
				</div>
			</div>				
		</form>
		<?php
			}
			else{
				
				?>
				<center style="color:red" >
				<div class="row" style="margin-top:70px;">
					<div class="col-md-8 col-md-offset-2" >
						<div class="alert alert-danger " style="font-size: 1.2em;">
							Password reset link has been expired.
						</div>
					</div>
				</div>
			</center>

				<?php
			}
	}
	else
	{
		if(!$this->session->flashdata("success_msg"))
		{
			?>
			<center style="color:red" >
				<div class="row" style="margin-top:70px;">
					<div class="col-md-8 col-md-offset-2" >
						<div class="alert alert-danger " style="font-size: 1.2em;">
							Password reset link has been expired.
						</div>
					</div>
				</div>
			</center>
			<?php
		}
	}
	?>
</div>
<script src="<?php echo base_url(); ?>assets/js/jquery-2.1.3.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery_1.validate.min.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-121715742-1"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'UA-121715742-1');
</script>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
	(adsbygoogle = window.adsbygoogle || []).push({
		google_ad_client: "ca-pub-1017769556716386",
		enable_page_level_ads: true
	});
</script>
<script type="text/javascript">
	$("#new-password").validate({
		rules: {
			newpassword: {
				required: true,
				minlength: 4
			},
			confirmpassword: {
				required: true,
				equalTo: "#newpassword",
			}
		},
		messages: {
			newpassword: {
				required: "Please enter password",
				minlength: "Your password must be at least 4 characters long",
			},
			confirmpassword: {
				required: "Please enter confirm password",
				equalTo: "Confirm password does not match",
			},
		},
	});
</script>
</body>
</html>