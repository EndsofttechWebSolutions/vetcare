<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>
<?php 
if(!is_user_logged_in()){
  wp_redirect(get_home_url());
}

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
 ?>
<style >
 .askte:after {
  content:"*";
  color:red;
}
</style>
<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4"></h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">Employees Lists</li>
			</ol>
			<br>
			<div class="col">
				<div class="col-lg-12">
					<div class="card mb-2">
						<div class="card-header"><i class="fas fa-table mr-1"></i>Employees Table
							<button type="button" class="btn btn-primary float-right" id="openmodalDoctor">
								<i class="fa fa-plus"></i>
							</button>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="employeeTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Last Name</th>
											<th>First Name</th>
											<th>Address</th>
											<th>Mobile No.</th>
											<th>Gender</th>
											<th>Email</th>
											<th>Role</th>
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
	</main>

	<!-- Modal  -->
	<div class="modal fade" id="add_doctor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form action="" method="POST" class="needs-validation" novalidate id="newEmployeeForm">
						<div class="form-row">
							<div class="col-md-6 mb-2">
								<label for="last_name" class="askte">Last Name</label>
								<input type="text" class="form-control " id="last_name" placeholder="Last Name" value="" name="lastName" autocomplete="off" required>
							</div> 
							<div class="col-md-6 mb-2">
								<label for="first_name" class="askte">First Name</label>
								<input type="text" class="form-control" id="first_name" placeholder="First Name" value="" name="firstName"  autocomplete="off" required>
							</div>

							<div class="col-md-6 mb-2">
								<label for="first_name">Address</label>
								<input type="text" class="form-control" id="address" placeholder="Address" value="" name="address" autocomplete="off">
							</div>
							<div class="col-md-6 mb-2" class="askte">
								<label for="mobile_no">Mobile Number</label>
								<input type="text" class="form-control input-phone" id="mobile_no" placeholder="Mobile Number" value="" name="mobileNumber"  autocomplete="off" required>
							</div>
							<!--<div class="col-md-6 mb-2">-->
							<!--	<label for="telephone_no">Telephone Number</label>-->
							<!--	<input type="number" class="form-control" id="telephone_no" placeholder="Telephone Number" value="" name="landlineNumber" autocomplete="off" >-->
							<!--</div>-->
							<!--<div class="col-md-6 mb-2">-->
							<!--	<label for="doctor_birthdate">Birthdate</label>-->
							<!--	<input type="date" class="form-control" id="doctor_birthdate" name="birthdate" placeholder="Birthdate" autocomplete="off" >-->
							<!--</div>-->
							<div class="col-md-6 mb-2">
								<label for="gender">Gender</label>
								<select class="custom-select" id="gender" name="gender">

									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>
								<div class="valid-feedback">
									Looks good!
								</div>
							</div>
							<div class="col-md-6 mb-2">
								<label for="email" class="askte">Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" required>
							</div>
							<div class="col-md-6 mb-2">
								<label for="uname" class="askte">Username</label>
								<input type="text" class="form-control" id="uname" name="uname" placeholder="Username" autocomplete="off" required>
							</div>
							<div class="col-md-6 mb-2">
								<label for="pass" class="askte">Password</label>
								<input type="password" class="form-control" id="pass" name="pass" autocomplete="off" required>
							</div>
							<div class="col-md-6 mb-2">
								<label for="gender" class="askte">Role</label>
								<select class="custom-select" id="gender" name="role">
									<option value="" selected disabled>Select Role</option>
									<option value="Doctor">Doctor</option>
									<option value="Groomer">Groomer</option>
								</select>
								<div class="valid-feedback">
									Looks good!
								</div>
							</div>
						</div> 

					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary float-right" name="adddoctorbutton" id="addEmployee"><i class="fas fa-plus"></i> Add Employee</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
				</div>
			</div>
		</div>
	</div>

	<!-- update form  -->
	<div class="modal fade" id="update_doctor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Update Information Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body" id="updatedoctorform">

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
				</div>
			</div>
		</div>
	</div>
<?php include "page-footer.php"; ?>
<script>
    
$('#openmodalDoctor').click(function(){
	$('#add_doctor').modal('show');
	$('#newEmployeeForm')[0].reset();
});

$('#employeeTable').DataTable({
	ajax: url+"employeetable",
});

$('#addEmployee').click(function(){
	let employeetable= $('#employeeTable').DataTable();

	let data = {
	    clinic_id : <?php echo $clinic_id;?>,
	};
	$('#newEmployeeForm').serializeArray().forEach(x=>{
		data[x.name] = x.value;
	});
	$.ajax({
		url: url+"addnewEmployee",
		data:JSON.stringify(data),
		type: 'post',
		dataType: 'json',
		success: function(response) {
			if(response.status == 404){
				Swal.fire({
					position: 'top-end',
					icon: 'info',
					title: 'Email Already Exist!',
					showConfirmButton: false,
					timer: 1500
				});
			}else if(response.status == 405){
				Swal.fire({
					position: 'top-end',
					icon: 'info',
					title: 'Username Already Exist!',
					showConfirmButton: false,
					timer: 1500
				});
			}else{
				$('#add_doctor').modal('hide');
				Swal.fire({
					position: 'top-end',
					icon: 'success',
					title: 'New Employee Added',
					showConfirmButton: false,
					timer: 1500
				});
				employeetable.ajax.reload();
			}
			

		}
	});
			// console.log(data);

		});
