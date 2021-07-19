<?php
// if ( is_woocommerce() || is_checkout() || is_cart() ) {
//     return;
// }
global $wp,$wpdb,$current_user;
 wp_get_current_user();
$user = get_userdata(get_current_user_id());

// Get all the user roles as an array.
$user_roles = $user->roles;
?>

<style>

    /*button#sidebarToggle {
        display: none!important;
    }*/
    /*a.navbar-brand {
        background-color: #687178!important;
    }*/

    .sb-sidenav-menu {
        background-color: #343A40!important;
    }

    .sb-nav-link-icon {
        color: white!Important;
    }

    span.settings {
        position: absolute;
        margin-top: 35px!Important;
        margin-left: 43px!Important;
        color: white;
    }
    span.dashboard {
        position: absolute;
        margin-top: 35px!Important;
        margin-left: 32px!Important;
        color: white;
    }
    span.appointment {
        position: absolute;
        margin-top: 35px!Important;
        margin-left: 25px!Important;
        color: white;
    }
    span.add_doctor {
        position: absolute;
        margin-top: 35px!Important;
        margin-left: 33px!Important;
        color: white;
    }
    span.add_groomers {
        position: absolute;
        margin-top: 35px!Important;
        margin-left: 20px!Important;
        color: white;
    }
    span.add_customer {
        position: absolute;
        margin-top: 35px!Important;
        margin-left: 20px!Important;
        color: white;
    }
    span.add_pets {
        position: absolute;
        margin-top: 35px!Important;
        margin-left: 36px!Important;
        color: white;
    }
    span.sms_balance {
        position: absolute;
        margin-top: 35px!Important;
        margin-left: 25px!Important;
        color: white;
    }
    svg.svg-inline--fa.fa-cog.fa-w-16.fa-3x {
        margin-left: 52px!Important;
    }

    svg.svg-inline--fa.fa-home.fa-w-18.fa-3x {
        margin-left: 49px!Important;
    }

    svg.svg-inline--fa.fa-calendar-alt.fa-w-14.fa-3x {
            margin-left: 56px!Important;
    }

    svg.svg-inline--fa.fa-user-md.fa-w-14.fa-3x {
            margin-left: 57px!Important;
    }

    svg.svg-inline--fa.fa-users.fa-w-20.fa-3x {
            margin-left: 46px!important;
    }

    svg.svg-inline--fa.fa-user.fa-w-14.fa-3x {
        margin-left: 53px!Important
    }

    svg.svg-inline--fa.fa-dog.fa-w-16.fa-3x {
        margin-left: 50px!important;
    }

    svg.svg-inline--fa.fa-comment-alt.fa-w-16.fa-3x {
            margin-left: 51px!Important;
    }

    /**/
/*    #layoutSidenav #layoutSidenav_nav {
        flex-basis: 180px!important;

    }*/

    /**/
    .col-md-2 {
        /*background-color: red!Important;*/
    }
    .sb-sidenav-menu {
    /*background-color: #3DB588!important;*/
    background-image: linear-gradient(to right top, #169cb2, #00a3ad, #00aaa4, #16b098, #3db588);
    }


    /*June 9 CSS Code*/

    p.settings {
        margin-top: -25px!Important;
        margin-left: -18px!Important;
    }

    p.dashboard {
        margin-top: -25px!Important;
                margin-left: -16px!Important;
    }

    p.appointment {
        margin-top: -25px!Important;
            margin-left: -18px!Important;
    }

    p.employee {
        margin-top: -25px!Important;
                margin-left: -18px!Important;
    }

    p.owners {
        margin-top: -25px!Important;
            margin-left: -26px!Important;
    }

    p.smsbalance {
        margin-top: -25px!Important;
            margin-left: -20px!Important;
    }

    a.nav-link-side {
        display: block!important;
        padding: 0.5rem 1rem!important;
    }

    a.nav-link-side {
        color: white!Important;
        text-decoration: none!Important;
    }



/**/
.fa-3x {
    font-size: 4em!important;
}
nav#sidenavAccordion {
    width: 86px!Important;
}

