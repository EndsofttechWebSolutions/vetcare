<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>

<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4"></h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">Add Groomers</li>
			</ol>
			<br>
			<div class="row">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-lg-5">
							<div class="card mb-4">
								<div class="card-header"><i class="fas fa-user mr-1"></i>Groomer Profile Picture<div class="float-right">               
              					</div></div>
              					<div class="card-body">              	
									<input type="file" name="uploader" id="uploader" accept="image/*"capture="camera"/>            
           						</div>
							</div>
						</div>

						<div class="col-lg-7">
							<div class="card mb-4">
								<div class="card-header"><i class="fas fa-info-circle mr-1"></i>Groomer Basic Profile Information</div>
								<div class="card-body">
									<form action="" method="POST" class="needs-validation" novalidate>
										<div class="form-row">
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
												<input type="number" class="form-control" id="mobile_no" placeholder="Mobile Number" value="" name="mobileNumber" required>
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
											</div>
											<div class="col-md-6 mb-2">
												<label for="email">Email</label>
												<input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
											</div>
											<div class="col-md-6 mb-2">
												<label for="email">Username</label>
												<input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
											</div>
											<div class="col-md-6 mb-2">
												<label for="email">Password</label>
												<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
											</div>
										</div>
											<button type="submit" class="btn btn-primary float-right" name="adddoctorbutton">Add Groomer</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>


<?php include "page-footer.php"; ?>
