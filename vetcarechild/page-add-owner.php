<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>
<?php

global $wpdb,$wp,$post, $current_user; wp_get_current_user();
$user = get_userdata( get_current_user_id());
$ID = get_current_user_id();
$user_roles = $user->roles;
if(!empty($user_roles)){
  if (in_array( 'um_doctors', $user_roles, true ) || in_array( 'um_groomers', $user_roles, true ) ) {
    $myclinic_id =  get_user_meta(get_current_user_id(),'clinic_id',true);
  }else if(in_array( 'um_clinic', $user_roles, true )){
    $myclinic_id = get_current_user_id();
  }else{
    $myclinic_id = get_current_user_id();
  }
}

?>
<style>
	#my_camera{
		width: 265px;
		height: 240px;
		border: 1px solid black;
	}

	@media only screen and (max-width: 1080px){
		#my_camera {
			width: 188px!Important;
			height: 169px!important;
			border: 1px solid black;
		}
		button#takesnap {
			margin-top: 17px!Important;
			margin-left: 12px!important;
			z-index: 99!important;
			position: absolute;
		}
		button#retake {
			margin-top: 17px!Important;
			margin-left: 12px!important;
			z-index: 99!important;
			position: absolute;
		}
	}

	@media only screen and (max-width: 768px){
		#my_camera {
			width: 110px!Important;
			height: 126px!important;
			border: 1px solid black;
		}
		button#takesnap {
			margin-top: 17px!Important;
			margin-left: -7px!important;
			z-index: 99!important;
			position: absolute;
		}
		button#retake {
			margin-top: 17px!Important;
			margin-left: -7px!important;
			z-index: 99!important;
			position: absolute;
		}
	}

	@media only screen and (max-width: 1143px){
		#my_camera {
			width: 188px!Important;
			height: 169px!important;
			border: 1px solid black;
		}
		button#takesnap {
			margin-top: 17px!Important;
			margin-left: 12px!important;
			z-index: 99!important;
			position: absolute;
		}
		button#retake {
			margin-top: 17px!Important;
			margin-left: 12px!important;
			z-index: 99!important;
			position: absolute;
		}
	}

