            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <!--- Divider -->
                    <div id="sidebar-menu">
                        <ul>

                        	<li class="text-muted menu-title">Navigation</li>

                            <li class="">
                                <a href="<?php echo base_url(); ?>admin/dashboard" class="waves-effect <?php echo ($page=='dashboard')? 'active':'';?>"><i class="ti-home"></i> <span> Dashboard </span> </a>
                            </li>
                            <li class="">
                                <a href="<?php echo base_url(); ?>admin/user/listing" class="waves-effect <?php echo ($page=='user')? 'active':'';?>"><i class="ti-user"></i> <span> User </span> </a>
                            </li>

                            <li class="">
                                <a href="<?php echo base_url(); ?>admin/brand/listing" class="waves-effect <?php echo ($page=='brand')? 'active':'';?>"><i class="glyphicon glyphicon-tasks"></i> <span> Brands / Business </span> </a>
                            </li>

                            <li class="">
                                <a href="<?php echo base_url(); ?>admin/gift/listing" class="waves-effect <?php echo ($page=='gift')? 'active':'';?>"><i class="glyphicon glyphicon-gift"></i> <span> Gifticons </span> </a>
                            </li>

                            <li class="has_sub">
                                <a href="#" class="waves-effect <?php if(isset($page_sname)) { echo ($page_sname=='cms')? 'active':''; } ?>"><i class="glyphicon glyphicon-cog"></i> <span> CMS Pages </span> </a>
                                <ul class="list-unstyled">
                                    <li <?php echo ($page=='about')? 'active':'';?> ><a href="<?php echo base_url(); ?>admin/page/about_us">About Us</a></li>
                                    <li <?php echo ($page=='terms')? 'active':'';?> ><a href="<?php echo base_url(); ?>admin/page/terms">Terms & Condition</a></li>
                                    <li <?php echo ($page=='faq')? 'active':'';?> ><a href="<?php echo base_url(); ?>admin/page/faq">FAQ</a></li>
                                    <li <?php echo ($page=='privacy')? 'active':'';?> ><a href="<?php echo base_url(); ?>admin/page/privacy">Privacy Policy</a></li>
                                </ul>
                            </li>

                            <li class="">
                                <a href="<?php echo base_url(); ?>admin/page/contact" class="waves-effect <?php echo ($page=='contact')? 'active':'';?>"><i class="glyphicon glyphicon-send"></i> <span> Contact Us </span> </a>
                            </li>

                            <li class="">
                                <a href="<?php echo base_url(); ?>admin/promocode/listing" class="waves-effect <?php echo ($page=='promocode')? 'active':'';?>"><i class="ion-arrow-shrink"></i> <span> Discount Code </span> </a>
                            </li>

                            <li class="">
                                <a href="<?php echo base_url(); ?>admin/home/notifications" class="waves-effect <?php echo ($page=='notification')? 'active':'';?>"><i class="fa fa-bell"></i> <span> Notification </span> </a>
                            </li>

                            <li class="">
                                <a href="<?php echo base_url(); ?>admin/reward/listing" class="waves-effect <?php echo ($page=='reward')? 'active':'';?>"><i class="glyphicon glyphicon-star"></i> <span> Reward </span> </a>
                            </li>

                            <li class="">
                                <a href="<?php echo base_url(); ?>admin/analitics/listing" class="waves-effect <?php echo ($page=='analitics')? 'active':'';?>"><i class="md md-spellcheck"></i> <span> Analitics </span> </a>
                            </li>
                           
                            <li class="">
                                <a href="<?php echo base_url(); ?>admin/Logout" class="waves-effect <?php echo ($page=='logout')? 'active':'';?>"><i class="ti-power-off"></i> <span> Logout </span><span class="label label-default pull-right"></span> </a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>