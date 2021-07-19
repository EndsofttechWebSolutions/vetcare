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

$table=$wpdb->prefix.'vet_appointments';
$ID = $_GET['id'];

$petinfo = getDetails(array($ID,'pet_id','vet_pets'));
foreach ($petinfo as $key ) {
  $owner_id = $key->owner_id;
  $pet_name = $key->pet_name;
  $pet_color = $key->pet_color;
  $pet_breed = $key->pet_breed;
  $pet_gender = $key->pet_gender;
  $pet_bday = $key->pet_birthdate;
  $pet_weight = $key->pet_weight;
  $pet_type = $key->pet_type;

  $age = date_diff(date_create(), date_create($pet_bday));
  $age = $age->format("%Y Year, %M Months, %d Days");
}
$owner_info = getDetails(array($owner_id,'owner_id','vet_owners'));
foreach ($owner_info as $key ) {
  $number =  $key->mobile_no;
}
$passDetails = [];
$past_med = $wpdb->get_results("SELECT * FROM {$table} where pet_id = {$ID}");
foreach ($past_med as $key ) {
  $appointment_id = $key->appointment_id;
  if($key->service_name == "Board"){
    $key->service_name = "Board & Lodging";
  }

}
?>
<style>
  td.highlight {
    background-color: whitesmoke !important;
  }

  #my_camera{
    /*width: 265px;
    height: 240px;*/
    border: 1px solid black;
    object-fit: cover;
  }


  ul.list-group li:nth-child(odd) {
    background: #eee; 
    /*color:white;*/
  }
  .list-group {
    max-height: 300px;
    margin-bottom: 10px;
    overflow: auto;
    overflow-x: hidden;
  }
   @media screen and (min-width: 768px) {
        .modal-dialog {
          /*width: 700px;*/ /* New width for default modal */
        }
        .modal-sm {
          width: 100%!important; /* New width for small modal */
        }
    }
    @media screen and (min-width: 992px) {
        .modal-lg {
         /* width: 1500px!important;*/ /* New width for large modal */
        }
    }

