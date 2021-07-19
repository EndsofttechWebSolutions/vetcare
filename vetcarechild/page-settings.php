<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>
<?php

global $post, $current_user; wp_get_current_user();
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
if(!empty($user_roles)){
  if (in_array( 'um_clinic', $user_roles, true ) || in_array( 'um_doctors', $user_roles, true )  || in_array( 'um_groomers', $user_roles, true ) ) {
    $clinic_name = get_user_meta($myclinic_id,'first_name',true).' '.get_user_meta($myclinic_id,'last_name',true);
    $clinic_address = get_user_meta($myclinic_id,'address',true);
    $mobile_number = get_user_meta($myclinic_id,'mobile_number',true);
    $landline = get_user_meta($myclinic_id,'landline',true);
  }else{
    $clinicinfo = getDetails(array("1",'clinic_id','vet_clinic'));
    foreach ($clinicinfo as $key ) {
      $clinic_name = $key->name;
      $clinic_address = $key->address;
      $mobile_number = $key->mobile_number;
      $landline = $key->landline;
    }
  }
}
$test_data = getFieldData('test',$myclinic_id);
$status_data = getFieldData('status',$myclinic_id);

?>
<style media="screen">
  #waiver_form .modal-dialog,
  #waiver_form .modal-content {
    /* 80% of window height */
    height: 100%;
  }

  #waiver_form .modal-body.modal-body {
    /* 100% = dialog height, 120px = header + footer */
    max-height: calc(100% - 120px);

  }

  div#list-tab {
    overflow: hidden;
  }

  /*Waiver Form*/
  .note-editable.card-block {
    min-height: 250px!important;
  }

