<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; ?>

<?php
global $wpdb;$current_user; wp_get_current_user();
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
	#my_camera_update{
		/*width: 265px;*/
		/*height: 240px;*/
		border: 1px solid black;
		object-fit: cover;
	}
	a:hover{
		text-decoration: none;	
	}
	
</style>

<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4"></h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="<?php echo get_site_url();?>/add-owner"> Owners Lists</a></li>
				<li class="breadcrumb-item active">Owner Details</li>
			</ol>
			<br>
			<div class="row my-2">
				<div class="col-lg-8 order-lg-2">
					<ul class="nav nav-tabs">
						<li class="nav-item">
							<a href="" data-target="#pets" data-toggle="tab" class="nav-link active">Pets</a>
						</li>
						<li class="nav-item">
							<a href="" data-target="#edit" data-toggle="tab" class="nav-link">Edit Profile</a>
						</li>
					</ul>
					<div class="tab-content py-4">
						<div class="tab-pane active" id="pets">
							<h5 class="mb-3">My Pets</h5>

							<div class="container">
								<div class="row" id="mypets">
								</div>

							</div>
							<!--/row-->
						</div>

						<div class="tab-pane" id="edit">
							<div class="row">	
								<div class="col-md-8">
									<form action="" method ="POST" class="needs-validation" id="mydetails" novalidate>
										<div class="form-group row">
											<label class="col-lg-3 col-form-label form-control-label">First name</label>
											<div class="col-lg-9">
												<input class="form-control" type="text" value="Jane" id="fname" name="firstName">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-form-label form-control-label">Last name</label>
											<div class="col-lg-9">
												<input class="form-control" type="text" value="Bishop" id="lname" name="lastName">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-form-label form-control-label">Birthday</label>
											<div class="col-lg-9">
												<input class="form-control" type="text" id="bday" name="birthdate">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-form-label form-control-label">Email</label>
											<div class="col-lg-9">
												<input class="form-control" type="email" value="email@gmail.com" id="email" name="email">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-form-label form-control-label">Mobile No.</label>
											<div class="col-lg-9">
												<input class="form-control input-phone" type="text" value="" id="mobile" name="mobileNumber">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-form-label form-control-label">Landline No.</label>
											<div class="col-lg-9">
												<input class="form-control" type="text" value="" id="landline" name="landlineNumber">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-form-label form-control-label">Address</label>
											<div class="col-lg-9">
												<input class="form-control" type="text" value="" placeholder="Street" id="address" name="address">
											</div>
										</div>
										<div class="form-group row" hidden>
											<label class="col-lg-3 col-form-label form-control-label">Gender</label>
											<div class="col-lg-9">
												<input class="form-control" type="text" value="" id="gender" name="gender">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-form-label form-control-label"></label>
											<div class="col-lg-9">
												<input type="reset" class="btn btn-secondary" value="Cancel">
												<input type="button" class="btn btn-primary" value="Update Changes" onclick="return updateMyDetails()">
											</div>
										</div>
									</form>
								</div>
								<div class="col-md-4">

									<div class="text-center col-md-12" id="my_camera_update"></div></hr>
									<button type="button" id="takephoto_update" class="btn btn-primary mt-2 col-md-12" name="button" onClick="editPicture()"><i class="fa fa-camera"></i> Take a Photo</button>
									<button type="button" id="takesnap_update" class="btn btn-primary mt-2 col-md-12" name="button" onClick="take_snapshot_pic()" hidden><i class="fa fa-camera"></i> Take Snapshot</button>
									<button type="button" id="retake_update" class="btn btn-danger mt-2 col-md-12" name="button" onClick="capture_again_pic()" hidden><i class="fa fa-camera"></i> Capture Again</button>
								</div>
							</div>	

						</div>
					</div>
				</div>
				<div class="col-lg-4 order-lg-1 text-center">
					<img src="//placehold.it/150" class="mx-auto img-fluid img-circle d-block" alt="avatar" id="mypic" height="150" width="150">
					<h6 class="mt-2" id="myname">Firstname Lastname</h6>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_pet_form" id="addpet">
						<i class="fa fa-plus"></i>Add New Pet
					</button>

					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_pendings" id="addpet">
						<i class="fa fa-eye"></i> Notes
					</button>
				</div>
			</div>
		</div>
	</main>
	<!-- Modal -->
	<div class="modal fade" id="add_pet_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add New Pets Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<div class="row">
						<div class="col-md-5">
							<div class="text-center" id="my_camera"></div></hr>
							<button type="button" id="takesnap" class="btn btn-primary mt-2 ml-5" name="button" onClick="take_snapshot()"><i class="fa fa-camera"></i> Take Snapshot</button>
							<button type="button" id="retake" class="btn btn-danger mt-2 ml-5" name="button" onClick="capture_again()" hidden><i class="fa fa-camera"></i> Capture Again</button>
						</div><!--/col-3-->
						<div class="col-md-7">
							<form action="" method="POST" class="needs-validation" id="mynewpets" novalidate>
								<div class="form-row">
									<div class="col-md-6 mb-2">
										<label for="pet_type">Pet Type</label>
										<select class="custom-select" id="pet_type" name="petType">
											<option value="Dog" selected>Dog</option>
											<option value="Cat">Cat</option>
										</select>
									</div>
									<div class="col-md-6 mb-2">
										<label for="pet_breed">Pet Breed</label>
										<div class="input-group mb-3">
											<select data-placeholder="Type Breed..." class="form-control" name="petBreed" id="pet_breed">
											</select>
										</div>
									</div>

									<div class="col-md-6 mb-2">
										<label for="pet_name">Pet name</label>
										<input type="text" class="form-control" id="pet_name" name="petName" placeholder="Pet name" required>

									</div>

									<div class="col-md-6 mb-2">
										<label for="pet_bday">Pet Birthdate</label>
										<input type="date" class="form-control" id="pet_bday" name="petBirthdate" placeholder="Birthdate" required>

									</div>

									<div class="col-md-6 mb-2">
										<label for="pet_color">Pet Color</label>
										<input type="text" class="form-control" id="pet_color" name="petColor" placeholder="Color"  required>

									</div>

									<div class="col-md-6 mb-2">
										<label for="pet_gender">Pet Gender</label>
										<select class="custom-select" id="pet_gender" name="petGender">

											<option value="Male">Male</option>
											<option value="Female">Female</option>
										</select>
										<div class="valid-feedback">
											Looks good!
										</div>
									</div>

									<div class="col-md-6 mb-2">
										<label for="pet_weight">Pet Weight</label>
										<input type="number" class="form-control" id="pet_weight" name="petWeight" placeholder="Weight in kg" required>
									</div>

								</div>
								<button type="submit" class="btn btn-primary float-right" name="addpetbutton">Add New Pet</button>
							</form>
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Add Pendings Form  -->
	<div class="modal fade" id="add_pendings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Owner Pendings</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form action="" method="POST" class="needs-validation" id="ownerpendings" novalidate>
						<div class="form-row">
							<div class="col-md-12 mb-2">
								<textarea type="text" class="form-control" id="pendings" name="pendings" placeholder="Ex: Payment need to pay..." required style="height:150px;" readonly></textarea>
							</div>
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" name="submit" onclick="editNow()">Edit Now</button>
					<button type="submit" class="btn btn-primary" name="submit" onclick="return okFunc()">Ok</button>
					<button type="submit" class="btn btn-primary" name="submit" onclick="return savepending()">Save Pendings</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<?php include "page-footer.php"; ?>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/dog-breed.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/cat-breed.js"></script>
	<script>
		let image_data ='';
		let mypic_data = '';
		let id = <?php echo $_GET['id']; ?>;
		pets = [];
		$(document).ready(function(){

			getmydetails(id);
			getmypets(id);


			let pet = [...new Set(dogs.map(item => item.name))];
			$('#pet_type').on('change',function(){
				pet = [...new Set(dogs.map(item => item.name))];
				if($('#pet_type').val()=="Dog"){
					pet = [];
					pet = [...new Set(dogs.map(item => item.name))];
				}else{
					pet = [];
					pet = [...new Set(cats.map(item => item.name))];
				}

				let ls ='';
				pet.forEach(e=>{
					ls+=`<option value="${e}">${e}</option>`;
				});
				$('#pet_breed').html(ls);
				$('#pet_breed').trigger("chosen:updated");
			});
			let ls ='';
			pet.forEach(e=>{
				ls+=`<option value="${e}">${e}</option>`;
			});
			$('#pet_breed').html(ls);
			$('#pet_breed').chosen({ width:'100%' });

			$('#addpet').click(function(){

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
function addmypet(){
	
}
$('#mynewpets').submit(function(e){
	e.preventDefault();
	let mynewpet = {};
	$('#mynewpets').serializeArray().forEach(x=>{
		mynewpet[x.name] = x.value;
	});
	mynewpet['ownerID'] = id;
	mynewpet['image'] = image_data;
	mynewpet['clinic_id'] = <?php echo $myclinic_id;?>;
	fetch(url+'mypets',{
		method:"POST",
		body: JSON.stringify(mynewpet)
	}).then(res=>res.json()).then(res=>{
				// console.log(res);
				if(res.d=="Success"){
					$('#add_pet_form').modal('hide');
					Swal.fire({
						position: 'top-end',
						icon: 'success',
						title: 'Your work has been saved',
						showConfirmButton: false,
						timer: 1500
					});
					getmypets(id);
				}
	});
})

function getmydetails(id){
	fetch(url+'owner/'+id).then(res=>res.json()).then(res=>{
		if(res[0].image == ""){
			res[0].image = "<?php echo get_site_url();?>/wp-content/uploads/2020/03/vet-app-icon-removebg-preview-1.png";
		}
		$('#myname').html(res[0].first_name+" "+res[0].last_name);
		$('#fname').val(res[0].first_name);
		$('#lname').val(res[0].last_name);
		$('#bday').val(res[0].birthday);
		$('#email').val(res[0].email);
		$('#landline').val(res[0].landline_no);
		$('#mobile').val(res[0].mobile_no);
		$('#address').val(res[0].address);
		$('#gender').val(res[0].gender);
		$('#mypic').attr('src',res[0].image);
		$('#pendings').html(res[0].pendings);
		$('#my_camera_update').html('<img src="'+res[0].image+'" class="avatar img-thumbnail" alt="avatar" id="thumbnail">');

		mypic_data = res[0].image;
		if(res[0].pendings !== ""){
			Swal.fire(res[0].pendings);
		}
	});
}
function getmypets(id){
	let ls='';
	fetch(url+'mypets/'+id).then(res=>res.json()).then(res=>{
		console.log(res);
		res.forEach(x=>{
			if(x.pet_image == ""){
				x.pet_image = "<?php echo get_site_url();?>/wp-content/uploads/2020/03/vet-app-icon-removebg-preview-1.png";
			}
			ls+=`<div class="col-md-4"><div class="card mb-4">
			<img class="card-img-top img-fluid" src="${x.pet_image}" style="height:150px;object-fit: cover;" alt="${x.pet_name}">
			<div class="card-body">
			<h4 class="card-title">${x.pet_name}</h4>
			<p class="card-text"> Kind : ${x.pet_type}</p>
			<p class="card-text"> <i class="fa fa-paw"></i> ${x.pet_breed}</p>
			<p class="card-text"> <i class="fa fa-paw"></i> Weight: ${x.pet_weight}</p>
			<a href="<?php echo get_site_url();?>/pet-details?id=${x.pet_id}" ><button class="btn btn-outline-info btn-block"><i class="fas fa-dog mr-1"></i>Visit</button></a>
			</div>
			</div></div>`;
		});
		console.log(res);
		document.querySelector('#mypets').innerHTML = ls;
	});
}

function updateMyDetails(){

	let mydetails = {};
	$('#mydetails').serializeArray().forEach(x=>{
		mydetails[x.name] = x.value;
	});
	mydetails['owner_id'] = id;
	mydetails['owner_pic'] = mypic_data;
// console.log(mydetails);

$.ajax({
	url: url+"updateowner/",
	data:mydetails,
	type: 'post',
	dataType: 'json',
	success: function(response) {
		if(response.message == "OK"){
			Swal.fire({
				position: 'top-end',
				icon: 'success',
				title: 'Owner Details Updated!',
				showConfirmButton: false,
				timer: 1500
			});
			location.reload();
		}
		
	}
});
}

function savepending(){
	let ownerpendings = {};
	$('#ownerpendings').serializeArray().forEach(x=>{
		ownerpendings[x.name] = x.value;
	});

	$.ajax({
		url: url+"ownerpendings/"+id,
		data:ownerpendings,
		type: 'post',
		dataType: 'json',
		success: function(response) {
			if(response.message == "OK"){
				Swal.fire({
					position: 'top-end',
					icon: 'success',
					title: 'Owner Pendings Updated!',
					showConfirmButton: false,
					timer: 1500
				});
				location.reload();
			}

		}
	});
}
function editNow(){
	$('#pendings').prop('readonly',false);
}
function okFunc(){
	let data = {
		'pendings' : ''
	}
	$.ajax({
		url: url+"ownerpendings/"+id,
		data:data,
		type: 'post',
		dataType: 'json',
		success: function(response) {
			if(response.message == "OK"){
				Swal.fire({
					position: 'top-end',
					icon: 'success',
					title: 'Owner Pendings Completed!',
					showConfirmButton: false,
					timer: 1500
				});
				location.reload();
			}

		}
	});


}


function editPicture(){
	Webcam.set({
		width: 265,
		height: 240,
		image_format: 'jpeg',
		jpeg_quality: 100,
		force_flash:false,
		flip_horiz: false,
		constraints: {
			video: true,
			facingMode: "environment"
		},
		fps:45
	});
	Webcam.attach( '#my_camera_update' );
	$('#takesnap_update').prop('hidden',false);
	$('#retake_update').prop('hidden',true);
	$('#takephoto_update').prop('hidden',true);
}
function take_snapshot_pic(){
	Webcam.snap( function(data_uri) {
	  // display results in page
	  Webcam.freeze();
	  Webcam.reset();
	  mypic_data = data_uri;
	  $('#my_camera_update').html('<img src="'+data_uri+'" class="avatar img-thumbnail" alt="avatar" id="thumbnail">');
	});
    // console.log(image_data);
    
    $('#takesnap_update').prop('hidden',true);
    $('#retake_update').prop('hidden',false);
    $('#takephoto_update').prop('hidden',true);
}
function capture_again_pic(){
	Webcam.set({
		width: 265,
		height: 240,
		image_format: 'jpeg',
		jpeg_quality: 100,
		force_flash:false,
		flip_horiz: false,
		constraints: {
			video: true,
			facingMode: "environment"
		},
		fps:45
	});
	Webcam.attach( '#my_camera_update' );
	$('#takesnap_update').prop('hidden',false);
	$('#retake_update').prop('hidden',true);

}
</script>
