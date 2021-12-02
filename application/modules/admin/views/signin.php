<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<head>
		<?php include(dirname(__FILE__)."/inc/header.php");?>
		<title><?php echo MY_SITE_NAME;?> | Login</title>
		<?php include(dirname(__FILE__)."/inc/style.php");?>

		<style type="text/css">
        #login-box{
            box-shadow:0px 0px 5px #220939;
            border-radius: 20px;
            background-color: #220939;
            transition-duration: .5s;
        }
        #login-box:hover{
            box-shadow:0px 0px 30px #220939;            
            color: #220939;
        }
    </style>
	</head>
	<body>
       <!--  <div class="account-pages"></div> -->
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <?php
        if($this->session->flashdata("msg"))
        {
            ?>  
            <div class="alert alert-danger">
                <?php echo $this->session->flashdata("msg") ?>
            </div>
            <?php
        }
        ?>  
        <?php
        if($this->session->flashdata("suc"))
        {
            ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata("suc") ?>
            </div>
            <?php
        }
        ?>
        	<div class=" card-box" id="login-box">
                <div class="panel-heading" style="margin-bottom: -20px"> 
                    <h3 class="text-center">
                        <img src="<?php echo base_url().LOGO; ?>"  height="100px"> <br>
                    </h3>
                    <h3 class="text-center">
                        <strong class="text-custom" style="color: white;"><?php echo MY_SITE_NAME;?> </strong><span style="color: white;" >Admin Panel </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal m-t-20" action="<?php echo base_url().'admin/login'?>" method="POST" data-parsley-validate>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group ">
                                    <input class="form-control" type="email" name="email"  placeholder="Email" data-parsley-required-message="Please Enter Email" data-parsley-type="email" autocomplete="off" required="" value="<?php if(isset($email) && $email!=''){echo $email;}?>">
                                    <small><?php echo form_error('email'); ?></small>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <input class="form-control" type="password" name="password"  placeholder="Password" data-parsley-required-message="Please Enter Password"  required="" value="<?php  if(isset($password) && $password!=''){echo $password;} ?>">
                                    <small><?php echo form_error('password'); ?></small>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group text-center m-t-40">
                                        <!-- <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button> -->
                                        <input type="submit" class="btn btn-white btn-block text-uppercase  waves-light" name="signin" value="SIGN IN">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>   
            </div>            
        </div>
        <?php include(dirname(__FILE__)."/inc/script.php");?>
	</body>
</html>