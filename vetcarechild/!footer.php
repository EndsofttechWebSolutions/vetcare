<?php 
global $post, $current_user; wp_get_current_user();
$user = get_userdata( get_current_user_id());

    // Get all the user roles as an array.
$user_roles = $user->roles;

if(!empty($user_roles)){
	if (in_array( 'um_clinic', $user_roles, true ) ) {
		$clinic_id = $user->ID;
		$clinic_email = $user->user_email;
	}else{
		$clinic_id = 1;
	}
}
$validity = checkValidity($clinic_email); 

?>

<footer class="py-4 bg-light mt-auto">
	<div class="container-fluid">
		<div class="d-flex align-items-center justify-content-between small">
			<div class="text-muted">Copyright &copy; Endsofttech Web Solutions 2020</div>
			<div>
				<a href="#">Privacy Policy</a>
				&middot;
				<a href="#">Terms &amp; Conditions</a>
			</div>
		</div>
	</div>
</footer>
</div>
</div>
<!-- Search Form -->
<div id="search">
	<button type="button" class="close">×</button>
	<form id="form-hockey_v1" name="form-hockey_v1" action="page-search-results.php">
		<div class="typeahead__container">
			<div class="typeahead__field">
				<div class="typeahead__query">
					<input id="searchval" class="js-typeahead-hockey_v1" name="hockey_v1[query]" placeholder="Search" autocomplete="off">
				</div>
				<div class="typeahead__button">
					<button type="submit">
						<i class="typeahead__search-icon"></i>
					</button>
				</div>
			</div>
		</div>
	</form>
	<br>

</div>

<!--Modal Forms-->

<div class="modal fade" id="each_appointment" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Update Appointment </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="update_app">

			</div>
			<div class="modal-footer">
				<button type="button" name="updateappointment" id="updateAppointment" class="btn btn-sub btn-info has-tooltip" data-placement="left" title="Update Appointment"> <i class="fa fa-edit"></i> </button>
				<button type="button" name="deleteappointment" id="deleteAppointment" class="btn btn-sub btn-danger has-tooltip" data-placement="left" title="Delete Appointment"> <i class="fa fa-trash-alt"></i> </button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-3-4-1.js" crossorigin="anonymous"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap-bundle.min.js" crossorigin="anonymous"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/scripts.js"></script>
<!--<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/chart.min.js" crossorigin="anonymous"></script>-->
<!--<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/demo/chart-area-demo.js"></script>-->
<!--<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/demo/chart-bar-demo.js"></script>-->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/datatable.min.js" crossorigin="anonymous"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/demo/datatables-demo.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/packages/core/main.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/packages/interaction/main.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/packages/daygrid/main.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/packages/timegrid/main.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/packages/list/main.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/moment.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/packages/bootstrap/main.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/sweetalert2.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap-select.min.js" ></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/chosen.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/signature-pad.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/app.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/webcam/webcam.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/cleave/cleave.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/cleave/cleave-phone.ph.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/text-counter/textcounter.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-typeahead/dist/jquery.typeahead.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/text-editor/summernote-bs4.js"></script>

<script>
	let url = '<?php echo get_site_url();?>/wp-json/vet/v1/';
	let homeurl = '<?php echo get_home_url();?>'
	$(document).ready(function(){
		$.ajax({
			url: url+"sms/balance",
			data:{name:"Raffielim",password:"123456"},
			type: 'POST',
			dataType: 'json',
			success: function(response) {
				$('#smsbal').html(response.balance);
			}
		});


		$.typeahead({	
			input: '.js-typeahead-hockey_v1',
			minLength: 1,
			maxItem: 20,
			maxItemPerGroup: 10,
			order: "asc",
			hint: true,
			cache: false,
			loadingAnimation: true,
			backdrop: false,
			backdropOnFocus: false,
			group: {
				key: "division",
				template: function (item) {

					var division = item.division;
					if (~division.toLowerCase().indexOf('pets')) {
						division += " ";
					} else if (~division.toLowerCase().indexOf('owners')) {
						division += " ";
					}

					return division;
				}
			},
			display: ["name", "type", "division"],
			dropdownFilter: [{
				key: 'division',
				template: '<strong>{{division}}</strong>',
				all: 'All'
			}],
			template: '<a href="{{url}}"><span>' +
			'<span class="team-logo ">' +
			'<img src="{{image}}" class="picture-search">' +
			'</span>' +
			'<span class="name">{{name}}</span>' +
			'<span class="division">' +
			'</span></a>',
			correlativeTemplate: true,
			source: {
				teams: {
					url: url+"search"
				}
			},
			callback: {
				onResult: function (node, query, result, resultCount) {
					if (query === "") return;

					var text = "";
					if (result.length > 0 && result.length < resultCount) {
						text = "Showing <strong>" + result.length + "</strong> of <strong>" + resultCount + '</strong> elements matching "' + query + '"';
					} else if (result.length > 0) {
						text = 'Showing <strong>' + result.length + '</strong> elements matching "' + query + '"';
					} else {
						text = 'No results matching "' + query + '"';
					}
					$('#result-container').html(text);

				},
			// Redirect to url after clicking or pressing enter
			onSubmit: function (node, form, item, event) {
				event.preventDefault();
				location.assign(homeurl+'/search-results?q='+node[0].value);
			}
		},
		debug :true
	});
		
		var cleave = new Cleave('.input-phone', {
			phone: true,
			uppercase: true,
			prefix: '+63',
			delimiters: ['(', ')',' ',' '],
			blocks: [0, 2 , 0, 3, 0, 3, 0, 4],
			phoneRegionCode: 'PH'
		});

	})


	var serializeArray = function (form) {

  // Setup our serialized data
  var serialized = [];

  // Loop through each field in the form
  for (var i = 0; i < form.elements.length; i++) {

  	var field = form.elements[i];

    // Don't serialize fields without a name, submits, buttons, file and reset inputs, and disabled fields
    if (!field.name || field.disabled || field.type === 'file' || field.type === 'reset' || field.type === 'submit' || field.type === 'button') continue;

    // If a multi-select, get all selections
    if (field.type === 'select-multiple') {
    	for (var n = 0; n < field.options.length; n++) {
    		if (!field.options[n].selected) continue;
    		serialized.push({
    			name: field.name,
    			value: field.options[n].value
    		});
    	}
    }

    // Convert field data to a query string
    else if ((field.type !== 'checkbox' && field.type !== 'radio') || field.checked) {
    	serialized.push({
    		name: field.name,
    		value: field.value
    	});
    }
}

return serialized;

};