</style>
<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid">
      <!-- <h1 class="mt-4">Dashboard</h1> -->
      <ol class="breadcrumb mb-4 mt-2">
        <li class="breadcrumb-item active ">Settings</li>
      </ol>
      <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
          <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-clinic" role="tab" aria-controls="nav-home" aria-selected="true">Clinic Details</a>
          <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-sms" role="tab" aria-controls="nav-sms" aria-selected="false">SMS Settings</a>
          <a class="nav-item nav-link" id="nav-waiver-tab" data-toggle="tab" href="#nav-waiver" role="tab" aria-controls="nav-waiver" aria-selected="false">Waiver</a>

          <!-- <a class="nav-item nav-link" id="nav-banner-tab" data-toggle="tab" href="#nav-banner" role="tab" aria-controls="nav-banner" aria-selected="false">Banner</a> -->
          <a class="nav-item nav-link" id="nav-appointment-tab" data-toggle="tab" href="#nav-appointment" role="tab" aria-controls="nav-appointment" aria-selected="false">Appointment Fields</a>
          <?php if(get_site_url() == 'https://vaxilifecorp.com'){ ?>
            <a class="nav-item nav-link" id="nav-consent-tab" data-toggle="tab" href="#nav-consent" role="tab" aria-controls="nav-consent" aria-selected="false">Inventory</a>
          <?php   } ?>
        </div>
      </nav>

      <div class="tab-content" id="nav-tabContent">
        <!--Clinic Tab-->
        <div class="tab-pane fade show active" id="nav-clinic" role="tabpanel" aria-labelledby="nav-home-tab">
          <form action="" method="POST" class="needs-validation" id="clinic_details" novalidate>
            <div class="form-row">
              <div class="col-md-12 mb-2">
                <label for="owner_id">Clinic Name</label>
                <input type="text" class="form-control" id="send_id" placeholder="Clinic Name" value="<?php echo $clinic_name;?>" name="clinic_name" readonly>
              </div>
              <div class="col-md-12 mb-2">
                <label for="owner_id">Address</label>
                <textarea type="date" class="form-control" id="validationCustom03" placeholder="Address" name="address" required><?php echo $clinic_address;?></textarea>
              </div>
              <div class="col-md-12 mb-2">
                <label for="owner_id">Mobile Number</label>
                <input type="text" class="form-control" id="password" placeholder="Mobile Number" value="<?php echo $mobile_number;?>" name="mobile_number" required>
              </div>
              <div class="col-md-12 mb-2">
                <label for="owner_id">Landline Number</label>
                <input type="text" class="form-control" id="password" placeholder="Landline Number" value="<?php echo $landline;?>" name="landline" required>
              </div>
            </div>
          </form>
          <button type="button" class="btn btn-success float-right" id="updateclinicbutton">
            <i class="fa fa-checked"></i> Save Changes
          </button>
        </div>
        <!--SMS Tab-->
        <div class="tab-pane fade" id="nav-sms" role="tabpanel" aria-labelledby="nav-profile-tab">

          <div class="card">
            <div class="card-header">
              <h5>SMS Settings</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">Account</a>
                    <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Message</a>
                    <a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Sending</a>

                  </div>
                </div>
                <div class="col-9">
                  <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                      <div class="col">
                        <div class="col-lg-12">
                          <div class="card mb-2">
                            <div class="card-header"><i class="fas fa-table mr-1"></i>SMS Account
                              <button type="button" class="btn btn-success float-right" id="saveSMSAccount">
                                <i class="fa fa-checked"></i> Save Changes
                              </button>
                            </div>
                            <div class="card-body">
                              <form action="" method="POST" class="needs-validation" id="smsaccount-form" novalidate>
                                <div class="form-row">
                                  <div class="col-md-12 mb-2">
                                    <label for="owner_id">Send ID</label>
                                    <input type="text" class="form-control" id="send_id" placeholder="Send ID" value="<?php echo $sender_id;?>" name="sendID" required>
                                  </div>
                                  <div class="col-md-12 mb-2">
                                    <label for="owner_id">Account Name</label>
                                    <input type="text" class="form-control" id="account_name" placeholder="Account Name" value="<?php echo $username_sms;?>" name="accountName" required>
                                  </div>
                                  <div class="col-md-12 mb-2">
                                    <label for="owner_id">Password</label>
                                    <input type="text" class="form-control" id="password" placeholder="Password" value="<?php echo $password_sms;?>" name="password" required>
                                  </div>
                                  <!-- <div class="col-md-12 mb-2">

                                    <label for="owner_id">Sending Time</label>
                                    <input type="time" class="form-control" id="sendingtime" value="<?php echo $time_sms;?>" name="time_sms" required>
                                  </div> -->
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                      <div class="col">
                        <div class="col-lg-12">
                          <div class="card mb-2">
                            <div class="card-header"><i class="fas fa-table mr-1"></i>SMS Message Template
                              <button type="button" class="btn btn-success float-right" id="savecontentMessage">
                                <i class="fa fa-checked"></i> Save Changes
                              </button>
                            </div>
                            <div class="card-body">
                              <form action="" method="POST" class="needs-validation" novalidate>
                                <div class="form-row">
                        <!--    <div class="col-md-12 mb-2">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" placeholder="Template Title" value="" name="title" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                              </div> -->
                              <div class="col-md-12 mb-2">
                                <label for="content">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="5"><?php echo $content_message?></textarea>
                                <div class="valid-feedback">
                                  Looks good!
                                </div>
                              </div>
                              <div class="notes">
                                <p><strong style="color: red;">Note:</strong> Please ensure that you have the following items in your message and it shouldn't be more than 160 characters long. Use keys below to add details.</p>
                                <li><span style="color: red;">{firstname}</span> - First Name of the customer</li>
                                <li><span style="color: red;">{services}</span>- Service Name (e.g. Checkup, Grooming)</li>
                                <li><span style="color: red;">{daysnotif}</span> - Schedule date</li>
                                <li><span style="color: red;">{pet_name}</span> - Pet name</li>
                                <li><span style="color: red;">{contact_number}</span> - Clinic contact number</li>
                              </div>
                              <br>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
                  <input type="hidden" id="number" hidden>
                  <input type="hidden" id="message" hidden>
                  <input type="hidden" id="datetosend" hidden>
                  <div class="col">
                    <div class="col-lg-12">
                      <div class="card mb-2">
                        <div class="card-header"><i class="fas fa-table mr-1"></i>SMS Sending
                          <button type="button" class="btn btn-success float-right" id="bulksms">
                            <i class="fa fa-checked"></i>Bulk Send
                          </button>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
                            <table class="table table-bordered hover" id="smstable" width="100%" cellspacing="0">
                              <thead>
                                <tr>
                                  <th>SMS No.</th>
                                  <th>Message</th>
                                  <th>Date to Send</th>
                                  <th>Status</th>
                                  <th>Action</th>

                                </tr>
                              </thead>
                            </table>
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
      </div>

    </div>
    <!--/SMS Tab-->
    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>

    <!--Waiver Tab-->
    <div class="tab-pane fade" id="nav-waiver" role="tabpanel" aria-labelledby="nav-waiver-tab">
      <div class="col">
        <div class="col-lg-12">
          <div class="card mb-2">
            <div class="card-header"><i class="fas fa-table mr-1"></i>Waiver Table
              <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#waiver_form">
                <i class="fa fa-plus"></i>
              </button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped" id="waiverTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Title</th>

                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/waiver Tab-->

    <!--Consent Tab-->
    <div class="tab-pane fade" id="nav-consent" role="tabpanel" aria-labelledby="nav-consent-tab">
      <div class="card">
        <div class="card-header">
          Inventory List
          <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#inventory_form">
            <i class="fa fa-plus"></i> Add New
          </button>
        </div>

        <div class="card-body">
          <div class="table-responsive">
           <!--  <table class="table table-striped" id="inventoryTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Stocks</th>
                  <th>Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table> -->
          </div>
        </div>
      </div>
    </div>
    <!--/Consent Tab-->


    <!--Appointment Fields Tab-->
    <div class="tab-pane fade" id="nav-appointment" role="tabpanel" aria-labelledby="nav-appointment-tab">
      <div class="card">
        <div class="card-header">
          Appointment Fields
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <div class="list-group" id="list-tab" role="tablist">
                <a class="list-group-item list-group-item-action active" id="list-status-list" data-toggle="list" href="#list-status" role="tab" aria-controls="status">Status</a>
                <a class="list-group-item list-group-item-action" id="list-test-list" data-toggle="list" href="#list-test" role="tab" aria-controls="test">Test</a>
                <?php if(get_site_url() == 'https://vaxilifecorp.com'){ ?>
                  <a class="list-group-item list-group-item-action" id="list-tax-list" data-toggle="list" href="#list-tax" role="tab" aria-controls="test">Tax Settings</a>
                  <!--<a class="list-group-item list-group-item-action" id="list-tax-list" data-toggle="list" href="#list-discount" role="tab" aria-controls="test">Discount Settings</a>-->
                <?php } ?>
              </div>
            </div>
            <div class="col-8">
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="list-status" role="tabpanel" aria-labelledby="list-status-list">
                  <button class="btn btn-primary float-right mb-3" id="addnewFields"><i class="fa fa-plus"></i>   Add New Fields</button>
                  <button class="btn btn-primary float-right mb-3 mr-3" onclick="insertDefaultFields('status')"><i class="fa fa-plus"></i>    Insert Default Fields</button> 
                  <div class="table-responsive">
                    <table class="table table-bordered" id="status_fields" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Fields</th>
                          <!-- <th>Price</th> -->
                          <th>Action</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

                <div class="tab-pane fade" id="list-test" role="tabpanel" aria-labelledby="list-test-list">
                 <button class="btn btn-primary float-right mb-3" id="addnewFieldsTest"><i class="fa fa-plus"></i>   Add New Fields</button>
                 <button class="btn btn-primary float-right mb-3 mr-3" onclick="insertDefaultFields('test')"><i class="fa fa-plus"></i>  Insert Default Fields</button> 
                 <div class="table-responsive">
                  <table class="table table-bordered" id="test_fields" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Fields</th>
                        <!-- <th>Price</th> -->
                        <th>Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="list-tax" role="tabpanel" aria-labelledby="list-test-list">
                <!-- <label for="validationCustom02">Set Tax Percentage (%)</label> -->
                <button type="button" class="btn btn-primary float-right mb-3" data-toggle="modal" data-target="#tax_form">
                  <i class="fa fa-plus"></i> Add New
                </button>

                <div class="table-responsive">
                  <table class="table table-bordered" id="tax_fields" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Fields</th>
                        <th>Value</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="list-discount" role="tabpanel" aria-labelledby="list-test-list">
                <button type="button" class="btn btn-primary float-right mb-3" data-toggle="modal" data-target="#coupon_form">
                  <i class="fa fa-plus"></i> Add New
                </button>

                <div class="table-responsive">
                  <table class="table table-bordered" id="coupon_table" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Code</th>
                        <th>Value</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/Appointment Fields Tab-->
