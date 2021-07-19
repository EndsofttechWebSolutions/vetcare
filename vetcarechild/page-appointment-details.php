<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>

<?php
global $wpdb,$post, $current_user; wp_get_current_user();
$table=$wpdb->prefix.'vet_appointments';
$ID = $_GET['id'];
$results = $wpdb->get_results("SELECT * FROM {$table} where appointment_id = {$ID}");
$arrayData = array();
foreach ($results as $key ) {
  if($key->service_name == "Board"){
    $key->service_name = "Board & Lodging";
  }
  $service_name =$key->service_name;
  $start = $key->start_date;
  $end = $key->end_date;
  $time = $key->from_time;
  $complaints = $key->complaints;
  $remarks = $key->Remarks;
  $pet_id = $key->pet_id;
  $staff_id = $key->emp_id;
  $general_id = $key->pet_general_id;
  $status_id = $key->pet_status_id;
  $test_id = $key->pet_test_id;
  $diag_id = $key->pet_diagnostics_id;
  $sms_id = $key->pet_sms_id;
  $notes = $key->notes;
}


$user = get_userdata(get_current_user_id());
$user_roles = $user->roles;
if(!empty($user_roles)){
  if (in_array( 'um_doctors', $user_roles, true ) || in_array( 'um_groomers', $user_roles, true ) ) {
    $myclinic_id =  get_user_meta($staff_id,'clinic_id',true);
  }else if(in_array( 'um_clinic', $user_roles, true )){
    $myclinic_id = get_current_user_id();
  }else{
    $myclinic_id = get_current_user_id();
  }
}
// var_dump($myclinic_id);
$petinfo = getDetails(array($pet_id,'pet_id','vet_pets'));
foreach ($petinfo as $key ) {
  $owner_id = $key->owner_id;
  $pet_name = $key->pet_name;
  $pet_color = $key->pet_color;
  $pet_breed = $key->pet_breed;
  $pet_gender = $key->pet_gender;
  $pet_bday = $key->pet_birthdate;
  $pet_weight = $key->pet_weight;

  $age = date_diff(date_create(), date_create($pet_bday));
  $age = $age->format("%Y Year, %M Months, %d Days");
}
$last = $wpdb->get_results("SELECT * FROM {$table} where pet_id = {$pet_id} and appointment_id != {$ID} AND end_date < '{$end}' AND end_date <> '' LIMIT 1");
foreach($last as $key){
  if($key->service_name == "Board"){
    $key->service_name = "Board & Lodging";
  }
  $last_service = $key->service_name;
  $last_service_date = date("l jS \of F Y",strtotime($key->end_date));
}

$general_info = getDetails(array($general_id,'general_id','vet_pet_general'));
foreach ($general_info as $key ) {
  $medication_owner = $key->medication_owner;
  $vaccine_given = $key->vaccine_given;
  $allergies = $key->allergies;
  $weight = $key->weight;
  $tempe = $key->temperature;
}

$diagnostics_info = getDetails(array($diag_id,'diagnostic_id','vet_pet_diagnostics'));
foreach ($diagnostics_info as $key ) {
  $procedure = $key->procedure_done;
  $tentative = $key->tentative;
  $medication = $key->medication;
  $prescription = $key->prescription;
}

$owner_info = getDetails(array($owner_id,'owner_id','vet_owners'));
foreach ($owner_info as $key ) {
  $owner_name = $key->first_name.' '.$key->last_name;
  $number =  $key->mobile_no;
}
$sms_info = getDetails(array($sms_id,'sms_id','vet_sms'));
foreach ($sms_info as $key ) {
  $days_notif = $key->date_to_send;
  $message = $key->message;
}

$clinicinfo = getDetails(array(1,'clinic_id','vet_clinic'));
foreach ($clinicinfo as $key ) {
  $mobile_number = $key->mobile_number;
  $landline = $key->landline;
}
if(strpos($content_message,'{firstname}') !== false) {
  $content_message = str_replace('{firstname}', $owner_name, $content_message);
}
if(strpos($content_message,'{service}') !== false) {
  $content_message = str_replace('{service}', $service_name, $content_message);
}
if(strpos($content_message,'{daysnotif}') !== false) {
  $content_message = str_replace('{daysnotif}', $start, $content_message);
}
if(strpos($content_message,'{pet_name}') !== false) {
  $content_message = str_replace('{pet_name}', $pet_name, $content_message);
}
if(strpos($content_message,'{contact_number}') !== false) {
  $content_message = str_replace('{contact_number}', $mobile_number, $content_message);
}
if(strpos($number,' ') !== false) {
  $number = str_replace(' ','', $number);
}

$taxes = getDetails(array($myclinic_id,'clinic_id','vet_tax_fields'));
$coupons = getDetails(array($myclinic_id,'clinic_id','vet_coupon'));

