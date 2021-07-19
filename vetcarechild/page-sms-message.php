<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>


<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4">SMS Message Template</h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">SMS Message Template</li>
			</ol>
			<br>
			<div class="col">
				<div class="col-lg-12">
					<div class="card mb-2">
						<div class="card-header"><i class="fas fa-table mr-1"></i>SMS Message Template
							<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#add_sms_template">
								<i class="fa fa-plus"></i>
							</button>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Template ID</th>
											<th>Title</th>
											<th>Content</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Template ID</th>
											<th>Title</th>
											<th>Content</th>
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
	<div class="modal fade" id="add_sms_template" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add SMS Template</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="" method="POST" class="needs-validation" novalidate>
						<div class="form-row">
							<div class="col-md-12 mb-2">
								<label for="title">Title</label>
								<input type="text" class="form-control" id="title" placeholder="Template Title" value="" name="title" required>
								<div class="valid-feedback">
									Looks good!
								</div>
							</div>
							<div class="col-md-12 mb-2">
								<label for="content">Content</label>
								<textarea class="form-control" id="content" name="content" rows="5"></textarea>
								<div class="valid-feedback">
									Looks good!
								</div>
							</div>
							<div class="notes">
								<p><strong style="color: red;">Note:</strong> Please ensure that you have the following items in your message and it shouldn't be more than 160 characters long</p>
								<li><span style="color: red;">{first name}</span> - First Name of the customer</li>
								<li><span style="color: red;">{services}</span>- Service Name (e.g. Checkup, Grooming)</li>
								<li><span style="color: red;">{date}</span> - Schedule date</li>
								<li><span style="color: red;">{pet-name}</span> - Pet name</li>
								<li><span style="color: red;">{contact-number}</span> - Clinice contact number</li>
							</div>
							<br>
						</div>
					</form>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary float-right" name="addsmsaccount">Add SMS Message Template</button>	
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>



<?php include "page-footer.php"; ?>