function updateDoctor(id){
	$('#update_doctor').modal('show');
	let content='';
	$.ajax({
		url: url+"doctors/"+id,
		type: 'get',
		dataType: 'json',
		success: function(x) {

			content+=`<form action="" method="POST" class="needs-validation" id="updateDoctor_form" novalidate>
			<div class="form-row">
			<div class="col-md-6 mb-2">
			<label for="last_name">Last Name</label>
			<input type="text" class="form-control" id="Last_name" placeholder="Last Name" value="${x.last_name}" name="lastName" required>
			</div>
			<div class="col-md-6 mb-2">
			<label for="first_name">First Name</label>
			<input type="text" class="form-control" id="First_name" placeholder="First Name" value="${x.first_name}" name="firstName" required>
			</div>

			<div class="col-md-6 mb-2">
			<label for="first_name">Address</label>
			<input type="text" class="form-control" id="Address" placeholder="Address" value="${x.address}" name="address" required>
			</div>
			<div class="col-md-6 mb-2">
			<label for="mobile_no">Mobile Number</label>
			<input type="text" class="form-control " id="Mobile_no" placeholder="Mobile Number" value="${x.mobile_no}" name="mobileNumber" required>
			</div>
			<div class="col-md-6 mb-2">
			<label for="gender">Gender</label>
			<select class="custom-select" id="Gender" name="gender">`;
			if(x.gender=="Male"){
				content+=`<option value="Male" selected>Male</option>
				<option value="Female">Female</option>`;
			}else{
				content+=`<option value="Male" >Male</option>
				<option value="Female" selected>Female</option>`;
			}

			content+=`</select>
			<div class="valid-feedback">
			Looks good!
			</div>
			</div>
			<div class="col-md-6 mb-2">
			<label for="email">Email</label>
			<input type="email" class="form-control" id="Email" name="email" placeholder="Email" value="${x.email}" required>
			</div>
			<div class="col-md-6 mb-2">
			<label for="gender">Role</label>
			<select class="custom-select" id="Gender" name="role">`;
			if(x.role=="Doctor"){
				content+=`<option value="Doctor" selected>Doctor</option>
				<option value="Groomer">Groomer</option>`;
			}else{
				content+=`<option value="Doctor" >Doctor</option>
				<option value="Groomer"selected>Groomer</option>`;
			}
			content +=	`</select>
			<div class="valid-feedback">
			Looks good!
			</div>
			</div>
			</div>
			<button type="button" class="btn btn-primary float-right" onclick="updateDoctorFunc(${x.doctor_id})">Update Information</button>
			</form>`;
			$('#updatedoctorform').html(content);
		}
	});
}
function updateDoctorFunc(id){
	let data = {};
	$('#updateDoctor_form').serializeArray().forEach(x=>{
		data[x.name] = x.value;
	});
	data['doctor_id'] = id;
	$.ajax({
		url: url+"doctors/",
		data:data,
		type: 'post',
		dataType: 'json',
		success: function(response) {
			if(response.message == "OK"){
				$('#update_doctor').modal('hide');
				Swal.fire({
					position: 'top-end',
					icon: 'success',
					title: 'Doctors Details Updated!',
					showConfirmButton: false,
					timer: 1500
				});
				location.reload();
			}

		}
	});
}
function deleteDoctor(id){
	let data ={
		'doctor_id': id,
		'action' :'delete'
	}

	Swal.fire({
		title: 'Are you sure?',
		text: "Once deleted, you will not be able to recover this employee details!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				url: url+"doctor/remove",
				data:data,
				type: 'post',
				dataType: 'json',
				success: function(response) {
					if(response.message=="OK"){
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: 'Employee Details Deleted!',
							showConfirmButton: false,
							timer: 1500
						})
						location.reload();
					}

				}
			});
		}
	})
}

document.getElementById('birth_date').attributes["type"].value = 'date';
document.getElementsByName('last_name')[0].placeholder='Last Name';
document.getElementsByName('first_name')[0].placeholder='First Name';
document.getElementsByName('mobile_number')[0].placeholder='';
document.getElementsByName('username')[0].placeholder='Username';
document.getElementsByName('email')[0].placeholder='Email';
document.getElementsByName('passw1')[0].placeholder='Password';
document.getElementsByName('telephone_number')[0].placeholder='0000-0000';

var d = document.getElementById("mobile_number");
d.className += "input-phone";

</script>