</div>
</div>
</main>
<!-- Modal Update Status and Test Field -->
<div class="modal fade" id="updatefieldform" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Field</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="updatefield_content">

      </div>
    </div>
  </div>
</div>

<!-- Modal Waiver-->
<div class="modal fade" id="waiver_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Waiver Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST" class="needs-validation" id="addnewWaiver_form" novalidate>
          <div class="form-row">
            <div class="col-md-12 mb-2">
              <label for="waiver_title">Title</label>
              <input type="text" class="form-control" id="waiver_title" placeholder="Waiver Title" value="" name="waiver_title" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="waiver_description">Descriptions</label>
              <textarea class="form-control " id="waiver_summernote" name="waiver_content" rows="5" ></textarea>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>

          </div>
        </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary float-right" onclick="addwaiver()">Save Waiver</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Consent-->
<div class="modal fade" id="inventory_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST" class="needs-validation" id="product-form" novalidate>
          <div class="form-row">
            <div class="col-md-12 mb-2">
              <label for="waiver_title">Product Name</label>
              <input type="text" class="form-control" id="product_name" placeholder="Name" value="" name="product_name" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="waiver_title">Stock</label>
              <input type="number" class="form-control" id="product_stock" placeholder="0" value="" name="stock" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="waiver_title">Price</label>
              <input type="number" class="form-control" id="product_price" placeholder="0.00" value="" name="price" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>

            <div class="col-md-12 mb-2">
              <label for="waiver_description">Description</label>
              <textarea class="form-control" id="product_description" name="content" rows="5"></textarea>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>

          </div>
          <button type="submit" class="btn btn-primary float-right mt-3"" >Save Product</button>
        </form>
        <div class="modal-footer">

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tax Form-->
<div class="modal fade" id="tax_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Tax Field</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST" class="needs-validation" id="tax-form" novalidate>
          <div class="form-row">
            <div class="col-md-4 mb-2">
              <label for="waiver_title">Tax Name</label>
              <input type="text" class="form-control" id="tax_name" placeholder="Name" value="" name="tax_name" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="waiver_title">Value</label>
              <input type="number" class="form-control" id="tax_value" placeholder="0.00" value="" name="tax_value" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>

            <div class="col-md-4 mb-2">
              <div class="form-group">
                <label for="waiver_description">Property</label>
                <select class="form-control" id="property" name="property">
                  <option value="Percent" selected>Percentage</option>
                  <option value="Fixed">Fixed</option>
                </select>
              </div>
            </div>

          </div>
          <button type="submit" class="btn btn-primary float-right mt-3">Save</button>
        </form>
        <div class="modal-footer">

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Coupon Form-->
<div class="modal fade" id="coupon_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Coupon</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST" class="needs-validation" id="coupon-form" novalidate>
          <div class="form-row">
            <div class="col-md-4 mb-2">
              <label for="waiver_title">Code</label>
              <div class="input-group">
               <input type="text" class="form-control" id="coupon" placeholder="Name" value="" name="coupon" required>
               <div class="input-group-append">
                <button type="button" class="btn btn-primary" id="generatecode">Generate Coupon</button>
              </div>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>

          </div>
          <div class="col-md-4 mb-2">
            <label for="waiver_title">Value</label>
            <input type="number" class="form-control" id="coupon_value" placeholder="0.00" value="" name="coupon_value" required>
            <div class="valid-feedback">
              Looks good!
            </div>
          </div>

          <div class="col-md-4 mb-2">
            <div class="form-group">
              <label for="waiver_description">Property</label>
              <select class="form-control" id="coupon_property" name="coupon_property">
                <option value="Percent" selected>Percentage</option>
                <option value="Fixed">Fixed</option>
              </select>
            </div>
          </div>

        </div>
        <button type="submit" class="btn btn-primary float-right mt-3">Save</button>
      </form>
      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</div>
<!-- Update Modal Waiver-->
<div class="modal fade" id="updatewaiver_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Waiver Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="updatewaiver_content">

      </div>
    </div>
  </div>
</div>

<!-- Update Modal Consent-->
<div class="modal fade" id="updateconsent_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Consent Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="updateconsent_content">

      </div>
    </div>
  </div>
</div>

<!-- Update Modal Consent-->
<div class="modal fade" id="updateproduct_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="updateproduct_content">

      </div>
    </div>
  </div>
</div>

<!-- Modal Consent-->
<div class="modal fade" id="addnewfield" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Status Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST" class="needs-validation" id="singlestatus_form" novalidate>
          <div class="form-row">
            <div class="col-md-6 mb-2">
              <label for="waiver_title">Status Field Name</label>
              <input type="text" class="form-control" id="singlestatus_field" placeholder="Status Field" value="" name="singlestatus_field" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="waiver_title">Price</label>
              <input type="text" class="form-control" id="singlestatus_price" placeholder="0.00" value="" name="singlestatus_price" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>
          </div>
        </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary float-right" onclick="addsingleField('status')">Add New</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Consent-->
