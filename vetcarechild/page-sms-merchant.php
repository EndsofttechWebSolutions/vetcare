<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>


<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4"></h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">SMS Merchant Account</li>
			</ol>
			<br>
			<div class="row">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-lg-9">
							<div class="card mb-4">
								<div class="card-header"><i class="fas fa-user mr-1"></i>SMS Merchant Table
								<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#add_merchant">
								<i class="fa fa-plus"></i> Add
								</button>  
              						<div class="float-right">
                
              						</div>
          						</div>
          						<div class="card-body">
          							<!-- Body -->
          							<div class="table-responsive">
          								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          									<thead>
          										<tr>
          											<th>Sender ID</th>
          											<th>Username</th>
          											<th>Password</th>
          											<th>Email Address</th>
          											<th>FullName</th>
          											<th>Daily</th>
          											<th>Monthly</th>
          											<th>Action</th>
          										</tr>
          									</thead>
          									<tfoot>
          										<tr>
          											<th>Sender ID</th>
          											<th>Username</th>
          											<th>Password</th>
          											<th>Email Address</th>
          											<th>FullName</th>
          											<th>Daily</th>
          											<th>Monthly</th>
          											<th>Action</th>
          										</tr>
          									</tfoot>
          									<tbody>
          										<?php
													global $wpdb;
													$table=$wpdb->prefix.'vet_sms_merchant';
													$results = $wpdb->get_results("SELECT * FROM {$table}");
													$arrayData = array();
													foreach ($results as $key ) {
												?>
												<tr>
													<td></td>
													<td><?php echo $key->username;?></td>
													<td><?php echo $key->password;?></td>
													<td><?php echo $key->email;?></td>
													<td><?php echo $key->fullname;?></td>
													<td><?php echo $key->daily_limit;?></td>
													<td><?php echo $key->monthly_limit;?></td>
													<td align="center">
														<button type="button" class="btn btn-success" title="Edit"><i class="fas fa-edit"></i></button>
														<button type="button" class="btn btn-danger" name="deletePet" title="Delete"><i class="far fa-trash-alt" ></i></button>
													</td>	
												</tr>
          										<?php } ?>
          									</tbody>
          								</table>
          							</div>
          						</div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="card mb-4">
								 <div class="card-header"><i class="fas fa-lock mr-1"></i>Credential Information</div>
								 <div class="card-body">
								 	<form action="" method="POST" class="needs-validation" novalidate>
								 		<div class="form-row">
								 			<div class="col-md-12 mb-2">
								 				<label for="loginName">Login Name</label>
								 				<input type="text" class="form-control" id="loginName" value="" name="loginName" disabled="">
								 			</div>
								 			<div class="col-md-12 mb-2">
								 				<label for="creditRemaining">Credit Remaining</label>
								 				<input type="text" class="form-control" id="creditRemaining" value="" name="creditRemaining" disabled="">
								 			</div>
								 			<div class="col-md-12 mb-2">
								 				<label for="expirationDate">Expiration Date</label>
								 				<input type="text" class="form-control" id="expirationDate" value="" name="expirationDate" disabled="">
								 			</div>
								 		</div>
								 		<br>
								 		<center><button type="submit" onclick="window.location.href = 'https://www.isms.com.my/buy_reload.php';" class="btn btn-success" name="addownerbutton">Top Up</button>
								 		<button type="submit" onclick="window.location.href = 'https://www.isms.com.my/contact_us.php';" class="btn btn-warning" name="addownerbutton">Extend</button></center>
								 	</form>
								 </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<div class="modal fade" id="add_merchant" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add SMS Merchant</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="" method="POST" class="needs-validation" novalidate>
						<div class="form-row">
							<div class="col-lg-12 mb-2">
								<label for="senderID">Sender ID</label>
								<input type="number" class="form-control" id="senderID" placeholder="Sender ID" value="" name="senderID" required disabled="">
							</div>
							<div class="col-lg-12 mb-2">
								<label for="userName">Username</label>
								<input type="text" class="form-control" id="userName" placeholder="Username" value="" name="userName" required>
							</div>
							<div class="col-lg-12 mb-2">
								<label for="password">Password</label>
								<input type="password" class="form-control" id="password" placeholder="Password" value="" name="password" required>
							</div>
							<div class="col-lg-12 mb-2">
								<label for="email">Email Address</label>
								<input type="email" class="form-control" id="email" placeholder="Email Address" value="" name="email" required>
							</div>
							<div class="col-lg-12 mb-2">
								<label for="fullName">Full Name</label>
								<input type="text" class="form-control" id="fullName" placeholder="Full Name" value="" name="fullName" required>
							</div>
							<div class="col-lg-12 mb-2">
								<label for="dailyLimit">Daily Limit</label>
								<input type="number" class="form-control" id="dailyLimit" placeholder="Daily Limit" value="" name="dailyLimit" required>
							</div>
							<div class="col-lg-12 mb-2">
								<label for="monthlyLimit">Monthly Limit</label>
								<input type="number" class="form-control" id="monthlyLimit" placeholder="Monthly Limit" value="" name="monthlyLimit" required>
							</div>
						</div>
						<button type="submit" class="btn btn-primary float-right" name="addsmsmerchantbutton">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>

<?php include "page-footer.php"; ?>