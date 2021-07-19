<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>

<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4">Waiver</h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">Waiver</li>
			</ol>
			<div class="col-md-3">
				
			</div>
			<br>
			<div class="col">
				<div class="col-lg-12">
					<div class="card mb-2">
						<div class="card-header"><i class="fas fa-table mr-1"></i>Waiver Table
							<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#waiver_form">
								<i class="fa fa-plus"></i>
							</button>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Title</th>
											<th>Description</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Title</th>
											<th>Description</th>
											<th>Action</th>
										</tr>
									</tfoot>
									<tbody>
										<td></td>
										<td></td>
										<td align="center">
											<button type="button" class="btn btn-primary"><i class="far fa-eye"></i></button>
              								<button type="button" class="btn btn-success"><i class="fas fa-edit"></i></button>
            								<button type="button" class="btn btn-danger" name="deletePet"><i class="far fa-trash-alt"></i></button>
										</td>
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
	<div class="modal fade" id="waiver_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add Waiver Form</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="" method="POST" class="needs-validation" novalidate>
						<div class="form-row">
							<div class="col-md-12 mb-2">
								<label for="waiver_title">Title</label>
									<input type="text" class="form-control" id="waiver_title" placeholder="Waiver Title" value="" name="waiverTitle" required>
								<div class="valid-feedback">
									Looks good!
								</div>
							</div>
							<div class="col-md-12 mb-2">
								<label for="waiver_description">Descriptions</label>
									<textarea class="form-control" id="waiver_description" name="waiverDescription" rows="5"></textarea>
								<div class="valid-feedback">
									Looks good!
								</div>
							</div>			
								
						</div>
					</form>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary float-right" name="savewaiverbutton">Save Waiver</button>	
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php include "page-footer.php"; ?>