</style>
<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid">

      <h1 class="mt-4"></h1>
      <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo get_site_url();?>/add-owner"> Owners Lists</a></li>
        <li class="breadcrumb-item" id="ownerlink">Owner Details</li>
        <li class="breadcrumb-item active">Pet Details</li>
      </ol>
      <br>
      <div class="row my-2">
        <div class="col-lg-8 order-lg-2">
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a href="" data-target="#pets" data-toggle="tab" class="nav-link active">Appointments</a>
            </li>
            <li class="nav-item">
              <a href="" data-target="#edit" data-toggle="tab" class="nav-link">Edit Profile</a>
            </li>
          </ul>
          <div class="tab-content py-4">
            <div class="tab-pane active" id="pets">

              <div class="row" id="appointment">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover" id="appointmentstable" width="100%" cellspacing="0" style="cursor:pointer">
                      <thead>
                        <tr>
                          <th>Appointment Date</th>
                          <th>Appointment Name</th>
                          <th>Status</th>
                          
                        </tr>
                      </thead>
                      
                    </table>
                  </div>
                </div>
              </div>
              <!--/row-->
            </div>

            <div class="tab-pane" id="edit">
              <div class="row">
                <div class="col-md-8">
                  <form action="" method ="POST" class="needs-validation" id="mypetdetails" novalidate>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label form-control-label">Pet Name</label>
                      <div class="col-lg-9">
                        <input class="form-control" type="text" value="Jane" id="pet_name"  name="up_petName">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label form-control-label">Pet Type</label>
                      <div class="col-lg-9">
                        <select class="custom-select" id="pet_type" name="up_petType">
                          <option value="Dog" selected>Dog</option>
                          <option value="Cat">Cat</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label form-control-label">Breed</label>
                      <div class="col-lg-9">
                        <select data-placeholder="Type Breed..." class="form-control" name="up_petBreed" id="pet_breed">
                        </select>
                        <!-- <input class="form-control" type="text" value="" id="pet_breed" > -->
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label form-control-label">Birthday</label>
                      <div class="col-lg-9">
                        <input class="form-control" type="date" value="" id="pet_birthdate" name="up_petBirthdate">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label form-control-label">Color</label>
                      <div class="col-lg-9">
                        <input class="form-control" type="text" value="" id="pet_color" name="up_petColor">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label form-control-label">Weight</label>
                      <div class="col-lg-9">
                        <input class="form-control" type="text" value="" id="pet_weight" name="up_petWeight">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label form-control-label">Gender</label>
                      <div class="col-lg-9">
                        <input class="form-control" type="text" value="" id="pet_gender" name="up_petGender">
                      </div>
                    </div>
                    <div class="form-group row" hidden>
                      <label class="col-lg-3 col-form-label form-control-label">Owner ID</label>
                      <div class="col-lg-9">
                        <input class="form-control" type="text" value="" id="ownerID" name="up_ownerID">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label form-control-label"></label>
                      <div class="col-lg-9">
                        <input type="reset" class="btn btn-secondary" value="Cancel">
                        <input type="button" class="btn btn-primary" value="Update Changes" onclick="updateMypets()">
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col-md-4">
                  <div class="text-center col-md-12" id="my_camera"></div></hr>
                  <button type="button" id="takephoto" class="btn btn-primary mt-2 col-md-12" name="button" onClick="editPicture()"><i class="fa fa-camera"></i> Take a Photo</button>
                  <button type="button" id="takesnap" class="btn btn-primary mt-2 col-md-12" name="button" onClick="take_snapshot()" hidden><i class="fa fa-camera"></i> Take Snapshot</button>
                  <button type="button" id="retake" class="btn btn-danger mt-2 col-md-12" name="button" onClick="capture_again()" hidden><i class="fa fa-camera"></i> Capture Again</button>
                  
                </div>
              </div>  
              
            </div>
          </div>
        </div>
        <div class="col-lg-4 order-lg-1 text-center">
          <img src="//placehold.it/150" class="mx-auto img-fluid img-circle d-block" alt="avatar" id="mypetpic" height="150" width="150">
          <h6 class="mt-2" id="petname">Pet Name: Raniel Lambaco</h6>
          <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#appointmentForm">
            <i class="fa fa-plus"></i>Add New Appointment
          </button><br>
          <button class="btn btn-outline-info ml-1 mb-2" name="submitappointment" id="quick_view_med">Quick View of Past Medical Services</button>
          <!-- <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addowners" ><i class="glyphicon glyphicon-plus"></i> Add Sub-Owner</button> -->

        </div>
      </div>
    </div>
  </main>
  <div class="modal fade" id="appointmentForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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

                <form action="" method ="POST" class="needs-validation" id="form_appointments" novalidate>
                  <div class="form-row">
                    <div class="col-md-12 mb-2">
                      <label for="searh_pet">Name of Pet</label>
                      <input type="text" class="form-control input-small" id="pet_name_add" readonly>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-12 mb-3">
                      <label for="validationCustom03">Remarks</label>
                      <textarea type="date" class="form-control" id="validationCustom03" placeholder="Remarks" name="complaints" required></textarea>
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
                      <!-- <label for="scheduledate">Schedule</label> -->
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch2">
                        <label class="custom-control-label" for="customSwitch2">Schedule</label>
                      </div>
                      <input type="date" class="form-control" id="scheduledate" name="schedule" required>
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
                        <option>Karl</option>
                        <option>Niel</option>
                        <option>Jorsen</option>
                      </select>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                  </div>

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
                      <input type="text" class="form-control input-phone" id="mob_num" name="number" placeholder="9123456789" value="<?php echo $number;?>" required>
                    </div>
                    <div class="col-md-12 mb-3">
                      <label for="validationCustom03">SMS Message</label>
                      <textarea type="date" class="form-control" id="validationCustom03" placeholder="Message" name="message" required><?php echo $content_message;?></textarea>
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
          <button class="btn btn-primary float-right" type="button" name="submitappointment" id="saveAppointment">Add Appointment</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="past_med" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog  modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Past Medical Services</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <ul class="list-group">
            <?php
            global $wpdb;
            $past_med = $wpdb->get_results("SELECT * FROM {$table} where pet_id = {$ID} AND service_name != 'Grooming' order by start_date DESC ");
            $arrayData = array();
            if(count($past_med) === 0){
              ?>
              <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                  No Results Found!
                </div>
              </div>  
              <?php
            }else{
              foreach ($past_med as $key ) {
                $appointment_id = $key->appointment_id;
                if($key->service_name == "Board"){
                  $key->service_name = "Board & Lodging";
                }
                ?>
                <li class="list-group-item " style="margin: 10px;">
                  <h1 style="font-weight: bold"><a href="<?php echo get_site_url()?>/appointment-details?id=<?php echo $appointment_id; ?>" style="text-decoration: none;color:black;font-size: 35px;"><?php echo $key->service_name;?></a></h1>
                  <small class="mb-5" style="color:red;"><?php echo date('l jS \of F Y',strtotime($key->start_date));?></small>
                  <div class="row">
                    <div class="col-md-8">
                      
                      <?php
                      global $wpdb;
                      $generaltable=$wpdb->prefix.'vet_pet_general';
                      $generallist = $wpdb->get_results("SELECT * FROM {$generaltable} WHERE appointment_id = ".$appointment_id." ");
                      $arrayData = array();
                      $show = true; 
                      ?>

                      <?php foreach ($generallist as $skey ) {
                        if($skey->temperature !== ""){
                          $show = true;
                        }else{
                          $show = false;
                        }
                      } 
                      if($show == true){
                        ?>

                        <h4 style="font-weight: bold;color:red">General</h5>

                          <?php
                          foreach ($generallist as $skey ) {
                             if($skey->temperature !== ""){
                            ?>
                            <p class="mb-1" >Temperature : <?php echo $skey->temperature; ?> </p>
                            <p class="mb-1" >Weight : <?php echo $skey->weight; ?> </p>
                          <?php }
                          }
                        } ?>


                        <?php
                        global $wpdb;
                        $testtable=$wpdb->prefix.'vet_pet_test';
                        $testlist = $wpdb->get_results("SELECT * FROM {$testtable} WHERE appointment_id = ".$appointment_id." group by meta_key");
                        $arrayData = array();
                        ?>
                        <?php if(count($testlist) > 0){ ?>
                          <hr>
                          <h4 style="font-weight: bold;color:red">Test</h5>
                          <?php } ?>
                          <?php
                          foreach ($testlist as $skey ) {
                            ?>
                            <p class="mb-1" ><?php echo $skey->meta_key; ?> : <?php echo $skey->meta_value; ?> </p>
                          <?php } ?>
                          

                          <?php
                          global $wpdb;
                          $statustable=$wpdb->prefix.'vet_pet_status';
                          $statuslist = $wpdb->get_results("SELECT * FROM {$statustable} WHERE appointment_id = ".$appointment_id." group by meta_key");
                          $arrayData = array();
                          ?>
                          <?php if(count($statuslist) > 0){ ?>
                            <hr>
                            <h4 style="font-weight: bold;color:red">Status</h5>
                            <?php } ?>
                            <?php
                            foreach ($statuslist as $skey ) {
                              ?>
                              <p class="mb-1" ><?php echo $skey->meta_key; ?> : <?php echo $skey->meta_value; ?></p>
                            <?php } ?>
                            

                            <?php
                            global $wpdb;
                            $statustable=$wpdb->prefix.'vet_pet_diagnostics';
                            $statuslist = $wpdb->get_results("SELECT * FROM {$statustable} WHERE appointment_id = ".$appointment_id." ");
                            $arrayData = array();
                            $show = true;
                            ?>
                            <?php 
                            foreach ($statuslist as $skey ) {
                              if($skey->procedure_done !== ""){
                                $show = true;
                              }else{
                                $show = false;
                              }
                            }
                            if($show == true){ ?>
                              <hr>  
                              <h4 style="font-weight: bold;color:red">Diagnostics</h5>

                                <?php
                                foreach ($statuslist as $skey ) {
                                  if($skey->procedure_done == ""){
                                    $show = false;
                                  }
                                  ?>
                                  <p class="mb-1">Procedure : <?php echo $skey->procedure_done; ?></p>
                                  <p class="mb-1">Tentative : <?php echo $skey->tentative; ?></p>
                                  <p class="mb-1">Medication : <?php echo $skey->medication; ?></p>
                                  <p class="mb-1">Prescription : <?php echo $skey->prescription; ?></p>
                                <?php }
                              } ?>
                            </div>  
                            <div class="col-md-4">
                              <?php if ($key->notes !== "" ): ?>
                                <h4 style="font-weight: bold;color:red">Notes</h5>
                                  <span><?php echo $key->notes; ?></span>
                                <?php endif ?>
                                
                                

                                <?php
                                global $wpdb;
                                $attachtb=$wpdb->prefix.'vet_pet_attachments';
                                $upload_dir = wp_upload_dir();
                                $results = $wpdb->get_results("SELECT * FROM {$attachtb} WHERE appointment_id = ".$appointment_id." ");
                                ?>
                                <?php if(count($results) > 0){ ?>
                                  <hr>
                                  <h4 style="font-weight: bold;color:red">Attachments</h5>
                                  <?php } ?>
                                  <?php
                                  foreach ($results as $skey ) {
                                    $location = $upload_dir['baseurl'] .'/'. $skey->uploaded_file;
                                    echo "<span><a href=".$location." target='_blank'>".$skey->uploaded_file."</a></span> <br>";
                                  }
                                  ?>

                                </div>  
                              </div>
                            </li>

                            <?php 
                          }
                        } 
                        ?>

                      </ul>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Add Sub-Owner -->
              <div class="modal fade" id="addowners" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Fill out Details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-sm-3"><!--left col-->
                          <div class="text-center">
                            <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" class="avatar img-circle img-thumbnail" alt="avatar">

                          </div></hr>
                        </div><!--/col-3-->
                        <div class="col-sm-9">
                          <div class="tab-content">
                            <div class="tab-pane active" id="home">
                              <hr>
                              <form action="" method="POST" class="needs-validation" id="newownerdetails" novalidate>
                                <input type="file" class="text-center center-block file-upload" name="uploader" id="uploader"
                                accept="image/*"
                                capture="camera">
                                <div class="form-row">
                                  <div class="col-md-6 mb-2">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" placeholder="Last Name" value="" name="lastName" required>
                                  </div>
                                  <div class="col-md-6 mb-2">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" placeholder="Last Name" value="" name="firstName" required>
                                  </div>
                                  <div class="col-md-12 mb-2">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" placeholder="Address" value="" name="address" required>
                                  </div>
                                  <div class="col-md-6 mb-2">
                                    <label for="gender">Gender</label>
                                    <select class="custom-select" id="gender" name="gender">
                                      <option value="Male">Male</option>
                                      <option value="Female">Female</option>
                                    </select>
                                  </div>
                                  <div class="col-md-6 mb-2">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Email" value="" name="email" required>
                                  </div>
                                  <div class="col-md-6 mb-2">
                                    <label for="number">Mobile No.</label>
                                    <input type="number" class="form-control" id="number" placeholder="Mobile Number" value="" name="mobileNumber" required>
                                  </div>
                                  <div class="col-md-6 mb-2">
                                    <label for="landline_number">Landline No.</label>
                                    <input type="number" class="form-control" id="landline_number" placeholder="Landline Number" value="" name="landlineNumber" required>
                                  </div>
                                  <div class="col-md-6 mb-2">
                                    <label for="landline_number">Birthdate</label>
                                    <input type="date" class="form-control" id="birthdate" placeholder="Birthdate" value="" name="birthDate" required>
                                  </div>

                                </div>
                                <button type="button" class="btn btn-primary float-right" name="addownerbutton" id="addownerbutton" onclick="addowner()"><i class="fas fa-plus"></i> Add Owner</button>
                              </form>

                              <hr>

                            </div><!--/tab-pane-->
                          </div><!--/tab-content-->

                        </div><!--/col-9-->
                      </div><!--/row-->
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php include "page-footer.php"; ?>
              <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/dog-breed.js"></script>
              <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/cat-breed.js"></script>
              <script>
                let image_data ='';
                let id = <?php echo $_GET['id']; ?>;
                pets = [];
                $(document).ready(function(){

                  let appointmentstable= $('#appointmentstable').DataTable( {
                    ajax: url+'appointmentInTable/<?php echo $ID;?>',
		    "order": [],
                    "columns": [
                    { "data": "date" },
                    { "data": "service" },
                    { "data": "remarks" }
                    ]
                  });
                  $('#appointmentstable tbody').on('click', 'tr', function () {
                    var data = appointmentstable.row( this ).data();
                    location.assign('<?php echo get_site_url()?>/appointment-details?id='+data.appointment_id);
                  } );

                  let pet = [...new Set(dogs.map(item => item.name))];
                  $('#pet_type').on('change',function(){
                    pet = [...new Set(dogs.map(item => item.name))];
                    if("<?php echo $pet_type; ?>"=="Dog"){
                      pet = [];
                      pet = [...new Set(dogs.map(item => item.name))];
                    }else{
                      pet = [];
                      pet = [...new Set(cats.map(item => item.name))];
                    }

                    let ls ='';
                    pet.forEach(e=>{
                      ls+=`<option value="${e}">${e}</option>`;
                    });
                    $('#pet_breed').html(ls);
                    $('#pet_breed').trigger("chosen:updated");
                  });
                  let ls ='';
                  pet.forEach(e=>{
                    if(e == "<?php echo $pet_breed; ?>"){
                      ls+=`<option value="${e}" selected>${e}</option>`;
                    }else{
                      ls+=`<option value="${e}">${e}</option>`;
                    }

                  });
                  $('#pet_breed').html(ls);
                  $('#pet_breed').chosen({ width:'100%' });
                  
      // $('#dataTable tbody').on( 'mouseenter', 'td', function () {
      //  var colIdx = table.cell(this).index().column;

      //  $( table.cells().nodes() ).removeClass( 'highlight' );
      //  $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
      // } );

      $('#quick_view_med').on('click',function(){
        $('#past_med').modal("show");
      });
      $('#past_med_table').DataTable();
      $('#from_time').timepicker();
      getmypetdetails(id);
      displayStaff();
      //Swithc Notif
      $('#scheduledate').change(function(){
        $('#daysnotif').val($('#scheduledate').val());
      });


      $('#customSwitch2').click(function(){
        if($(this).prop('checked')){
          $('#scheduledate').val(moment().format('YYYY-MM-DD'));
          $('#nav-sms-tab').prop('hidden',true);
        }else{
          $('#scheduledate').val("");
          $('#nav-sms-tab').prop('hidden',false);
        }
      });
        //Save Appointment
        $("#saveAppointment").click(function( event ) {
          event.preventDefault();
          let data = {};
          data['petID'] = id;
          $('#form_appointments').serializeArray().forEach(x=>{
            data[x.name] = x.value;
          });
          data['clinic_id'] = <?php echo $myclinic_id;?>;
          $('#sms_form').serializeArray().forEach(x=>{
            data[x.name] = x.value;
          });
          // console.log(data);
          $.ajax({
            url: url+"appointment/",
            data:data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
              if(response.message=="Data Created"){
                $('#appointmentForm').modal('hide');
                Swal.fire({
                  position: 'top-end',
                  icon: 'success',
                  title: 'New Appointment Added!',
                  showConfirmButton: false,
                  timer: 1500
                })
                location.assign("<?php echo get_site_url();?>/appointment-details/?id="+response.id);
              }

            }
          });
          return false;
      });

      })

                function getmypetdetails(id){
                  fetch(url+'mypetsdetails/'+id).then(res=>res.json()).then(res=>{
                       if(res[0].pet_image == ""){
                        res[0].pet_image = "<?php echo get_site_url();?>/wp-content/uploads/2020/03/vet-app-icon-removebg-preview-1.png";
                      }
        // console.log(res);
        $('#petname').html("Pet Name: "+res[0].pet_name);
        $('#pet_name_add').val(res[0].pet_name);
        $('#pet_name').val(res[0].pet_name);
        $('#pet_type').val(res[0].pet_type);
        // $('#pet_breed').val(res[0].pet_breed);
        $('#pet_birthdate').val(res[0].pet_birthdate);
        $('#pet_color').val(res[0].pet_color);
        $('#pet_weight').val(res[0].pet_weight);
        $('#pet_gender').val(res[0].pet_gender);
        $('#ownerID').val(res[0].owner_id);
        $('#mypetpic').attr('src',res[0].pet_image);
        $('#ownerlink').html('<a href="<?php echo get_site_url();?>/owners-details?id='+res[0].owner_id+'">Owner Details</a>');
        $('#my_camera').html('<img src="'+res[0].pet_image+'" class="avatar img-thumbnail" alt="avatar" id="thumbnail">');
        // console.log(res[0].pet_image);
        image_data = res[0].pet_image;


        fetch(url+'owner/'+res[0].owner_id).then(resx=>resx.json()).then(resx=>{
          if(resx[0].pendings !== ""){
            Swal.fire(resx[0].pendings);
          }
        });
        // console.log(pet);
      });
                }
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
                function addowner(){
                  let newownerdetails = {};
                  $('#newownerdetails').serializeArray().forEach(x=>{
                    newownerdetails[x.name] = x.value;
                  });
                  newownerdetails['role'] = "Sub-Owner";
                  console.log(newownerdetails)
                  fetch(url+'owners',{
                    method:"POST",
                    body: JSON.stringify(newownerdetails)
                  }).then(res=>res.json()).then(res=>{
        // console.log(res);
        if(res.d=="Success"){
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Your work has been saved',
            showConfirmButton: false,
            timer: 1500
          });
          location.assign("<?php echo get_site_url();?>/owners-details?id="+res.id);
        }
      });
                }
                function updateMypets(){

                  let mypetdetails = {};
                  $('#mypetdetails').serializeArray().forEach(x=>{
                    mypetdetails[x.name] = x.value;
                  });
                  mypetdetails['up_pet_id'] = id;
                  mypetdetails['up_pet_image'] = image_data;
    // console.log(mydetails);

    $.ajax({
      url: url+"pets/",
      data:mypetdetails,
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if(response.message=="OK"){
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Pet Details Updated!',
            showConfirmButton: false,
            timer: 1500
          });
          location.reload();
        }
        
      }
    });
}
let gotoPetPage = (id) =>{
  location.assign('<?php echo get_site_url();?>/appointment-details?id='+id);
}
function editPicture(){
  Webcam.set({
    width: 265,
    height: 240,
    image_format: 'jpeg',
    jpeg_quality: 100,
    force_flash:false,
    flip_horiz:false,
    fps:45
  });
  Webcam.attach( '#my_camera' );
  $('#takesnap').prop('hidden',false);
  $('#retake').prop('hidden',true);
  $('#takephoto').prop('hidden',true);
}
function take_snapshot(){
  Webcam.snap( function(data_uri) {
    // display results in page
    Webcam.freeze();
    Webcam.reset();
    image_data = data_uri;
    $('#my_camera').html('<img src="'+data_uri+'" class="avatar img-thumbnail" alt="avatar" id="thumbnail">');
  });
    // console.log(image_data);
    
    $('#takesnap').prop('hidden',true);
    $('#retake').prop('hidden',false);
    $('#takephoto').prop('hidden',true);
}
function capture_again(){
  Webcam.set({
    width: 265,
    height: 240,
    image_format: 'jpeg',
    jpeg_quality: 100,
    force_flash:false,
    flip_horiz:false,
    fps:45
  });
  Webcam.attach( '#my_camera' );
  $('#takesnap').prop('hidden',false);
  $('#retake').prop('hidden',true);

}
</script>
