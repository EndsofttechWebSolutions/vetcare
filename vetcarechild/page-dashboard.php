<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>
<?php
global $wpdb;$current_user; wp_get_current_user();
$user = get_userdata( get_current_user_id());
$ID = get_current_user_id();
$user_roles = $user->roles;
if(!empty($user_roles)){
  if (in_array( 'um_doctors', $user_roles, true ) || in_array( 'um_groomers', $user_roles, true ) ) {
    $myclinic_id =  get_user_meta(get_current_user_id(),'clinic_id',true);
  }else if(in_array( 'um_clinic', $user_roles, true )){
    $myclinic_id = get_current_user_id();
  }else{
    $myclinic_id = get_current_user_id();
  }
}
$username_sms = get_post_meta( $myclinic_id, 'username_sms', true );
$password_sms = get_post_meta( $myclinic_id, 'password_sms', true );
if(!is_user_logged_in()){
  wp_redirect(get_home_url());
}
$table=$wpdb->prefix.'vet_appointments';
$table_pets=$wpdb->prefix.'vet_pets';
$table_owners=$wpdb->prefix.'vet_owners';
$results = $wpdb->get_results("SELECT COUNT(start_date) AS totalappointment FROM wp_vet_appointments where start_date= CURRENT_DATE() AND clinic_id = {$myclinic_id}");
$arrayData = array();
$total_pets = $wpdb->get_var("SELECT COUNT(*) FROM {$table_pets} WHERE clinic_id = {$myclinic_id}");
$total_owner = $wpdb->get_var("SELECT COUNT(*) FROM {$table_owners} WHERE clinic_id = {$myclinic_id}");
foreach ($results as $key ) {
  $totalappointment = $key->totalappointment;
}

$arrayIMG = offlineview1();
$arrayIMG2 = offlineview2();

?>

<style>

  * {box-sizing: border-box;}
  .mySlides {
    display: none;
    width: 100%;
    height: auto;
    background: none;
  }
  img {vertical-align: middle;}

  /* Slideshow container */
  .slideshow-container {
    max-width: auto;
    position: relative;
    margin: auto;
  }


  /* On smaller screens, decrease text size */
  @media only screen and (max-width: 300px) {
    .text {font-size: 11px}
  }

  .col-md-2 {
    /*background-color: #F7F7F7!Important;*/
  }
  /**/
  input.form-control.form-control-sm {
    position: absolute;
    width: 74%!Important;
    margin-left: -147px!Important;
  }
  div#history_info {
    display: none!Important;
  }
  @media only screen and (max-width: 1440px){
    h1.card-text-total-balance {
      position: absolute;
      margin-top: -65px!Important;
      margin-left: 140px!Important;
      font-size: 47px!Important;
    }   
  }

  @media only screen and (max-width: 1366px){
    h1.card-text-total-balance {
      position: absolute;
      margin-top: -70px!Important;
      margin-left: 135px!Important;
      font-size: 51px!Important;
    }
  }

  @media only screen and (min-width: 1920px){
    h1.card-text-total-balance {
      position: absolute;
      margin-top: -80px!Important;
      margin-left: 255px!Important;
      font-size: 51px!Important;
    }
  }
  @media only screen and (max-width: 1080px){
    h1#smsbalance {
      position: absolute;
      margin-top: -53px!Important;
      margin-left: 74px!Important;
      font-size: 40px!Important;
    }
    p.card-title {
      margin-left: 49px!Important;
      position: absolute;
      margin-top: -11px!Important;
      font-size: 11px!Important;
    }
  }
  /*1143*/
  @media only screen and (max-width: 1143px){
    .fa-5x {
      font-size: 3em!important;
      margin-top: 10px!Important;
    }
    p.card-title {
      margin-left: 46px!Important;
      position: absolute;
      margin-top: -11px!Important;
      font-size: 11px!Important;
    }
    p.card-title-total-pets {
      margin-left: 54px!Important;
      position: absolute;
      margin-top: -11px!Important;
      font-size: 11px!Important;
    }
    p.card-title-total-owners {
      font-size: 11px!Important;
      margin-left: 61px!Important;
      position: absolute;
      margin-top: -11px!Important;
    }
    p.card-title-total-balance {
      font-size: 11px!Important;
      margin-left: 80px!Important;
      position: absolute;
      margin-top: -11px!Important;
    }
    h1.card-text {
      position: absolute;
      margin-top: -53px!Important;
      margin-left: 108px!Important;
      font-size: 40px!Important;
    }
    h1.card-text-total-pets {
      position: absolute;
      margin-top: -53px!Important;
      margin-left: 108px!Important;
      font-size: 40px!Important;
    }
    h1#smsbalance {
      position: absolute;
      margin-top: -53px!Important;
      margin-left: 66px!Important;
      font-size: 40px!Important;
    }



  }
  /*768*/

  @media only screen and (max-width: 768px) {
    p.card-title {
      margin-left: 43px!Important;
      position: absolute;
      margin-top: -11px!Important;
      font-size: 10px!Important;
    }
    h1.card-text {
      position: absolute;
      margin-top: -47px!Important;
      margin-left: 78px!Important;
      font-size: 35px!Important;
    }
    p.card-title-total-pets {
      margin-left: 50px!Important;
      position: absolute;
      margin-top: -11px!Important;
      font-size: 10px!Important;
    }
    h1.card-text-total-pets {
      position: absolute;
      margin-top: -47px!Important;
      margin-left: 78px!Important;
      font-size: 35px!Important;
    }
    p.card-title-total-owners {
      font-size: 11px!Important;
      margin-left: 57px!Important;
      position: absolute;
      margin-top: -10px!Important;
    }
    p.card-title-total-balance {
      font-size: 11px!Important;
      margin-left: 56px!Important;
      position: absolute;
      margin-top: -10px!Important;
    }
    h1#smsbalance {
      position: absolute;
      margin-top: -47px!Important;
      margin-left: 67px!Important;
      font-size: 35px!Important;
    }
    /*Appointments*/
    .fc-left h2 {
      font-size: 24px!important;
    }
  }

  @media only screen and (max-width: 800px) {
    h1.card-text {
      position: absolute;
      margin-top: -45px!Important;
      margin-left: 93px!Important;
      font-size: 35px!Important;
    }
    p.card-title-total-pets {
      margin-left: 53px!Important;
      position: absolute;
      margin-top: -11px!Important;
      font-size: 11px!Important;
    }
  }
  @media (max-width: 768px){
    .fc-toolbar .fc-left, .fc-toolbar .fc-center, .fc-toolbar .fc-right {
      display: inline-block;
      float: none !important;
    }
  }
  @media (max-width: 1024px){
    .fc-toolbar .fc-left, .fc-toolbar .fc-center, .fc-toolbar .fc-right {
      display: inline-block;
      float: none !important;
    }
  }
  /**/
  .grid-container {
    display: grid;
    grid-template-columns: 25% 75%;
    padding: 10px;
    background-color: #00349A;
    border-radius: 5px;
    margin-bottom: 12px;
  }
  .grid-item {

    padding-left: 15px;
    font-size: 15px;
    text-align: left;
  }
  .appointment {
    color: white;
  }
  .appointment-icon {
    color: white;
  }
  .grid-item-right {
    padding-left: 76px;
    text-align: center;
  }
  .grid-first-item-right {
    padding-left: 60px;
    text-align: center;
  }
  p.total-text {
    font-size: 0.75rem;
    color: white;
  }
  p.total-number a {
    font-size: 47px;
    color: white;
    text-decoration: none;
  }
  p#smsbalance {
    font-size: 47px;
    color: white;
  }
