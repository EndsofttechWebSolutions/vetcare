function appointment_all(id){
  let appointments = [];
  let ls ='';
  appointments = <?php get_appointments_all();?>;
  appointments.forEach(x=>{
    if(id == x.appointment_id){
     ls =`<div class="container-fluid content-body">
     <nav class="mb-4">
     <div class="nav nav-tabs" id="nav-tab" role="tablist">
     <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-details" role="tab" aria-controls="nav-home" aria-selected="true">Details</a>
     <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-sms" role="tab" aria-controls="nav-profile" aria-selected="false">SMS</a>
     </div>
     </nav>
     <div class="tab-content" id="nav-tabContent">
     <div class="tab-pane fade show active" id="nav-details" role="tabpanel" aria-labelledby="nav-home-tab">
     <form action="" method ="POST" class="needs-validation" novalidate>
     <div class="form-row">
     <div class="col-md-12 mb-2">
     <label for="validationCustom01">Name of Pet</label>
     <input type="text" class="form-control" id="validationCustom01" placeholder="Search Pet Name..." value="${x.pet_id}" name="petID" required>
     <div class="valid-feedback">
     Looks good!
     </div>
     </div>
     <div class="col-md-12 mb-3">
     <label for="validationCustom03">Remarks</label>
     <textarea type="text" class="form-control" id="validationCustom03" placeholder="Remarks" required>${x.complaints}</textarea>
     <div class="invalid-feedback">
     Please provide a valid Remarks.
     </div>
     </div>
     <div class="col-md-4 mb-2">
     <label for="validationCustom02">Service Type</label>
     <select class="form-control" id="exampleFormControlSelect1" name="servicename">`
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
   <input type="text" class="form-control" id="scheduledate" name="schedule" value="${x.start_date}" readonly required>
   <div class="valid-feedback">
   Looks good!
   </div>
   </div>
   <div class="col-md-4 mb-2 bootstrap-timepicker timepicker">
   <label for="scheduledate">Time</label>
   <input type="text" class="form-control input-small" id="from_time" name="from_time" value="${x.from_time}" required>
   <div class="valid-feedback">
   Looks good!
   </div>
   </div>
   <div class="col-md-12 mb-2">
   <label for="validationCustom03">Assigned To</label>
   <select class="form-control" id="validationCustom03" name="staffid" value="${x.staff_id}">
   <option>Karl</option>
   <option>Niel</option>
   <option>Jorsen</option>
   </select>
   <div class="valid-feedback">
   Looks good!
   </div>
   </div>
   </div>
   <button class="btn btn-info float-right " type="submit" name="" id="saveAppointment">View Details</button>
   <button class="btn btn-primary float-right mr-2" type="submit" name="" id="saveAppointment">Update Appointment</button>
   </form>
   </div>
   <div class="tab-pane fade" id="nav-sms" role="tabpanel" aria-labelledby="nav-profile-tab">
   <div class="form-row">
   <div class="col-md-5 mb-2">
   <label for="validationCustom02">Next Schedule Notify</label>
   <select class="form-control" id="exampleFormControlSelect1">
   <option value="5">5 Days Before</option>
   <option value="3">3 Days Before</option>
   <option value="1">1 Day Before</option>
   <option value="now">Now</option>
   </select>
   </div>
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
}