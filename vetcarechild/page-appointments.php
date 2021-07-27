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

?>
<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4">Appointments</h1>
			<ol class="breadcrumb mb-4">
				<li class="breadcrumb-item active">Appointments Calendar</li>
			</ol>
			<div class="row">
				<div class="col-md-12">
					<div class="card mb-4">
						<div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Appointments  <div class="float-right">
								<span class="badge badge-primary">Upcoming</span>
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
              <a class="nav-item nav-link" id="nav-sms-tab" data-toggle="tab" href="#nav-sms" role="tab" aria-controls="nav-profile" aria-selected="false">SMS</a>
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

              <form action="" method ="POST" class="needs-validation" id="form_appointments" novalidate>
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
                    <label for="scheduledate">Schedule</label>
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
                      <option>Karl</option>
                      <option>Niel</option>
                      <option>Jorsen</option>
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
<?php include "page-footer.php"; ?>
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
	let ls ='';
	$.ajax({
		url: url+"appointment/<?php echo $myclinic_id;?>",
		type: 'get',
		dataType: 'json',
		success: function(response) {
			let unique = [...new Set(response.map(item => item.title))];
			
			unique.forEach(e=>{
				ls+=`<option value="${e}">${e}</option>`;
			});
			$('#dropdownListing').html(ls);
			$('#dropdownListing').chosen({
				no_results_text: "Oops, nothing found!"
			})
		}
	});
}


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
		start: '',
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
      $('#complaints').get(0).reset() // or $('form')[0].reset()
    },
    eventRender: function(info) {

      if (info.view.type == "listWeek"){
         info.el.children[2].innerHTML = `<b>${info.event.title}</b> : <i>${info.event.extendedProps.description}</i><br>
      <b>Owner</b> : <i>${info.event.extendedProps.ownername}</i><br>
      <b>Pet</b> : <i>${info.event.extendedProps.petname}</i>
       `;
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
	loadOption();
  	$('#from_time').timepicker();
  	$('#history').DataTable();
  	$('#pet-table').DataTable();
  	displayStaff();
  	displaypets();
  	get_appointments_all();

  });


  $("#form_appointments").submit(function( event ) {
  	event.preventDefault();
  	let data = {};
  	let complaints = $('#complaints').val();
    let petname = $('#pet_list').val();
                        
  	$( this ).serializeArray().forEach(x=>{
  		data[x.name] = x.value;
  	});
  	$('#sms_form').serializeArray().forEach(x=>{
             data[x.name] = x.value;
    });
  	 data['clinic_id'] = <?php echo $myclinic_id;?>;
  	if(complaints !== "" && petname !== null){
  	$.ajax({
  		url: url+"appointment/",
  		data:data,
  		type: 'post',
  		dataType: 'json',
  		success: function(response) {
  			get_appointments_all();
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
  	}
  	return false;
  });


  function displayStaff(){
	  let ls='';
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
  async function appointment_all(id){
      let data = {
        clinic_id : <?php echo $myclinic_id;?>
    }
     let appointments = [];
    let ls ='';
   	let response =await fetch(url+"petsdetails",{method: 'POST',body : JSON.stringify(data)});
    let pets =await  response.json();
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
$('#up_pet_id').chosen({ width:'100%' });
    });

}


</script>
<?php include "each_appointment.php";?>