(function() {
	'use strict';
	window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                	form.addEventListener('submit', function(event) {
                		if (form.checkValidity() === false) {
                			event.preventDefault();
                			event.stopPropagation();
                		}
                		form.classList.add('was-validated');

                	}, false);
                });
            }, false);
})();


</script>
<?php 
if(count($validity) == 0 && $user_roles[0] !== 'administrator' ){ ?>
	<script>

		var ifConnected = window.navigator.onLine;
		let urlnew = 'https://vaxilifecorp.com/wp-json/vet/v1/';
		if (ifConnected) {
			Swal.fire({
				title: 'Serial Key Not Found!',
				text: "Please Contact Administrator for your serial key",
				html: 'Please Enter Serial Key <br> Please Contact Website to get Access Key <a href="#">here.</a>',
				input: 'text',
				inputAttributes: {
					required: true
				},
				icon: 'warning',
				allowOutsideClick: false,
				showCancelButton: false,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Submit',
				showLoaderOnConfirm: true,
				preConfirm: (serialkey) => {
					let data = {
						serial_number : serialkey,
						customer_email: "<?php echo $clinic_email; ?>"
					}
					return fetch(urlnew+'serialverify',{
						method:"POST",
						body:JSON.stringify(data)
					}).then(response => {
						if (response.status == 404) {
							throw new Error(response.statusText)
						}
						return response.json()
					})
					.catch(error => {
						Swal.showValidationMessage(
							`Request failed: ${error}`
							)
					})
				}
			}).then((result) => {
				console.log(result);
				if (result.value.status == 200) {
					let newdata = {
						'serial_number': result.value.data[0].serial_number,
						'customer_id' : result.value.data[0].customer_id,
						'customer_email' : result.value.data[0].customer_email,
						'validity' : result.value.data[0].validity,
						'status' : result.value.data[0].status,
						'date_created': result.value.data[0].date_created
					}
					let timerInterval;
					Swal.fire({
						title: 'Serial Key Found!',
						html: 'Updating Database in Local Server',
						timer: 2000,
						timerProgressBar: true,
						onBeforeOpen: () => {
							Swal.showLoading();
							updatelocaltable(newdata);
							timerInterval = setInterval(() => {
								const content = Swal.getContent()
								if (content) {
									const b = content.querySelector('b')
									if (b) {
										b.textContent = Swal.getTimerLeft()
									}
								}
							}, 100)
						},
						onClose: () => {
							clearInterval(timerInterval)
						}
					}).then((result) => {
						/* Read more about handling dismissals below */
						if (result.dismiss === Swal.DismissReason.timer) {
							console.log('I was closed by the timer')

						}
					})
				}else{
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Seems like we cant find your validation'
					});
					location.reload();
				}
			})
		}else{
			Swal.fire(
				'The Internet?',
				'Please Check Internet Connection',
				'question'
				);

		}



		async function updatelocaltable(newdata){
			return await fetch(url+'updateseriallocal',{
				method:'POST',
				body:JSON.stringify(newdata)
			}).then(res=>{
				console.log(res);
			})
		}
	</script>
<?php } ?>
</body>
</html>
