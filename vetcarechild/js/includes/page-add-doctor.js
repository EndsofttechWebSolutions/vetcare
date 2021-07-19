
$('#openmodalDoctor').click(function(){
	$('#add_doctor').modal('show');
	$('#newEmployeeForm')[0].reset();
});

$('#employeeTable').DataTable({
	ajax: url+"employeetable",
});

$('#addEmployee').click(function(){
	let employeetable= $('#employeeTable').DataTable();

	let data = {};
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
			<label for="telephone_no">Telephone Number</label>
			<input type="text" class="form-control" id="Telephone_no" placeholder="Telephone Number" value="${x.landline_no}" name="landlineNumber" >
			</div>
			<div class="col-md-6 mb-2">
			<label for="doctor_birthdate">Birthdate</label>
			<input type="text" class="form-control" id="doctor_birthdate" name="birthdate" placeholder="Birthdate" value="${x.birthdate}" required>
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