<div class="modal fade" id="addnewfieldtest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Test Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST" class="needs-validation" id="singletest_form" novalidate>
          <div class="form-row">
            <div class="col-md-6 mb-2">
              <label for="waiver_title">Test Field Name</label>
              <input type="text" class="form-control" id="singletest_field" placeholder="Test Field" value="" name="singletest_field" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="waiver_title">Price</label>
              <input type="text" class="form-control" id="singletest_price" placeholder="0.00" value="" name="singletest_price" required>
              <div class="valid-feedback">
                Looks good!
              </div>
            </div>
          </div>
        </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary float-right" onclick="addsingleField('test')">Add New</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Update Modal Tax-->
<div class="modal fade" id="updatetax_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Tax Field</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="updatetax_content">

      </div>
    </div>
  </div>
</div>

<?php include "page-footer.php"; ?>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/initialize-summernote.js"></script>
<script>
  let waiver=[];
  let consent=[];
  var ifConnected = window.navigator.onLine;
  $(document).ready(function(){
    if (ifConnected) {

      let timerInterval;
      Swal.fire({
        title: 'SMS Messages!',
        html: 'Sending SMS Message in the cloud',
        timer: 2000,
        timerProgressBar: true,
        onBeforeOpen: () => {
          Swal.showLoading();
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
          bulksend();

        }
      })
    }else{
      Swal.fire(
        'The Internet?',
        'Please Check Internet Connection',
        'question'
        );
    }

    $('#saveSMSAccount').click(function(){
      let data = {};
      $('#smsaccount-form').serializeArray().forEach(x=>{
        data[x.name] = x.value;
      });
      data['clinic_id'] = <?php echo currentLogged_in();?>

      $.ajax({
        url: url+"smsaccountapi",
        data:JSON.stringify(data),
        type: 'post',
        dataType: 'json',
        success: function(response) {
          if(response.message == "OK"){
                    // $('#updatewaiver_form').modal('hide');
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: 'SMS Account Updated!',
                      showConfirmButton: false,
                      timer: 1500
                    })
                    // waivertable.ajax.reload();
                    // reloadwaiver();
                  }
                }
              });

    });
    $('#savecontentMessage').click(function(){
      let data = {
        'content' : $('#content').val(),
        'clinic_id': <?php echo $myclinic_id;?>
      }
      $.ajax({
        url: url+"messtemp",
        data:JSON.stringify(data),
        type: 'post',
        dataType: 'json',
        success: function(response) {
          if(response.message == "OK"){
                    // $('#updatewaiver_form').modal('hide');
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: 'Message Template Updated!',
                      showConfirmButton: false,
                      timer: 1500
                    })
                    // waivertable.ajax.reload();
                    // reloadwaiver();
                  }
                }
              });

    });
    $('#waiver_summernote').summernote({
      placeholder: 'Create a Waiver form',
      tabsize: 2,
      height: 600
    });

    $('#waiverTable').DataTable({
      ajax:{
        type: 'POST',
        url : url+"waiverdata",
        data: {
         clinic_id: <?php echo $myclinic_id;?>
                   // etc..
                 }
               } 
             });

    $('#status_fields').DataTable({
      ajax:{
        type: 'POST',
        url : url+"statusdata",
        data: {
         clinic_id: <?php echo $myclinic_id;?>
                   // etc..
                 }
               } 
             });

    $('#test_fields').DataTable({
     ajax:{
      type: 'POST',
      url : url+"testdata",
      data: {
       clinic_id: <?php echo $myclinic_id;?>
                   // etc..
                 }
               } 
             });
    $('#tax_fields').DataTable({
     ajax:{
      type: 'POST',
      url : url+"tax",
      data: {
       clinic_id: <?php echo $myclinic_id;?>
                   // etc..
                 }
               } 
             });
    $('#coupon_table').DataTable({
     ajax:{
      type: 'POST',
      url : url+"coupon",
      data: {
       clinic_id: <?php echo $myclinic_id;?>
                   // etc..
                 }
               } 
             });
    $('#inventoryTable').DataTable({
      ajax:{
        type: 'POST',
        url : url+"inventory",
        data: {
         clinic_id: <?php echo $myclinic_id;?>
                   // etc..
                 }
               },
               "order": [[ 1, "asc" ]],
             });
    fetch(url+"waiver/<?php echo $myclinic_id;?>").then(res=>res.json()).then(res=>{
      waiver = res;
    });

    $('#addnewFields').click(function(){
      $('#addnewfield').modal('show');
    });
    $('#addnewFieldsTest').click(function(){
      $('#addnewfieldtest').modal('show');
    });

    $('#updateclinicbutton').click(function(){
      let data = {
        'user_id' : <?php echo $myclinic_id;?>,
      };
      $('#clinic_details').serializeArray().forEach(x=>{
        data[x.name] = x.value;
      });
      console.log(data);
      $.ajax({
        url: url+"clinicupdate",
        data:JSON.stringify(data),
        type: 'post',
        dataType: 'json',
        success: function(response) {
          if(response.message == "OK"){
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Clinic Details Updated!',
              showConfirmButton: false,
              timer: 1500
            })
          }
        }
      });
    });

    $('#savetax').click(function(){
      let taxvalue = $('#taxvalue').val();
      console.log(taxvalue);
      let data= {
        clinic_id : <?php echo $myclinic_id;?>,
        taxvalue  : taxvalue
      }
      $.ajax({
        url: url+"tax/change",
        data:JSON.stringify(data),
        type: 'post',
        dataType: 'json',
        success: function(response) {
          if(response.message == "OK"){
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Tax Value Saved!',
              showConfirmButton: false,
              timer: 1500
            });
          }

        }
      });
    });
  });
