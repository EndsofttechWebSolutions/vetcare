<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>

<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4"></h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">Owner & Pet Information</li>				
			</ol>
			<p>List all information regarding both the owner and their pet's profile</p>
			<br>
		<div class="row">
         <div class="col-lg-12">
          <div class="row">
           <div class="col-lg-5">
            <div class="card mb-4">
              <div class="card-header"><i class="fas fa-user mr-1"></i>Owner Information  <div class="float-right">
                
              </div></div>

              <div class="card-body">
              	<form action="" method="POST" class="needs-validation" novalidate>
              		<center><img src="https://cdn.pixabay.com/photo/2016/08/08/09/17/avatar-1577909_960_720.png" alt="Profile Picture" style="width:100px"></center>
              		<br>
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
					<div class="col-md-6 mb-2">
						<label for="role">Role</label>
							<select class="custom-select" id="role" name="role">
								<option value="Owner">Owner</option>
								<option value="-Sub-Owner">Sub-Owner</option>
							</select>
					</div>
              	</div>
              	
              </form>
			<br>
                 <center><button type="submit" class="btn btn-success"><i class="fas fa-edit"></i> Update this Record</button>
                  <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete This Record</button></center>
              </div>
            </div>
          </div>
          <div class="col-lg-7">
           <div class="card mb-4">
            <div class="card-header"><i class="fas fa-dog mr-1"></i>Pet Lists</div>
            <div class="card-body">          
              	 <button class="btn btn-primary" onclick="window.location.href = '<?php echo get_home_url().'/add-pets'; ?>';"><i class="fas fa-plus"></i> Add New Pet</button>
          </div>
        </div>
      </div>
    </div>


</div>  

</div>

	</main>

<?php include "page-footer.php"; ?>