$bg = '';
if($remarks == 'Upcoming'){
    $bg = 'style="background:yellow;"';
}else if($remarks == 'Absent'){
    $bg = 'style="background:red;color:white"';
}else{
     $bg = 'style="background:green;color:white"';
}
?>
<style>
  .files:before {
    position: absolute;
    bottom: 10px;
    left: 0;  pointer-events: none;
    width: 100%;
    right: 0;
    height: 60px;

    display: block;
    margin: 0 auto;
    color: #2ea591;
    font-weight: 600;
    text-transform: capitalize;
    text-align: center;
  }
  textarea#mycontent {
    padding-top:10px;
    padding-bottom:25px; /* increased! */
    width:100%; /* changed from 96 to 100% */
    display:block;
  }
  .dropzone {
    background: white;
    border-radius: 5px;
    border: 2px dashed rgb(0, 135, 247);
    border-image: none;
    max-width: inherit;
    margin-left: auto;
    margin-right: auto;
  }
  /*Next Schedule*/
  input#scheduledate {
    margin-top: 7px!important;
  }
  button#delete_eachAppointment {
    margin-top: 10px!important;
  }
  /*Date*/
  .bootstrap-timepicker-widget table td input {
    width: 32px!important;
    margin: 0;
    text-align: center;
  }
  /*Attachments*/
  /*button#savesnapshot {*/
    /*  margin-left: 420px!Important;*/
    /*  position: absolute;*/
    /*}*/
    ul.list-group li:nth-child(odd) {
      background: #eee; 
      /*color:white;*/
    }
    .list-group{
      overflow-x: hidden;
    }
    @media only screen and (max-width: 768px) {
      #signature-pad{
        width: 550px;
        margin-left: 16px;
        margin-top: 5px;
      }
    }
    @media only screen and (max-width: 1024px) {
      #signature-pad{
        margin-top: 5px;
        margin-left: 46px;
      }
    }

    @media only screen and (max-width: 1920px) {
      #signature-pad{
        width: 80%;
        margin-left: 120px;
        margin-top: 5px;
      }
    }
    ul.typeahead__list {
        background: #F2F6FC;
    }


  </style>

  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid">

        <h3 class="mt-4">Appoinments</h3>
        <ol class="breadcrumb mb-4">
          <li class="breadcrumb-item "><a href="<?php echo get_home_url()?>/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item "><a href="<?php echo get_home_url()?>/owners-details/?id=<?php echo $owner_id; ?>"><?php echo $owner_name; ?></a></li>
          <li class="breadcrumb-item "><a href="<?php echo get_home_url()?>/pet-details/?id=<?php echo $pet_id; ?>"><?php echo $pet_name; ?></a></li>
          <li class="breadcrumb-item active">Appointment Details</li>
        </ol>
        <div class="card">
          <div class="card-header">
            <h5><?php echo $service_name; ?> | <?php echo date('l jS \of F Y',strtotime($start));?> @ <?php echo $time;?></h5>
            <small>Click on each tab to input findings, upload attachments and schedule next appointment</small>
            <button class="btn btn-outline-info float-right ml-1 mb-2" name="submitappointment" id="quick_view_med">Quick View of Past Medical Services</button>
            <div class="col-md-3 mt-3">
              <div class="input-group" id="remarksdiv">
                <div class="input-group-prepend">
                  <label class="input-group-text" for="inputGroupSelect01">Status</label>
                </div>
                <select class="custom-select" id="remarks" <?php echo $bg;?> >
                  <option selected disabled> Choose...</option>
                  <option value="Upcoming" <?php echo ($remarks == "Upcoming" ) ? 'selected':''; ?>>Upcoming</option>
                  <option value="Absent" <?php echo ($remarks == "Absent" ) ? 'selected':''; ?>>Absent</option>
                  <option value="Completed" <?php echo ($remarks == "Completed" ) ? 'selected':''; ?>>Completed</option>
                </select>
              </div>
            </div>

          </div>
          <div class="card-body">

            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
              <li class="nav-item" hidden>
                <a class="nav-link " id="pet-tab" data-toggle="tab" href="#petinfo" role="tab" aria-controls="petinfo" aria-selected="false"><i class="fa fa-paw"></i> Pet Info</a>
              </li>
              <?php if($service_name!="Vaccination" && $service_name!="De-Worming" && $service_name!="Grooming"){?>
                <li class="nav-item">
                  <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><i class="fa fa-id-card"></i> General </a>
                </li>
              <?php }?>
              <?php if($service_name!="Grooming"){?>
                <li class="nav-item">
                  <a class="nav-link" id="status-tab" data-toggle="tab" href="#status" role="tab" aria-controls="status" aria-selected="false"><i class="fa fa-info"></i> Status</a>
                </li>
              <?php }?>
              <?php if($service_name!="Vaccination" && $service_name!="De-Worming" && $service_name!="Grooming"){?>
                <li class="nav-item">
                  <a class="nav-link" id="test-tab" data-toggle="tab" href="#test" role="tab" aria-controls="test" aria-selected="false"><i class="fa fa-vials"></i> Test</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="diag-tab" data-toggle="tab" href="#diag" role="tab" aria-controls="diag" aria-selected="false"><i class="fa fa-file-medical-alt"></i> Diagnostics</a>
                </li>
              <?php } ?>

              <li class="nav-item">
                <a class="nav-link" id="attach-tab" data-toggle="tab" href="#attach" role="tab" aria-controls="attach" aria-selected="false"><i class="fa fa-paperclip"></i> Attachmments</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false"><i class="fa fa-sticky-note-o"></i> Notes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="next-tab" data-toggle="tab" href="#next" role="tab" aria-controls="next" aria-selected="false"><i class="fa fa-clock"></i> Next Schedule</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="sms-tab" data-toggle="tab" href="#sms" role="tab" aria-controls="sms" aria-selected="false"><i class="fa fa-sms"></i> SMS Reminder</a>
              </li>
              <?php if(get_site_url() == 'https://vaxilifecorp.com'){ ?>
                <li class="nav-item">
                  <a class="nav-link" id="invoice-tab" data-toggle="tab" href="#invoice" role="tab" aria-controls="sms" aria-selected="false"><i class="fa fa-file-invoice"></i> Invoice</a>
                </li>
              <?php } ?>

            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade " id="petinfo" role="tabpanel" aria-labelledby="pet-tab" hidden>
                <form action="" method ="POST" class="needs-validation" id="pet_form" novalidate>
                  <div class="form-row">
                    <div class="col-md-4 mb-2">
                      <label for="validationCustom01">Name of Pet</label>
                      <input type="hidden" class="form-control" id="pet_id" placeholder="Pet Name" value="<?php echo $pet_id;?>" hidden>
                      <input type="hidden" class="form-control" id="owner_id" placeholder="Pet Name" value="<?php echo $owner_id;?>" hidden>
                      <input type="text" class="form-control" id="validationCustom01" placeholder="Pet Name" value="<?php echo $pet_name;?>" name="mypet_name" required>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="validationCustom03">Color</label>
                      <input type="text" class="form-control" id="validationCustom01" placeholder="Color" value="<?php echo $pet_color;?>" name="mypet_color" required>
                      <div class="invalid-feedback">
                        Please provide a valid Remarks.
                      </div>
                    </div>
                    <div class="col-md-4 mb-2">
                      <label for="validationCustom02">Breed</label>
                      <input type="text" class="form-control" id="validationCustom01" placeholder="Breed" value="<?php echo $pet_breed;?>" name="mypet_breed" required>
                      <div class="invalid-feedback">
                        Please provide a valid Remarks.
                      </div>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="validationCustom03"> <?php echo $service_name;?> Remarks</label>
                      <textarea type="date" class="form-control" id="validationCustom03" placeholder="Remarks" name="pet_remarks" required><?php echo $complaints;?></textarea>
                      <div class="invalid-feedback">
                        Please provide a valid Remarks.
                      </div>
                    </div>
                    <div class="col-md-4 mb-2">
                      <label for="validationCustom02">Gender</label>
                      <input type="text" class="form-control" id="validationCustom01" placeholder="Breed" value="<?php echo $pet_gender;?>" name="mypet_gender" required>
                      <div class="invalid-feedback">
                        Please provide a valid Remarks.
                      </div>
                    </div>
                    <div class="col-md-4 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Birthday</label>
                      <input type="text" class="form-control input-small" id="from_time"  value="<?php echo $age;?>" name="mypet_bday" required>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>

                    <div class="col-md-6 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Last Service</label>
                      <input type="text" class="form-control input-small" id="from_time" value="<?php echo $last_service; ?>" required readonly>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-6 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Last Service Date</label>
                      <input type="text" class="form-control input-small" id="from_time" value="<?php echo $last_service_date; ?>" required readonly>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                  </div>
                  <!-- <button class="btn btn-primary float-right" type="button" name="submitappointment" id="savepet">Save Changes</button> -->
                </form>
              </div>
              <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <form action="" method ="POST" class="needs-validation" id="general_form" novalidate >
                  <div class="form-row">
                    <div class="col-md-4 mb-2 bootstrap-timepicker">
                      <label for="scheduledate">Appointment Schedule</label>
                      <input type="hidden" class="form-control input-small" id="from_time" name="general_id" value="<?php echo $general_id;?>" hidden >
                      <input type="hidden" class="form-control input-small" name="appointment_id" id="appointment_id" value="<?php echo $ID;?>" hidden >

                      <input type="date" class="form-control input-small" id="from_time" value="<?php echo $start;?>" required readonly>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-4 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Time</label>
                      <input type="text" class="form-control input-small" id="from_time" value="<?php echo $time;?>" required readonly>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-6 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Complaints / Request</label>
                      <textarea type="date" class="form-control" id="validationCustom03" name="complaints" required><?php echo $complaints;?></textarea>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-6 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Medication given by the owner</label>
                      <textarea type="date" class="form-control" id="validationCustom03" name="medication_given" required><?php echo $medication_owner;?></textarea>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-6 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Vaccine Given</label>
                      <textarea type="date" class="form-control" id="validationCustom03" name="vaccine_given" required><?php echo $vaccine_given;?></textarea>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-6 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Allergies</label>
                      <textarea type="date" class="form-control" id="validationCustom03" name="allergies" required><?php echo $allergies;?></textarea>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-6 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Weight</label>
                      <input type="text" class="form-control input-small" id="from_time" name="weight" value="<?php echo $weight;?>" required>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-6 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Temperature</label>
                      <input type="text" class="form-control input-small" id="temperature" name="temperature" value="<?php echo $tempe;?>" required>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                  </div>

                  <!-- <button class="btn btn-primary float-right" type="button" name="submitgeneral" id="savegeneral">Save Changes</button> -->
                </form>
              </div>
              <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="status-tab">
                <div class="card">
                  <div class="card-body">
                    <label for="status_fields">Status Test Fields</label>
                    <form id="form-hockey_v2" name="form-hockey_v2">
                      <div class="typeahead__container">
                        <div class="typeahead__field">
                          <div class="typeahead__query">
                            <input  id="statusfields" class="js-typeahead-hockey_v2" name="hockey_v2[query]" autocomplete="off">
                          </div>

                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <form action="" method ="POST" class="needs-validation" id="status_form" novalidate>
                  <div class="form-row" id="status-row">
                  </div>

                  <!-- <button class="btn btn-primary float-right" type="button" name="submitstatus" id="savestatus" onclick="savedata()">Save Changes</button> -->
                </form>
              </div>
              <div class="tab-pane fade" id="test" role="tabpanel" aria-labelledby="test-tab">
                <div class="card">
                  <div class="card-body">
                    <label for="status_fields">Select Test Fields</label>

                    <form id="form-hockey_v2" name="form-hockey_v2">
                      <div class="typeahead__container">
                        <div class="typeahead__field">
                          <div class="typeahead__query">
                            <input  id="testfields" class="js-typeahead-hockey_v2" name="hockey_v2[query]" autocomplete="off">
                          </div>

                        </div>
                      </div>
                    </form>

                  </div>
                </div>
                <form action="" method ="POST" class="needs-validation" id="test_form" novalidate>
                  <div class="form-row" id="test-row">

                  </div>

                  <!-- <button class="btn btn-primary float-right" type="button" name="submittest" id="savetest">Save Changes</button> -->
                </form>
              </div>
              <div class="tab-pane fade" id="diag" role="tabpanel" aria-labelledby="diag-tab">
                <form action="" method ="POST" class="needs-validation" id="diag_form" novalidate>
                  <div class="form-row">
                    <div class="col-md-12 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Procedure Done</label>
                      <input type="hidden" class="form-control input-small" name="diag_id" value="<?php echo $diag_id;?>" hidden >
                      <input type="hidden" class="form-control input-small" name="appointment_id" value="<?php echo $ID;?>" hidden >

                      <textarea type="date" class="form-control" id="validationCustom03" placeholder="Proedure Done" name="procedure" required><?php echo $procedure;?></textarea>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-12 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Tentative Diagnostics</label>
                      <textarea type="date" class="form-control" id="validationCustom03" placeholder="Tentative" name="tentative" required><?php echo $tentative;?></textarea>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-12 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Medication Given</label>
                      <textarea type="date" class="form-control" id="validationCustom03" placeholder="Medication Given" name="medication" required><?php echo $medication;?></textarea>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-12 mb-2 bootstrap-timepicker timepicker">
                      <label for="scheduledate">Prescribed Medication</label>
                      <textarea type="date" class="form-control" id="validationCustom03" placeholder="Prescription" name="prescription" required><?php echo $prescription;?></textarea>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                  </div>

                  <!-- <button class="btn btn-primary float-right" type="button" name="submitdiagnostics" id="savediag">Save Changes</button> -->
                </form>
              </div>
              <div class="tab-pane fade" id="attach" role="tabpanel" aria-labelledby="attach-tab">
                <input type="hidden" name="signature_data" id="imageData" hidden>
                <div class="row">
                  <div class="col-md-6">
                    <div id="actions" class="row">
                      <div class="col-md-12">
                        <div class="float-right">
                          <!-- The fileinput-button span is used to style the file input field as button -->
                          <span class="btn btn-success fileinput-button dz-clickable" id="addfile" title="Add New File">
                            <i class="glyphicon glyphicon-plus"></i>
                          </span>
                          <button type="button" class="btn btn-secondary start" id="uploadAll"  title="Upload all Files">
                            <i class="glyphicon glyphicon-cloud-upload"></i>
                          </button>
                          <button type="reset" class="btn btn-warning cancel" id="cancelupload" title="Cancel All Upload">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                          </button>
                          <button type="button" class="btn btn-primary" id="openwaiver"><i class="glyphicon glyphicon-upload"></i> Waiver</button>
                          <button type="submit" class="btn btn-success" data-toggle="modal" data-target="#camfile" id="camera">
                            <i class="glyphicon glyphicon-camera"></i>
                          </button>
                        </div>
                      </div>

                      <div class="col-md-12 mt-2 mb-2">
                        <!-- The global file processing state -->
                        <span class="fileupload-process ">
                          <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-success" style="width:0%; margin-bottom: 10px!importantl" data-dz-uploadprogress=""></div>
                          </div>
                        </span>
                        <div class="md-2" class="files" id="previews" style="margin-top: 5px">
                          <div id="template" class="d-flex justify-content-between mb-3">
                            <input type="hidden" class="form-control" name="appointment_id" value="<?php echo $ID;?>" hidden>
                            <input type="hidden" class="form-control" name="pet_id" value="<?php echo $pet_id;?>" hidden>
                            <input type="hidden" class="form-control" name="owner_id" value="<?php echo $owner_id;?>" hidden>
                            <div>
                              <span class="preview"><img data-dz-thumbnail /></span>
                            </div>
                            <div>
                              <p class="name" data-dz-name></p>
                              <strong class="error text-danger" data-dz-errormessage></strong>
                            </div>
                            <div>
                              <p class="size" data-dz-size></p>
                              <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                              </div>
                            </div>
                            <div>
                              <button class="btn btn-primary start">
                                <i class="glyphicon glyphicon-upload"></i>
                                <span>Start</span>
                              </button>
                              <button data-dz-remove class="btn btn-warning cancel">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                                <span>Cancel</span>
                              </button>
                              <button data-dz-remove class="btn btn-danger delete">
                                <i class="glyphicon glyphicon-trash"></i>
                                <span>Delete</span>
                              </button>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <h4>Files</h4>
                    <div class="table-responsive">
                      <table class="table table-striped" id="files_list" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>File</th>
                            <th>Action</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                <form action="" method ="POST" class="needs-validation" id="notes_form" novalidate>
                  <div class="form-row">
                    <div class="col-md-12 mb-3">
                      <!--<label for="validationCustom03">Notes</label>-->
                      <textarea type="date" class="form-control" id="notes_content" placeholder="Notes" name="notes" required style="height: 200px;"><?php echo $notes; ?></textarea>
                      <div class="invalid-feedback">
                        Please provide a valid Remarks.
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane fade" id="next" role="tabpanel" aria-labelledby="next-tab">
                <form action="" method ="POST" class="needs-validation" id="next_form" novalidate>
                  <div class="form-row">
                    <input type="hidden" name="petID" value="<?php echo $pet_id;?>" hidden>
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
                      <input type="text" class="form-control input-small" id="next_time" name="from_time" required>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                    <div class="col-md-12 mb-2">
                      <label for="staffs">Assigned To</label>
                      <select class="form-control" id="staffs" name="staffid">
                      </select>
                      <div class="valid-feedback">
                        Looks good!
                      </div>
                    </div>
                  </div>
                  <button class="btn btn-primary" type="button" name="nextappointment" id="nextAppointment">Add Appointment</button>
                </form>
              </div>
              <div class="tab-pane fade" id="sms" role="tabpanel" aria-labelledby="sms-tab">
                <form action="" method ="POST" class="needs-validation" id="sms_form" novalidate>
                  <input type="hidden" class="form-control input-small" name="remarks" value="<?php echo $remarks;?>" hidden >
                  <form action="" method ="POST" class="needs-validation" id="sms_form" novalidate>
                    <div class="form-row">
                      <div class="col-md-5 mb-2">
                        <label for="validationCustom02">Next Schedule Notify</label>
                        <input type="date" class="form-control" id="daysnotif" name="daysnotif" value="" required readonly>
                      </div>
                      <div class="col-md-7 mb-2">
                        <label for="validationCustom02">Owner Number</label>
                        <input type="text" class="form-control input-phone" id="mob_num" name="number" placeholder="9123456789" value="<?php echo $number; ?>" required readonly>
                      </div>
                      <div class="col-md-12 mb-3">
                        <label for="validationCustom03">SMS Message</label>
                        <textarea type="date" class="form-control" id="validationCustom03" placeholder="Message" name="message" required readonly><?php echo $content_message;?></textarea>
                        <div class="invalid-feedback">
                          Please provide a valid Remarks.
                        </div>
                        <div class="notes">
                          <p><strong style="color: red;">Note:</strong> Please ensure that you have the following items in your message and it shouldn't be more than 160 characters long. Use keys below to add details.</p>
                          <li><span style="color: red;">{firstname}</span> - First Name of the customer</li>
                          <li><span style="color: red;">{services}</span>- Service Name (e.g. Checkup, Grooming)</li>
                          <li><span style="color: red;">{daysnotif}</span> - Schedule date</li>
                          <li><span style="color: red;">{pet_name}</span> - Pet name</li>
                          <li><span style="color: red;">{contact_number}</span> - Clinic contact number</li>
                        </div>
                      </div>
                    </div>
                  </form>

                  <!-- <button class="btn btn-primary float-right" type="button" name="submitappointment" id="savesms">Save Changes</button> -->
                </form>
              </div>
              <div class="tab-pane fade" id="invoice" role="tabpanel" aria-labelledby="notes-tab">
                <div class="card" style="border:none;">
                  <div class="card-body">
                    <form id="form-hockey_v2" name="form-hockey_v2">
                      <div class="typeahead__container">
                        <div class="typeahead__field">
                          <div class="typeahead__query">
                            <input  id="productlist" class="js-typeahead-hockey_v2" name="hockey_v2[query]" autocomplete="off" placeholder="Search Products...">
                          </div>
                        </div>
                      </div>
                    </form>
                    <br>
                    <form>
                      <div class="form-row align-items-center">
                        <div class="col-md-3">
                          <label class="sr-only" for="inlineFormInput">Product</label>
                          <input type="text" class="form-control" id="product_name" placeholder="Item">
                        </div>
                        <div class="col-md-3">
                          <label class="sr-only" for="inlineFormInput">Quantity</label>
                          <input type="number" class="form-control" id="quantity" placeholder="Quantity">
                        </div>
                        <div class="col-md-3">
                          <label class="sr-only" for="inlineFormInputGroup">Price</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <div class="input-group-text">&#8369;</div>
                            </div>
                            <input type="number" class="form-control" id="price" placeholder="Price">
                            <div class="input-group-append">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <button  type="button" class="btn btn-primary" id="addnewproduct"><i class="fa fa-plus"></i>Add Product</button>
                        </div>
                      </div>
                    </form>

                    <form action="" method ="POST" class="needs-validation" id="invoice_form" novalidate>

                    </form>

                    <div class="row mt-3">
                      <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
                    <!--   <div class="row">-->
                    <!--    <div class="col-md-6">-->
                    <!--     <div class="input-group mb-2">-->
                    <!--      <div class="input-group-prepend">-->
                    <!--        <div class="input-group-text">&#8369;</div>-->
                    <!--      </div>-->
                    <!--      <input type="number" class="form-control" id="paymentvalue">-->
                    <!--      <div class="input-group-prepend">-->
                    <!--        <button class="btn btn-primary"  id="payment"> Pay</button>-->
                    <!--      </div>-->
                    <!--    </div>-->
                    <!--  </div>-->
                    <!--  <div class="col-md-6">-->
                    <!--    <div class="input-group mb-2">-->
                    <!--      <div class="input-group-prepend">-->
                    <!--        <div class="input-group-text">Have a Coupon?</div>-->
                    <!--      </div>-->
                    <!--      <input type="text" class="form-control" id="couponcode">-->
                    <!--      <div class="input-group-prepend">-->
                    <!--        <button class="btn btn-primary" id="couponsubmit"> Submit</button>-->
                    <!--      </div>-->
                    <!--    </div>-->
                    <!--  </div>-->
                    <!--</div>-->
                    <div class="row">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Product</th>
                            <th>#</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Total</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody id="productcontent">


                        </tbody>
                      </table>
                      <table class="table table-hover">
                        <tbody>
                          <tr>
                            <td>  </td>
                            <td>  </td>
                            <td class="text-right">
                              <p><strong>Subtotal:</strong></p>
                              <?php foreach($taxes as $tax){?>
                                <p><strong><?php echo $tax->tax_name;?>:</strong></p>
                              <?php } ?>
                            </td>
                            <td class="text-center">
                              <p id="initialpricehtml"><strong>&#8369; 0.00</strong></p>
                              <?php foreach($taxes as $tax){
                                if($tax->is_percent == 1){
                                  $taxv = 0;
                                }else{
                                  $taxv = $tax->tax_value;
                                  $famount +=$taxv;
                                }
                                ?>
                                <p id="tax<?php echo $tax->tax_id;?>"><strong>&#8369; <?php echo number_format($taxv,2);?></strong></p>
                              <?php } ?>

                            </td>
                          </tr>
                          <tr>
                            <td></td>
                            <td></td>
                            <td class="text-right"><h4><strong>Total:</strong></h4></td>
                            <td class="text-center text-danger" id="totalamounthtml"><h1><strong>&#8369; <?php echo number_format($famount,2);?></strong></h1></td>
                          </tr>
                        </tbody>
                      </table>
                      <!--<table class="table table-hover">-->
                      <!--  <tbody id="deductions">-->

                      <!--  </tbody>-->
                      <!--</table>-->
                      <!--<table class="table table-hover">-->
                      <!--  <tbody>  -->
                      <!--    <tr>-->
                      <!--      <td></td>-->
                      <!--      <td></td>-->
                      <!--      <td class="text-right"><h4><strong>Balance:</strong></h4></td>-->
                      <!--      <td class="text-center text-danger" id="balancehtml"><h1><strong>&#8369;0.00</strong></h1></td>-->
                      <!--    </tr>-->
                      <!--  </tbody>-->
                      <!--</table>-->

                    </div>
                  </div>
                  <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
                   <div class="row">
                    <div class="col-md-12">
                      <div class="table-responsive">
                        <table class="table table-striped" id="transaction_table" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>Date / Time</th>
                              <th>Amount</th>
                              <th>Balance</th>
                            </tr>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <button type="button" class="btn btn-success btn-lg btn-block"  id="createinvoicebtn">
                            <i class="fa fa-file-invoice"></i> Create Invoice
                          </button>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

              </div>

            </div>

          </div>
        </div>
        <button class="btn btn-danger ml-1" type="button" name="delete_eachAppointment" id="delete_eachAppointment">Delete Appointment</button>
        <!-- <button class="btn btn-secondary ml-1" type="button" name="cancelappointment" id="cancelappointment">Cancel</button> -->
        <button class="btn btn-success ml-1 mt-2" type="button" name="saveclose" id="saveclose">Save & Close</button>
        <button class="btn btn-primary float-right ml-1 mr-2 mt-2" type="button" name="saveclose" onclick="savedata()">Save Changes</button>

      </div>
    </div>
  </div>
