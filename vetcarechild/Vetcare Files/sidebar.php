<?php
global $current_user; wp_get_current_user();
$user = get_userdata( get_current_user_id());

// Get all the user roles as an array.
$user_roles = $user->roles;
?>

<!--  -->

<style>

    button#sidebarToggle {
        display: none!important;
    }
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
    #layoutSidenav #layoutSidenav_nav {
        flex-basis: 180px!important;

    }

    /**/
    .col-md-2 {
        background-color: red!Important;
    }
    .sb-sidenav-menu {
    background-color: #30A1AB!important;
    }


    /*June 9 CSS Code*/

    p.settings {
        margin-top: -25px!Important;
    }

    p.dashboard {
        margin-top: -25px!Important;
    }

    p.appointment {
        margin-top: -25px!Important;
    }

    p.employee {
        margin-top: -25px!Important;
    }

    p.owners {
        margin-top: -25px!Important;
    }

    p.smsbalance {
        margin-top: -25px!Important;
    }

    a.nav-link-side {
        display: block!important;
        padding: 0.5rem 1rem!important;
    }

    a.nav-link-side {
        color: white!Important;
        text-decoration: none!Important;
    }


</style>

<!--  -->

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
            <div class="sb-sidenav-menu">

        <!-- Start -->
                <div class="item">
                    <a class="nav-link-side" href="<?php echo get_home_url().'/settings'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-cog fa-3x"></i></div><center><p class="settings">
                Settings</a></p></center>
                </div>

                <div class="item">
                    <a class="nav-link-side" href="<?php echo get_home_url().'/dashboard'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-home fa-3x"></i></div><center><p class="dashboard">
                Dashboard</a></p></center>
                </div>

                <div class="item">
                    <a class="nav-link-side" href="<?php echo get_home_url().'/appointments'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt fa-3x"></i></div><center><p class="appointment">
                Appointment</a></p></center>
                </div>

                <div class="item">
                    <?php if ( in_array( 'administrator', $user_roles, true ) || in_array( 'um_groomer', $user_roles, true ) ) {?>
                <a class="nav-link-side" href="<?php echo get_home_url().'/add-doctor'; ?>">
                  <div class="sb-nav-link-icon"><i class="fas fa-user-md fa-3x"></i></div><center><p class="employee">Add Employee</a></p></center>
                 <?php } ?>
                </div>

                <div class="item">
                    <a class="nav-link-side" href="<?php echo get_home_url().'/add-owner'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-user fa-3x"></i></div><center><p class="owners">
                Add Owners</a></p></center>
                </div>

                <div class="item">
                    <a class="nav-link-side" href="#">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment-alt fa-3x"></i></div><center><p class="smsbalance">
                SMS Balance</a></p><br><!-- <p id="smsbal" style="color: white;margin-top: 10px;margin-left: 71px;"></p> --></center>
                </div>

          <!--  End-->



                <div class="nav">
                   <!--  <a class="nav-link text-center" id="myTime">Time</a>
                    <a class="nav-link" id="myDate">Date</a> -->
                  </span>
                <br>
                    <!-- <a class="nav-link" href="<?php echo get_home_url().'/dashboard'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-home fa-3x"></i></div><span class="dashboard">
                Dashboard</a></span> -->
                <br>
                <!-- <a class="nav-link" href="<?php echo get_home_url().'/appointments'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt fa-3x"></i></div><span class="appointment">
                Appointment</a></span> -->
                <br>
                 <!-- <a class="nav-link" href="<?php echo get_home_url().'/add-pets'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-dog fa-3x"></i></div><span class="add_pets">
                Add Pets</a></span>
                <br> -->
                <!-- <?php if ( in_array( 'administrator', $user_roles, true ) || in_array( 'um_groomer', $user_roles, true ) ) {?>
                <a class="nav-link" href="<?php echo get_home_url().'/add-doctor'; ?>">
                  <div class="sb-nav-link-icon"><i class="fas fa-user-md fa-3x"></i></div><span class="add_doctor">Add Employee</a></span>
                 <?php } ?> -->
                <br>
                <?php if ( in_array( 'administrator', $user_roles, true ) || in_array( 'um_groomer', $user_roles, true ) ) {?>
                <!-- <a class="nav-link" href="<?php echo get_home_url().'/add-groomers'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-users fa-3x"></i></div><span class="add_groomers">
                Add Groomers</a></span>
                <br> -->
                <?php } ?>
                <!-- <a class="nav-link" href="<?php echo get_home_url().'/add-owner'; ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-user fa-3x"></i></div><span class="add_customer">
                Add Owners</a></span> -->
                <br>

                <!-- <a class="nav-link" href="#">
                        <div class="sb-nav-link-icon"><i class="fas fa-comment-alt fa-3x"></i></div>
                        <span class="sms_balance">SMS Balance </a></span><p id="smsbal" style="color: white;margin-top: 10px;margin-left: 71px;"></p> -->

