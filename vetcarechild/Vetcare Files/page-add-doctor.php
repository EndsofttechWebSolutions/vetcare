<?php get_header(); ?>
<?php include "sidebar.php"; ?>

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
							<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#add_doctor">
								<i class="fa fa-plus"></i>
							</button>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Last Name</th>
											<th>First Name</th>
											<th>Address</th>
											<th>Mobile No.</th>
											<th>Landline No.</th>
											<th>Birthdate</th>
											<th>Gender</th>
											<th>Email</th>
											<th>Role</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										global $wpdb;
										$table=$wpdb->prefix.'vet_doctors';
										$results = $wpdb->get_results("SELECT * FROM {$table}");
										$arrayData = array();
										foreach ($results as $key ) {
											?>
											<tr>
												<td><?php echo $key->last_name;?></td>
												<td><?php echo $key->first_name;?></td>
												<td><?php echo $key->address;?></td>
												<td><?php echo $key->mobile_no;?></td>
												<td><?php echo $key->landline_no;?></td>
												<td><?php echo $key->birthdate;?></td>
												<td><?php echo $key->gender;?></td>
												<td><?php echo $key->email;?></td>
												<td><?php echo $key->role;?></td>
												<td align="center">
													<button type="button" class="btn btn-success" title="Edit" onclick="updateDoctor(<?php echo $key->doctor_id;?>)"><i class="fas fa-edit"></i></button>
													<button type="button" class="btn btn-danger" name="deletePet" title="Delete" onclick="deleteDoctor(<?php echo $key->doctor_id;?>)"><i class="far fa-trash-alt" ></i></button>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<!-- Modal -->
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
					<form action="" method="POST" class="needs-validation" novalidate>

						<?php echo do_shortcode( '[erforms id="186"]' ); ?> 
						<!-- <div class="form-row">
							<div class="col-md-6 mb-2">
								<label for="last_name">Last Name</label>
								<input type="text" class="form-control" id="last_name" placeholder="Last Name" value="" name="lastName" required>
							</div>
							<div class="col-md-6 mb-2">
								<label for="first_name">First Name</label>
								<input type="text" class="form-control" id="first_name" placeholder="First Name" value="" name="firstName" required>
							</div>

							<div class="col-md-6 mb-2">
								<label for="first_name">Address</label>
								<input type="text" class="form-control" id="address" placeholder="Address" value="" name="address" required>
							</div>
							<div class="col-md-6 mb-2">
								<label for="mobile_no">Mobile Number</label>
								<input type="text" class="form-control input-phone" id="mobile_no" placeholder="Mobile Number" value="" name="mobileNumber" required>
							</div>
							<div class="col-md-6 mb-2">
								<label for="telephone_no">Telephone Number</label>
								<input type="number" class="form-control" id="telephone_no" placeholder="Telephone Number" value="" name="landlineNumber" required>
							</div>
							<div class="col-md-6 mb-2">
								<label for="doctor_birthdate">Birthdate</label>
								<input type="date" class="form-control" id="doctor_birthdate" name="birthdate" placeholder="Birthdate" required>
							</div>
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
								<label for="email">Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
							</div>
							<div class="col-md-6 mb-2">
								<label for="uname">Username</label>
								<input type="text" class="form-control" id="uname" name="uname" placeholder="Username" required>
							</div>
							<div class="col-md-6 mb-2">
								<label for="pass">Password</label>
								<input type="password" class="form-control" id="pass" name="pass" required>
							</div>
							<div class="col-md-6 mb-2">
								<label for="gender">Role</label>
								<select class="custom-select" id="gender" name="role">

									<option value="Doctor">Doctor</option>
									<option value="Groomer">Groomer</option>
								</select>
								<div class="valid-feedback">
									Looks good!
								</div>
							</div>
						</div>
						<button type="submit" class="btn btn-primary float-right" name="adddoctorbutton"><i class="fas fa-plus"></i> Add Employee</button>
					</form>
				</div> -->

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- update form -->
	<div class="modal fade" id="update_doctor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Update Doctor Details</h5>
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
	<?php get_footer(); ?>
	<script>
		function updateDoctor(id){
			$('#update_doctor').modal('show');

			$.ajax({
				url: url+"doctors/",
				type: 'get',
				dataType: 'json',
				success: function(response) {
					let content='';
					response.forEach(x=>{
						if(id == x.doctor_id){
							console.log(x);
							content+=`<form action="" method="POST" class="needs-validation" id="updateDoctor_form" novalidate>
							<div class="form-row">
							<div class="col-md-6 mb-2">
							<label for="last_name">Last Name</label>
							<input type="text" class="form-control" id="last_name" placeholder="Last Name" value="${x.last_name}" name="lastName" required>
							</div>
							<div class="col-md-6 mb-2">
							<label for="first_name">First Name</label>
							<input type="text" class="form-control" id="first_name" placeholder="First Name" value="${x.first_name}" name="firstName" required>
							</div>

							<div class="col-md-6 mb-2">
							<label for="first_name">Address</label>
							<input type="text" class="form-control" id="address" placeholder="Address" value="${x.address}" name="address" required>
							</div>
							<div class="col-md-6 mb-2">
							<label for="mobile_no">Mobile Number</label>
							<input type="text" class="form-control" id="mobile_no" placeholder="Mobile Number" value="${x.mobile_no}" name="mobileNumber" required>
							</div>
							<div class="col-md-6 mb-2">
							<label for="telephone_no">Telephone Number</label>
							<input type="text" class="form-control" id="telephone_no" placeholder="Telephone Number" value="${x.landline_no}" name="landlineNumber" required>
							</div>
							<div class="col-md-6 mb-2">
							<label for="doctor_birthdate">Birthdate</label>
							<input type="text" class="form-control" id="doctor_birthdate" name="birthdate" placeholder="Birthdate" value="${x.birthdate}" required>
							</div>
							<div class="col-md-6 mb-2">
							<label for="gender">Gender</label>
							<select class="custom-select" id="gender" name="gender">`;
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
							<input type="email" class="form-control" id="email" name="email" placeholder="Email" value="${x.email}" required>
							</div>

							</div>
							<button type="button" class="btn btn-primary float-right" onclick="updateDoctorFunc(${x.doctor_id})">Update Doctor Details</button>
							</form>`;
						}
					});
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
					$('#update_doctor').modal('hide');
					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: 'Doctors Details Updated!',
						showConfirmButton: false,
						timer: 1500
					})
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
				text: "Once deleted, you will not be able to recover this doctor details!",
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
							Swal.fire({
								position: 'top-end',
								icon: 'success',
								title: 'Pets Details Deleted!',
								showConfirmButton: false,
								timer: 1500
							})
							location.reload();
						}
					});
				}
			})
		}
	</script>