</div>
</main>

<div class="modal fade" id="past_med" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
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
          $past_med = $wpdb->get_results("SELECT * FROM {$table} where pet_id = {$pet_id} AND appointment_id != {$ID} AND service_name != 'Grooming'  order by appointment_id DESC ");
          $arrayData = array();
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
                                $location = $upload_dir['baseurl'] .'/'.  $skey->uploaded_file;
                                echo "<span><a href=".$location." target='_blank'>".$skey->uploaded_file."</a></span> <br>";
                              }
                              ?>

                            </div>  
                          </div>
                        </li>

                      <?php } ?>

                    </ul>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Waiver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="form-group">
                        <select class="form-control" id="waiver_title">
                          <option selected disabled>Select Waiver Type</option>
                          <option>1</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                        </select>
                      </div>
                      <div class="form-group" id="waiver_content">

                      </div>
                      <div class="col-md-12">
                        <div class="" style="text-align: center;">
                          Sign the document if you agree<br />
                          <button id="ShowSig" type="button" data-toggle="modal" data-target="#sigModal" class="btn btn-raised btn-primary waves-effect m-l--10 m-r--10">Input your signature</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-waiver">Save Waiver</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="sigModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
              <div class="modal-dialog modal-lg scroll" role="document">
                <div class="modal-content">
                  <div class="modal-header">Input your signature below</div>
                  <div class="modal-body">
                    <div class="wrapper-pen align-center" style="background: #eee">
                      <canvas id="signature-pad" class="signature-pad" width="900" height="500" style="background: white;"></canvas>
                    </div>
                  </div>
                  <div class="modal-body">
                    <div class="row clearfix">
                      <div class="col-sm-6">
                        <button id="btnCloseSIg" type="button" data-dismiss="modal" class="btn btn-raised waves-effect">Close</button>
                      </div>
                      <div class="col-sm-6 float-right">
                        <button id="getSignature" type="button" class="btn btn-raised waves-effect btn-success float-right">Accept Signature</button>
                        <button id="clearSignature" type="button" class="btn btn-raised waves-effect float-right">Clear</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <div class="modal fade" id="camfile" tabindex="-1" role="dialog" style="display: none;"  aria-hidden="true">
              <div class="modal-dialog modal-lg scroll">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Capture File to upload </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  </div>
                  <div class="modal-body">
                    <div class="col-md-12">
                      <div id="mywebcam" style="background: #eee;"></div>
                    </div>

                    <div class="row clearfix">
                      <div class="col-sm-12 mt-3 " style="text-align: center;">
                       <button type="button" class="btn btn-raised waves-effect btn-success" id="savesnapshot"><i class="glyphicon glyphicon-camera"></i> Save Snapshot</button>
                       <button type="button" class="btn btn-raised waves-effect btn-primary" id="takesnapshot"><i class="glyphicon glyphicon-camera"></i> Take a Snapshot</button>
                       <button type="button" class="btn btn-raised waves-effect btn-danger" id="retake" hidden><i class="glyphicon glyphicon-camera"></i> Capture Again</button>
                     </div>

                   </div>

                 </div>
               </div>
               <!-- /.modal-content -->
             </div>
             <!-- /.modal-dialog -->
           </div>

           <?php include "page-footer.php"; ?>
           <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/min/dropzone.js"></script>
           <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/min/jquery-dropzone.js"></script>
           <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/initialize-summernote.js"></script>
           <script>
            let waiver = [];
            let filelisttable ='';
            let existing = [];
            let contentready = [];
            let statuscontents = [];
            let contentreadytest = [];
            let existingtest = [];
            let testcontents = [];
            let allprice = [];
            let alldeductions = [];
            document.addEventListener('DOMContentLoaded', function(e) {

              $('#notes_content').summernote({
                placeholder: 'Notes',
                tabsize: 2,
                height: 300
              });


              $('#openwaiver').click(function(){
                $('#largeModal').modal('show');
                $('#waiver_title').trigger('change');

              });

              getProductlist();
              fetch(url+'statusfields/<?php echo $ID;?>').then(res=>res.json()).then(res=>{
                let content = '';
                console.log(res);


                res.forEach(x=>{
                  contentready.push(x.meta_key);
                  statuscontents.push(x.meta_key);
                  if(x.meta_key === null){

                  }else{
                    content +=`<div class="col-md-6 mb-3">
                    <label for="res">${x.meta_key}</label>
                    <div class="input-group mb-3">
                    <input type="text" class="form-control" id="res"  name="${x.meta_key}" value="${x.meta_value}" required>
                    <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1" onclick="removeMeta('${x.meta_key}')">x</span>
                    </div>

                    </div>
                    </div>`;
                  }

                });
                if(contentready.length > 0){
                  document.querySelector('#status-row').innerHTML = content;
                }
                existing = res;
              });
              fetch(url+'testfields/<?php echo $ID;?>').then(res=>res.json()).then(res=>{

                let content = '';

                res.forEach(x=>{
                  contentreadytest.push(x.meta_key);
                  testcontents.push(x.meta_key);
                  content +=`<div class="col-md-6 mb-3">
                  <label for="res">${x.meta_key}</label>
                  <div class="input-group mb-3">
                  <input type="text" class="form-control" id="res"  name="${x.meta_key}" value="${x.meta_value}" required>
                  <div class="input-group-append">
                  <span class="input-group-text" id="basic-addon1" onclick="removeMetaTest('${x.meta_key}')">x</span>
                  </div>

                  </div>
                  </div>`;
                });
                if(contentreadytest.length > 0){
                  document.querySelector('#test-row').innerHTML = content;
                }
                existingtest = res;
              });
              displayStaff();

              readyTestFields()
              readyStatusFields()
              $('#scheduledate').change(function(){
                $('#daysnotif').val($('#scheduledate').val());
              });
              $('#customSwitch2').click(function(){
                if($(this).prop('checked')){
                  $('#scheduledate').val(moment().format('YYYY-MM-DD'));
                  $('#sms-tab').prop('hidden',true);
                }else{
                  $('#scheduledate').val("");
                  $('#sms-tab').prop('hidden',false);
                }
              });

              $('#waiver').DataTable();
              $('#quickmed').DataTable();

              $('#transaction_table').DataTable({
                ajax:{
                  type: 'POST',
                  url : url+"transactions",
                  data: {
                   appointment_id: <?php echo $ID;?>
                   // etc..
                 }
               } 
             });

              let filelisttable= $('#files_list').DataTable( {
                ajax: url+'filelists/<?php echo $ID;?>',
                "order": [[ 2, "desc" ]]
              });

              $('#next_time').timepicker();
              $('#quick_view_med').on('click',function(){
                $('#past_med').modal("show");

                $('#past_med').resizable({
        //alsoResize: ".modal-dialog",
        minHeight: 300,
        minWidth: 300
      });
                $('.modal-dialog').draggable();

                $('#past_med').on('show.bs.modal', function() {
                  $(this).find('.modal-body').css({
                    'max-height': '100%'
                  });
                });
              });
    // $('#takesnapshot').tooltip('toggle');
    // $('#addfile').tooltip('toggle');
    // $('#uploadAll').tooltip('toggle');
    // $('#cancelupload').tooltip('toggle');

    var cleave = new Cleave('.input-phone', {
      phone: true,
      uppercase: true,
      prefix: '+63',
      delimiters: ['(', ')',' ',' '],
      blocks: [0, 2 , 0, 3, 0, 3, 0, 4],
      phoneRegionCode: 'PH'
    });



    $('#customSwitch1').click(function(){
      if($(this).prop('checked')){
        $('#daysnotif').val(moment().format('YYYY-MM-DD'));
      }else{
        $('#daysnotif').val("<?php echo $days_notif;?>");
      }
    });

    getwaiverdetails();

    // $('#savegeneral').click(function(x){
    //  x.preventDefault();
    //  savedata();
    // });
    // $('#savestatus').click(function(event){
    //  event.preventDefault();
    //  savedata();
    // });
    // $('#savetest').click(function(event){
    //  event.preventDefault();
    //  savedata();
    // });
    // $('#savediag').click(function(event){
    //  event.preventDefault();
    //  savedata();
    // });
    // $('#savesms').click(function(event){
    //  event.preventDefault();
    //  savedata();
    // });

    $('#nextAppointment').click(function(event){
      event.preventDefault();
      let data = {};
      $('#next_form').serializeArray().forEach(x=>{
        data[x.name] = x.value;
      });
      $('#sms_form').serializeArray().forEach(x=>{
        data[x.name] = x.value;
      });
      // console.log(data);
      data['clinic_id'] = <?php echo $myclinic_id;?>;
      $.ajax({
        url: url+"appointment/",
        data:data,
        type: 'post',
        dataType: 'json',
        success: function(response) {
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
    });

    document.querySelector('#delete_eachAppointment').addEventListener("click",function(e) {
      e.preventDefault();
      let data = {
        'appointment_id' : $('#appointment_id').val()
      };
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to recover this appointment!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: url+"appointment/removeeach",
            data:data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
              if(response.status == 200){
                Swal.fire(
                  'Deleted!',
                  'Your appointment has been deleted.',
                  'success'
                  );
                location.assign('<?php echo get_home_url();?>/pet-details/?id=<?php echo $pet_id;?>');
              }

            }
          });
          return false;
        }
      });
    });


    $('#waiver_title').on('change', function(){
      let content= '';
      waiver.forEach(x=>{
        if($('#waiver_title').val()==x.waiver_title){
          content = `${x.waiver_content}`;
        }
      });
      $('#waiver_content').html(content);
      // $('#waiver_content').summernote('code', $('#waiver_content').val());
      
    });

    var canvas = document.querySelector("canvas");

    var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
      backgroundColor: 'rgba(255, 255, 255, 0)',
      penColor: 'rgb(0, 0, 0)'
    });

    $('#getSignature').on('click', function(){
      if (signaturePad.isEmpty()) {
        alert("Please provide a signature first.");
      } else {
                //close modal
                $("#ShowSig").html("Replace your signature");
                $("#ShowSig").removeClass("btn-primary");
                $("#ShowSig").removeClass("btn-danger");
                $("#ShowSig").addClass("btn-success")
                $('#sigModal').modal('toggle');
              }
            });

    $('#clearSignature').click(function () {
      signaturePad.clear();
    });

    if (signaturePad.isEmpty()){
      $("#ShowSig").html("Input your signature");
      $("#ShowSig").removeClass("btn-primary");
      $("#ShowSig").removeClass("btn-success");
      $("#ShowSig").addClass("btn-danger")
    }else{
      $("#ShowSig").html("replace your signature");
      $("#ShowSig").removeClass("btn-primary");
      $("#ShowSig").removeClass("btn-danger");
      $("#ShowSig").addClass("btn-success")
    }
    let filepaths ='';
    $('input[type=file]').change(function (e) {
      var fileName = e.target.files[0].name;
      filepaths = fileName;
    });

    $("#save-waiver").on('click', function(){
      if (signaturePad.isEmpty()) {
        alert("Please provide a signature first.");
      } else {
        var image = document.getElementById("signature-pad").toDataURL("image/png");
        image = image.replace('data:image/png;base64,', '');

                //console.log(pethash)
                $('#imageData').val(image);
                $(this).attr('value', 'Please wait...');
                let data = {
                  'appointment_id': $('#appointment_id').val(),
                  'signature_data': $('#imageData').val(),
                  'pet_id' : $('#pet_id').val(),
                  'owner_id': $('#owner_id').val(),
                  'content':$('#waiver_content').summernote('code'),
                  'title':$('#waiver_title').val()
                }
                $('#waiver_content').summernote('destroy');
                $.ajax({
                  type: "POST",
                  url: url+"generate/pdf",
                  data: JSON.stringify(data),
                  dataType: "json",
                  success: function (data) {
                    console.log(data);
                    if (data.d === "Success") {
                      $('#largeModal').modal('hide');
                      // loadAttachementRows();
                      let timerInterval
                      Swal.fire({
                        title: 'Saving Changes!',
                        html: 'Please take a moment to breathe.',
                        timer: 2000,
                        timerProgressBar: true,
                        onBeforeOpen: () => {
                          Swal.showLoading()
                          timerInterval = setInterval(() => {
                            const content = Swal.getContent()
                            if (content) {
                              const b = content.querySelector('b')
                              if (b) {
                                b.textContent = Swal.getTimerLeft()
                              }
                            }
                          }, 100)
                        },
                        onClose: () => {
                          clearInterval(timerInterval)
                        }
                      }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                          Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Your work has been saved',
                            showConfirmButton: false,
                            timer: 1500
                          })
                          filelisttable.ajax.reload();
                        }
                      });

                    } else {
                      Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                      })
                    }
                  },
                  error: function () {
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Something went wrong!'
                    })
                  }
                })
              }


            });


    $('#camera').click(function(){
      Webcam.set({
        width:800,
        height:500,
        image_format: 'jpeg',
        jpeg_quality: 100,
        force_flash:false,
        flip_horiz:false,
        fps:45
      });
      Webcam.attach('#mywebcam');
    });
    let data = {};
    $('#takesnapshot').click(function(){

      Webcam.snap(function(data_uri){
        Webcam.freeze();
        Webcam.reset();
        data = {
          'appointment_id': $('#appointment_id').val(),
          'image_data': data_uri,
          'pet_id' : $('#pet_id').val(),
          'owner_id': $('#owner_id').val()
        }
        $('#mywebcam').html('<img src="'+data_uri+'" class="avatar img-thumbnail" alt="avatar" id="thumbnail">');
      });
      
      
      $('#retake').prop('hidden',false);
      $('#takesnapshot').prop('hidden',true);
    });

    $('#retake').click(function(){
      Webcam.set({
        width:800,
        height:400,
        image_format: 'jpeg',
        jpeg_quality: 100,
        force_flash:false,
        flip_horiz:true,
        fps:45
      });
      Webcam.attach('#mywebcam');
      $('#retake').prop('hidden',true);
      $('#takesnapshot').prop('hidden',false);
    });

    $('#savesnapshot').click(function(){
      $.ajax({
        url: url+"image/attach",
        data:data,
        type: 'post',
        dataType: 'json',
        success: function(response) {
          let timerInterval
          Swal.fire({
            title: 'Saving Image!',
            html: 'Please take a moment to breathe.',
            timer: 2000,
            timerProgressBar: true,
            onBeforeOpen: () => {
              Swal.showLoading()
              timerInterval = setInterval(() => {
                const content = Swal.getContent()
                if (content) {
                  const b = content.querySelector('b')
                  if (b) {
                    b.textContent = Swal.getTimerLeft()
                  }
                }
              }, 100)
            },
            onClose: () => {
              clearInterval(timerInterval)
            }
          }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1500
              })
              filelisttable.ajax.reload();
            }
          });
        }
      });
      $('#camfile').modal('hide');
    });

    $('#saveclose').click(function(e){
      savedata();
      location.assign('<?php echo get_home_url();?>/pet-details/?id=<?php echo $pet_id;?>');
    });

    let countInvoicePro = 0;
    let initialAmount = $('#initialprice').val();
    let count = 0;

    $('#addnewproduct').click(function(){
      let content = document.querySelector('#productcontent');
      let id = count;
      let name = $('#product_name').val();
      let price = $('#price').val();
      let quantity = $('#quantity').val();
      let ls='';
      let total = 0;
      let tax = 0;
      let grosstotal = 0;
      let producttotal = 0;
      if(price === 0 || price === ''){
        alert('Please input valid amount');
        return;
      }
      if(name == ''){
        alert('Please input product name');
        return; 
      }
      if(quantity == ''){
        alert('Please input valid quantity');
        return; 
      }
      if(quantity <= 0){
        alert('Please input valid quantity');
        return; 
      }
      price = parseFloat(price).toFixed(2)    
      producttotal = price * parseInt(quantity);
      producttotal = parseFloat(producttotal).toFixed(2) 
      let newelement = document.createElement('tr');
      newelement.setAttribute('id',"invoice"+id);
      content.appendChild(newelement);
      ls += `<td class="col-md-9"><em>${name}</em></h4></td>
      <td class="col-md-2" style="text-align: center"> ${quantity}</td>
      <td class="col-md-2 text-center">${formatMoney(price)}</td>
      <td class="col-md-2 text-center">${formatMoney(producttotal)}</td>
      <td class="col-md-2 text-center"><button  type="button" class="btn btn-danger" onclick="delInvoice(${count},'${name}')"><i class="fa fa-trash"></i></button></td>
      </tr>`;
      newelement.innerHTML = ls;
      let data = {
        'productname' : name,
        'quantity' : quantity,
        'productprice' : parseFloat(price),
        'price' : parseFloat(producttotal)
      }
      allprice.push(data);
      // console.log(allprice);


      allprice.forEach(x=>{
        total += x.price;
      });
      let totaltax = 0;
       <?php foreach($taxes as $tax){?>
        let tax<?php echo $tax->tax_id;?> = 0;
        if(<?php echo $tax->is_percent;?> == 1){

          tax<?php echo $tax->tax_id;?> = total * <?php echo ($tax->tax_value / 100);?>;
        }else{
          tax<?php echo $tax->tax_id;?> = <?php echo $tax->tax_value;?>;
        }
        console.log(<?php echo $tax->tax_value;?>);
        totaltax += tax<?php echo $tax->tax_id;?>;
        $('#tax<?php echo $tax->tax_id;?>').html('&#8369; '+formatMoney(tax<?php echo $tax->tax_id;?>));
      <?php } ?>
      // tax = total * (<?php echo ($tax_percentage / 100);?> );
      grosstotal = total + totaltax;
      $('#initialprice').val(formatMoney(total));
      $('#initialpricehtml').html('&#8369; '+formatMoney(total));
      $('#tax').val(formatMoney(tax));
      $('#totalamount').val(formatMoney(grosstotal));
      $('#totalamounthtml').html('<h1> &#8369; '+formatMoney(grosstotal)+'</h1>');
      // document.getElementById('invoice_form').innerHTML += ls;
      
      $('#iplabel').html('&#8369; '+formatMoney(total));
      $('#taxlabel').html('&#8369; '+formatMoney(tax));
      $('#talabel').html('&#8369; '+formatMoney(grosstotal));
      countInvoicePro++;
      count++;
      // console.log(<?php echo ($tax_percentage / 100);?> );

      $('#product_name').val('');
      $('#quantity').val('');
      $('#price').val('');

    });

    $('#createinvoicebtn').click(function(){
      let total = 0;
      let tax = 0;
      let grosstotal = 0;
      let producttotal = 0;

      let invoice_list = {
        data:allprice,
        'appointment_id': $('#appointment_id').val(),
        'pet_id' : $('#pet_id').val(),
        'owner_id': $('#owner_id').val(),
        'clinic_id': <?php echo $myclinic_id;?>,
      }
      let countquantity = 0;
      let invoice = $('#invoice_form').serializeArray();

      // invoice.forEach(function(x, index){
      //   invoice_list.data[x.name] = x.value;
      // });
      allprice.forEach(x=>{
        total += x.price;
      });

      invoice_list['subtotal'] = total;
      
      console.log(invoice_list);
      $.ajax({
        url: url+'invoice/pdf',
        data:JSON.stringify(invoice_list),
        type: 'post',
        dataType: 'json',
        success: function(response) {
          let timerInterval
          Swal.fire({
            title: 'Saving Invoice to Attachments!',
            html: 'Please take a moment to breathe. Please go to Attachments Tab',
            timer: 2000,
            timerProgressBar: true,
            onBeforeOpen: () => {
              Swal.showLoading()
              timerInterval = setInterval(() => {
                const content = Swal.getContent()
                if (content) {
                  const b = content.querySelector('b')
                  if (b) {
                    b.textContent = Swal.getTimerLeft()
                  }
                }
              }, 100)
            },
            onClose: () => {
              clearInterval(timerInterval)
            }
          }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1500
              })
              filelisttable.ajax.reload();
            }
          });
        }
      });

    });
    function addProduct(name,price,stocks){

      let content = document.querySelector('#productcontent');
      let id = count;

      let ls='';
      let total = 0;
      let tax = 0;
      let grosstotal = 0;
      let producttotal = 0;
      let quantity = 1;
      //let quantity = prompt("How many Items you want to add?", "#");
      //console.log(stocks);
      //if(stocks > 0){
        //let limit =0;
        //limit = stocks - quantity;
        //if(limit <= 0){
          //alert('Quantity exceed the stock limit');
         // return;
        //}
     // }
      //if(price === 0 || price === ''){
       // alert('Please input valid amount');
      //  return;
      //}
     // if(name == ''){
       // alert('Please input product name');
      //  return; 
     // }
      //if(quantity == ''){
       // alert('Please input valid quantity');
       // return; 
      //}
     // if(quantity <= 0){
        //alert('Please input valid quantity');
        //return; 
      //}
      price = parseFloat(price).toFixed(2)    
      producttotal = price * parseInt(quantity);
      producttotal = parseFloat(producttotal).toFixed(2) 
      let newelement = document.createElement('tr');
      newelement.setAttribute('id',"invoice"+id);
      content.appendChild(newelement);
      ls += `<td class="col-md-9"><em>${name}</em></h4></td>
      <td class="col-md-2" style="text-align: center"> ${quantity}</td>
      <td class="col-md-2 text-center">${formatMoney(price)}</td>
      <td class="col-md-2 text-center">${formatMoney(producttotal)}</td>
      <td class="col-md-2 text-center"><button  type="button" class="btn btn-danger" onclick="delInvoice(${count},'${name}')"><i class="fa fa-trash"></i></button></td>
      </tr>`;
      newelement.innerHTML = ls;
    // ls=`
    // <div class="form-row align-items-center" id="invoice${count}">
    // <div class="col-md-6" >
    // <label class="sr-only" for="inlineFormInput">Product</label>
    // <input type="text" class="form-control mb-2" id="inlineFormInput" value="${name}" name="${name}" readonly>
    // </div>
    // <div class="col-md-3">
    // <label class="sr-only" for="inlineFormInputGroup">Price</label>
    // <div class="input-group mb-2">
    // <div class="input-group-prepend">
    // <div class="input-group-text">&#8369;</div>
    // </div>
    // <input type="number" class="form-control initialAmount" id="inlineFormInputGroup" value="${price}" name="${name}" readonly>
    // </div>
    // </div>
    // <div class="col-md-3">
    // <button  type="button" class="btn btn-danger" onclick="delInvoice(${count},'${name}')"><i class="fa fa-trash"></i></button>
    // </div>
    // </div>
    // `;
    let data = {
      'productname' : name,
      'quantity' : quantity,
      'productprice' : parseFloat(price),
      'price' : parseFloat(producttotal)
    }
    allprice.push(data);
      // console.log(allprice);
      allprice.forEach(x=>{
        total += x.price;
      });
      
      // tax = total * (<?php echo ($tax_percentage / 100);?> );
      let totaltax = 0;
      <?php foreach($taxes as $tax){?>
        let tax<?php echo $tax->tax_id;?> = 0;
        if(<?php echo $tax->is_percent;?> == 1){

          tax<?php echo $tax->tax_id;?> = total * <?php echo ($tax->tax_value / 100);?>;
        }else{
          tax<?php echo $tax->tax_id;?> = <?php echo $tax->tax_value;?>;
        }
        console.log(<?php echo $tax->tax_value;?>);
        totaltax += tax<?php echo $tax->tax_id;?>;
        $('#tax<?php echo $tax->tax_id;?>').html('&#8369; '+formatMoney(tax<?php echo $tax->tax_id;?>));
      <?php } ?>
      grosstotal = total + totaltax;
      $('#initialprice').val(formatMoney(total));
      $('#tax').val(formatMoney(tax));
      $('#totalamount').val(formatMoney(grosstotal));
      // document.getElementById('invoice_form').innerHTML += ls;
      
      $('#iplabel').html('&#8369; '+formatMoney(total));
      $('#taxlabel').html('&#8369; '+formatMoney(tax));
      $('#talabel').html('&#8369; '+formatMoney(grosstotal));
      $('#initialpricehtml').html('&#8369; '+formatMoney(total));
      $('#totalamounthtml').html('<h1> &#8369; '+formatMoney(grosstotal)+'</h1>');
      countInvoicePro++;
      count++;
    }
    function getProductlist(){
      let ls = '';
      $.typeahead({
        input: '#productlist',
        minLength: 0,
        maxItem: 8,
        order: "asc",
        hint: true,
        searchOnFocus: true,
        blurOnTab: false,
        multiselect: {
          matchOn: ["product_title"],
          cancelOnBackspace: true,
          data: function () {

            var deferred = $.Deferred();

            setTimeout(function () {
              deferred.resolve(existing);
            }, 2000);

            deferred.always(function () {
              console.log('data loaded from promise');
            });

            return deferred;

          },
          callback: {
            onClick: function (node, item, event) {
              console.log(item);
              alert(item.meta_key + ' Clicked!');
            },
            onCancel: function (node, item, event) {
              console.log(item);
              alert(item.meta_key + ' Removed!');

            }
          }
        },
        templateValue: "{{product_title}}",
        display: ["product_title"],
        emptyTemplate: 'no result for {{query}}',
        source: {
          teams: {
            url: url+'products/<?php echo $myclinic_id;?>'
          }
        },
        callback: {
          onClick: function (node, a, item, event) {
            console.log(item.product_title + ' Added!');
        // console.log(item);
        addProduct(item.product_title,item.product_price,item.product_quantity);
        // let ls ='';
        // contentready.push(item.meta_key);
        // console.log(contentready);
        // loadStatusFields(contentready)
      },
      onSubmit: function (node, form, items, event) {
        event.preventDefault();

        alert(JSON.stringify(items))
      }
    },
    debug: true
  });

    }


    function loadStatusFields(arr){
      let ls ='';
      console.log(existing);
      $('#statusfields').removeAttr('placeholder');
      existing.forEach(x=>{

        ls+=`
        <div class="col-md-6 mb-3">
        <label for="res">${x.meta_key}</label>
        <div class="input-group mb-3">
        <input type="text" class="form-control" id="res"  name="${x.meta_key}" value="${x.meta_value}" required>
        <div class="input-group-append">
        <span class="input-group-text" id="basic-addon1" onclick="removeMeta('${x.meta_key}')">x</span>
        </div>

        </div>
        </div>`;
      // ls +=`<div class="col-md-6  mb-3">
      // <label for="res">${x.meta_key}</label>
      // <input type="text" class="form-control" id="res"  name="${x.meta_key}" value="${x.meta_value}" required>
      // </div>`;
    });
      for(let i=0;i<arr.length;i++){
        console.log(existing.includes(arr[i]));
        if(statuscontents.includes(arr[i])){

        }else{

          ls+=`
          <div class="col-md-6 mb-3">
          <label for="res">${arr[i]}</label>
          <div class="input-group mb-3">
          <input type="text" class="form-control" id="res"  name="${arr[i]}" required>


          </div>
          </div>`;

        // ls+=`<div class="col-md-6 mb-3">
        // <label for="res">${arr[i]}</label>
        // <input type="text" class="form-control" id="res"  name="${arr[i]}" required>
        // </div>`
      }

    }
    document.querySelector('#status-row').innerHTML = ls;
  }
  function readyStatusFields(){
    let ls = '';
    $.typeahead({
      input: '#statusfields',
      minLength: 0,
      maxItem: 8,
      order: "asc",
      hint: true,
      searchOnFocus: true,
      blurOnTab: false,
      matcher: function (item, displayKey) {
        if (contentready.includes(item.meta_key)) {
            // Disable Boston for X reason
            item.disabled = true;
          }
        // Add all items matched items
        return true;
      },
      multiselect: {
        matchOn: ["meta_key"],
        cancelOnBackspace: true,
        data: function () {

          var deferred = $.Deferred();

          setTimeout(function () {
            deferred.resolve(existing);
          }, 2000);

          deferred.always(function () {
            console.log('data loaded from promise');
          });

          return deferred;

        },
        callback: {
          onClick: function (node, item, event) {
            console.log(item);
            
            alert(item.meta_key + ' Clicked!');
          },
          onCancel: function (node, item, event) {
          // console.log(item);
          // alert(item.meta_key + ' Removed!');
          var index = contentready.indexOf(item.meta_key);
          contentready.splice(index, 1);
          console.log(contentready);
          loadStatusFields(contentready)
        }
      }
    },
    templateValue: "{{meta_key}}",
    display: ["meta_key"],
    emptyTemplate: 'no result for {{query}}',
    source: {
      teams: {
        url: url+'statusfields/select/<?php echo $myclinic_id;?>'
      }
    },
    callback: {
      onClick: function (node, a, item, event) {
        console.log(item.meta_key + ' Added!');
        let ls ='';
        contentready.push(item.meta_key);
        console.log(contentready);
        loadStatusFields(contentready)
        addProduct(item.meta_key,item.meta_value)
      },
      onSubmit: function (node, form, items, event) {
        event.preventDefault();

        alert(JSON.stringify(items))
      }
    },
    debug: true
  });

  }
  function loadTestFields(arr){
    let ls ='';
    $('#testfields').removeAttr('placeholder');
    existingtest.forEach(x=>{
      ls+=`
      <div class="col-md-6 mb-3">
      <label for="res">${x.meta_key}</label>
      <div class="input-group mb-3">
      <input type="text" class="form-control" id="res"  name="${x.meta_key}" value="${x.meta_value}" required>
      <div class="input-group-append">
      <span class="input-group-text" id="basic-addon1" onclick="removeMetaTest('${x.meta_key}')">x</span>
      </div>
      
      </div>
      </div>`;
    });
    for(let i=0;i<arr.length;i++){
      console.log(existingtest.includes(arr[i]));
      if(testcontents.includes(arr[i])){

      }else{

        ls+=`
        <div class="col-md-6 mb-3">
        <label for="res">${arr[i]}</label>
        <div class="input-group mb-3">
        <input type="text" class="form-control" id="res"  name="${arr[i]}" required>

        
        </div>
        </div>`;

      }

    }
    document.querySelector('#test-row').innerHTML = ls;
  }
  function readyTestFields(){
    let ls = '';

    $.typeahead({
      input: '#testfields',
      minLength: 0,
      maxItem: 8,
      order: "asc",
      hint: true,
      searchOnFocus: true,
      blurOnTab: false,
      matcher: function (item, displayKey) {
        if (contentreadytest.includes(item.meta_key)) {
            // Disable Boston for X reason
            item.disabled = true;
          }
        // Add all items matched items
        return true;
      },
      multiselect: {
        matchOn: ["meta_key"],
        cancelOnBackspace: true,
        data: function () {

          var deferred = $.Deferred();

          setTimeout(function () {
            deferred.resolve(existingtest);
          }, 2000);

          deferred.always(function () {
            console.log('data loaded from promise');
          });

          return deferred;

        },
        callback: {
          onClick: function (node, item, event) {
            console.log(item);
            alert(item.meta_key + ' Clicked!');
          },
          onCancel: function (node, item, event) {
            console.log(item);
            alert(item.meta_key + ' Removed!');
            var index = contentreadytest.indexOf(item.meta_key);
            contentreadytest.splice(index, 1);
            console.log(contentreadytest);
            loadTestFields(contentreadytest)

          }
        }
      },
      templateValue: "{{meta_key}}",
      display: ["meta_key"],
      emptyTemplate: 'no result for {{query}}',
      source: {
        teams: {
          url: url+'testfields/select/<?php echo $myclinic_id;?>'
        }
      },
      callback: {
        onClick: function (node, a, item, event) {
          console.log(item.meta_key + ' Added!');
          let ls ='';
          contentreadytest.push(item.meta_key);
          console.log(contentreadytest);
          loadTestFields(contentreadytest)
          addProduct(item.meta_key,item.meta_value)
        },
        onSubmit: function (node, form, items, event) {
          event.preventDefault();

          alert(JSON.stringify(items))
        }
      },
      debug: true
    });


  }
});