$('#smstable').DataTable({
 ajax:{
  type: 'POST',
  url : url+"messagedata",
  data: {
   clinic_id: <?php echo $myclinic_id;?>
                   // etc..
                 }
               },
               "order": [[ 2, "desc" ]],
             });
$('#content').textcounter({
  type: "letter",
  max: 159,
  stopInputAtMaximum: true,
  countDown: true,
  countDownText: "%d characters remaining",
  countSpaces: true
});

$('#bulksms').click(function(){
  let smstable = $('#smstable').DataTable();
  $.ajax({
    url: url+"sms/scheduler",
    type: 'POST',
    dataType: 'json',
    data: {
      clinic_id : <?php echo $myclinic_id;?>,
    },
    success: function(response) {
      if(response.message == "OK"){
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: 'SMS Schedule Saved!',
          showConfirmButton: false,
          timer: 1500
        });
        smstable.ajax.reload();
      }

    }
  });
  return false;
});
function bulksend(){
  let smstable = $('#smstable').DataTable();
  $.ajax({
    url: url+"sms/scheduler",
    type: 'POST',
    dataType: 'json',
    data: {
      clinic_id : <?php echo $myclinic_id;?>,
    },
    success: function(response) {
      if(response.message == "OK"){
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: 'SMS Schedule Saved!',
          showConfirmButton: false,
          timer: 1500
        });
        smstable.ajax.reload();
      }

    }
  });
}

function showRow(row){
  var x=row.cells;
  document.getElementById("number").value = x[0].innerHTML;
  document.getElementById("message").value = x[1].innerHTML;
  document.getElementById("datetosend").value = x[2].innerHTML;
}
function updateWaiver(id){
  $('#updatewaiver_form').modal('show');
  let ls='';
  waiver.forEach(x=>{
    if(id == x.waiver_id){

      ls +=`<form action="" class="needs-validation" id="updateWaiver_form" novalidate>
      <div class="form-row">
      <div class="col-md-12 mb-2">
      <label for="waiver_title">Title</label>
      <input type="text" class="form-control" id="waiver_title_update" placeholder="Waiver Title" value="${x.waiver_title}" name="waiver_title" required>
      <div class="valid-feedback">
      Looks good!
      </div>
      </div>
      <div class="col-md-12 mb-2">
      <label for="waiver_description">Descriptions</label>
      <textarea class="form-control summernote" id="waiver_update" name="waiver_content" rows="5">${x.waiver_content}</textarea>
      <div class="valid-feedback">
      Looks good!
      </div>
      </div>

      </div>
      </form>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary float-right" onclick="savewaiverbutton(${x.waiver_id})">Save Waiver</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>`;
    }
  });

  $('#updatewaiver_content').html(ls);
  $('#waiver_update').summernote('code', $('#waiver_update').val());
}
function savewaiverbutton(id){
  let waivertable = $('#waiverTable').DataTable();
  let data = {
    'waiver_id' : id,
    'waiver_title' : $('#waiver_title_update').val(),
    'waiver_content': $('#waiver_update').summernote('code')
  };

  $.ajax({
    url: url+"waiver/redo",
    data:JSON.stringify(data),
    type: 'post',
    dataType: 'json',
    success: function(response) {
      if(response.message == "OK"){
        $('#updatewaiver_form').modal('hide');
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: 'Waiver Details Updated!',
          showConfirmButton: false,
          timer: 1500
        })
        waivertable.ajax.reload();
        reloadwaiver();
      }
    }
  });


}

