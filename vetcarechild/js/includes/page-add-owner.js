
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
				flip_horiz:true,
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
		flip_horiz:true,
		fps:45
	});
	Webcam.attach( '#my_camera' );
	$('#takesnap').prop('hidden',false);
	$('#retake').prop('hidden',true);

}

function addowner(){
	let newownerdetails = {};
	$('#newownerdetails').serializeArray().forEach(x=>{
		newownerdetails[x.name] = x.value;
	});
	newownerdetails['role'] = "Owner";
	newownerdetails['image'] = image_data;
	if(newownerdetails['birthDate'] === "" || newownerdetails['mobileNumber'] === "" || newownerdetails['email'] === "" || newownerdetails['lastName'] === "" || newownerdetails['lastName'] === "firstName"){
		return;
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
          		title: 'Your work has been saved',
          		showConfirmButton: false,
          		timer: 1500
          	});
          	location.assign("<?php echo get_site_url();?>/owners-details?id="+res.id);
          }
      });
	}

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