function delInvoice(myid,name){
  let id = '#invoice'+myid;
  let total = 0;
  let tax = 0;
  let grosstotal = 0;
  // console.log(id);
  $(id).prop('hidden',true);
  $(id).remove();
  console.log($(id));
  const indx = allprice.findIndex(v => v.productname == name);
  allprice.splice(indx, indx >= 0 ? 1 : 0);

  allprice.forEach(x=>{
    total += x.price;
  });
  // console.log(allprice);
  // tax = total * (<?php echo ($tax_percentage / 100);?> );
  let totaltax = 0;
  <?php foreach($taxes as $tax){?>
    let tax<?php echo $tax->tax_id;?> = 0;
    if(<?php echo $tax->is_percent;?> == 1){

      tax<?php echo $tax->tax_id;?> = total * <?php echo ($tax->tax_value / 100);?>;
    }else{
      tax<?php echo $tax->tax_id;?> = <?php echo $tax->tax_value;?>;
    }
    console.log(<?php echo $tax->tax_value;?>);
    totaltax += tax<?php echo $tax->tax_id;?>;
    $('#tax<?php echo $tax->tax_id;?>').html('&#8369; '+parseFloat(tax<?php echo $tax->tax_id;?>).toFixed(2));
  <?php } ?>
  grosstotal = total + totaltax;
  $('#initialprice').val(total.toFixed(2));
  $('#tax').val(tax.toFixed(2));
  $('#totalamount').val(grosstotal.toFixed(2));
  $('#initialpricehtml').html('&#8369; '+parseFloat(total).toFixed(2));
  $('#totalamounthtml').html('&#8369; '+parseFloat(grosstotal).toFixed(2));

  $('#iplabel').html('&#8369; '+total.toFixed(2));
  $('#taxlabel').html('&#8369; '+tax.toFixed(2));
  $('#talabel').html('&#8369; '+grosstotal.toFixed(2));
}
async function savedata(){
  let general_data = {};
  let status_data = {};
  let test_data = {};
  let diag_data = {};
  let appointment_data = {
    'up_appointmentID':$('#appointment_id').val(),
    'up_petID':$('#pet_id').val(),
    'up_staffid':"<?php echo $staff_id;?>",
    'up_servicename':"<?php echo $service_name;?>",
    'up_complaints':"<?php echo $complaints;?>",
    'up_schedule':"<?php echo $start;?>",
    'up_from_time':"<?php echo $time;?>",
    'up_appointmentRem':$('#remarks').val(),
    'up_pet_general_id':"<?php echo $general_id;?>",
    'up_pet_status_id':"<?php echo $status_id;?>",
    'up_pet_diagnostics_id':"<?php echo $diag_id;?>",
    'up_pet_test_id':"<?php echo $test_id;?>",
    'up_notes' : $('#notes_content').summernote('code')
  }
  $('#general_form').serializeArray().forEach(x=>{
    general_data[x.name] = x.value;
  });
  $('#status_form').serializeArray().forEach(x=>{
    status_data[x.name] = x.value;
  });
  status_data['appointment_id'] = <?php echo $ID;?>;
  $('#test_form').serializeArray().forEach(x=>{
    test_data[x.name] = x.value;
  });
  test_data['appointment_id'] = <?php echo $ID;?>;
  $('#diag_form').serializeArray().forEach(x=>{
    diag_data[x.name] = x.value;
  });
// console.log(appointment_data);

await Promise.all([
  update_data('appointment/edit',appointment_data),
  update_data('appointment/general',general_data),
  update_status('appointment/status',status_data),
  update_status('appointment/test',test_data),
  update_data('appointment/diagnostics',diag_data)]
  ).then(results=>{
    let timerInterval
    Swal.fire({
      title: 'Saving Changes!',
      html: 'Please take a moment to breathe.',
      timer: 2000,
      timerProgressBar: true,
      onBeforeOpen: () => {
        Swal.showLoading()
        timerInterval = setInterval(() => {
          const content = Swal.getContent()
          if (content) {
            const b = content.querySelector('b')
            if (b) {
              b.textContent = Swal.getTimerLeft()
            }
          }
        }, 100)
      },
      onClose: () => {
        clearInterval(timerInterval)
      }
    }).then((result) => {
      /* Read more about handling dismissals below */
      if (result.dismiss === Swal.DismissReason.timer) {
        console.log(results)
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: 'Your work has been saved',
          showConfirmButton: false,
          timer: 1500
        })
      }
    });

  })


}