function addwaiver(){
  let waivertable = $('#waiverTable').DataTable();
  let data = {
    'clinic_id' : <?php echo $myclinic_id;?>,
    'waiver_title' : $('#waiver_title').val(),
    'waiver_content': $('#waiver_summernote').summernote('code')
  };
        // $('#addnewWaiver_form').serializeArray().forEach(x=>{
        //  data[x.name] = x.value;
        // });
        // console.log(data);
        if(data['waiver_title'] !== "" && data['waiver_content'] !== ""){
          $.ajax({
            url: url+"waiver",
            data:JSON.stringify(data),
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response.length);
                
              }
            });
          $('#waiver_form').modal('hide');
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'New Waiver Added!',
            showConfirmButton: false,
            timer: 1500
          })
          waivertable.ajax.reload();
          reloadwaiver();
        }else{
          Swal.fire({
            position: 'top-end',
            icon: 'info',
            title: 'Please Complete Details before Submitting!',
            showConfirmButton: false,
            timer: 1500
          })
        }
        
      }

      function deleteWaiver(id){
        let waivertable = $('#waiverTable').DataTable();
        let data ={
          'waiver_id': id,
          'action' :'delete'
        }

        Swal.fire({
          title: 'Are you sure?',
          text: "Once deleted, you will not be able to recover this doctor details!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: url+"waiver/remove",
              data:data,
              type: 'post',
              dataType: 'json',
              success: function(response) {
                if(response.message == "OK"){
                  Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Waiver Details Deleted!',
                    showConfirmButton: false,
                    timer: 1500
                  })
                  waivertable.ajax.reload();
                }
              }
            });
          }
        });


      }

      function insertDefaultFields(table){
        let statustable = $('#status_fields').DataTable();
        let testtable = $('#test_fields').DataTable();
        let timerInterval
        Swal.fire({
          title: 'Saving Default Fields!',
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
            if(table == 'status'){
              $.ajax({
                url: url+"status/fields/<?php echo $myclinic_id;?>",
                data:{'action':'Insert Default'},
                type: 'post',
                dataType: 'json',
                success: function(response) {
                  if(response.message =="OK"){
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: 'Status Default Details Added!',
                      showConfirmButton: false,
                      timer: 1500
                    });
                    statustable.ajax.reload();
                  }
                }
              });


            }else if(table == 'test'){
              $.ajax({
                url: url+"test/fields/<?php echo $myclinic_id;?>",
                data:{'action':'Insert Default'},
                type: 'post',
                dataType: 'json',
                success: function(response) {
                  if(response.message =="OK"){
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: 'Test Default Details Added!',
                      showConfirmButton: false,
                      timer: 1500
                    })
                    testtable.ajax.reload();
                  }
                }
              });
            }

          }
        });
      }

      function deleteStatusFields(id,table){
        let statustable = $('#status_fields').DataTable();
        let testtable = $('#test_fields').DataTable();
        let data ={
          'status_field_id': id,
          'action' :'delete'
        }


        let testdata ={
          'test_field_id': id,
          'action' :'delete'
        }

        Swal.fire({
          title: 'Are you sure?',
          text: "Once deleted, you will not be able to recover this status fields details!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            if(String(table) == "status"){
              $.ajax({
                url: url+"status/fields/remove",
                data:data,
                type: 'post',
                dataType: 'json',
                success: function(response) {
                  if(response.message == "OK"){
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: 'Status Field Deleted!',
                      showConfirmButton: false,
                      timer: 1500
                    });
                    statustable.ajax.reload();
                  }
                }
              });

            }else if(table == 'test'){
              $.ajax({
                url: url+"test/fields/remove",
                data:testdata,
                type: 'post',
                dataType: 'json',
                success: function(response) {
                  if(response.message == "OK"){
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: 'Test Field Deleted!',
                      showConfirmButton: false,
                      timer: 1500
                    });
                    testtable.ajax.reload();
                  }
                }
              });

            }

          }
        })
      }
      function addsingleField(table){
        let statustable = $('#status_fields').DataTable();
        let testtable = $('#test_fields').DataTable();
        let clinic_id = <?php echo $myclinic_id;?>;
        if(table == 'status'){
          $('#addnewfield').modal('toggle');
          $.ajax({
            url: url+"status/single",
            data:{
              'clinic_id' : clinic_id,
              'fldname':$('#singlestatus_field').val(),
              'price' : $('#singlestatus_price').val()
            },
            type: 'post',
            dataType: 'json',
            success: function(response) {
              if(response.message == "OK"){
                Swal.fire({
                  position: 'top-end',
                  icon: 'success',
                  title: 'Status Field Details Added!',
                  showConfirmButton: false,
                  timer: 1500
                });
                statustable.ajax.reload();
              }else{
                Swal.fire({
                  position: 'top-end',
                  icon: 'warning',
                  title: 'Status Field Exist!',
                  showConfirmButton: false,
                  timer: 1500
                });
              }

            }
          });
        }else if(table == 'test'){
          $('#addnewfieldtest').modal('toggle');
          $.ajax({
            url: url+"test/single",
            data:{
              'clinic_id' : clinic_id,
              'fldname':$('#singletest_field').val(),
              'price':$('#singletest_price').val()
            },
            type: 'post',
            dataType: 'json',
            success: function(response) {
              if(response.message == "OK"){
                Swal.fire({
                  position: 'top-end',
                  icon: 'success',
                  title: 'Test Field Details Added!',
                  showConfirmButton: false,
                  timer: 1500
                })
                testtable.ajax.reload();
              }else{
                Swal.fire({
                  position: 'top-end',
                  icon: 'warning',
                  title: 'Test Field Exist!',
                  showConfirmButton: false,
                  timer: 1500
                });
              }

            }
          });
        }

      }

      function reloadwaiver(){
        waiver = [];
        fetch(url+"waiver").then(res=>res.json()).then(res=>{
          waiver = res;
        });
      }

      function deletemessage(id){
        let smstable = $('#smstable').DataTable();
        let data = {
          action : 'delete'
        }
        Swal.fire({
          title: 'Are you sure?',
          text: "Once deleted, you will not be able to recover this details!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: url+"sms/remove/"+id,
              data:data,
              type: 'post',
              dataType: 'json',
              success: function(response) {
                if(response.message == "OK"){
                  Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Message Deleted!',
                    showConfirmButton: false,
                    timer: 1500
                  })
                  smstable.ajax.reload();
                }
              }
            });
          }
        });
      }
    // function addproduct(){
      $('#product-form').submit(function(event){
       event.preventDefault();
       let inventoryTable = $('#inventoryTable').DataTable();
       let datas = {};
       $('#product-form').serializeArray().forEach(x=>{
        datas[x.name] = x.value;
      });
       datas['author_id'] = '<?php echo $myclinic_id;?>';
       console.log(datas);

       if(datas['content'] !== "" && datas['price'] !== "" && datas['product_name'] !== "" && datas['stock'] !== ""){
        $.ajax({
          url: url+"products",
          data:JSON.stringify(datas),
          type: 'post',
          dataType: 'json',
          success: function(response) {
            if(response.message == "OK"){
              Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'New Product Added!',
                showConfirmButton: false,
                timer: 1500
              })
              inventoryTable.ajax.reload();
              $('#inventory_form').modal('hide');
                //      location.reload();
              }
            }
          });
      }
      document.getElementById('product-form').reset();
    });

      $('#tax-form').submit(function(event){
       event.preventDefault();
       let taxTable = $('#tax_fields').DataTable();
       let datas = {};
       $(this).serializeArray().forEach(x=>{
        datas[x.name] = x.value;
      });
       datas['clinic_id'] = '<?php echo $myclinic_id;?>';
           // console.log(datas);

           if(datas['property'] !== "" && datas['tax_name'] !== "" && datas['tax_value'] !== ""){
            $.ajax({
              url: url+"tax/new",
              data:JSON.stringify(datas),
              type: 'post',
              dataType: 'json',
              success: function(response) {
                if(response.message == "OK"){
                  Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'New Tax Field Added!',
                    showConfirmButton: false,
                    timer: 1500
                  })
                  taxTable.ajax.reload();
                  $('#tax_form').modal('hide');
                }
              }
            });
          }
        });



    // }
    function updateProduct(id){
      let ls= '';
      $('#updateproduct_form').modal('show');
      fetch(url+'products/<?php echo $myclinic_id;?>').then(res=>res.json()).then(res=>{
            // console.log(res);
            res.forEach(x=>{
              if(x.product_id == id){
                ls +=`
                <form action="" method="POST" class="needs-validation" id="upproduct-form" novalidate>
                <div class="form-row">
                <div class="col-md-12 mb-2">
                <label for="waiver_title">Product Name</label>
                <input type="text" class="form-control" id="product_name" placeholder="Name" value="${x.product_title}" name="product_name" required>
                <div class="valid-feedback">
                Looks good!
                </div>
                </div>
                <div class="col-md-6 mb-2">
                <label for="waiver_title">Stock</label>
                <input type="number" class="form-control" id="product_stock" placeholder="0" value="${x.product_quantity}" name="stock" required>
                <div class="valid-feedback">
                Looks good!
                </div>
                </div>
                <div class="col-md-6 mb-2">
                <label for="waiver_title">Price</label>
                <input type="number" class="form-control" id="product_price" placeholder="0.00" value="${x.product_price}" name="price" required>
                <div class="valid-feedback">
                Looks good!
                </div>
                </div>

                <div class="col-md-12 mb-2">
                <label for="waiver_description">Description</label>
                <textarea class="form-control" id="product_description" name="content" rows="5">${x.product_content}</textarea>
                <div class="valid-feedback">
                Looks good!
                </div>
                </div>

                </div>
                </form>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary float-right" onclick="saveproduct(${x.product_id})">Save Product</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                `
              }
            });
            $('#updateproduct_content').html(ls);
          });
    }
    function saveproduct(id){
     let inventoryTable = $('#inventoryTable').DataTable();
     let data = {};
     $('#upproduct-form').serializeArray().forEach(x=>{
      data[x.name] = x.value;
    });
     data['product_id'] = id;
     data['action'] = 'update';
     console.log(data);
     $.ajax({
      url: url+"modify/product",
      data:JSON.stringify(data),
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if(response.message == "OK"){
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Update Success!',
            showConfirmButton: false,
            timer: 1500
          })
          inventoryTable.ajax.reload();
                //      location.reload();
              }
            }
          });
   }
   function deleteProduct(id){
     let inventoryTable = $('#inventoryTable').DataTable();
     let data = {
      product_id : id,
      action:'delete'};
      $.ajax({
        url: url+"modify/product",
        data:JSON.stringify(data),
        type: 'post',
        dataType: 'json',
        success: function(response) {
          if(response.message == "OK"){
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Removed Product Successfully',
              showConfirmButton: false,
              timer: 1500
            })
            inventoryTable.ajax.reload();
                //      location.reload();
              }
            }
          });
    }

    function updateTax(id){
      let ls= '';
      $('#updatetax_form').modal('show');
      fetch(url+'tax/<?php echo $myclinic_id;?>').then(res=>res.json()).then(res=>{
            // console.log(res);
            res.forEach(x=>{
              if(x.tax_id == id){
                ls +=`
                <form action="" method="POST" class="needs-validation" id="uptax-form" novalidate>
                <div class="form-row">
                <div class="col-md-4 mb-2">
                <label for="waiver_title">Tax Name</label>
                <input type="text" class="form-control" id="tax_name" placeholder="Name" value="${x.tax_name}" name="tax_name" required>
                <div class="valid-feedback">
                Looks good!
                </div>
                </div>
                <div class="col-md-4 mb-2">
                <label for="waiver_title">Value</label>
                <input type="number" class="form-control" id="tax_value" placeholder="0.00" value="${x.tax_value}" name="tax_value" required>
                <div class="valid-feedback">
                Looks good!
                </div>
                </div>

                <div class="col-md-4 mb-2">
                <div class="form-group">
                <label for="waiver_description">Property</label>
                <select class="form-control" id="property" name="property">`;
                if(x.is_percent == 1){
                  ls +=`<option value="Percent" selected>Percentage</option>
                  <option value="Fixed" >Fixed</option>`;
                }else{
                  ls +=`<option value="Percent" >Percentage</option>
                  <option value="Fixed" selected>Fixed</option>`;
                }

                ls +=`</select>
                </div>
                </div>
                </div>

                </form>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary float-right" id="upsavetax" onclick="upsavetax(${x.tax_id})">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                `
              }
            });
            $('#updatetax_content').html(ls);
          });
    }
    
    function upsavetax(id){
      let taxTable = $('#tax_fields').DataTable();
      let datas = {};
      $('#uptax-form').serializeArray().forEach(x=>{
        datas[x.name] = x.value;
      });
      datas['tax_id'] = id;
      datas['clinic_id'] = '<?php echo $myclinic_id;?>';
        // console.log(datas);

        if(datas['property'] !== "" && datas['tax_name'] !== "" && datas['tax_value'] !== ""){
         $.ajax({
           url: url+"tax/update",
           data:JSON.stringify(datas),
           type: 'post',
           dataType: 'json',
           success: function(response) {
             if(response.message == "OK"){
               Swal.fire({
                 position: 'top-end',
                 icon: 'success',
                 title: 'Tax Field Updated!',
                 showConfirmButton: false,
                 timer: 1500
               })
               taxTable.ajax.reload();
               $('#updatetax_form').modal('hide');
             }
           }
         });
       }else{
         Swal.fire({
           position: 'top-end',
           icon: 'info',
           title: 'Please Complete Fields!',
           showConfirmButton: false,
           timer: 1500
         })
       }
     }

     function deleteTax(id){
       let taxTable = $('#tax_fields').DataTable();
       let data = {
        tax_id : id,
        action:'delete'
      };

      Swal.fire({
        title: 'Are you sure?',
        text: "Once deleted, you will not be able to recover this details!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: url+"tax/remove",
            data:JSON.stringify(data),
            type: 'post',
            dataType: 'json',
            success: function(response) {
              if(response.message == "OK"){
                Swal.fire({
                  position: 'top-end',
                  icon: 'success',
                  title: 'Removed Tax Field Successfully',
                  showConfirmButton: false,
                  timer: 1500
                })
                taxTable.ajax.reload();
                        //      location.reload();
                      }
                    }
                  });
        }
      });

    }
    function generatecoupon(length) {
     var result           = '';
     var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
     var charactersLength = characters.length;
     for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }

  $('#generatecode').click(function(){
    let code  = 'vaxi-'+generatecoupon(5);
    $('#coupon').val(code);
  });

  $('#coupon-form').submit(function(event){
   event.preventDefault();
   let taxTable = $('#coupon_table').DataTable();
   let datas = {};
   $(this).serializeArray().forEach(x=>{
    datas[x.name] = x.value;
  });
   datas['clinic_id'] = '<?php echo $myclinic_id;?>';

   if(datas['coupon_property'] !== "" && datas['coupon'] !== "" && datas['coupon_value'] !== ""){
    $.ajax({
      url: url+"coupon/new",
      data:JSON.stringify(datas),
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if(response.message == "OK"){
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'New Coupon Added!',
            showConfirmButton: false,
            timer: 1500
          })
          taxTable.ajax.reload();
          $('#coupon_form').modal('hide');
        }
      }
    });
  }
});

  function deleteCoupon(id){
   let taxTable = $('#coupon_table').DataTable();
   let data = {
    coupon_id : id,
    action:'delete'
  };

  Swal.fire({
    title: 'Are you sure?',
    text: "Once deleted, you will not be able to recover this details!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: url+"coupon/remove",
        data:JSON.stringify(data),
        type: 'post',
        dataType: 'json',
        success: function(response) {
          if(response.message == "OK"){
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Removed Coupon Successfully',
              showConfirmButton: false,
              timer: 1500
            })
            taxTable.ajax.reload();
                        //      location.reload();
                      }
                    }
                  });
    }
  });
}

