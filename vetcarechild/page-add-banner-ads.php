<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>

<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4"></h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">Classified Ads</li>
			</ol>

			<div class="col-md-3">
				
			</div>
			<br>
			<div class="col">
				<div class="col-lg-12">
					<div class="card mb-2">
						<div class="card-header"><i class="fas fa-table mr-1"></i>Banner Ads Table
							<button type="button" class="btn btn-primary float-rig	ht" data-toggle="modal" data-target="#add_banner_ads">
								<i class="fa fa-plus"></i>
							</button>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Name</th>
											<th>Start Date</th>
											<th>End Date</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Name</th>
											<th>Start Date</th>
											<th>End Date</th>
											<th>Action</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

<?php include "page-footer.php"; ?>