</style>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid">
      <!-- <h1 class="mt-4">Dashboard</h1> -->
      <ol class="breadcrumb mb-4 mt-2">
        <li class="breadcrumb-item active ">Dashboard</li>
      </ol>
      <div class="row">

        <div class="col-lg-3 col-md-6">
          <div class="grid-container">
            <div class="grid-item">
              <div class="appointment">
                <p>Appointment</p>
              </div>
              <div class="appointment-icon">
                <i class="fas fa-calendar-alt fa-5x"></i>
              </div>
            </div>
            <div class="grid-first-item-right">
              <br>
              <div class="total-appointment">
                <p class="total-number"><a href="<?php echo get_site_url();?>/appointments"><?php echo $totalappointment; ?></a></p>
              </div>
              <div class="total-appointment-text">
                <p class="total-text">Total Appointment for the Day</p>
              </div>
            </div> 
          </div>
          <!--<div class="card text-white bg-primary mb-3">-->
            <!--  <div class="card-header">Appointment</div>-->
            <!--  <div class="card-body">-->
              <!--    <i class="fas fa-calendar-alt fa-5x"></i>-->
              <!--    <p class="card-title-total-pets">Total Appointment for the Day</p>-->
              <!--    <a href="<?php echo get_site_url();?>/appointments" style="color:white;"><h1 class="card-text"><?php echo $totalappointment; ?></h1></a>-->
              <!--  </div>-->
              <!--</div>-->
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="grid-container" style="background-color:#0077F7;">
                <div class="grid-item">
                  <div class="appointment">
                    <p>Pets</p>
                  </div>
                  <div class="appointment-icon">
                    <i class="fas fa-paw fa-5x"></i>
                  </div>
                </div>
                <div class="grid-item-right">
                  <br>
                  <div class="total-appointment">
                    <p class="total-number"><a href="<?php echo get_site_url();?>/add-pets/"><?php echo  $total_pets;?></a></p>
                  </div>
                  <div class="total-appointment-text">
                    <p class="total-text">Total Number of Pets</p>
                  </div>
                </div> 
              </div>
              <!--<div class="card text-white bg-secondary mb-3">-->
                <!--  <div class="card-header">Pets</div>-->
                <!--  <div class="card-body">-->
                  <!--    <i class="fas fa-paw fa-5x"></i>-->
                  <!--    <p class="card-title-total-pets">Total Number of Pets</p>-->
                  <!--    <a href="<?php echo get_site_url();?>/add-pets/" style="color:white;"><h1 class="card-text-total-pets"><?php echo  $total_pets;?></h1></a>-->
                  <!--  </div>-->
                  <!--</div>-->
                </div>
                <div class="col-lg-3 col-md-6">
                  <div class="grid-container" style="background-color:#6CCAC9;">
                    <div class="grid-item">
                      <div class="appointment">
                        <p>Owners</p>
                      </div>
                      <div class="appointment-icon">
                        <i class="fas fa-users fa-5x"></i>
                      </div>
                    </div>
                    <div class="grid-item-right">
                      <br>
                      <div class="total-appointment">
                        <p class="total-number"><a href="<?php echo get_site_url();?>/add-owner/"><?php echo  $total_owner;?></a></p>
                      </div>
                      <div class="total-appointment-text">
                        <p class="total-text">Total Number of Owners</p>
                      </div>
                    </div> 
                  </div>
                  <!--<div class="card text-white bg-success mb-3">-->
                    <!--  <div class="card-header">Owners</div>-->
                    <!--  <div class="card-body">-->
                      <!--    <i class="fas fa-users fa-5x"></i>-->
                      <!--    <p class="card-title-total-owners">Total Number of Owners</p>-->
                      <!--    <a href="<?php echo get_site_url();?>/add-owner/" style="color:white;"><h1 class="card-text"><?php echo  $total_owner;?></h1></a>-->
                      <!--  </div>-->
                      <!--</div>-->
                    </div>
                    <div class="col-lg-3 col-md-6">
                      <div class="grid-container" style="background-color:#169CB2;">
                        <div class="grid-item">
                          <div class="appointment">
                            <p>SMS</p>
                          </div>
                          <div class="appointment-icon">
                            <i class="fa fa-comments fa-5x"></i>
                          </div>
                        </div>
                        <div class="grid-item-right">
                          <br>
                          <div class="total-appointment">
                            <p class="total-number" id="smsbalance">0</p>
                          </div>
                          <div class="total-appointment-text">
                            <p class="total-text">Total SMS Balance</p>
                          </div>
                        </div> 
                      </div>
                      <!--<div class="card text-white bg-info mb-3" >-->
                        <!--  <div class="card-header">SMS</div>-->
                        <!--  <div class="card-body">-->
                          <!--    <i class="fas fa-comment-alt fa-5x"></i>-->
                          <!--    <p class="card-title-total-balance">Total Balance</p>-->
                          <!--    <h1 class="card-text-total-balance" id="smsbalance">0</h1>-->
                          <!--  </div>-->
                          <!--</div>-->
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-md-10">
                          <div class="row">
                            <div class="col-xl-12">
                              <div class="card mb-4">
                                <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Appointments  <div class="float-right">
                                  <span class="badge badge-warning">Upcoming</span>
                                  <span class="badge badge-success">Completed</span>
                                  <span class="badge badge-danger">Absent</span>
                                </div></div>

                                <div class="card-body">

                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="col-md-12">
                                        <div class="input-group mb-3">
                                          <select data-placeholder="Type Service name..." class="form-control" name="test" id="dropdownListing" multiple="multiple">
                                          </select>
                                        </div>
                                      </div>   

                                      <div class='calendar-container'>
                                        <div id='calendar'></div>
                                      </div>
                                    </div>
                                  </div>

                                </div>
                              </div>
                            </div>
    <!--         <div class="col-xl-4">
    <div class="card mb-4">
    <div class="card-header">
    <span><i class="fas fa-table mr-1"></i>History</span>
    
    <button class="btn btn-primary float-right" id="hidehistory" ><i class="fa fa-eye-slash"></i></button>
    <button class="btn btn-primary float-right" id="showhistory" hidden><i class="fa fa-eye"></i></button>
    </div>
    <div class="card-body">
    <div class="table-responsive">
    <table class="table table-bordered" id="history" width="100%" cellspacing="0">
    <thead>
    <tr>
    <th>Action</th>
    <th>Date</th>
    </tr>
    </thead>
    <tbody>
    <?php
    global $wpdb;
    $table=$wpdb->prefix.'vet_history';
    $results = $wpdb->get_results("SELECT * FROM {$table}");
    $arrayData = array();
    foreach ($results as $key ) {
        ?>
        <tr>
        <td><?php echo $key->action;?></td>
        <td><?php echo date('D M d Y',strtotime($key->date_created));?></td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
        </div>
        </div>
        </div>
      </div> -->
    </div>
        <!--   <div class="row">
        <?php if ( in_array( 'administrator', $user_roles, true ) || in_array( 'um_groomer', $user_roles, true ) ) {?>
            <div class="col-xl-12">
            <div class="card mb-4">
            
            <div class="card-header"><i class="fas fa-table mr-1"></i>Reminders</div>
            <div class="card-body">
            <?php echo do_shortcode('[data_table id="dataTable"]'); ?>
            </div>
            
            </div>
            </div>
            <?php } ?>
          </div> -->

        </div>
        <div class="col-md-2">
          <div class="card mb-4">
            <div class="upper_Ban">
              <div class="slideshow-container" data-cycle="7000">
                <?php 
                if(count($arrayIMG2) !== 0 ) {
                foreach ($arrayIMG as $row) { 
                  ?>
                  <div class="mySlides">
                    <a style="cursor: pointer;" onClick="parent.open('https://<?php echo $row['targeturl']?>/')">
                      <img src="data:image/jpeg;base64,<?php echo $row['data_img'];?>" alttag="<?php echo $row['alttag']?>" style="width: 100%;">
                    </a>
                  </div>
                <?php }} ?>
              </div>
            </div>
          </div>
          <div class="card mb-4">
            <div class="upper_Ban">
              <div class="slideshow-container" data-cycle="7000">
                <?php
                if(count($arrayIMG2) !== 0 ) {
                 foreach ($arrayIMG2 as $row) {
                  // var_dump($row);
                  ?>
                  <div class="mySlides">
                    <a style="cursor: pointer;" onClick="parent.open('https://<?php echo $row['targeturl']?>/')">
                      <img src="data:image/jpeg;base64,<?php echo $row['data_img'];?>" alttag="<?php echo $row['alttag']?>" style="width: 100%;">
                    </a>
                  </div>
                <?php }
                } ?>
              </div>
            </div>
          </div>

        </div>

      </main>

      <div class="modal fade" id="appointmentForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Add New Appointment</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container-fluid content-body">
                <nav class="mb-4">
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Details</a>
                    <a class="nav-item nav-link" id="nav-sms-tab" data-toggle="tab" href="#nav-sms" role="tab" aria-controls="nav-sms" aria-selected="false">SMS</a>
                  </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                    <form action="" method="POST" class="needs-validation" id="form_appointments" novalidate>
                      <div class="form-row">
                        <div class="col-md-12 mb-2">
                          <label for="searh_pet">Name of Pet</label>
                          <div class="input-group mb-3">
                            <select data-placeholder="Type Pet Name..." class="form-control" name="petID" id="pet_list">
                            </select>
                          </div>
                          <div class="valid-feedback">
                            Looks good!
                          </div>
                        </div>
                        <div class="col-md-12 mb-3">
                          <label for="complaints">Remarks</label>
                          <textarea type="date" class="form-control" id="complaints" placeholder="Remarks" name="complaints" required></textarea>
                          <div class="invalid-feedback">
                            Please provide a valid Remarks.
                          </div>
                        </div>
                        <div class="col-md-4 mb-2">
                          <label for="validationCustom02">Service Type</label>
                          <select class="form-control" id="exampleFormControlSelect1" name="servicename">
                            <option value="Grooming">Grooming</option>
                            <option value="De-Worming">De-Worming</option>
                            <option value="Vaccination">Vaccination</option>
                            <option value="Hospitalization">Hospitalization</option>
                            <option value="Checkup">Checkup</option>
                            <option value="Board">Board & Lodging</option>
                          </select>
                        </div>
                        <div class="col-md-4 mb-2">
                          <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch2">
                            <label class="custom-control-label" for="customSwitch2">Schedule</label>
                          </div>
                          <input type="text" class="form-control" id="scheduledate" name="schedule" readonly required>
                          <div class="valid-feedback">
                            Looks good!
                          </div>
                        </div>
                        <div class="col-md-4 mb-2 bootstrap-timepicker timepicker">
                          <label for="scheduledate">Time</label>
                          <input type="text" class="form-control input-small" id="from_time" name="from_time" required>
                          <div class="valid-feedback">
                            Looks good!
                          </div>
                        </div>
                        <div class="col-md-12 mb-2">
                          <label for="staffs">Assigned To</label>
                          <select class="form-control" id="staffs" name="staffid">
                            <option>Staff 1 </option>
                            <option>Staff 2 </option>
                            <option>Staff 3 </option>
                          </select>
                          <div class="valid-feedback">
                            Looks good!
                          </div>
                        </div>
                      </div>
                      <button class="btn btn-primary float-right" type="submit" name="submitappointment" id="saveAppointment">Add Appointment</button>
                    </form>
                  </div>
                  <div class="tab-pane fade" id="nav-sms" role="tabpanel" aria-labelledby="nav-sms-tab">
                    <form action="" method ="POST" class="needs-validation" id="sms_form" novalidate>
                      <div class="form-row">
                        <div class="col-md-5 mb-2">
                          <label for="validationCustom02">Next Schedule Notify</label>
                          <input type="date" class="form-control" id="daysnotif" name="daysnotif" required readonly>
                        </div>
                        <div class="col-md-7 mb-2">
                          <label for="validationCustom02">Owner Number</label>
                          <input type="text" class="form-control input-phone" id="mob_num" name="number" placeholder="9123456789" value="" required readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                          <label for="validationCustom03">SMS Message</label>
                          <textarea type="date" class="form-control" id="validationCustom03" placeholder="Message" name="message" required readonly><?php echo $content_message;?></textarea>
                          <div class="invalid-feedback">
                            Please provide a valid Remarks.
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">

              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>
          </div>
        </div>
      </div>
      <?php wp_list_users(); ?>
      <?php include "page-footer.php"; ?>
      <!-- <script type="text/javascript" src="js/includes/page-dashboard.js"></script> -->
      <script>

                    //query selectors
                    var calendarEl = document.getElementById('calendar');
                    var containerEl = document.getElementById('external-events');
                    var checkbox = document.getElementById('drop-remove');
                    
                    //array for get data from rest
                    let globalData = [];
                    
                    //  array for sending data to rest
                    let passData=[];
                    
                    // array for appointment list from database
                    let appointmentList = [];
                    
                    // manually input 24 hour
                    let timePm=0;
                    let arr = [];
                    for(let i=1;i<25;i++){
                      if(i>12){
                        timePm=i % 12;

                        if(timePm==0){
                          timePm = 12;
                          arr.push(timePm+":00 AM");
                        }else{
                          arr.push(timePm+":00 PM");
                        }

                      }else{
                            // i=i % 12;
                            if(i==12){
                              arr.push(i+":00 PM");
                            }else{
                              arr.push(i+":00 AM");

                            }
                          }
                        }

                    //banner
                    /* Find all slideshow containers */
                    <?php if(count($arrayIMG2) !== 0 ) { ?>
                    var slideshowContainers = document.getElementsByClassName("slideshow-container");
                    /* For each container get starting variables */
                    for(let s = 0; s < slideshowContainers.length; s++) {
                      /* Read the new data attribute */        
                      var cycle = slideshowContainers[s].dataset.cycle;
                      /* Find all the child nodes with class mySlides */
                      var slides = slideshowContainers[s].querySelectorAll('.mySlides');
                      var slideIndex = 0;
                      /* Now we can cycle slides, but this recursive function must have parameters */
                      /* slides and cycle never change, those are unique for each slide container */
                      /* slideIndex will increase during each iteration */
                      showSlides(slides, slideIndex, cycle);
                    };
                    
                    /* Function is alsmost same, but now it uses 3 new parameters */
                    function showSlides(slides, slideIndex, cycle) {
                      for (i = 0; i < slides.length; i++) {
                        slides[i].style.display = "none";
                      };
                      slideIndex++;
                      if (slideIndex > slides.length) {
                        slideIndex = 1
                      };
                      slides[slideIndex - 1].style.display = "block";
                      /* Calling same function, but with new parameters and cycle time */
                      setTimeout(function() {
                        showSlides(slides, slideIndex, cycle)
                      }, 60000);
                    };
                    
                    
                    <?php } ?>
                    
                    // display current date and time
                    let todays = new Date();
                    let monthName = todays.getMonth()+1;
                    let dayName = todays.getDate();
                    let yearName = todays.getFullYear();
                    let hourName = todays.getHours();
                    let hourNameGMT = todays.getHours()-8;
                    let minsName = todays.getMinutes();
                    let secondssName = todays.getSeconds();
                    if(hourName <10){
                      hourName = '0'+hourName;
                    }
                    if(hourNameGMT < 10){
                      hourNameGMT = '0'+hourNameGMT;
                    }
                    if(minsName < 10){
                      minsName = '0'+minsName;
                    }
                    
                    let currentDate = yearName+"-"+monthName+"-"+dayName+" "+hourName+":"+minsName+":"+secondssName ;
                    let currentDateGMT = yearName+"-"+monthName+"-"+dayName+" "+hourNameGMT+":"+minsName+":"+secondssName ;
                    
                    
                    function removeEvent(id){

                      swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this appointment!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                      })
                      .then((willDelete) => {
                        if (willDelete) {
                          fetch(url+'trash/'+id,{
                            method:"DELETE"
                          }).then(res=>{
                            if(res.status==200){
                              calendarEl.innerHTML = '';
                              LoadCalendar();
                              summaryAppointment();
                            }
                          }).catch(e=>{
                          });
                          swal("Poof! Your Appointment has been deleted!", {
                            icon: "success",
                          });
                        }
                      });
                    }
                    
                    
                    
                    function loadOption(){
                      $.ajax({
                        url: url+"appointment/<?php echo $myclinic_id;?>",
                        type: 'get',
                        dataType: 'json',
                        success: function(response) {
                          let unique = [...new Set(response.map(item => item.title))];
                          let ls ='';
                          unique.forEach(e=>{
                            ls+=`<option value="${e}">${e}</option>`;
                          });
                          dropdownListing.innerHTML = ls;
                          $('#dropdownListing').chosen({
                            no_results_text: "Oops, nothing found!"
                          })
                        }
                      });
                    }
                    
                    
                    loadOption();
                    let today = new Date();
                    let month = today.getMonth()+1;
                    let day= today.getDate();
                    let year = today.getFullYear();
                    let clickDates = [];
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                      plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list','bootstrap'],
                      height: 'child',
                      header: {
                        right: 'prev,next today',
                        left: 'title',
                        center: 'dayGridMonth,timeGridDay,listWeek'
                      },
                      defaultView: 'listWeek',
                      themeSystem:'standard',
                      validRange: {
                        start:  moment().format('YYYY-MM-DD'),
                        end: ''
                      },
                        navLinks: true, // can click day/week names to navigate views
                        editable: true,
                        eventLimit: true, // allow "more" link when too many events
                        selectable:true,
                        longPressDelay:0,
                        dateClick:function(info){

                        },
                        select: function(info) {
                          $('#appointmentForm').modal("show");
                          $('#scheduledate').val(info.startStr);
                          if(info.startStr !== moment().format('YYYY-MM-DD')){
                            $('#daysnotif').val(info.startStr);
                            $('#nav-sms-tab').prop('hidden',false);
                          }else{
                            $('#nav-sms-tab').prop('hidden',true);
                          }
                          $('#complaints').trigger("reset");
                        },
                        eventRender: function(info) {
                            // console.log(info);
                            // console.log(view);
                            
                            if (info.view.type == "listWeek"){
                              info.el.children[2].innerHTML = `<b>${info.event.title}</b> : <i>${info.event.extendedProps.description}</i><br>
                              <b>Owner</b> : <i>${info.event.extendedProps.ownername}</i><br>
                              <b>Pet</b> : <i>${info.event.extendedProps.petname}</i>`;
                                // info.el.find('.fc-list-item-title').html(newdisplay);
                                // console.log(info);
                              }
                              $(info.el).tooltip({
                                title:    '<div class="popoverTitleCalendar" style="background-color:'+ info.event.backgroundColor +'; color:'+ info.event.textColor +'">'+ info.event.title +'</div>',
                                content:  '<div class="popoverInfoCalendar">' +
                                '<p><strong>ID:</strong> ' + info.event.id + '</p>' +
                                '<p><strong>Time:</strong> ' + moment(info.event.start).format('HH:mm A') +'-'+  moment(info.event.end).format('HH:mm A') +'</p>' +
                                '</div>',
                                delay: {
                                  show: "50",
                                  hide: "50"
                                },
                                trigger: 'hover',
                                placement: 'right',
                                html: true,
                                container: 'body'
                              });
                              let show_type = true;
                              const selected = document.querySelectorAll('#dropdownListing option:checked');
                              const types = Array.from(selected).map(el => el.value);
                              if (types && types.length > 0) {
                                if (types[0] == "all") {
                                  show_type = true;
                                } else {
                                  show_type = types.indexOf(info.event.title) >= 0;
                                }
                              }
                              return show_type;

                            },
                            eventSources:[{url:url+"appointment/<?php echo $myclinic_id;?>"}],
                            eventTimeFormat:{
                              hour: 'numeric',
                              minute: '2-digit',
                              meridiem: 'short'
                            },
                            eventClick: function(info) {
                              $('#each_appointment').modal("show");
                              appointment_all(info.event.id);
                            },

                          });

