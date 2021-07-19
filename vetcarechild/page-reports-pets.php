<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>

<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4"></h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">Report Pets</li>
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
											<th>Type</th>
											<th>Breed</th>
											<th>Gender</th>
											<th>Birthdate</th>
											<th>Color</th>
											<th>Weight</th>
											<th>Last Visit</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Owner Name</th>
											<th>Pet Name</th>
											<th>Type</th>
											<th>Breed</th>
											<th>Gender</th>
											<th>Birthdate</th>
											<th>Color</th>
											<th>Weight</th>
											<th>Last Visit</th>
											<th>Date Created</th>
										</tr>
									</tfoot>
									<tbody>
										<?php
										global $wpdb;
										$table=$wpdb->prefix.'vet_pets';
										$results = $wpdb->get_results("SELECT * FROM {$table}");
										$arrayData = array();
										foreach ($results as $key ) {
											?>
											<tr>
												<td></td>
												<td><?php echo $key->pet_name;?></td>
												<td><?php echo $key->pet_type;?></td>
												<td><?php echo $key->pet_breed;?></td>
												<td><?php echo $key->pet_gender;?></td>
												<td><?php echo $key->pet_birthdate;?></td>
												<td><?php echo $key->pet_color;?></td>
												<td><?php echo $key->pet_weight;?></td>
												<td></td>
												<td><?php echo $key->date_created;?></td>
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
<?php include "page-footer.php"; ?>