function updateStatusFields(id,table){

 let ls = '';
 if(table == 'status'){
  <?php foreach($status_data as $key):?>
    if(id == <?php echo $key->status_field_id;?>){
     ls = `<form action="" method="POST" class="needs-validation" id="upsinglestatus_form" novalidate>
     <div class="form-row">
     <div class="col-md-6 mb-2">
     <label for="waiver_title">Status Field Name</label>
     <input type="text" class="form-control" id="up_singlestatus_field" placeholder="Status Field" value="<?php echo $key->meta_key;?>" name="singlestatus_field" required>
     <div class="valid-feedback">
     Looks good!
     </div>
     </div>
     <div class="col-md-6 mb-2">
     <label for="waiver_title">Price</label>
     <input type="text" class="form-control" id="upsinglestatus_price" placeholder="0.00" value="<?php echo $key->meta_value;?>" name="singlestatus_price" required>
     <div class="valid-feedback">
     Looks good!
     </div>
     </div>
     </div>
     </form>
     <div class="modal-footer">
     <button type="button" class="btn btn-primary float-right" onclick="savefield(<?php echo $key->status_field_id;?>,'status')">Save</button>
     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
     </div>
     `;
   }
 <?php endforeach; ?>
}else{
 <?php foreach($test_data as $key):?>
  if(id == <?php echo $key->test_field_id;?>){
   ls = `<form action="" method="POST" class="needs-validation" id="uptestsingle_form" novalidate>
   <div class="form-row">
   <div class="col-md-6 mb-2">
   <label for="waiver_title">Status Field Name</label>
   <input type="text" class="form-control" id="up_singlestatus_field" placeholder="Status Field" value="<?php echo $key->meta_key;?>" name="singlestatus_field" required>
   <div class="valid-feedback">
   Looks good!
   </div>
   </div>
   <div class="col-md-6 mb-2">
   <label for="waiver_title">Price</label>
   <input type="text" class="form-control" id="upsinglestatus_price" placeholder="0.00" value="<?php echo $key->meta_value;?>" name="singlestatus_price" required>
   <div class="valid-feedback">
   Looks good!
   </div>
   </div>
   </div>
   </form>
   <div class="modal-footer">
   <button type="button" class="btn btn-primary float-right" onclick="savefield(<?php echo $key->test_field_id;?>,'test')">Save</button>
   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
   </div>
   `;
 }
<?php endforeach; ?>
}
$('#updatefieldform').modal('show');
$('#updatefield_content').html(ls);
}

function savefield(id, table){
 let status_fields = $('#status_fields').DataTable();
 let test_fields = $('#test_fields').DataTable();

 let datas = {};
 if(table == 'status'){
   $('#upsinglestatus_form').serializeArray().forEach(x=>{
    datas[x.name] = x.value;
  });
   datas['table'] = 'status';
 }else{
  $('#uptestsingle_form').serializeArray().forEach(x=>{
    datas[x.name] = x.value;
  });
  datas['table'] = 'test';
}
datas['id'] = id;
datas['clinic_id'] = '<?php echo $myclinic_id;?>';

$.ajax({
  url: url+"field/update",
  data:JSON.stringify(datas),
  type: 'post',
  dataType: 'json',
  success: function(response) {
    if(response.message == "OK"){
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Field Updated',
        showConfirmButton: false,
        timer: 1500
      })
      status_fields.ajax.reload();
      test_fields.ajax.reload();
      $('#updatefieldform').modal('hide');
    }
  }
});
}
</script>
