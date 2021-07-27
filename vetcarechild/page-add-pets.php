<?php include "page-header.php"; ?>
<?php include "page-sidebar.php"; 

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

<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4"></h1>
			
			<br>
			<div class="row">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-lg-12">
							<div class="card mb-4">
								<div class="card-header"><i class="fas fa-dog mr-1"></i>Pets Lists  <div class="float-right">

								</div></div>

								<div class="card-body">
									<div class="table-responsive table-striped">
										<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>Image</th>
													<th>Name</th>
													<th>Breed</th>
													
													<th>Age</th>
													
													<th>Gender</th>
													<!--<th>Date Created</th>-->
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												global $wpdb;
												$table=$wpdb->prefix.'vet_pets';
												$results = $wpdb->get_results("SELECT * FROM {$table} where clinic_id = {$myclinic_id}");
												$arrayData = array();
												foreach ($results as $key ) {
													$age = date_diff(date_create(), date_create($key->pet_birthdate));
													$age = $age->format("%Y Year, %M Months, %d Days");
													?>
													<tr>
														<td style="width: '100%'; height: '100%';background: url('<?php echo $key->pet_image; ?>') no-repeat center center /cover"></td>
														<td><?php echo $key->pet_name;?></td>
														<td><?php echo $key->pet_breed;?></td>
														<td><?php echo $age;?></td>
														<td><?php echo $key->pet_gender;?></td>
														<td align="center">
															<a href="<?php echo get_site_url().'/pet-details?id='.$key->pet_id;?>"><button type="button" class="btn btn-primary" title="Edit"><i class="fas fa-eye"></i></button></a>
															<button type="button" class="btn btn-danger" name="deletePet" title="Delete" onclick="deletePet(<?php echo $key->pet_id;?>)"><i class="far fa-trash-alt" ></i></button>
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

			</div>






		</main>

		<?php include "page-footer.php"; ?>
		<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/dog-breed.js"></script>
		<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/cat-breed.js"></script>
		<script>
			
			pets = [];
			$(document).ready(function(){
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

				$.ajax({
					url: url+"pets/",
					type: 'get',
					dataType: 'json',
					success: function(response) {
						let owner_info = removeDuplicates(response,'owner_info');
						let ss='';
						owner_info.forEach(x=>{
							ss+=`<option value="${x.owner_id}">${x.owner_info}</option>`;
						});
						$('#ownerID').html(ss);
						$('#ownerID').chosen({ width:'100%' });

					}
				});

			});


			function updatePets(id){
				$('#update_pet_form').modal('show');

				let pet = [...new Set(dogs.map(item => item.name))];
				$('#up_pet_type').on('change',function(){
					pet = [...new Set(dogs.map(item => item.name))];
					if($('#up_pet_type').val()=="Dog"){
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
					$('#up_pet_breed').html(ls);
					$('#up_pet_breed').trigger("chosen:updated");
				});
				let as ='';
				pet.forEach(e=>{
					as+=`<option value="${e}">${e}</option>`;
				});
				$('#up_pet_breed').html(as);
				$('#up_pet_breed').chosen({ width:'100%' });

				$.ajax({
					url: url+"pets/",
					type: 'get',
					dataType: 'json',
					success: function(response) {
						let owner_info = removeDuplicates(response,'owner_info');
						let ss='';

						response.forEach(x=>{
							if(id == x.pet_id){
								ss +=`<form action="" method="POST" class="needs-validation" id="updatePetForms" novalidate>
								<div class="form-row">
								<div class="col-md-6 mb-2">
								<label for="owner_id">Name of Owner</label>
								<div class="input-group mb-3">
								<select data-placeholder="Search Pet Owner" class="form-control" name="up_ownerID" id="up_ownerID">`;
								owner_info.forEach(y=>{
									if(y.owner_id = x.owner_id){
										ss+= `<option value="${y.owner_id}" selected> ${y.owner_info}</option>`;
									}else{
										ss+=`<option value="${y.owner_id}"> ${y.owner_info}</option>`;
									}
								});
								ss+=`</select>
								</div> 
								</div>
								<div class="col-md-6 mb-2">
								<label for="pet_type">Pet Type</label>
								<select class="custom-select" id="up_pet_type" name="up_petType">`;
								if(x.pet_type=="Dog"){
									ss+=`<option value="Dog" selected>Dog</option>`;
									ss+=`<option value="Cat" >Cat</option>`;
								}else if(x.pet_type=="Cat"){
									ss+=`<option value="Dog" >Dog</option>`;
									ss+=`<option value="Cat" selected>Cat</option>`;
								}
								ss+=`</select>
								</div>
								<div class="col-md-6 mb-2">
								<label for="pet_breed">Pet Breed</label>
								<div class="input-group mb-3">
								<select data-placeholder="Type Breed..." class="form-control" name="up_petBreed" id="up_pet_breed">`
								pet.forEach(e=>{
									if(e == x.pet_breed){
										ss+=`<option value="${e}" selected>${e}</option>`;
									}else{
										ss+=`<option value="${e}">${e}</option>`;
									}
								});
								ss+=`</select>
								</div> 
								</div>

								<div class="col-md-6 mb-2">
								<label for="pet_name">Pet name</label>
								<input type="text" class="form-control" id="up_pet_name" name="up_petName" placeholder="Pet name" value="${x.pet_name}" required>

								</div>

								<div class="col-md-6 mb-2">
								<label for="pet_bday">Pet Birthdate</label>
								<input type="date" class="form-control" id="up_pet_bday" name="up_petBirthdate" placeholder="Birthdate" value="${x.pet_birthdate}" required>

								</div>

								<div class="col-md-6 mb-2">
								<label for="pet_color">Pet Color</label>
								<input type="text" class="form-control" id="up_pet_color" name="up_petColor" placeholder="Color" value="${x.pet_color}" required>

								</div>

								<div class="col-md-6 mb-2">
								<label for="pet_gender">Pet Gender</label>
								<select class="custom-select" id="up_pet_gender" name="up_petGender">`
								if(x.pet_gender=="Male"){
									ss+=`<option value="Male" selected>Male</option>`;
									ss+=`<option value="Female" >Female</option>`;
								}else if (x.pet_gender=="Female"){
									ss+=`<option value="Male" >Male</option>`;
									ss+=`<option value="Female" selected>Female</option>`;
								}


								ss+=`</select>
								<div class="valid-feedback">
								Looks good!
								</div>
								</div>

								<div class="col-md-6 mb-2">
								<label for="pet_weight">Pet Weight</label>
								<input type="number" class="form-control" id="up_pet_weight" name="up_petWeight" placeholder="Weight in kg" value="${x.pet_weight}" required>
								</div>
								<div class="col-md-6">
								<label for="inputGroupFile01">Upload File</label>
								<div class="input-group mb-3">
								<div class="input-group-prepend">
								<span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
								</div>
								<div class="custom-file">
								<input type="file" class="custom-file-input" id="inputGroupFile01" name="petImage" aria-describedby="inputGroupFileAddon01" required>
								<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
								</div>
								</div>
								</div>
								</div>
								<button type="button" class="btn btn-primary float-right" onclick="updatePetFunc(${x.pet_id})">Update Pet Details</button>
								</form>`;
							}
						});
$('#updateformpets').html(ss);
$('#up_ownerID').chosen({ width:'100%' });
$('#up_pet_breed').chosen({ width:'100%' });

					// response.forEach(res=>{
					// 	if(res.pet_id == id){
					// 		$('#pet_type').val(res.pet_type)
					// 	}
					// });
				}
			});

}
function removeDuplicates(myArr, prop) {
	return myArr.filter((obj, pos, arr) => {
		return arr.map(mapObj => mapObj[prop]).indexOf(obj[prop]) === pos;
	});
}
function updatePetFunc(id){
	let data = {};
	$('#updatePetForms').serializeArray().forEach(x=>{
		data[x.name] = x.value;
	});
	data['up_pet_id'] = id;
	console.log(data);
	$.ajax({
		url: url+"pets/",
		data:data,
		type: 'post',
		dataType: 'json',
		success: function(response) {
			$('#updatePetForms').modal('hide');
			Swal.fire({
				position: 'top-end',
				icon: 'success',
				title: 'Pets Details Updated!',
				showConfirmButton: false,
				timer: 1500
			})
		}
	});
}
function deletePet(id){
	let data ={
		'pet_id': id,
		'action' :'delete'
	}

	Swal.fire({
		title: 'Are you sure?',
		text: "Once deleted, you will not be able to recover this pet details!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
		if (result.value) {
			$.ajax({
				url: url+"pets/remove",
				data:data,
				type: 'post',
				dataType: 'json',
				success: function(response) {
					
				}
			});
			Swal.fire({
				position: 'top-end',
				icon: 'success',
				title: 'Pets Details Deleted!',
				showConfirmButton: false,
				timer: 1500
			})
			location.reload();
		}
	})
	
}
</script>