p.settings a {
    font-size: 18px!Important;
}
p.dashboard a {
    font-size: 18px!Important;
}
p.appointment a {
    font-size: 18px!Important;
}
p.employee a {
    font-size: 18px!Important;
}
p.owners a {
    font-size: 18px!Important;
}
p.smsbalance a {
    font-size: 18px!Important;
}
.activate{
    background:white;
    color:black;
}
button#sidebarToggle {
    display: none;
}
#layoutSidenav #layoutSidenav_nav {
    flex-basis: 1px!important; 
}
/*Tablets*/
/*@media (max-width: 1350px) {*/
/*    #layoutSidenav #layoutSidenav_nav {*/
/*    flex-basis: 225px!important;*/
/*    flex-shrink: 0;*/
/*    transition: transform 0.15s ease-in-out;*/
/*    z-index: 1038;*/
/*    transform: translateX(-225px);*/
/*}*/
/*}*/
/*@media (max-width: 1200px) {*/
/*    #layoutSidenav #layoutSidenav_nav {*/
/*    flex-basis: 225px!important;*/
/*    flex-shrink: 0;*/
/*    transition: transform 0.15s ease-in-out;*/
/*    z-index: 1038;*/
/*    transform: translateX(-225px);*/
/*}*/
/*}*/
/*@media (max-width: 1024px) {*/
/*    #layoutSidenav #layoutSidenav_nav {*/
/*    flex-basis: 225px!important;*/
/*    flex-shrink: 0;*/
/*    transition: transform 0.15s ease-in-out;*/
/*    z-index: 1038;*/
/*    transform: translateX(-225px);*/
/*}*/
/*}*/
/*@media (max-width: 860px) {*/
/*    #layoutSidenav #layoutSidenav_nav {*/
/*    flex-basis: 225px!important;*/
/*    flex-shrink: 0;*/
/*    transition: transform 0.15s ease-in-out;*/
/*    z-index: 1038;*/
/*    transform: translateX(-225px);*/
/*}*/
/*}*/
/*Small Devices Max 600px*/
@media screen and (max-width: 600px) {
#layoutSidenav #layoutSidenav_nav {
    flex-basis: 225px!important;
    flex-shrink: 0;
    transition: transform 0.15s ease-in-out;
    z-index: 1038;
    transform: translateX(-225px);
}
button#sidebarToggle {
    display: block;
}
}
</style>

        
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">

                        <!-- Start -->
                <div class="item <?php echo (get_the_title() == "Settings") ? 'activate':''; ?>">
                    <a class="nav-link-side" href="<?php echo get_home_url().'/settings'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-cog fa-2x" <?php echo (get_the_title() == "Settings") ? 'style="color:#30A1AB;"':''; ?> ></i></div>
                        <!--<center><p class="settings">Settings</a></p></center>-->
                </div>

                <div class="item <?php echo (get_the_title() == "Dashboard") ? 'activate':''; ?>">
                    <a class="nav-link-side" href="<?php echo get_home_url().'/dashboard'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-home fa-2x" <?php echo (get_the_title() == "Dashboard") ? 'style="color:#30A1AB;"':''; ?>></i></div>
                        <!--<center><p class="dashboard">Dashboard</a></p></center>-->
                </div>

                <div class="item <?php echo (get_the_title() == "Appointments") ? 'activate':''; ?>">
                    <a class="nav-link-side" href="<?php echo get_home_url().'/appointments'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt fa-2x" <?php echo (get_the_title() == "Appointments") ? 'style="color:#30A1AB;"':''; ?>></i></div>
                        <!--<center><p class="appointment">Appointment</a></p></center>-->
                </div>

                <div class="item <?php echo (get_the_title() == "Add Doctor") ? 'activate':''; ?>">
                    <?php if ( in_array( 'administrator', $user_roles, true ) || in_array( 'um_clinic', $user_roles, true ) ) {?>
                <a class="nav-link-side" href="<?php echo get_home_url().'/add-doctor'; ?>">
                  <div class="sb-nav-link-icon"><i class="fas fa-user-md fa-2x" <?php echo (get_the_title() == "Add Doctor") ? 'style="color:#30A1AB;"':''; ?>></i></div>
                  <!--<center><p class="employee">Add Employee</a></p></center>-->
                 <?php } ?>
                </div>

                <div class="item  <?php echo (get_the_title() == "Add Owner") ? 'activate':''; ?>">
                    <a class="nav-link-side" href="<?php echo get_home_url().'/add-owner'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-user fa-2x" <?php echo (get_the_title() == "Add Owner") ? 'style="color:#30A1AB;"':''; ?>></i></div>
                        <!--<center><p class="owners">Add Owners</a></p></center>-->
                </div>

                <div class="item">
                    <a class="nav-link-side" href="#">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment-alt fa-2x"></i></div>
                        <center><p class="smsbalance"></a></p>
                        <br><p id="smsbal" style="color: white;margin-top: -40px;margin-left: -28px;"></p></center>
                </div>

          <!--  End-->

                        <!-- <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Interface</div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Layouts
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Static Navigation</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Pages
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="login.html">Login</a>
                                            <a class="nav-link" href="register.html">Register</a>
                                            <a class="nav-link" href="password.html">Forgot Password</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Error
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="401.html">401 Page</a>
                                            <a class="nav-link" href="404.html">404 Page</a>
                                            <a class="nav-link" href="500.html">500 Page</a>
                                        </nav>
                                    </div>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Addons</div>
                            <a class="nav-link" href="charts.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a>
                        </div> -->
                    </div>
                    <!--<div class="sb-sidenav-footer">-->
                    <!--    <div class="small">Logged in as:</div>-->
                    <!--   Hi <?php echo $current_user->display_name; ?>-->
                    <!--</div>-->
                </nav>
            </div>

