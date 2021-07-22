<?php
global $wpdb;$current_user; wp_get_current_user();

if(!is_user_logged_in()){
  wp_redirect(get_home_url());
}

// if ( is_woocommerce() || is_checkout() || is_cart() ) {
//   return;
// }
/**
 * The Header for our theme.
 *
 * @package OceanWP WordPress theme
 */ ?>
 <?php if(!is_page(140)){ ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?php the_title();?></title>
    <link rel="icon" href="<?php echo get_home_url(); ?>/wp-content/uploads/2020/03/vet-app-icon-removebg-preview-1.png">
   

    <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/datatable-bootstrap4.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/js/packages/core/main.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/js/packages/daygrid/main.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/js/packages/timegrid/main.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/js/packages/list/main.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/fullcalendar.css" />
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/fullcalendar.print.css" media='print' />
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap-timepicker.min.css" />
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap-select.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/css/sweetalert2.min.css">
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/all-min.js" crossorigin="anonymous"></script>
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/chosen.min.css" rel="stylesheet"/>
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" rel="stylesheet" />
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/font-google.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/glyphicons.css" rel="stylesheet" type="text/css">
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/js/min/basic.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/js/min/dropzone.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-typeahead/dist/jquery.typeahead.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/js/text-editor/summernote-bs4.css" />
    
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

    <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/styles.css" rel="stylesheet" />

    <!--   <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/signature-pad.css" rel="stylesheet" type="text/css"> -->
    <style>
      #external-events {
        float: left;
        width: 100%;
        padding: 0 10px;
        border: 1px solid #ccc;
        background: #eee;
        text-align: left;
        font-size: 16px;
        margin-bottom: 10px;
      }

      #external-events h4 {
        font-size: 16px;
        margin-top: 0;
        padding-top: 1em;
      }

      #external-events .fc-event {
        margin: 10px 10px;
        cursor: pointer;
      }

      #external-events p {
        margin: 1.5em 0;
        font-size: 11px;
        color: #666;
      }

      #external-events p input {
        margin: 0;
        vertical-align: middle;
      }
      span.input-group-addon {
        color: white;
        padding: 7px 11px;
      }
      .list-group{
        max-height: 300px;
        margin-bottom: 10px;
        overflow:scroll;
        -webkit-overflow-scrolling: touch;
      }
      .fc-state-highlight{
        background-color: #ffED83!important;
      }
      .typeahead__search-icon {
        padding: 0 1.25rem;
        width: 16px;
        height: 16px;
        background: url(data:image/svg+xml;charset=utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTguMS4xLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDI1MC4zMTMgMjUwLjMxMyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjUwLjMxMyAyNTAuMzEzOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCI+CjxnIGlkPSJTZWFyY2giPgoJPHBhdGggc3R5bGU9ImZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkOyIgZD0iTTI0NC4xODYsMjE0LjYwNGwtNTQuMzc5LTU0LjM3OGMtMC4yODktMC4yODktMC42MjgtMC40OTEtMC45My0wLjc2ICAgYzEwLjctMTYuMjMxLDE2Ljk0NS0zNS42NiwxNi45NDUtNTYuNTU0QzIwNS44MjIsNDYuMDc1LDE1OS43NDcsMCwxMDIuOTExLDBTMCw0Ni4wNzUsMCwxMDIuOTExICAgYzAsNTYuODM1LDQ2LjA3NCwxMDIuOTExLDEwMi45MSwxMDIuOTExYzIwLjg5NSwwLDQwLjMyMy02LjI0NSw1Ni41NTQtMTYuOTQ1YzAuMjY5LDAuMzAxLDAuNDcsMC42NCwwLjc1OSwwLjkyOWw1NC4zOCw1NC4zOCAgIGM4LjE2OSw4LjE2OCwyMS40MTMsOC4xNjgsMjkuNTgzLDBDMjUyLjM1NCwyMzYuMDE3LDI1Mi4zNTQsMjIyLjc3MywyNDQuMTg2LDIxNC42MDR6IE0xMDIuOTExLDE3MC4xNDYgICBjLTM3LjEzNCwwLTY3LjIzNi0zMC4xMDItNjcuMjM2LTY3LjIzNWMwLTM3LjEzNCwzMC4xMDMtNjcuMjM2LDY3LjIzNi02Ny4yMzZjMzcuMTMyLDAsNjcuMjM1LDMwLjEwMyw2Ny4yMzUsNjcuMjM2ICAgQzE3MC4xNDYsMTQwLjA0NCwxNDAuMDQzLDE3MC4xNDYsMTAyLjkxMSwxNzAuMTQ2eiIgZmlsbD0iIzU1NTU1NSIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=) no-repeat scroll center center transparent;
        font-size: 106%!Important;
    }
    
      .typeahead__container {
        width: 100%;
        margin-left: 20px;
      }
      .btn, .navbar-nav {
        display: inline-block;
      }

      </style>
  </head>
  <body <?php body_class(); ?> id="testbody">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
      
    <a class="navbar-brand" href="<?php echo get_home_url().'/dashboard'; ?>"><img src="<?php echo get_home_url(); ?>/wp-content/uploads/2020/03/vet-app-icon-removebg-preview-1.png" style="width:26%;"> VETCARE</a>
      
    <!-- Navbar Search-->
      <form id="searchform" class="d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
          <div class="typeahead__container">
            <div class="typeahead__field">
              <div class="typeahead__query">
                <input class="js-typeahead-hockey_v1" name="hockey_v1[query]" placeholder="Search" autocomplete="off">
              </div>
              <div class="typeahead__button">
                <button type="submit">
                  <i class="typeahead__search-icon"></i>
                </button>
              </div>
            </div>
          </div>
        </form>
    
      
      
      <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
      
      <!-- Navbar-->
      <ul class="navbar-nav ml-auto ml-md-0">
        <!--<li class="nav-item">-->
        <!--  <a class="nav-link"><i class="fas fa-bell fa-fw"></i><span class="badge badge-danger">4</span></a>-->
        <!--</li>-->
        <!--<li class="nav-item">-->
        <!--  <a class="nav-link"><i class="fas fa-envelope fa-fw"></i><span class="badge badge-danger">4</span></a>-->
        <!--</li>-->
          <?php global $current_user; wp_get_current_user(); ?>
          <?php if ( is_user_logged_in() ) {
            ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i> Hi <?php echo $current_user->display_name; ?></a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <!--<a class="dropdown-item" href="#">My Account</a>-->
            <!--<a class="dropdown-item" href="#">Edit Profile</a>-->
            <!--<div class="dropdown-divider"></div>-->
            <a class="dropdown-item" href="<?php echo wp_logout_url(); ?>">Logout</a>
          </div>
        </li>
        <?php
          }
          ?>
      </ul>
    </nav>
    <?php }?>