</style>
<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4"></h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">Owners Lists</li>
			</ol>
			<br>

			<div class="col">
				<div class="col-lg-12">
					<div class="card mb-2">
						<div class="card-header"><i class="fas fa-table mr-1"></i>Owners Table
							<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addowners" id="addowner"><i class="glyphicon glyphicon-plus"></i></button>
						</div>
						<div class="card-body">
							<div class="table-responsive table-striped">
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Image</th>
											<th>Last Name</th>
											<th>First Name</th>
											<th>Mobile No.</th>
											<th>Email</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										global $wpdb;
										$table=$wpdb->prefix.'vet_owners';
										$results = $wpdb->get_results("SELECT * FROM {$table} where clinic_id = {$myclinic_id} ORDER BY date_created DESC");
										$arrayData = array();
										foreach ($results as $key ) {
											?>
											<tr>
												<td style="width: 150px; height: 100px;background: url('<?php echo $key->image; ?>') no-repeat center center /cover"></td>
												<td><?php echo $key->last_name;?></td>
												<td><?php echo $key->first_name;?></td>
												
												<td><?php echo $key->mobile_no;?></td>

												<td><?php echo $key->email;?></td>
												
												<td align="center">
													<a href="<?php echo get_site_url().'/owners-details?id='.$key->owner_id;?>"><button type="button" class="btn btn-primary" title="Edit"><i class="fas fa-eye"></i></button></a>
													<button type="button" class="btn btn-success" title="Edit" onclick="updateOwner(<?php echo $key->owner_id;?>)"><i class="fas fa-edit"></i></button>
													<button type="button" class="btn btn-danger" name="deletePet" title="Delete" onclick="deleteOwner(<?php echo $key->owner_id;?>)"><i class="far fa-trash-alt" ></i></button>
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


		<div class="modal fade" id="addowners" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Fill out Details</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-5"><!--left col-->
								<div class="text-center" id="my_camera">



								</div></hr>
								<button type="button" id="takesnap" class="btn btn-primary mt-2 ml-5" name="button" onClick="take_snapshot()"><i class="fa fa-camera"></i> Take Snapshot</button>
								<button type="button" id="retake" class="btn btn-danger mt-2 ml-5" name="button" onClick="capture_again()" hidden><i class="fa fa-camera"></i> Capture Again</button>
							</div><!--/col-3-->
							<div class="col-md-7">
								<div class="tab-content">
									<div class="tab-pane active" id="home">
								
										<form action="" method="POST" class="needs-validation" id="newownerdetails" novalidate>
											<input type="file" class="text-center center-block file-upload" name="uploader" id="uploader"
											accept="image/*"
											capture="camera" hidden>
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
													<input type="text" class="form-control" id="address" placeholder="Address" value="" name="address" >
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
													<input type="text" class="form-control input-phone" id="number" placeholder="Mobile Number" value="" name="mobileNumber" required>
												</div>
												<div class="col-md-6 mb-2">
													<label for="landline_number">Landline No.</label>
													<input type="text" class="form-control" id="landline_number"  value="" name="landlineNumber" placeholder="0000-0000">
												</div>
												<div class="col-md-6 mb-2">
													<label for="landline_number">Birthdate</label>
													<input type="date" class="form-control" id="birthdate" placeholder="Birthdate" value="" name="birthDate" required>
												</div>


											</div>
											<button type="submit" class="btn btn-primary float-right mb-3 " name="addownerbutton" id="addownerbutton"><i class="fas fa-plus"></i> Add Owner</button>
										</form>

									</div><!--/tab-pane-->
								</div><!--/tab-content-->

							</div><!--/col-9-->
						</div><!--/row-->
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- update form -->
		<div class="modal fade" id="update_doctor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Update Owner Details</h5>
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
	</main>

	<?php include "page-footer.php"; ?>
	<script>
		
		let file ='';
		let image_data ='';
		$(document).ready(function(){
			getallowners();


			$('#thumbnail').click(function(){
				$('#uploader').click();
			});
		//show image profile
		var readURL = function(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					$('.avatar').attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
				file =input.files[0];
			}
		}


		$(".file-upload").on('change', function(){
			readURL(this);

		});
		$('#addowner').click(function(){
			Webcam.set({
				width: 265,
				height: 240,
				image_format: 'jpeg',
				jpeg_quality: 100,
				force_flash:false,
				flip_horiz:false,
				fps:45
			});
			Webcam.attach( '#my_camera' );
		});
		$('#newownerdetails').submit(function(e){
		    e.preventDefault();
			let newownerdetails = {};
			$('#newownerdetails').serializeArray().forEach(x=>{
				newownerdetails[x.name] = x.value;
			});
			newownerdetails['role'] = "Owner";
			newownerdetails['image'] = image_data;
			newownerdetails['clinic_id'] = <?php echo $myclinic_id;?>;
			if(newownerdetails['birthDate'] === "" || newownerdetails['mobileNumber'] === "" || newownerdetails['email'] === "" || newownerdetails['lastName'] === "" || newownerdetails['lastName'] === "firstName"){
				alert('Please Complete Details');
			}else{
				$('#newownerdetails').serializeArray().forEach(x=>{
					newownerdetails[x.name] = x.value;
				});
				newownerdetails['role'] = "Owner";
				newownerdetails['image'] = image_data;
				fetch(url+'owners',{
					method:"POST",
					body: JSON.stringify(newownerdetails)
				}).then(res=>res.json()).then(res=>{
                  // console.log(res);
                  if(res.d=="Success"){
                  	Swal.fire({
                  		position: 'top-end',
                  		icon: 'success',
                  		title: 'Added New Owner Successfully',
                  		showConfirmButton: false,
                  		timer: 1500
                  	});
                  	location.assign("<?php echo get_site_url();?>/owners-details?id="+res.id);
                  }
                });
    // console.log(newownerdetails);
			}
		})
	});
		function take_snapshot() {

	 // take snapshot and get image data
	 Webcam.snap( function(data_uri) {
	  // display results in page
	  Webcam.freeze();
	  Webcam.reset();
	  image_data = data_uri;
	  $('#my_camera').html('<img src="'+data_uri+'" class="avatar img-thumbnail" alt="avatar" id="thumbnail">');
	});
    // console.log(image_data);
    
    $('#takesnap').prop('hidden',true);
    $('#retake').prop('hidden',false);
}
function capture_again(){
	Webcam.set({
		width: 265,
		height: 240,
		image_format: 'jpeg',
		jpeg_quality: 100,
		force_flash:false,
		flip_horiz:false,
		fps:45
	});
	Webcam.attach( '#my_camera' );
	$('#takesnap').prop('hidden',false);
	$('#retake').prop('hidden',true);

}