calendar.render();
                    //rerender events
                    $('#dropdownListing').on('change',function(x){
                      x.preventDefault();
                      calendar.rerenderEvents();
                        // console.log('ok');
                      });
                    
                    let fetchAllAppointments;
                    
                    document.addEventListener('DOMContentLoaded', function(e) {
                      $('#customSwitch2').click(function(){
                        if($(this).prop('checked')){
                          $('#scheduledate').val(moment().format('YYYY-MM-DD'));
                          $('#nav-sms-tab').prop('hidden',true);
                        }else{
                          $('#scheduledate').val("");
                          $('#nav-sms-tab').prop('hidden',false);
                        }
                      });

                      $('#hidehistory').click(function(){
                        $('#hidehistory').prop('hidden',true);
                        $('#showhistory').prop('hidden',false);
                        $('#history').prop('hidden',true);

                      });
                      $('#showhistory').click(function(){
                        $('#showhistory').prop('hidden',true);
                        $('#hidehistory').prop('hidden',false);
                        $('#history').prop('hidden',false);
                      });

                      $('#from_time').timepicker();
                      $('#history').DataTable();
                      $('#pet-table').DataTable();
                      displayStaff();
                      displaypets();
                      get_appointments_all();
                      smsbalance();

                      var ifConnected = window.navigator.onLine;
                      if(ifConnected){
                        checkNewBanner();
                      }

                    });
                    
                    
                    $("#form_appointments").submit(function( event ) {
                      event.preventDefault();
                      let data = {};
                      let complaints = $('#complaints').val();
                      let petname = $('#pet_list').val();
                      if(complaints !== '' && petname !== null){
                        $( this ).serializeArray().forEach(x=>{
                          data[x.name] = x.value;
                        });

                        $('#sms_form').serializeArray().forEach(x=>{
                          data[x.name] = x.value;
                        });
                        data['clinic_id'] = <?php echo $myclinic_id;?>;
                        $.ajax({
                          url: url+"appointment/",
                          data:data,
                          type: 'post',
                          dataType: 'json',
                          success: function(response) {
                                    // get_appointments_all();
                                    calendar.refetchEvents();
                                    calendar.rerenderEvents();
                                    $('#appointmentForm').modal('hide');
                                    Swal.fire({
                                      position: 'top-end',
                                      icon: 'success',
                                      title: 'New Appointment Added!',
                                      showConfirmButton: false,
                                      timer: 1500
                                    })
                                  }
                                });
                        return false;
                      }

                    });
                    
                    function displayStaff(){
                      let ls="";
                      let data = {
                        clinic_id : <?php echo $myclinic_id;?>
                      }
                      fetch(url+"employees",{
                        method: 'POST',
                        body : JSON.stringify(data)
                      }).then(res=>res.json()).then(res=>{
                        res.forEach(x=>{
                          ls+=`<option value="${x.ID}">${x.name} (${x.role}) </option>`
                        });

                        $('#staffs').html(ls);
                        $('#up_staffs').html(ls);
                      });

                    }
                    function displayPetsName(){
                      let data = {
                        clinic_id : <?php echo $myclinic_id;?>
                      }
                      return fetch(url+"petsdetails",{
                        method: 'POST',
                        body : JSON.stringify(data)
                      });
                    }
                    async function displaypets(){
                      let data = {
                        clinic_id : <?php echo $myclinic_id;?>
                      }
                      let response =await fetch(url+"petsdetails",{method: 'POST',body : JSON.stringify(data)});
                      let pets =await  response.json();
                      let ls ='';
                      pets.forEach(e=>{
                        ls+=`<option value="${e.pet_id}">${e.pet_name} (${e.pet_owner}) </option>`;
                      });
                      $('#pet_list').html(ls);
                      $('#pet_list').chosen({ width:'100%' });
                    }
                    
                    function smsbalance(){
                      let data = {
                        name:"<?php echo $username_sms;?>",
                        password:"<?php echo $password_sms;?>"
                      }
                      $.ajax({
                        url: url+"sms/balance",
                        data:JSON.stringify(data),
                        type: 'POST',
                        dataType: 'json',
                        success: function(response) {
                          let balance = 0;
                          if(response.balance == '-1001 = AUTHENTICATION FAILED' || response.balance == '-1008 = MISSING PARAMETER' ){
                            balance = 0;
                          }else{
                            balance = response.balance;
                          }
                          $('#smsbalance').html(balance);
                        }
                      });
                    }
                    
                    function checkNewBanner(){
                      let newurl = '<?php echo get_site_url();?>/wp-json/vet/v1/';
                      fetch(newurl+'banner').then(res=>res.json()).then(res=>{
                        updateLocalTableBanner(res);
                      });
                    }
                    
                    async function updateLocalTableBanner(data){
                      await  fetch(url+'updateLocalbanner',{
                        method:'POST',
                        body:JSON.stringify(data)
                      }).then(res=>{
                        return res.json();
                      })
                    }
                    
                    
                    async function appointment_all(id){
                      let appointments = [];
                      let ls ='';
                      let data = {
                        clinic_id : <?php echo $myclinic_id;?>
                      }
                      let response =await fetch(url+"petsdetails",{method: 'POST',body : JSON.stringify(data)});
                      let pets = await response.json();
                      fetch(url+"appointment_all").then(res=>res.json()).then(res=>{
                        res.forEach(x=>{
                          if(id == x.appointment_id){
                            ls =`<div class="container-fluid content-body">
                            <nav class="mb-4">
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-details" role="tab" aria-controls="nav-home" aria-selected="true">Details</a>
			    
                            </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-details" role="tabpanel" aria-labelledby="nav-home-tab">
                            <form action="" method ="POST" class="needs-validation" id="update_form_appointment" novalidate>
                            <div class="form-row">
                            <div class="col-md-12 mb-2">
                            <label for="validationCustom01">Name of Pet</label>
                            <input type="hidden" class="form-control" value="${x.appointment_id}" name="up_appointmentID" hidden>
                            <input type="hidden" class="form-control" value="${x.Remarks}" name="up_appointmentRem" hidden>
                            <input type="hidden" class="form-control" value="${x.pet_general_id}" name="up_pet_general_id" hidden>
                            <input type="hidden" class="form-control" value="${x.pet_status_id}" name="up_pet_status_id" hidden>
                            <input type="hidden" class="form-control" value="${x.pet_diagnostics_id}" name="up_pet_diagnostics_id" hidden>
                            <input type="hidden" class="form-control" value="${x.pet_test_id}" name="up_pet_test_id" hidden>
                            <div class="input-group mb-3">
                            <select data-placeholder="Type Pet Name..." class="form-control" name="up_petID" id="up_pet_id">`;
                            pets.forEach(a=>{
                              if(x.pet_id == a.pet_id){
                                ls+=`<option value="${a.pet_id}" selected>${a.pet_name} (${a.pet_owner}) </option>`;
                              }
                              ls+=`<option value="${a.pet_id}">${a.pet_name} (${a.pet_owner}) </option>`;
                            });
                            ls+=`</select>
                            </div>
                            <div class="valid-feedback">
                            Looks good!
                            </div>
                            </div>
                            <div class="col-md-12 mb-3">
                            <label for="validationCustom03">Remarks</label>
                            <textarea type="text" class="form-control" id="validationCustom03" placeholder="Remarks" name="up_complaints" required>${x.complaints}</textarea>
                            <div class="invalid-feedback">
                            Please provide a valid Remarks.
                            </div>
                            </div>
                            <div class="col-md-4 mb-2">
                            <label for="validationCustom02">Service Type</label>
                            <select class="form-control" id="exampleFormControlSelect1" name="up_servicename">`
                            if(x.service_name=="Grooming"){
                              ls+= `<option value="Grooming" selected>Grooming</option>
                              <option value="De-Worming">De-Worming</option>
                              <option value="Vaccination">Vaccination</option>
                              <option value="Hospitalization">Hospitalization</option>
                              <option value="Checkup">Checkup</option>
                              <option value="Board">Board & Lodging</option>`;
                            }else if(x.service_name=="De-Worming"){
                              ls+= `<option value="Grooming">Grooming</option>
                              <option value="De-Worming" selected>De-Worming</option>
                              <option value="Vaccination">Vaccination</option>
                              <option value="Hospitalization">Hospitalization</option>
                              <option value="Checkup">Checkup</option>
                              <option value="Board">Board & Lodging</option>`;
                            }else if(x.service_name=="Vaccination"){
                              ls+= `<option value="Grooming">Grooming</option>
                              <option value="De-Worming">De-Worming</option>
                              <option value="Vaccination" selected>Vaccination</option>
                              <option value="Hospitalization">Hospitalization</option>
                              <option value="Checkup">Checkup</option>
                              <option value="Board">Board & Lodging</option>`;
                            }else if(x.service_name=="Hospitalization"){
                              ls+= `<option value="Grooming">Grooming</option>
                              <option value="De-Worming">De-Worming</option>
                              <option value="Vaccination">Vaccination</option>
                              <option value="Hospitalization" selected>Hospitalization</option>
                              <option value="Checkup">Checkup</option>
                              <option value="Board">Board & Lodging</option>`;
                            }else if(x.service_name=="Checkup"){
                              ls+= `<option value="Grooming">Grooming</option>
                              <option value="De-Worming">De-Worming</option>
                              <option value="Vaccination">Vaccination</option>
                              <option value="Hospitalization">Hospitalization</option>
                              <option value="Checkup" selected>Checkup</option>
                              <option value="Board">Board & Lodging</option>`;
                            }else{
                              ls+= `<option value="Grooming">Grooming</option>
                              <option value="De-Worming">De-Worming</option>
                              <option value="Vaccination">Vaccination</option>
                              <option value="Hospitalization">Hospitalization</option>
                              <option value="Checkup">Checkup</option>
                              <option value="Board" selected>Board & Lodging</option>`;
                            }
                            ls += `
                            </select>
                            </div>
                            <div class="col-md-4 mb-2">
                            <label for="scheduledate">Schedule</label>
                            <input type="date" class="form-control" id="scheduledate" name="up_schedule" value="${x.start_date}" required>
                            <div class="valid-feedback">
                            Looks good!
                            </div>
                            </div>
                            <div class="col-md-4 mb-2 bootstrap-timepicker timepicker">
                            <label for="scheduledate">Time</label>
                            <input type="text" class="form-control input-small" id="from_time" name="up_from_time" value="${x.from_time}" required>
                            <div class="valid-feedback">
                            Looks good!
                            </div>
                            </div>
                            <div class="col-md-12 mb-2">
                            <label for="up_staffs">Assigned To</label>
                            <select class="form-control" id="up_staffs" name="up_staffid" value="${x.staff_id}">`;
                            staffList.forEach(y=>{
                              if(y.ID == x.staff_id ){
                                ls+= `<option value="${y.ID}" selected>${y.name} (${y.role}) </option>`;
                              }else{
                                ls+=`<option value="${y.ID}">${y.name} (${y.role}) </option>`;
                              }

                            });
                            ls+=`</select>
                            <div class="valid-feedback">
                            Looks good!
                            </div>
                            </div>
                            </div>
                            <div class="btn-group-fab float-right" role="group" aria-label="FAB Menu">
                            <div>
                            <a href="<?php echo get_home_url()?>/appointment-details?id=${x.appointment_id}"><button type="button" class="btn btn-main btn-primary has-tooltip" data-placement="left" title="View Details"> <i class="fa fa-eye"></i> View Details </button></a>

                            </div>
                            </div>
                            </form>
                            </div>
                            <div class="tab-pane fade" id="nav-sms2" role="tabpanel" aria-labelledby="nav-sms2-tab">
                            <div class="form-row">
                     
                            <div class="col-md-7 mb-2">
                            <label for="validationCustom02">Owner Number</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend">(+63)</span>
                            </div>
                            <input type="text" class="form-control" id="validationCustom01" placeholder="9123456789" value="" required>
                            </div>
                            </div>
                            <div class="col-md-12 mb-3">
                            <label for="validationCustom03">SMS Message</label>
                            <textarea type="date" class="form-control" id="validationCustom03" placeholder="Message" required></textarea>
                            <div class="invalid-feedback">
                            Please provide a valid Remarks.
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>`;
                          }
                        });
$('#update_app').html(ls);
$('#up_pet_id').chosen({ width:'100%' });
});

}

</script>
<?php include "each_appointment.php";?>
