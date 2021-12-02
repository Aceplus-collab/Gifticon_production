
            <div class="topbar">
                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="<?php echo base_url(); ?>admin/dashboard" class="logo">
                            <i class="ti-car icon-c-logo"><img src="<?php echo base_url().LOGO;?>" style="height: 30px;" class="" alt="user-img"></i> 
                            <span>
                                Gifticon
                                <!-- <img src="<?php echo base_url().NAME_LOGO; ?>" style="height: 50px;" class="" alt="user-img" > -->
                            </span>
                        </a>
                    </div>
                </div>
               
                <!-- Button mobile view to collapse sidebar menu -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="container">
                        <div class="">
                            <div class="pull-left">
                                <button class="button-menu-mobile open-left">
                                    <i class="ion-navicon"></i>
                                </button>
                                <span class="clearfix"></span>
                            </div>

                            <ul class="nav navbar-nav navbar-right pull-right">
                                <li class="dropdown">
                                    <a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true"><img src="<?php echo base_url(); ?>assets/images/logo.png" alt="user-img" class="img-circle"> </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url();?>admin/ChangePassword" data-toggle="modal"><i class="fa fa-key m-r-5"></i> Change Password</a></li>
                                        <li><a href="<?php echo base_url(); ?>admin/Logout"><i class="ti-power-off m-r-5"></i> Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!--/.nav-collapse -->
                    </div>
                </div>
            </div>