function getallowners(){
	let ls='';
	fetch(url+'owners').then(res=>res.json()).then(res=>{
		res.forEach(x=>{
			ls+=`<div class="col-4">
			<div class="card">
			<div class="card-body">
			<div class="row">
			<div class="col-12 col-lg-8 col-md-6">
			<h3 class="mb-0 text-truncated">${x.name}</h3>
			<p class="lead">${x.role}
			<p> Email: ${x.email}</p>
			<p> Mobile #: ${x.mobile_no}</p>
			<p> Address: ${x.address}</p>
			</div>
			<div class="col-12 col-lg-4 col-md-6 text-center">
			<img src="${x.image}" alt="" class="mx-auto rounded-circle img-fluid">

			</div>
			<div class="col-12 col-lg-4">

			</div>
			<div class="col-12 col-lg-4">
			<a href="<?php echo get_site_url();?>/owners-details?id=${x.owner_id}"><button class="btn btn-outline-info btn-block"><span class="fa fa-user"></span> View Profile</button></a>
			</div>
			<div class="col-12 col-lg-4">

			</div>
			<!--/col-->
			</div>
			<!--/row-->
			</div>
			<!--/card-block-->
			</div>
			</div>`;
		});
		$('#owners').html(ls)
	});
}

function updateOwner(id){
	$('#update_doctor').modal('show');
	let content='';
	fetch(url+'owner/'+id).then(res=>res.json()).then(response=>{
		console.log(response);
		content+=`<form action="" method="POST" class="needs-validation" id="updateDoctor_form" novalidate>
		<div class="form-row">
		<div class="col-md-6 mb-2">
		<label for="last_name">Last Name</label>
		<input type="text" class="form-control" id="last_name" placeholder="Last Name" value="${response[0].last_name}" name="lastName" required>
		</div>
		<div class="col-md-6 mb-2">
		<label for="first_name">First Name</label>
		<input type="text" class="form-control" id="first_name" placeholder="First Name" value="${response[0].first_name}" name="firstName" required>
		</div>

		<div class="col-md-6 mb-2">
		<label for="first_name">Address</label>
		<input type="text" class="form-control" id="address" placeholder="Address" value="${response[0].address}" name="address" required>
		</div>
		<div class="col-md-6 mb-2">
		<label for="mobile_no">Mobile Number</label>
		<input type="text" class="form-control" id="mobile_no" placeholder="Mobile Number" value="${response[0].mobile_no}" name="mobileNumber" required>
		</div>
		<div class="col-md-6 mb-2">
		<label for="telephone_no">Telephone Number</label>
		<input type="text" class="form-control" id="telephone_no" placeholder="Telephone Number" value="${response[0].landline_no}" name="landlineNumber" required>
		</div>
		<div class="col-md-6 mb-2">
		<label for="doctor_birthdate">Birthdate</label>
		<input type="text" class="form-control" id="doctor_birthdate" name="birthdate" placeholder="Birthdate" value="${response[0].birthday}" required>
		</div>
		<div class="col-md-6 mb-2">
		<label for="gender">Gender</label>
		<select class="custom-select" id="gender" name="gender">`;
		if(response[0].gender=="Male"){
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
		<input type="email" class="form-control" id="email" name="email" placeholder="Email" value="${response[0].email}" required>
		</div>

		</div>
		<button type="button" class="btn btn-primary float-right" onclick="updateOwnerFunc(${response[0].owner_id})">Update Owner Details</button>
		</form>`;
		$('#updatedoctorform').html(content);
	});

}
function updateOwnerFunc(id){
	let data = {};
	$('#updateDoctor_form').serializeArray().forEach(x=>{
		data[x.name] = x.value;
	});
	data['owner_id'] = id;
	$.ajax({
		url: url+"updateowner/",
		data:data,
		type: 'post',
		dataType: 'json',
		success: function(response) {
			$('#update_doctor').modal('hide');
			Swal.fire({
				position: 'top-end',
				icon: 'success',
				title: 'Owner Details Updated!',
				showConfirmButton: false,
				timer: 1500
			})
			location.reload();
		}
	});
}
function deleteOwner(id){
	let data ={
		'owner_id': id,
		'action' :'delete'
	}

	Swal.fire({
		title: 'Are you sure?',
		text: "Once deleted, you will not be able to recover this owner details!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				url: url+"owner/remove",
				data:data,
				type: 'post',
				dataType: 'json',
				success: function(response) {
					if(response.message == "OK"){
						Swal.fire({
							position: 'top-end',
							icon: 'success',
							title: 'Owner Details Deleted!',
							showConfirmButton: false,
							timer: 1500
						})
						location.reload();
					}
				}
			});
		}
	})
} 
</script>
