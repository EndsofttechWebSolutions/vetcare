<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>

<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4">Pet Diagnostics</h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">Pet Diagnostics</li>
			</ol>
			<br>
			<div class="col">
				<div class="col-lg-12">
					<div class="card mb-2">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Owner Name</th>
											<th>Pet Name</th>
											<th>Pet Type</th>
											<th>Breed</th>
											<th>Diagnostic</th>
											<th>Last Visit</th>									
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Owner Name</th>
											<th>Pet Name</th>
											<th>Pet Type</th>
											<th>Breed</th>
											<th>Diagnostic</th>
											<th>Last Visit</th>									
											<th>Action</th>
										</tr>
									</tfoot>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
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
<?php include "page-footer.php"; ?>