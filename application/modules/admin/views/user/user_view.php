<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include(dirname(__FILE__)."/../inc/header.php");?>
        <title><?php echo MY_SITE_NAME;?> | User Profile</title>
        <?php include(dirname(__FILE__)."/../inc/style.php");?>
        <style type="text/css">
            .contact-card .member-info h4, .contact-card .member-info p{
                display: block;
                overflow: hidden;
                text-overflow: inherit;
                white-space: inherit;
                width: 100%;
            }
            .bg-picture {
                padding: 23px 0;
            }

            .shadow{
                box-shadow: 0px 0px 2px gray;
            }
            .shadow:hover{
                box-shadow: 0px 0px 10px gray;
                transition-duration: 1s;
            }
        </style>
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin_assets/plugins/magnific-popup/dist/magnific-popup.css"/>
        <link href="<?php echo base_url() ?>assets/admin_assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
        <link href="<?php echo base_url() ?>assets/admin_assets/plugins/owl.carousel/dist/assets/owl.carousel.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url() ?>assets/admin_assets/plugins/owl.carousel/dist/assets/owl.theme.default.min.css" rel="stylesheet" type="text/css" />
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
                    <div class="wraper container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="bg-picture text-center">
                                    <div class="bg-picture-overlay"></div>
                                    <div class="profile-info-name">
                                        <img src="<?php echo PROFILE_IMAGE.$user['profile_image']; ?>" class="thumb-lg img-circle img-thumbnail" alt="profile-image">
                                        <h4 class="m-b-5"><b><?php echo $user['name']; ?></b></h4>
                                    </div>
                                </div>               
                            </div>
                        </div>
                       

                         <div class="row">
                            <div class="col-sm-12">
                                    <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card-box">
                                                    <h4 class="m-t-0 header-title"><b>User Personal Information</b></h4>
                                    <div class="p-20">
                                        <div class="about-info-p">
                                            <strong>Name</strong>                        
                                            <p class="text-muted"><?php echo $user['name']; ?></p>
                                        </div>
                                    
                                        <div class="about-info-p">
                                            <strong>UserName</strong>                        
                                            <p class="text-muted"><?php echo $user['username']; ?></p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Email</strong>
                                            <p class="text-muted"><?php echo $user['email']; ?></p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Birth Year</strong>                        
                                            <p class="text-muted"><?php echo $user['dob']; ?></p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Phone number</strong>                        
                                            <p class="text-muted"><?php echo $user['country_code'].' '.$user['phone']; ?></p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Hear Aboutu</strong>                        
                                            <p class="text-muted"><?php echo $user['hear_about_us']; ?></p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Country</strong>                        
                                            <p class="text-muted"><?php echo $user['country']; ?></p>
                                        </div>
                                        <div class="about-info-p">
                                            <strong>Login Type</strong>                        
                                            <p class="text-muted"><?php if($user['signup_type'] == 'G')
                                                    {
                                                        echo  'Google';
                                                    }elseif ($user['signup_type'] == 'F') {
                                                        echo  'Facebook';
                                                    }elseif ($user['signup_type'] == 'A') {
                                                        echo  'Apple';
                                                    }else{
                                                        echo  'Normal';
                                                    } ?>
                                                        
                                             </p>
                                        </div>
                                        
                                        <div class="about-info-p">
                                            <strong>Status</strong>

                                            <p class="text-muted">
                                            <?php 
                                                if($user['is_active']=="1")
                                                {
                                                    echo "<span class='text-success'>Active</span>"; 
                                                }
                                                else
                                                {
                                                    echo "<span class='text-danger'>Inactive</span>"; 
                                                }
                                            ?>                                    
                                            </p>
                                            </p>
                                        </div>

                                        <div class="about-info-p">
                                            <strong>Is Login</strong>

                                            <p class="text-muted">
                                            <?php 
                                                if($user['is_login']=="1")
                                                {
                                                    echo "<span class='text-success'>Yes</span>"; 
                                                }
                                                else
                                                {
                                                    echo "<span class='text-danger'>No</span>"; 
                                                }
                                            ?>                                    
                                            </p>
                                            </p>
                                        </div>
                                      
                                        <div class="about-info-p">
                                            <strong>Join Date</strong>                        
                                            <p class="text-muted"><?php echo $user['inertdate']; ?></p>
                                        </div>
                                    </div>
                                </div>           
                                                </div>
                                            </div>


                                        </div>

                                </div>
                            </div>     
                    </div>
                </div> 
                <?php include(dirname(__FILE__)."/../inc/footer.php");?>
            </div>
                <!-- ============================================================== -->
                <!-- End Right content here -->
                <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->
        <?php include(dirname(__FILE__)."/../inc/script.php");?>
            
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/admin_assets/plugins/isotope/dist/isotope.pkgd.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/admin_assets/plugins/magnific-popup/dist/jquery.magnific-popup.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/admin_assets/plugins/owl.carousel/dist/owl.carousel.min.js"></script>

        <script type="text/javascript">
            $(window).load(function(){
                var $container = $('.portfolioContainer');
                $container.isotope({
                    filter: '*',
                    animationOptions: {
                        duration: 750,
                        easing: 'linear',
                        queue: false
                    }
                });

                $('.portfolioFilter a').click(function(){
                    $('.portfolioFilter .current').removeClass('current');
                    $(this).addClass('current');

                    var selector = $(this).attr('data-filter');
                    $container.isotope({
                        filter: selector,
                        animationOptions: {
                            duration: 750,
                            easing: 'linear',
                            queue: false
                        }
                    });
                    return false;
                });
            });
            $(document).ready(function() {
                $('.image-popup').magnificPopup({
                    type: 'image',
                    closeOnContentClick: true,
                    mainClass: 'mfp-fade',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                                preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                            }
                        });
            });
        </script>

        <script type="text/javascript">

            $(document).ready(function() {

                jQuery(document).ready(function($) {
                    $('.owl-carousel').owlCarousel({
                        loop:false,
                        margin:20,
                        nav:false,
                        autoplay:true,
                        responsive:{
                            0:{
                                items:1
                            },
                            480:{
                                items:2
                            },
                            700:{
                                items:4
                            },
                            1000:{
                                items:3
                            },
                            1100:{
                                items:5
                            }
                        }
                    })
                });

            </script>
    </body>
</html>