function update_data(endpoint,datas){
  $.ajax({
    url: url+endpoint,
    data:JSON.stringify(datas),
    type: 'post',
    dataType: 'json',
    success: function(response) {
    }
  });
    // return false;
  }
  function update_status(endpoint,datas){
    $.ajax({
      url: url+endpoint,
      data:JSON.stringify(datas),
      type: 'post',
      dataType: 'json',
      success: function(response) {
      }
    });
    // return false;
  }

  function getwaiverdetails(){
    let ls = '';
    let content = '';
    fetch(url+"waiver/<?php echo $myclinic_id;?>").then(res=>res.json()).then(res=>{
      waiver = res;
      res.forEach(x=>{
        ls += `<option value="${x.waiver_title}">${x.waiver_title}</option>`;
      });
      $('#waiver_title').html(ls);

    });
  }
  function deleteAttach(id){
    let filelisttable= $('#files_list').DataTable();
    let datas = {
      'attach_id' : id
    }
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to recover this file!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: url+"appointment/attach/removeeach",
          data:datas,
          type: 'post',
          dataType: 'json',
          success: function(response) {
            if(response.status == 200){
              Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
                )
              filelisttable.ajax.reload();
            }

          }
        });
        return false;
      }
    });
  }





  function displayStaff(){
    let data = {
      clinic_id : <?php echo $myclinic_id;?>
    }
    let ls="";
    fetch(url+"employees",{
      method: 'POST',
      body : JSON.stringify(data)
    }).then(res=>res.json()).then(res=>{
      res.forEach(x=>{
        if(x.ID == <?php echo $staff_id;?>){
          ls+=`<option value="${x.ID}" selected>${x.name} (${x.role}) </option>`;
        }else{
          ls+=`<option value="${x.ID}">${x.name} (${x.role}) </option>`
        }

      });

      $('#staffs').html(ls);
    });

  }
  function removeMeta(name){
    let data={
      'meta_key' : name,
      'appointment_id': <?php echo $ID;?>
    }
    console.log(data);

    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to recover this file!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: url+"remove/appointment/status",
          data:JSON.stringify(data),
          type: 'post',
          dataType: 'json',
          success: function(response) {
            if(response.status == 200){
              Swal.fire(
                'Deleted!',
                'Status Field deleted in appointment.',
                'success'
                )
              location.reload();            }

            }
          });
        return false;
      }
    });
  }
  function removeMetaTest(name){
    let data={
      'meta_key' : name,
      'appointment_id': <?php echo $ID;?>
    }
    console.log(data);

    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to recover this file!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: url+"remove/appointment/test",
          data:JSON.stringify(data),
          type: 'post',
          dataType: 'json',
          success: function(response) {
            if(response.status == 200){
              let content ='';
              Swal.fire(
                'Deleted!',
                'Test Field deleted in appointment.',
                'success'
                )
              location.reload();
            }
          }
        });
        return false;
      }
    });
  }

  function savetransaction(){
    let data = {
      order : allprice,
      appointment_id : ''
    }
    console.log(data);
  }
  $('#payment').click(function(){
    // console.log(this.value);
    let content = document.querySelector('#deductions');
    let payment = $('#paymentvalue').val();
    let total = 0;
    let tax = 0;
    let grosstotal = 0;
    let producttotal = 0;
    let totaldeductions = 0;
    allprice.forEach(x=>{
      total += x.price;
    });
    let totaltax = 0;
    let ls = ''; 
    <?php foreach($taxes as $tax){?>
      let tax<?php echo $tax->tax_id;?> = 0;
      if(<?php echo $tax->is_percent;?> == 1){

        tax<?php echo $tax->tax_id;?> = total * <?php echo ($tax->tax_value / 100);?>;
      }else{
        tax<?php echo $tax->tax_id;?> = <?php echo $tax->tax_value;?>;
      }
      console.log(<?php echo $tax->tax_value;?>);
      totaltax += tax<?php echo $tax->tax_id;?>;
    <?php } ?>
    grosstotal = total + totaltax;
    let data = {
      'payment' : 'Payment',
      'amount' : payment
    }
    alldeductions.push(data);

    alldeductions.forEach(x=>{
      totaldeductions += x.amount;
    });
    grosstotal = grosstotal - totaldeductions;

    let newelement = document.createElement('tr');
    content.appendChild(newelement);
    ls +=`<tr>
    <td></td>
    <td></td>
    <td class="text-right"><h4><strong>Payment:</strong></h4></td>
    <td class="text-center text-danger"><h1><strong>&#8369; ${payment}</strong></h1></td>
    </tr>`;
    newelement.innerHTML = ls;


    $('#balancehtml').html('<h1> &#8369; '+parseFloat(grosstotal).toFixed(2)+'</h1>')
  });

  $('#couponsubmit').click(function(){
    let content = document.querySelector('#deductions');
    let code = $('#couponcode').val();
    let ls = '';
    let total = 0;
    let tax = 0;
    let grosstotal = 0;
    let coup = 0;
    let totaldeductions = 0;

    allprice.forEach(x=>{
      total += x.price;
    });



    let totaltax = 0;


    <?php foreach($taxes as $tax){?>
      let tax<?php echo $tax->tax_id;?> = 0;
      if(<?php echo $tax->is_percent;?> == 1){

        tax<?php echo $tax->tax_id;?> = total * <?php echo ($tax->tax_value / 100);?>;
      }else{
        tax<?php echo $tax->tax_id;?> = <?php echo $tax->tax_value;?>;
      }
      console.log(<?php echo $tax->tax_value;?>);
      totaltax += tax<?php echo $tax->tax_id;?>;
      $('#tax<?php echo $tax->tax_id;?>').html('&#8369; '+parseFloat(tax<?php echo $tax->tax_id;?>).toFixed(2));
    <?php } ?>
    grosstotal = total + totaltax;

    <?php foreach($coupons as $key):?>

      if(code == '<?php echo $key->code; ?>'){
      // console.log(code);
      if(<?php echo $key->is_percent;?> == 1){
        coup = grosstotal * <?php echo ($key->value / 100);?>;
      }else{
        coup = <?php echo $key->value;?>;
      }

      let data = {
        'coupon' : code,
        'amount' : coup
      }
      alldeductions.push(data);

      alldeductions.forEach(x=>{
        totaldeductions += x.amount;
      });

      grosstotal = grosstotal - totaldeductions;


      let newelement = document.createElement('tr');
      newelement.setAttribute('id',"coup<?php echo $key->coupon_id;?>");
      content.appendChild(newelement);
      ls +=`<tr>
      <td></td>
      <td></td>
      <td class="text-right"><h4><strong>Code: ${code} </strong></h4></td>
      <td class="text-center text-danger"><h1><strong>&#8369; ${coup}</strong></h1></td>
      </tr>`;
      newelement.innerHTML = ls;
      // $('#deductions').html(ls);
      $('#balancehtml').html('<h1> &#8369; '+parseFloat(grosstotal).toFixed(2)+'</h1>')
    }else{
      // alert('Coupon not found');
    }
  <?php endforeach;?>

});

  function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
    try {
      decimalCount = Math.abs(decimalCount);
      decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

      const negativeSign = amount < 0 ? "-" : "";

      let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
      let j = (i.length > 3) ? i.length % 3 : 0;

      return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
      console.log(e)
    }
  }

</script>