<!--  -->
                <!-- <a class="nav-link" href="<?php echo get_home_url().'/dashboard'; ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard</a> -->


                <!-- <?php if ( in_array( 'administrator', $user_roles, true ) ) {?>
                    <div class="sb-sidenav-menu-heading">Appointments</div>
                    <a class="nav-link" href="index.html">
                        <div class="sb-nav-link-icon"><i class="fas fa-info-circle"></i></div>
                    Details</a>
                    <a class="nav-link" href="<?php echo get_home_url().'/waiver'; ?>">
                        <div class="sb-nav-link-icon"><i class="far fa-sticky-note"></i></i></div>
                    Waiver Form</a>
                <?php } ?> -->
                <!-- <div class="sb-sidenav-menu-heading">Actions</div>
                <?php if ( in_array( 'administrator', $user_roles, true ) || in_array( 'um_doctor', $user_roles, true ) ) {?>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsedoctors" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Staffs
                        <div class="sb-sidenav-collapse-arrow">
                            <i class="fas fa-angle-down"></i>
                        </div>
                    </a> -->
                    <!-- <div class="collapse" id="collapsedoctors" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="<?php echo get_home_url().'/add-doctor'; ?>">Doctors</a>
                            <?php if ( in_array( 'administrator', $user_roles, true ) || in_array( 'um_groomer', $user_roles, true ) ) {?>
                            <a class="nav-link" href="<?php echo get_home_url().'/add-doctor'; ?>">Groomers</a>
                        <?php } ?>
                        </nav>
                    </div> -->
               <!--  <?php } ?>
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseowners" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                    Owners
                    <div class="sb-sidenav-collapse-arrow">
                        <i class="fas fa-angle-down"></i>
                    </div>
                </a>
                <div class="collapse" id="collapseowners" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?php echo get_home_url().'/add-owner'; ?>">Owner</a>
                        <a class="nav-link" href="layout-sidenav-light.html">Sub-Owner</a>
                        <a class="nav-link" href="<?php echo get_home_url().'/add-pets'; ?>">Pets</a>

                    </nav>
                </div> -->

                <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesms" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-sms"></i></div>
                    SMS
                    <div class="sb-sidenav-collapse-arrow">
                        <i class="fas fa-angle-down"></i>
                    </div>
                </a>
                <div class="collapse" id="collapsesms" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?php echo get_home_url().'/sms-account'; ?>">SMS Account</a>
                        <a class="nav-link" href="<?php echo get_home_url().'/sms-sending'; ?>">SMS Sending</a>
                        <a class="nav-link" href="<?php echo get_home_url().'/sms-message'; ?>">SMS Message</a>
                    </nav>
                </div>
                <?php if ( in_array( 'administrator', $user_roles, true ) ) {?>
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapserecords" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-clipboard"></i></div>
                    Records
                    <div class="sb-sidenav-collapse-arrow">
                        <i class="fas fa-angle-down"></i>
                    </div>
                </a>
                <div class="collapse" id="collapserecords" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="#">Reset Database</a>
                    </nav>
                </div> -->

                <!-- <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsereports" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Reports
                    <div class="sb-sidenav-collapse-arrow">
                        <i class="fas fa-angle-down"></i>
                    </div>
                </a>
                <div class="collapse" id="collapsereports" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?php echo get_home_url().'/reports-pet-owners'; ?>">Owner</a>
                        <a class="nav-link" href="<?php echo get_home_url().'/reports-pets'; ?>">Pets</a>
                    </nav>
                </div>
            <?php } ?> -->
                 <!-- <a class="nav-link" href="index.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i>
                    </div>
                SMS Balance</a> -->
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?php global $current_user; wp_get_current_user(); ?>
            <?php if ( is_user_logged_in() ) {
                echo $current_user->user_login;}
                else { wp_loginout(); } ?>
            </a>
        </div>
    </nav>
</div>
