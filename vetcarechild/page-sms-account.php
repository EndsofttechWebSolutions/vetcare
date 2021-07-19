<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>

<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4">SMS Account</h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">SMS Account</li>
			</ol>
			<br>
			<div class="col">
				<div class="col-lg-12">
					<div class="card mb-2">
						<div class="card-header"><i class="fas fa-table mr-1"></i>SMS Account
							<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#add_sms_account">
								<i class="fa fa-plus"></i>
							</button>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Send ID</th>
											<th>Account Name</th>
											<th>Password</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Send ID</th>
											<th>Account Name</th>
											<th>Password</th>
											<th>Action</th>
										</tr>
									</tfoot>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
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
	<div class="modal fade" id="add_sms_account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add SMS Account</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="" method="POST" class="needs-validation" novalidate>
						<div class="form-row">
							<div class="col-md-12 mb-2">
								<label for="owner_id">Send ID</label>
								<input type="text" class="form-control" id="send_id" placeholder="Send ID" value="" name="sendID" required>
							</div>
							<div class="col-md-12 mb-2">
								<label for="owner_id">Account Name</label>
								<input type="text" class="form-control" id="account_name" placeholder="Account Name" value="" name="accountName" required>
							</div>
							<div class="col-md-12 mb-2">
								<label for="owner_id">Password</label>
								<input type="text" class="form-control" id="password" placeholder="Password" value="" name="password" required>
							</div>
						</div>
					</form>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary float-right" name="addsmsaccount">Add SMS Account</button>	
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php include "page-footer.php"; ?>