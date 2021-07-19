<?php
	/*This file is part of vetcarechild, oceanwp child theme.

	All functions of this file will be loaded before of parent theme functions.
	Learn more at https://codex.wordpress.org/Child_Themes.

	Note: this function loads the parent stylesheet before, then child theme stylesheet
	(leave it in place unless you know what you are doing.)
	*/
	


	global $post, $current_user; wp_get_current_user();
	$username_sms = get_post_meta( '130', 'username_sms', true );
	$password_sms = get_post_meta( '130', 'password_sms', true );
	$sender_id = get_post_meta( '130', 'sender_id', true );
	$content_message = get_post_meta( '130', 'content_message', true );
	$tax_percentage = get_post_meta( '130', 'tax_percentage', true );

	add_role('um_clinic', 'Clinic',true);
	remove_role('wpamelia-manager');
	remove_role('wpamelia-provider');
	remove_role('wpamelia-customer');
	
	if(!empty(get_current_user_id())){
		$currentuser = get_userdata( get_current_user_id());
		$my_role = $currentuser->roles;	
	}

	if(!empty($my_role)){
		if (in_array( 'um_doctors', $my_role, true ) || in_array( 'um_groomers', $my_role, true ) ) {
			$global_clinic_id =  get_user_meta(get_current_user_id(),'clinic_id',true);
		}else if(in_array( 'um_clinic', $my_role, true )){
			$global_clinic_id = get_current_user_id();
		}else{
			$global_clinic_id = get_current_user_id();
		}
	}
	
	function vetcarechild_enqueue_child_styles() {
		$parent_style = 'parent-style';
		wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css' );
		wp_enqueue_style(
			'child-style',
			get_stylesheet_directory_uri() . '/style.css',
			array( $parent_style ),
			wp_get_theme()->get('Version') );
	}
	add_action( 'wp_enqueue_scripts', 'vetcarechild_enqueue_child_styles' );
	
	function currentLogged_in(){
		global $post, $current_user; wp_get_current_user();
		if(!empty(get_current_user_id())){
			$user = get_userdata( get_current_user_id());
			$ID = get_current_user_id();
			$user_roles = $user->roles;
		}
		$myclinic_id = '';
		if(!empty($user_roles)){
			if (in_array( 'um_doctors', $user_roles, true ) || in_array( 'um_groomers', $user_roles, true ) ) {
				$myclinic_id =  get_user_meta(get_current_user_id(),'clinic_id',true);
			}else if(in_array( 'um_clinic', $user_roles, true )){
				$myclinic_id = get_current_user_id();
			}else{
				$myclinic_id = get_current_user_id();
			}
		}
		return $myclinic_id;
	}
	function updateMessageTempate(){
		$data = json_decode(file_get_contents("php://input"));
		$clinic_id = $data->clinic_id;
		update_post_meta('130','content_message',$data->content);
		return array('status'=>200,'message'=>'OK');
	}
	function updateSMSAccount(){
		$data = json_decode(file_get_contents("php://input"));
		$myclinic_id = $data->clinic_id;
		update_post_meta('130', 'username_sms',$data->accountName);
		update_post_meta('130', 'password_sms',$data->password);
		update_post_meta('130', 'sender_id',$data->sendID);
		update_post_meta('130', 'time_sms',$data->time_sms);
		return array('status'=>200,'message'=>'OK' ,'clinic_id' => $myclinic_id);
	}
	function updateClinic(){
		$data = json_decode(file_get_contents("php://input"));
		$clinic_address = get_user_meta($data->user_id,'address',true);
		$mobile_number = get_user_meta($data->user_id,'mobile_number',true);
		$landline = get_user_meta($data->user_id,'landline',true);
			update_user_meta($data->user_id,'address',$data->address,$clinic_address);
			update_user_meta($data->user_id,'mobile_number',$data->mobile_number,$mobile_number);
			update_user_meta($data->user_id,'landline',$data->landline,$landline);

		return array('status'=>200,'message'=>'OK','user'=>$data->user_id);
	}
	
	if(isset($_POST['adddoctorbutton'])){
		insert_doctor();
	}
	if(isset($_POST['addsmsmerchantbutton'])){
		insert_merchant();
	}

	if(isset($_POST['delete_eachAppointment'])){
		$appointment_id = isset( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : '';
		delete_eachAppointment($appointment_id);
	}
	if(isset($_POST['submitlogin'])){
		login_doctor();
	}

	function insert_appointments($arr){
		global $wpdb;
		$table=$wpdb->prefix.'vet_appointments';
		$wpdb->insert( $table, $arr[0],array('%s','%s','%s','%s','%s','%s','%s','%s'));
		$appointment_number = $wpdb->insert_id;
		historyInsert(["action"=>"Created new appointment with appointment # ". $appointment_number]);
		$page_url = home_url( $wp->request );
		$redirect_to = add_query_arg($page_url);
		wp_safe_redirect( $redirect_to );
		exit;

	}
	
	// Start Login Doctors
	function login_doctor(){
		global $wpdb;  
	    //We shall SQL escape all inputs  
		$username = $wpdb->escape($_REQUEST['username']);  
		$password = $wpdb->escape($_REQUEST['password']);  
		// $remember = $wpdb->escape($_REQUEST['rememberme']);  

		// if($remember) $remember = "true";  
		// else $remember = "false";  

		$login_data = array();  
		$login_data['user_login'] = $username;  
		$login_data['user_password'] = $password;  
		// $login_data['remember'] = $remember;  

		$user_verify = wp_signon( $login_data, false );  

		if ( is_wp_error($user_verify) ) 
		{ 
			echo "<script> alert('Invalid Login Details!');</script>";
		} else
		{ 
			echo "<script type='text/javascript'>window.location.href='". home_url('/dashboard') ."'</script>"; 
			exit(); 
		} 

	}

	//End Login Doctors



	// Karl add doctors code

	function insert_doctor(){
		global $wpdb;
		$table=$wpdb->prefix.'vet_doctors';
		$last_name = isset( $_POST['lastName'] ) ? sanitize_text_field( $_POST['lastName'] ) : '';
		$first_name = isset( $_POST['firstName'] ) ? sanitize_text_field( $_POST['firstName'] ) : '';
		$address = isset( $_POST['address'] ) ? sanitize_text_field( $_POST['address'] ) : '';
		$mobile_no = isset( $_POST['mobileNumber'] ) ? sanitize_text_field( $_POST['mobileNumber'] ) : '';
		$landline_no = isset( $_POST['landlineNumber'] ) ? sanitize_text_field( $_POST['landlineNumber'] ) : '';
		$birthdate = isset( $_POST['birthdate'] ) ? sanitize_text_field( $_POST['birthdate'] ) : '';
		$gender = isset( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
		$email = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
		$uname = isset( $_POST['uname'] ) ? sanitize_text_field( $_POST['uname'] ) : '';
		$pass = isset( $_POST['pass'] ) ? sanitize_text_field( $_POST['pass'] ) : '';
		$role = isset( $_POST['role'] ) ? sanitize_text_field( $_POST['role'] ) : '';
		$post_data=array(
			'doctor_id'=>NULL,
			'last_name' => $last_name,
			'first_name' => $first_name,
			'address' => $address,
			'mobile_no' => $mobile_no,
			'landline_no' => $landline_no,
			'birthdate' => $birthdate,
			'gender' => $gender,
			'email' => $email,
			'username' => $uname,
			'password' => $pass,
			'role' => $role

		);
		$wpdb->insert( $table, $post_data,array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'));
		$page_url = home_url( $wp->request );
		$redirect_to = add_query_arg($page_url);

		wp_safe_redirect( $redirect_to );
		exit;

	}
	//

	// Karl add sms merchant code

	function insert_merchant(){
		global $wpdb;
		$table=$wpdb->prefix.'vet_sms_merchant';
		$username = isset( $_POST['userName'] ) ? sanitize_text_field( $_POST['userName'] ) : '';
		$password = isset( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';
		$email = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
		$fullname = isset( $_POST['fullName'] ) ? sanitize_text_field( $_POST['fullName'] ) : '';
		$daily_limit = isset( $_POST['dailyLimit'] ) ? sanitize_text_field( $_POST['dailyLimit'] ) : '';
		$monthly_limit = isset( $_POST['monthlyLimit'] ) ? sanitize_text_field( $_POST['monthlyLimit'] ) : '';


		$post_data=array(
			'merchant_id'=>NULL,
			'username' => $username,
			'password' => $password,
			'email' => $email,
			'fullname' => $fullname,
			'daily_limit' => $daily_limit,
			'monthly_limit' => $monthly_limit,

		);
		$wpdb->insert( $table, $post_data,array('%s','%s','%s','%s','%s','%s'));
		$page_url = home_url( $wp->request );
		$redirect_to = add_query_arg($page_url);

		wp_safe_redirect( $redirect_to );
		exit;

	}
	//
	
	function getownerbyIDS($id){
		global $wpdb;
		$table_owners=$wpdb->prefix.'vet_owners';
		$owners = $wpdb->get_results("SELECT * FROM {$table_owners}");
		$info = [];
		foreach ($owners as $key){
			if($id == $key->owner_id){
				$info = $key;
			}
		}
		return $info;
	}
	function displayPetsName(){
		global $wpdb;
		$table=$wpdb->prefix.'vet_pets';
		$data = json_decode(file_get_contents("php://input"));
		$results = $wpdb->get_results("SELECT * FROM {$table} where clinic_id = {$data->clinic_id}");
		$arrayData = array();
		foreach ($results as $key ) {
			$owner_info = getownerbyIDS($key->owner_id);
			$arrayDatas = [
				"pet_id" => $key->pet_id,
				"pet_name"=> $key->pet_name,
				"pet_owner" => $owner_info->first_name.' '.$owner_info->last_name
			];
			array_push($arrayData, $arrayDatas);
		}
		return $arrayData;
	}

	function wp_list_users(){
		global $wpdb,$wp;
		$data = json_decode(file_get_contents("php://input"));
		$users = get_users( [ 'role__in' => [ 'um_doctors', 'um_groomers'] ] );
		$info = [];
		foreach($users as $key){
			// $clinic_id = get_user_meta($key->ID,'clinic_id',true);
			if($key->roles[0] == "um_doctors"){
				$key->roles[0] = "Doctor";
			}
			if($key->roles[0] == "um_groomers"){
				$key->roles[0] = "Groomer";
			}

			$array = [
				'ID' => $key->ID,
				'name'=>$key->display_name,
				'role'=>$key->roles[0]
			];
			array_push($info,$array);
		}
		return $info;

	}

	function getStaff(){
		global $wpdb,$wp;
		$users = get_users( [ 'role__in' => [ 'um_doctors', 'um_groomers'] ] );
		$info = [];
		foreach($users as $key){
			if($key->roles[0] == "um_doctors"){
				$key->roles[0] = "Doctor";
			}
			if($key->roles[0] == "um_groomers"){
				$key->roles[0] = "Groomer";
			}
			$array = [
				'ID' => $key->ID,
				'name'=>$key->display_name,
				'role'=>$key->roles[0]
			];
			array_push($info,$array);
		}
		return $info;

	}

	function getPets(){
		global $wpdb;
		$table=$wpdb->prefix.'vet_pets';
		$results = $wpdb->get_results("SELECT * FROM {$table}");
		$info = [];
		foreach ($results as $key){
			$owner_info = get_userdata($key->owner_id);

			$arr = [
				'pet_id'=>  $key->pet_id,
				'owner_id' => $key->owner_id,
				'pet_image'=> $key->pet_image,
				'pet_name'=> $key->pet_name,
				'pet_type'=> $key->pet_type,
				'pet_breed'=>$key->pet_breed,
				'pet_birthdate'=>$key->pet_birthdate,
				'pet_color'=>$key->pet_color,
				'pet_weight'=>$key->pet_weight,
				'pet_gender'=>$key->pet_gender,
				'owner_info'=> $owner_info->display_name
			];
			array_push($info,$arr);

		}
		return $info;
	}
	// function getDoctors(){
	// 	global $wpdb;
	// 	$table=$wpdb->prefix.'vet_doctors';
	// 	$results = $wpdb->get_results("SELECT * FROM {$table}");
	// 	$info = [];
	// 	foreach ($results as $key){
	// 		$arr = [
	// 			'doctor_id'=>  $key->doctor_id ,
	// 			'last_name' => $key->last_name,
	// 			'first_name'=> $key->first_name,
	// 			'address'=> $key->address,
	// 			'mobile_no'=> $key->mobile_no,
	// 			'landline_no'=>$key->landline_no,
	// 			'birthdate'=>$key->birthdate,
	// 			'gender'=>$key->gender,
	// 			'email'=>$key->email,
	// 			'role'=>$key->role

	// 		];
	// 		array_push($info,$arr);

	// 	}
	// 	return $info;
	// }
	function addnewEmployee(){
		global $wp,$wpdb;
		$table=$wpdb->prefix.'users';
		$data = json_decode(file_get_contents("php://input"));
		$results = $wpdb->get_results("SELECT * FROM {$table} WHERE user_email = '{$data->email}' ");
		if($data->role == "Doctor"){
			$data->role = "um_doctors";
		}else{
			$data->role = "um_groomers";
		}
		if(count($results) > 0){
			return array('status'=>404,'message'=>'Email Already Used');
		}else{
			$user = wp_insert_user(array(
				'user_login'  =>  $data->uname,
				'user_email'  =>  $data->email,
				'user_pass'   =>  $data->pass,
				'role'        =>  $data->role,
				'first_name' => $data->firstName,
				'last_name' => $data->lastName,
			));
			add_user_meta($user,'address',$data->address);
			add_user_meta($user,'mobile_number',$data->mobileNumber);
			add_user_meta($user,'telephone_number',$data->landlineNumber);
			add_user_meta($user,'birthdate',$data->birthdate);
			add_user_meta($user,'gender',$data->gender);
			add_user_meta($user,'clinic_id',$data->clinic_id);

			if( !is_wp_error( $user ) ){
				return array('status'=>200,'message'=>'Data Created', 'user'=>$user);
			} else {		
        // success message
				
				return array('status'=>405,'message'=>'Username Already Exist');
			}

			

		}
		
	}
	function getDoctors($data){
		global $wp;
		$userinfo = get_userdata($data['id']);
		if($userinfo->roles[0] == "um_doctors"){
			$userinfo->roles[0] = "Doctor";
		}
		if($userinfo->roles[0] == "um_groomers"){
			$userinfo->roles[0] = "Groomer";
		}
		$arr = [
			'doctor_id'=>  $userinfo->ID ,
			'last_name' => $userinfo->last_name,
			'first_name'=> $userinfo->first_name,
			'address'=> $userinfo->address,
			'mobile_no'=> $userinfo->mobile_number,
			'landline_no'=>$userinfo->telephone_number,
			'birthdate'=>$userinfo->birthdate,
			'gender'=>$userinfo->gender,
			'email'=>$userinfo->user_email,
			'role'=>$userinfo->roles[0]
		];

		return $arr;
	}
	function updateDoctors(){
		global $wp;
		$lastName = isset( $_POST['lastName'] ) ? sanitize_text_field( $_POST['lastName'] ) : '';
		$firstName = isset( $_POST['firstName'] ) ? sanitize_text_field( $_POST['firstName'] ) : '';
		$address = isset( $_POST['address'] ) ? sanitize_text_field( $_POST['address'] ) : '';
		$mobileNumber = isset( $_POST['mobileNumber'] ) ? sanitize_text_field( $_POST['mobileNumber'] ) : '';
		$landlineNumber = isset( $_POST['landlineNumber'] ) ? sanitize_text_field( $_POST['landlineNumber'] ) : '';
		$birthdate = isset( $_POST['birthdate'] ) ? sanitize_text_field( $_POST['birthdate'] ) : '';
		$gender = isset( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
		$email = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
		$doctor_id = isset( $_POST['doctor_id'] ) ? sanitize_text_field( $_POST['doctor_id'] ) : '';
		$role = isset( $_POST['role'] ) ? sanitize_text_field( $_POST['role'] ) : '';
		$userinfo = get_userdata($doctor_id);
		if($role == "Doctor"){
			$role = "um_doctors";
		}
		if($role == "Groomer"){
			$role = "um_groomers";
		}
		$user_data = wp_update_user(array(
			'ID'=>$doctor_id,
			'last_name' => $lastName,
			'first_name'=> $firstName,
			'user_email'=>$email,
		));
		$u = new WP_User($doctor_id);
		$u->remove_role($userinfo->roles[0]);
		$u->add_role($role);
		$metas = array( 
			'address'=> $address,
			'mobile_number'=> $mobileNumber,
			'telephone_number'=>$landlineNumber,
			'birthdate'=>$birthdate,
			'gender'=>$gender
		);
		foreach($metas as $key => $value) {
			update_user_meta( $doctor_id, $key, $value );
		}
		if ( is_wp_error( $user_data ) ) {
	        // There was an error; possibly this user doesn't exist.
			echo 'Error.';
		} else {
	        // Success!
			return array('status'=>200,'message'=>'OK');
		}
	}
	// function updateDoctors(){
	// 	global $wpdb;
	// 	$table=$wpdb->prefix.'vet_doctors';
	// 	$lastName = isset( $_POST['lastName'] ) ? sanitize_text_field( $_POST['lastName'] ) : '';
	// 	$firstName = isset( $_POST['firstName'] ) ? sanitize_text_field( $_POST['firstName'] ) : '';
	// 	$address = isset( $_POST['address'] ) ? sanitize_text_field( $_POST['address'] ) : '';
	// 	$mobileNumber = isset( $_POST['mobileNumber'] ) ? sanitize_text_field( $_POST['mobileNumber'] ) : '';
	// 	$landlineNumber = isset( $_POST['landlineNumber'] ) ? sanitize_text_field( $_POST['landlineNumber'] ) : '';
	// 	$birthdate = isset( $_POST['birthdate'] ) ? sanitize_text_field( $_POST['birthdate'] ) : '';
	// 	$gender = isset( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
	// 	$email = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
	// 	$doctor_id = isset( $_POST['doctor_id'] ) ? sanitize_text_field( $_POST['doctor_id'] ) : '';
	// 	$role = isset( $_POST['role'] ) ? sanitize_text_field( $_POST['role'] ) : '';

	// 	$update_data=array(
	// 		'last_name' => $lastName,
	// 		'first_name'=> $firstName,
	// 		'address'=> $address,
	// 		'mobile_no'=> $mobileNumber,
	// 		'landline_no'=>$landlineNumber,
	// 		'birthdate'=>$birthdate,
	// 		'gender'=>$gender,
	// 		'email'=>$email,
	// 		'role'=>$role
	// 	);
	// 	$where = array('doctor_id' => $doctor_id);
	// 	$wpdb->update( $table, $update_data,$where,array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'));

	// }
	function delete_doctor(){
		global $wpdb,$wp;
		$table=$wpdb->prefix.'users';

		if($_POST['action']=="delete"){
			$doctor_id = isset( $_POST['doctor_id'] ) ? sanitize_text_field( $_POST['doctor_id'] ) : '';
			// wp_delete_user($doctor_id); 
			$wpdb->delete( $table, array( 'ID' => $doctor_id ) );
			return array('status'=>200,'message'=>'OK');
			// historyInsert(["action"=>"Deleted Pets Details ". $appointment_id]);
		}

	}
	function updatePets(){
		global $wpdb;
		$table=$wpdb->prefix.'vet_pets';
		$up_pet_id = isset( $_POST['up_pet_id'] ) ? sanitize_text_field( $_POST['up_pet_id'] ) : '';
		$up_ownerID = isset( $_POST['up_ownerID'] ) ? sanitize_text_field( $_POST['up_ownerID'] ) : '';
		$up_petBreed = isset( $_POST['up_petBreed'] ) ? sanitize_text_field( $_POST['up_petBreed'] ) : '';
		$up_petType = isset( $_POST['up_petType'] ) ? sanitize_text_field( $_POST['up_petType'] ) : '';
		$up_petName = isset( $_POST['up_petName'] ) ? sanitize_text_field( $_POST['up_petName'] ) : '';
		$up_petColor = isset( $_POST['up_petColor'] ) ? sanitize_text_field( $_POST['up_petColor'] ) : '';
		$up_petGender = isset( $_POST['up_petGender'] ) ? sanitize_text_field( $_POST['up_petGender'] ) : '';
		$up_petWeight = isset( $_POST['up_petWeight'] ) ? sanitize_text_field( $_POST['up_petWeight'] ) : '';
		$up_petBirthdate = isset( $_POST['up_petBirthdate'] ) ? sanitize_text_field( $_POST['up_petBirthdate'] ) : '';
		$up_pet_image = isset( $_POST['up_pet_image'] ) ? sanitize_text_field( $_POST['up_pet_image'] ) : '';

		$update_data=array(
			'owner_id' => $up_ownerID,
			'pet_image'=> $up_pet_image,
			'pet_name'=> $up_petName,
			'pet_type'=> $up_petType,
			'pet_breed'=>$up_petBreed,
			'pet_birthdate'=>$up_petBirthdate,
			'pet_color'=>$up_petColor,
			'pet_weight'=>$up_petWeight,
			'pet_gender'=>$up_petGender
		);
		$where = array('pet_id' => $up_pet_id);
		$wpdb->update( $table, $update_data,$where,array('%s','%s','%s','%s','%s','%s','%s','%s','%s'));
		return array('status'=>200,'message'=>"OK");
	}
	function delete_pets(){
		global $wpdb;
		$table=$wpdb->prefix.'vet_pets';

		if($_POST['action']=="delete"){
			$pet_id = isset( $_POST['pet_id'] ) ? sanitize_text_field( $_POST['pet_id'] ) : '';
			$ID = array('pet_id' => $pet_id);
			$wpdb->delete( $table, $ID);
			// historyInsert(["action"=>"Deleted Pets Details ". $appointment_id]);
		}

	}
	function insertwaiver(){
		global $wpdb;
		$data = json_decode(file_get_contents("php://input"));
		$table=$wpdb->prefix.'vet_waiver';
		$waiver_title =$data->waiver_title;
		$waiver_content = $data->waiver_content;
		$clinic_id = $data->clinic_id;
		$post_data=array(
			'waiver_id'=>NULL,
			'waiver_title'=> $waiver_title,
			'waiver_content'=> $waiver_content,
			'clinic_id' => $clinic_id
		);

		$wpdb->insert( $table, $post_data,array('%s','%s','%s'));
		$page_url = home_url( $wp->request );
		$redirect_to = add_query_arg($page_url);
		wp_safe_redirect( $redirect_to );
		exit;
	}
	function insertconsent(){
		global $wpdb;
		$table=$wpdb->prefix.'vet_consent';
		$consent_title = isset( $_POST['consent_title'] ) ? sanitize_text_field( $_POST['consent_title'] ) : '';
		$consent_content = isset( $_POST['consent_content'] ) ? sanitize_text_field( $_POST['consent_content'] ) : '';

		$post_data=array(
			'consent_id'=>NULL,
			'consent_title'=> $consent_title,
			'consent_content'=> $consent_content
		);
		$wpdb->insert( $table, $post_data,array('%s','%s','%s'));
		$page_url = home_url( $wp->request );
		$redirect_to = add_query_arg($page_url);
		wp_safe_redirect( $redirect_to );
		exit;
	}
	function get_waiverdatatable(){
		global $wpdb;
		$myclinic_id = isset( $_POST['clinic_id'] ) ? sanitize_text_field( $_POST['clinic_id'] ) : '';
		$table=$wpdb->prefix.'vet_waiver';
		$results = $wpdb->get_results("SELECT * FROM {$table} where clinic_id = {$myclinic_id}");
		$arrayData = ["data"=>[]];
		foreach ($results as $key ) {
			$arrayDatas = [$key->waiver_title,'<button type="button" class="btn btn-success" title="Edit" onclick="updateWaiver('.$key->waiver_id.')"><i class="far fa-edit"></i></button> <button type="button" class="btn btn-danger" name="deletePet" title="Delete" onclick="deleteWaiver('.$key->waiver_id.')"><i class="far fa-trash-alt"></i></button>'
		];
		array_push($arrayData['data'], $arrayDatas);
	}
	return $arrayData;
}
function get_messagesdata(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_sms';
	$myclinic_id = isset( $_POST['clinic_id'] ) ? sanitize_text_field( $_POST['clinic_id'] ) : '';
	$results = $wpdb->get_results("SELECT * FROM {$table}  ORDER BY sms_id  DESC");
	$arrayData = ["data"=>[]];
	foreach ($results as $key ) {
		$arrayDatas = [$key->contact_number,$key->message,$key->date_to_send,'<span class="badge badge-pill badge-warning">'.$key->status.'</span>','<button class="btn btn-danger btn-sm" onclick=deletemessage('.$key->sms_id.')><i class="fa fa-trash-alt"></i></button>'

	];
	array_push($arrayData['data'], $arrayDatas);
}
return $arrayData;
}
function get_statusfields(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_status_fields';
	$myclinic_id = isset( $_POST['clinic_id'] ) ? sanitize_text_field( $_POST['clinic_id'] ) : '';
	$results = $wpdb->get_results("SELECT * FROM {$table} where clinic_id = {$myclinic_id} ");
	$arrayData = ["data"=>[]];
	foreach ($results as $key ) {
		$arrayDatas = [
			$key->meta_key,
			
			'<button type="button" class="btn btn-success" name="deletePet" title="Delete" onclick=updateStatusFields("'.$key->status_field_id.'","status")><i class="far fa-edit" ></i></button> <button type="button" class="btn btn-danger" name="deletePet" title="Delete" onclick=deleteStatusFields("'.$key->status_field_id.'","status")><i class="far fa-trash-alt" ></i></button>'
		];
		array_push($arrayData['data'], $arrayDatas);
	}
	return $arrayData;
}
function get_testfields(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_test_fields';
	$myclinic_id = isset( $_POST['clinic_id'] ) ? sanitize_text_field( $_POST['clinic_id'] ) : '';
	$results = $wpdb->get_results("SELECT * FROM {$table} where clinic_id = {$myclinic_id} ");
	$arrayData = ["data"=>[]];
	foreach ($results as $key ) {
		$arrayDatas = [$key->meta_key,'<button type="button" class="btn btn-success" name="deletePet" title="Delete" onclick=updateStatusFields("'.$key->test_field_id.'","test")><i class="far fa-edit" ></i></button> <button type="button" class="btn btn-danger" name="deletePet" title="Delete" onclick=deleteStatusFields("'.$key->test_field_id.'","test")><i class="far fa-trash-alt" ></i></button>'
	];
	array_push($arrayData['data'], $arrayDatas);
}
return $arrayData;
}
function get_consentdatatable(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_consent';
	$results = $wpdb->get_results("SELECT * FROM {$table}");
	$arrayData = ["data"=>[]];
	foreach ($results as $key ) {
		$arrayDatas = [$key->consent_title,'<button type="button" class="btn btn-success" title="Edit" onclick="updateConsent('.$key->consent_id.')"><i class="far fa-edit"></i></button> <button type="button" class="btn btn-danger" name="deletePet" title="Delete" onclick="deleteConsent('.$key->consent_id.')"><i class="far fa-trash-alt"></i></button>'
	];
	array_push($arrayData['data'], $arrayDatas);
}
return $arrayData;
}
function get_waiver($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_waiver';
	$results = $wpdb->get_results("SELECT * FROM {$table} where clinic_id =".$data['id']." ");
	$arrayData = array();
	foreach ($results as $key ) {
		$arrayDatas = [
			'waiver_id' => $key->waiver_id,
			'waiver_title' => $key->waiver_title,
			'waiver_content' => $key->waiver_content
		];
		array_push($arrayData, $arrayDatas);
	}
	return $arrayData;
}
function get_consent(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_consent';
	$results = $wpdb->get_results("SELECT * FROM {$table}");
	$arrayData = array();
	foreach ($results as $key ) {
		$arrayDatas = [
			'consent_id' => $key->consent_id,
			'consent_title' => $key->consent_title,
			'consent_content' => $key->consent_content
		];
		array_push($arrayData, $arrayDatas);
	}
	return $arrayData;
}
function updatewaiver(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_waiver';
	$data = json_decode(file_get_contents("php://input"));
	$waiver_id = $data->waiver_id;
	$waiver_title = $data->waiver_title;
	$waiver_content = $data->waiver_content;

	$update_data=array(
		'waiver_title'=> $waiver_title,
		'waiver_content'=> $waiver_content
	);
	$where = array('waiver_id' => $waiver_id);
	$wpdb->update( $table, $update_data,$where,array('%s','%s','%s'));
	return array('status'=>200,'message'=>'OK');
}
function updateconsent(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_consent';
	$consent_id = isset( $_POST['consent_id'] ) ? sanitize_text_field( $_POST['consent_id'] ) : '';
	$consent_title = isset( $_POST['consent_title'] ) ? sanitize_text_field( $_POST['consent_title'] ) : '';
	$consent_content = isset( $_POST['consent_content'] ) ? sanitize_text_field( $_POST['consent_content'] ) : '';

	$update_data=array(
		'consent_title'=> $consent_title,
		'consent_content'=> $consent_content
	);
	$where = array('consent_id' => $consent_id);
	$wpdb->update( $table, $update_data,$where,array('%s','%s','%s'));
}
function deletewaiver(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_waiver';

	if($_POST['action']=="delete"){
		$waiver_id = isset( $_POST['waiver_id'] ) ? sanitize_text_field( $_POST['waiver_id'] ) : '';
		$ID = array('waiver_id' => $waiver_id);
		$wpdb->delete( $table, $ID);
			// historyInsert(["action"=>"Deleted Pets Details ". $appointment_id]);
	}
	return array('status'=>200,'message'=>'OK');
}
function deletemessage($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_sms';

	if($_POST['action']=="delete"){
		$ID = array('sms_id' => $data['id']);
		deleteBulkSMS($data['id']);
		$wpdb->delete( $table, $ID);
			// historyInsert(["action"=>"Deleted Pets Details ". $appointment_id]);
	}
	return array('status'=>200,'message'=>'OK');
}

function statusFields($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_status_fields';
	$id = $data['id'];
	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE clinic_id  = $id group by meta_key");
	$arrayData = array();
	foreach ($results as $key ) {
		$arrayDatas = [
			'meta_key' => $key->meta_key,
			'meta_value' => $key->meta_value
		];
		array_push($arrayData, $arrayDatas);
	}
	return $arrayData;

}
function statusFieldsByID($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_pet_status';
	$id = '1';
	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE appointment_id = ".$data['id']." group by meta_key");
	$arrayData = array();
	foreach ($results as $key ) {
		$arrayDatas = [
			'status_id' => $key->status_id,
			'meta_key' => $key->meta_key,
			'meta_value'=> $key->meta_value
		];
		array_push($arrayData, $arrayDatas);
	}
	return $arrayData;

}
function testFields($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_test_fields';
	$id = $data['id'];
	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE clinic_id  = $id group by meta_key");
	$arrayData = array();
	foreach ($results as $key ) {
		$arrayDatas = [
			'meta_key' => $key->meta_key,
			'meta_value' => $key->meta_value
		];
		array_push($arrayData, $arrayDatas);
	}
	return $arrayData;

}
function testFieldsByID($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_pet_test';
	$id = '1';
	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE appointment_id = ".$data['id']." group by meta_key");
	$arrayData = array();
	foreach ($results as $key ) {
		$arrayDatas = [
			'test_id' => $key->status_id,
			'meta_key' => $key->meta_key,
			'meta_value'=> $key->meta_value
		];
		array_push($arrayData, $arrayDatas);
	}
	return $arrayData;

}
function fileslisttotable($data){
	global $wpdb;
	$table_pets=$wpdb->prefix.'vet_pet_attachments';
	$upload_dir = wp_upload_dir();
	$results = $wpdb->get_results("SELECT * FROM {$table_pets} WHERE appointment_id = ".$data['id']."");
	$arrayData = ["data"=>[]];
	foreach ($results as $key ) {
		$location = $upload_dir['baseurl'] .'/'.  $key->uploaded_file;
		$arrayDatas = [
			$key->uploaded_file,
			'<a href="'.$location.'" target="_blank"><button class="btn btn-primary"><i class="fas fa-eye"></i></button></a> <a href="'.$location.'" target="_blank" download><button class="btn btn-secondary"><i class="fas fa-download"></i></button></a> <button class="btn btn-danger" onclick="deleteAttach('.$key->attach_id.')"><i class="fas fa-trash"></i></button>',
			$key->date_created
		];
		array_push($arrayData['data'], $arrayDatas);
	}
	return $arrayData;
}

function putOldAppInTable($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_appointments';
	$past_med = $wpdb->get_results("SELECT * FROM {$table} where pet_id = '".$data['id']."' ORDER BY start_date DESC");
	$arrayData = ["data"=>[]];
	foreach ($past_med as $key ) {
		$appointment_id = $key->appointment_id;
		if($key->service_name == "Board"){
			$key->service_name = "Board & Lodging";
		}
		$date = $key->start_date;
		if($key->Remarks == "Completed"){
			$remarks =  '<span class="badge badge-success">Completed</span>';
		} else if($key->Remarks == "Upcoming"){
			$remarks = '<span class="badge badge-warning">Upcoming</span>';
		}else {
			$remarks =  '<span class="badge badge-danger">Absent</span>';
		}

		$arrayDatas = [
			'appointment_id'=>$key->appointment_id,
			'date'=> '<small>'.date('l jS \of F Y',strtotime($date)).'</small>',
			'service'=>'<div class="d-flex w-100 justify-content-between">
			<h5 class="mb-1">'.$key->service_name.'</h5>
			</div>',
			'remarks'=>$remarks
		];
		array_push($arrayData['data'], $arrayDatas);
	}
	return $arrayData;

}
function getallowners(){
	global $wpdb;
	$table_owners=$wpdb->prefix.'vet_owners';
	$owners = $wpdb->get_results("SELECT * FROM {$table_owners}");
	$info = [];
	foreach ($owners as $key){
		$arr = [
			'owner_id'=>  $key->owner_id,
			'name'=> $key->first_name.' '.$key->last_name,
			'address'=> $key->address,
			'gender'=>$key->gender,
			'birthday'=>$key->birthdate,
			'email'=>$key->email,
			'mobile_no'=>$key->mobile_no,
			'landline_no'=>$key->landline_no,
			'image'=>$key->image,
			'role'=>$key->role
		];
		array_push($info,$arr);

	}
	return $info;
}
function getownerbyid($data){
	global $wpdb;
	$table_owners=$wpdb->prefix.'vet_owners';
	$owners = $wpdb->get_results("SELECT * FROM {$table_owners} where owner_id = ".$data['id']." ");
	$info = [];
	foreach ($owners as $key){
		$arr = [
			'owner_id'=>  $key->owner_id,
			'first_name'=> $key->first_name,
			'last_name'=> $key->last_name,
			'address'=> $key->address,
			'gender'=>$key->gender,
			'birthday'=>$key->birthdate,
			'email'=>$key->email,
			'mobile_no'=>$key->mobile_no,
			'landline_no'=>$key->landline_no,
			'image'=>$key->image,
			'role'=>$key->role,
			'pendings'=> $key->pendings
		];
		array_push($info,$arr);

	}
	return $info;
}
function getpetdetailsbyid($data){
	global $wpdb;
	$table_owners=$wpdb->prefix.'vet_pets';
	$owners = $wpdb->get_results("SELECT * FROM {$table_owners} where pet_id = ".$data['id']." ");
	$info = [];
	foreach ($owners as $key){
		$arr = [
			'pet_id'=>  $key->pet_id,
			'owner_id'=> $key->owner_id,
			'pet_image'=> $key->pet_image,
			'pet_name'=>$key->pet_name,
			'pet_type'=>$key->pet_type,
			'pet_breed'=>$key->pet_breed,
			'pet_birthdate'=>$key->pet_birthdate,
			'pet_color'=>$key->pet_color,
			'pet_weight'=>$key->pet_weight,
			'pet_gender'=>$key->pet_gender
		];
		array_push($info,$arr);

	}
	return $info;
}
function insert_pets(){
	$data = json_decode(file_get_contents("php://input"));
	$post_data=array(
		'pet_id'=>NULL,
		'owner_id' => $data->ownerID,
		'pet_image' => $data->image,
		'pet_name' => $data->petName,
		'pet_type' => $data->petType,
		'pet_breed' => $data->petBreed,
		'pet_birthdate' => $data->petBirthdate,
		'pet_color' => $data->petColor,
		'pet_weight' => $data->petWeight,
		'pet_gender' => $data->petGender,
		'clinic_id' => $data->clinic_id
	);
		// var_dump($post_data);
	$new_id = insert_some(['vet_pets',$post_data]);
	$info = ['status'=>200,'d'=>'Success', 'id'=>$new_id];
	return $info;

}
function insertnewowner(){
	$data = json_decode(file_get_contents("php://input"));
			// var_dump($data);
	$image = explode(',', $data->image);
	$image_upload = base64_decode($image[1]);
	$post_data=array(
		'owner_id'=>NULL,
		'last_name'=>$data->lastName,
		'first_name'=>$data->firstName,
		'address'=>$data->address,
		'gender'=>$data->gender,
		'email'=>$data->email,
		'mobile_no'=>$data->mobileNumber,
		'landline_no'=>$data->landlineNumber,
		'birthdate'=>$data->birthDate,
		'role'=>$data->role,
		'image'=>$data->image,
		'clinic_id' => $data->clinic_id
	);
		// var_dump($data->image);
	$attach_id = insert_some(['vet_owners',$post_data]);
	$info = ['status'=>200,'d'=>'Success', 'id'=>$attach_id];
	return $info;
}
function updateOwners(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_owners';
	$lastName = isset( $_POST['lastName'] ) ? sanitize_text_field( $_POST['lastName'] ) : '';
	$firstName = isset( $_POST['firstName'] ) ? sanitize_text_field( $_POST['firstName'] ) : '';
	$address = isset( $_POST['address'] ) ? sanitize_text_field( $_POST['address'] ) : '';
	$mobileNumber = isset( $_POST['mobileNumber'] ) ? sanitize_text_field( $_POST['mobileNumber'] ) : '';
	$landlineNumber = isset( $_POST['landlineNumber'] ) ? sanitize_text_field( $_POST['landlineNumber'] ) : '';
	$birthdate = isset( $_POST['birthdate'] ) ? sanitize_text_field( $_POST['birthdate'] ) : '';
	$gender = isset( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
	$email = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
	$owner_id = isset( $_POST['owner_id'] ) ? sanitize_text_field( $_POST['owner_id'] ) : '';
	$owner_pic = isset( $_POST['owner_pic'] ) ? sanitize_text_field( $_POST['owner_pic'] ) : '';

	$update_data=array(
		'last_name' => $lastName,
		'first_name'=> $firstName,
		'address'=> $address,
		'mobile_no'=> $mobileNumber,
		'landline_no'=>$landlineNumber,
		'birthdate'=>$birthdate,
		'gender'=>$gender,
		'email'=>$email,
		'image'=>$owner_pic 
	);
	$where = array('owner_id' => $owner_id);
	$wpdb->update( $table, $update_data,$where,array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'));
	return array('status'=>200,'message'=>"OK");
}
function delete_owners(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_owners';

	if($_POST['action']=="delete"){
		$pet_id = isset( $_POST['owner_id'] ) ? sanitize_text_field( $_POST['owner_id'] ) : '';
		$ID = array('owner_id' => $pet_id);
		$wpdb->delete( $table, $ID);
			// historyInsert(["action"=>"Deleted Pets Details ". $appointment_id]);
	}
	return array('status'=>200, 'message'=>'OK');

}
function getpetsbyowner($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_pets';
	$results = $wpdb->get_results("SELECT * FROM {$table} where owner_id = ".$data['id']."");
	$arrayData = array();
	foreach ($results as $key ) {
		$arrayDatas = [
			"pet_id" => $key->pet_id,
			"owner_id"=> $key->owner_id,
			"pet_image"=> $key->pet_image,
			"pet_name"=> $key->pet_name,
			"pet_type"=> $key->pet_type,
			"pet_breed"=> $key->pet_breed,
			"pet_birthdate"=> $key->pet_birthdate,
			"pet_color"=> $key->pet_color,
			"pet_weight"=> $key->pet_weight,
			"pet_gender" => $key->pet_gender
		];
		array_push($arrayData, $arrayDatas);
	}
	return $arrayData;
}


function searchPetsOwner(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_pets';
	$table_owners=$wpdb->prefix.'vet_owners';
	$results = $wpdb->get_results("SELECT * FROM {$table}");
	$owners = $wpdb->get_results("SELECT * FROM {$table_owners}");
	$info = [];
	foreach ($results as $key){
		$owner_info = get_userdata($key->owner_id);

		$arr = [
			'id'=>  $key->pet_id,
			'image'=> $key->pet_image,
			'name'=> $key->pet_name,
			'type'=> $key->pet_breed,
			'url'=>get_site_url().'/pet-details?id='.$key->pet_id,
			'division'=>'Pets'
		];
		array_push($info,$arr);

	}
	foreach ($owners as $key){
		$arr = [
			'id'=>  $key->owner_id,
			'image'=> $key->image,
			'type'=> $key->role,
			'url'=> get_site_url().'/owners-details?id='.$key->owner_id,
			'name'=> $key->first_name.' '.$key->last_name,
			'division'=>'Owners'
		];
		array_push($info,$arr);

	}
	return $info;
}

function getStatusDetails($id){
	global $wpdb;
	$statustable=$wpdb->prefix.'vet_pet_status';
	$statuslist = $wpdb->get_results("SELECT * FROM {$statustable} WHERE appointment_id = ".$id." group by meta_key");
	$arrayData = array();
	foreach ($statuslist as $skey ) {
		$arr = [
			'status_name' => $skey->meta_key,
			'status_value' => $skey->meta_value
		];
		array_push($arrayData,$arr);
	}
	return $arrayData;
}
function getTestDetails($id){
	global $wpdb;
	$testtable=$wpdb->prefix.'vet_pet_test';
	$testlist = $wpdb->get_results("SELECT * FROM {$testtable} WHERE appointment_id = ".$id." group by meta_key");
	$arrayData = array();
	foreach ($testlist as $skey ) {
		$arr = [
			'test_name' => $skey->meta_key,
			'test_value' => $skey->meta_value
		];
		array_push($arrayData,$arr);
	}
	return $arrayData;
}

function updateOwnersPendings($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_owners';
	$pendings = isset( $_POST['pendings'] ) ? sanitize_text_field( $_POST['pendings'] ) : '';

	$update_data=array(
		'pendings' => $pendings,
	);
	$where = array('owner_id' => $data['id']);
	$wpdb->update( $table, $update_data,$where,array('%s'));

	return array('status'=>200 , 'message'=>'OK');

}
function removeStatusinAp(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_pet_status';
	$data = json_decode(file_get_contents("php://input"));
	$ID = array('appointment_id' => $data->appointment_id,'meta_key'=> $data->meta_key);
	$wpdb->delete( $table, $ID);

	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE appointment_id = ".$data->appointment_id." group by meta_key");
	$arrayData = array();
	foreach ($results as $key ) {
		$arrayDatas = [
			'status_id' => $key->status_id,
			'meta_key' => $key->meta_key,
			'meta_value'=> $key->meta_value
		];
		array_push($arrayData, $arrayDatas);
	}

	return array('status'=>200 , 'message'=>'OK', 'data' => $arrayData);

}
function removeTestinAp(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_pet_test';
	$data = json_decode(file_get_contents("php://input"));
	$ID = array('appointment_id' => $data->appointment_id,'meta_key'=> $data->meta_key);
	$wpdb->delete( $table, $ID);

	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE appointment_id = ".$data->appointment_id." group by meta_key");
	$arrayData = array();
	foreach ($results as $key ) {
		$arrayDatas = [
			'status_id' => $key->status_id,
			'meta_key' => $key->meta_key,
			'meta_value'=> $key->meta_value
		];
		array_push($arrayData, $arrayDatas);
	}

	return array('status'=>200 , 'message'=>'OK', 'data' => $arrayData);

}

function getEmployees(){
	global $wpdb;
	$usersall = get_users( [ 'role__in' => [ 'um_doctors', 'um_groomers'] ] );
	$arrayData = ["data"=>[]];
	foreach ($usersall as $key ) {
		if($key->roles[0] == "um_doctors"){
			$key->roles[0] = "Doctor";
		}
		if($key->roles[0] == "um_groomers"){
			$key->roles[0] = "Groomer";
		}
		$arrayDatas = [
			$key->last_name,
			$key->first_name,
			$key->address,
			$key->mobile_number,
			$key->telephone_number,
			$key->gender,
			$key->user_email,
			'<button type="button" class="btn btn-success" title="Edit" onclick="updateDoctor('.$key->ID.')"><i class="fas fa-edit"></i></button><button type="button" class="btn btn-danger" name="deletePet" title="Delete" onclick="deleteDoctor('.$key->ID.')"><i class="far fa-trash-alt" ></i></button>'

		];
		array_push($arrayData['data'], $arrayDatas);
	}
	return $arrayData;
}


add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
	if ( is_woocommerce() || is_checkout() || is_cart() ) {
		wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/header.php' );
		wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/footer.php' );
	}

}

function getAllProducts($datas){
	$args     = array( 'post_type' => 'product', 'post_status' => 'publish', 'post_author'=>$datas['id'], 'posts_per_page' => -1 );
	$products = get_posts( $args ); 
	$data = [];
// 	var_dump($datas['id']);
	foreach ($products as $key ) {
		if($key->post_author == $datas['id'] ){
			$product = new WC_Product( $key->ID );
			$arrayDatas = [
				'product_id' => $key->ID,
				'product_title' => $key->post_title,
				'product_content' => $key->post_content,
				'product_status' => $key->post_status,
				'product_quantity' => $product->get_stock_quantity(),
				'product_status' => $product->get_stock_status(),
				'product_price' => $product->get_price()
			];
			array_push($data,$arrayDatas);
		}
	}

	return $data;

}
function display_products(){
	global $post, $current_user; wp_get_current_user();
	$global_clinic_id = isset( $_POST['clinic_id'] ) ? sanitize_text_field( $_POST['clinic_id'] ) : '';
	$args     = array( 'post_type' => 'product','post_status' => 'publish', 'post_author' => $global_clinic_id, 'posts_per_page' => -1 );
	$products = get_posts( $args ); 
	$arrayData = ["data"=>[]];
	$quantity = 0;
	foreach ($products as $key ) {
		if($key->post_author == $global_clinic_id ){
			$product = new WC_Product( $key->ID );
			if($product->get_stock_quantity() <= 0){
				$quantity = 0;
			}else{
				$quantity = $product->get_stock_quantity();
			}
			$arrayDatas = [
				$key->ID,
				$key->post_title,
				$quantity,
				$product->get_price(),
				'<button type="button" class="btn btn-success" title="Edit" onclick="updateProduct('.$key->ID.')"><i class="fas fa-edit"></i></button> <button type="button" class="btn btn-danger" name="deletePet" title="Delete" onclick="deleteProduct('.$key->ID.')"><i class="far fa-trash-alt" ></i></button>'
			];
			array_push($arrayData['data'], $arrayDatas);
		}
	}
	return $arrayData;
}
function addproduct(){
	global $wpdb,$wp;
	$data = json_decode(file_get_contents("php://input"));
	$product_1 = array(
		'post_author' => $data->author_id,
		'post_title' => $data->product_name,
		'post_content' => $data->content,
		'post_status' => 'publish',
		'post_type' => "product"
		
	);
	$post_id = wp_insert_post( $product_1 );
	wp_set_object_terms( $post_id, 'simple', 'product_type' );
	update_post_meta( $post_id, '_price', $data->price );
	update_post_meta( $post_id, '_featured', 'yes' );
	update_post_meta( $post_id, '_stock', $data->stock );
	update_post_meta( $post_id, '_stock_status', 'instock');
	update_post_meta( $post_id, '_sku', 'jk01' );
	return array('status'=>200 , 'message'=>'OK');
}
function modifyProduct(){
	global $wpdb,$wp;
	$data = json_decode(file_get_contents("php://input"));
	if($data->action == 'delete'){
		$posts = array(
			'ID' => $data->product_id,
			'post_status' => 'draft'
		);
		wp_update_post($posts);
		return array('status'=>200 , 'message'=>'OK');
	}else{
		$posts = array(
			'ID' => $data->product_id,
			'post_title' => $data->product_name,
			'post_content' => $data->content
		);
		wp_update_post($posts);
		update_post_meta( $data->product_id, '_price', $data->price );
		update_post_meta( $data->product_id, '_stock', $data->stock );
		return array('status'=>200 , 'message'=>'OK');
	}

}
function changeTaxVal(){
	$data = json_decode(file_get_contents("php://input"));
	$clinic_id = $data->clinic_id;
	update_post_meta($clinic_id, 'tax_percentage',$data->taxvalue);
	return array('status'=>200 , 'message'=>'OK');
}

function inserttaxfields(){
	$data = json_decode(file_get_contents("php://input"));
	if($data->property == 'Percent'){
		$data->property = 1;
	}else{
		$data->property = 0;
	}
	$post_data=array(
		'tax_id'=>NULL,
		'tax_name'=>$data->tax_name,
		'tax_value'=>$data->tax_value,
		'is_percent'=>$data->property,
		'clinic_id'=>$data->clinic_id
	);
	$tax_id = insert_some(['vet_tax_fields',$post_data]);
	$info = ['status'=>200,'message'=>'OK', 'id'=>$tax_id];
	return $info;
}
function getTaxFields($data){
	global $wpdb,$wp;
	$table=$wpdb->prefix.'vet_tax_fields';
	$tax = $wpdb->get_results("SELECT * FROM {$table} where clinic_id = ".$data['id']." ");
	$info = [];
	foreach ($tax as $key){
		$arr = [
			'tax_id'=>  $key->tax_id,
			'tax_name'=> $key->tax_name,
			'tax_value'=> $key->tax_value,
			'is_percent'=>$key->is_percent
		];
		array_push($info,$arr);

	}
	return $info;
}
function getTaxData(){
	global $wpdb,$wp;
	$table=$wpdb->prefix.'vet_tax_fields';
	$global_clinic_id = isset( $_POST['clinic_id'] ) ? sanitize_text_field( $_POST['clinic_id'] ) : '';
    // $tax_percentage = get_post_meta( $clinic_id, 'tax_percentage', true );
	$arrayData = ["data"=>[]];
	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE clinic_id = ".$global_clinic_id." ");
	foreach ($results as $key ) {
		$arrayDatas = [
			$key->tax_name,
			$key->tax_value,
			'<button type="button" class="btn btn-success" title="Edit" onclick="updateTax('.$key->tax_id.')"><i class="fas fa-edit"></i></button> <button type="button" class="btn btn-danger" name="deletePet" title="Delete" onclick="deleteTax('.$key->tax_id.')"><i class="far fa-trash-alt" ></i></button>'
		];
		array_push($arrayData['data'], $arrayDatas);
	}
	
	return $arrayData;
}
function updateTax(){
	global $wpdb,$wp;
	$table=$wpdb->prefix.'vet_tax_fields';
	$data = json_decode(file_get_contents("php://input"));
	if($data->property == 'Percent'){
		$data->property = 1;
	}else{
		$data->property = 0;
	}
	$update_data=array(
		'tax_name'=> $data->tax_name,
		'tax_value'=> $data->tax_value,
		'is_percent'=> $data->property,
		'clinic_id'=>$data->clinic_id,
	);
	$where = array('tax_id' => $data->tax_id);
	$wpdb->update( $table, $update_data,$where,array('%s','%s','%s','%s'));
	return array('status'=>200,'message'=>"OK",'data'=>$update_data);
}
function delete_tax(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_tax_fields';
	$data = json_decode(file_get_contents("php://input"));
	if($data->action=="delete"){
		$ID = array('tax_id' => $data->tax_id);
		$wpdb->delete( $table, $ID);
			// historyInsert(["action"=>"Deleted Pets Details ". $appointment_id]);
	}
	return array('status'=>200, 'message'=>'OK');
}

function getCouponData(){
	global $wpdb,$wp;
	$table=$wpdb->prefix.'vet_coupon';
	$global_clinic_id = isset( $_POST['clinic_id'] ) ? sanitize_text_field( $_POST['clinic_id'] ) : '';
    // $tax_percentage = get_post_meta( $clinic_id, 'tax_percentage', true );
	$arrayData = ["data"=>[]];
	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE clinic_id = ".$global_clinic_id." ");
	foreach ($results as $key ) {
		$arrayDatas = [
			$key->code,
			$key->value,
			'<button type="button" class="btn btn-danger" name="deletePet" title="Delete" onclick="deleteCoupon('.$key->coupon_id.')"><i class="far fa-trash-alt" ></i></button>'
		];
		array_push($arrayData['data'], $arrayDatas);
	}
	
	return $arrayData;
}
function insertcoupon(){
	$data = json_decode(file_get_contents("php://input"));
	if($data->coupon_property == 'Percent'){
		$data->coupon_property = 1;
	}else{
		$data->coupon_property = 0;
	}
	$post_data=array(
		'coupon_id'=>NULL,
		'code'=>$data->coupon,
		'value'=>$data->coupon_value,
		'is_percent'=>$data->coupon_property,
		'clinic_id'=>$data->clinic_id
	);
	$coupon_id = insert_some(['vet_coupon',$post_data]);
	$info = ['status'=>200,'message'=>'OK', 'id'=>$coupon_id];
	return $info;
}
function delete_coupon(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_coupon';
	$data = json_decode(file_get_contents("php://input"));
	if($data->action=="delete"){
		$ID = array('coupon_id' => $data->coupon_id);
		$wpdb->delete( $table, $ID);
			// historyInsert(["action"=>"Deleted Pets Details ". $appointment_id]);
	}
	return array('status'=>200, 'message'=>'OK');
}
function getTransactions(){
	global $wpdb,$wp;
	$table=$wpdb->prefix.'vet_transactions';
	$appointment_id = isset( $_POST['appointment_id'] ) ? sanitize_text_field( $_POST['appointment_id'] ) : '';
    // $tax_percentage = get_post_meta( $clinic_id, 'tax_percentage', true );
	$arrayData = ["data"=>[]];
	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE appointment_id = ".$appointment_id." ");
	foreach ($results as $key ) {
		$arrayDatas = [
			date("F j, Y, g:i a",strtotime($key->payment_date)),
			$key->amount,
			$key->balance,
			$key->status
		];
		array_push($arrayData['data'], $arrayDatas);
	}
	
	return $arrayData;
}

function getFieldData($table,$clinic_id){
	global $wpdb;
	if($table == 'status'){
		$table=$wpdb->prefix.'vet_status_fields';    
	}else{
		$table=$wpdb->prefix.'vet_test_fields';    
	}
	$results = $wpdb->get_results("SELECT * FROM {$table} where clinic_id = {$clinic_id} ");
	return $results; 
}
function updateEachField(){
	global $wpdb;
	$data = json_decode(file_get_contents("php://input"));
	if($data->table == 'status'){
		$table = $wpdb->prefix.'vet_status_fields';   
		$where = array('status_field_id' => $data->id);
	}else{
		$table=$wpdb->prefix.'vet_test_fields';  
		$where = array('test_field_id' => $data->id);
	}
	$update_data=array(
		'clinic_id'=> $data->clinic_id,
		'meta_key'=> $data->singlestatus_field,
		'meta_value'=> $data->singlestatus_price,
	);
	$wpdb->update( $table, $update_data,$where,array('%s','%s','%s'));
	return array('status'=>200,'message'=>"OK",'data'=>$update_data);
}
add_action('rest_api_init',function(){
	register_rest_route('vet/v1' , 'field/update',[
		'methods' => 'POST',
		'callback' => 'updateEachField',
	]);
	register_rest_route('vet/v1' , 'transactions',[
		'methods' => 'POST',
		'callback' => 'getTransactions',
	]);

	register_rest_route('vet/v1' , 'coupon/remove',[
		'methods' => 'POST',
		'callback' => 'delete_coupon',
	]);
	register_rest_route('vet/v1' , 'coupon/new',[
		'methods' => 'POST',
		'callback' => 'insertcoupon',
	]);
	register_rest_route('vet/v1' , 'coupon',[
		'methods' => 'POST',
		'callback' => 'getCouponData',
	]);
	register_rest_route('vet/v1' , 'tax/remove',[
		'methods' => 'POST',
		'callback' => 'delete_tax',
	]);
	register_rest_route('vet/v1' , 'tax/update',[
		'methods' => 'POST',
		'callback' => 'updateTax',
	]);
	register_rest_route('vet/v1' , 'tax/new',[
		'methods' => 'POST',
		'callback' => 'inserttaxfields',
	]);
	register_rest_route('vet/v1' , 'tax/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'getTaxFields',
	]);
	register_rest_route('vet/v1' , 'tax',[
		'methods' => 'POST',
		'callback' => 'getTaxData',
	]);
	register_rest_route('vet/v1' , 'tax/change',[
		'methods' => 'POST',
		'callback' => 'changeTaxVal',
	]);
	register_rest_route('vet/v1' , 'ownerpendings/(?P<id>\d+)',[
		'methods' => 'POST',
		'callback' => 'updateOwnersPendings',
	]);
	register_rest_route('vet/v1' , 'owner/remove',[
		'methods' => 'POST',
		'callback' => 'delete_owners',
	]);
	register_rest_route('vet/v1' , 'updateowner',[
		'methods' => 'POST',
		'callback' => 'updateOwners',
	]);
	register_rest_route('vet/v1' , 'mypetsdetails/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'getpetdetailsbyid',
	]);
	register_rest_route('vet/v1' , 'mypets',[
		'methods' => 'POST',
		'callback' => 'insert_pets',
	]);
	register_rest_route('vet/v1' , 'mypets/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'getpetsbyowner',
	]);
	register_rest_route('vet/v1' , 'owner/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'getownerbyid',
	]);
	register_rest_route('vet/v1' , 'owners',[
		'methods' => 'GET',
		'callback' => 'getallowners',
	]);

	register_rest_route('vet/v1' , 'owners',[
		'methods' => 'POST',
		'callback' => 'insertnewowner',
	]);

	register_rest_route('vet/v1' , 'search',[
		'methods' => 'GET',
		'callback' => 'searchPetsOwner',
	]);

	register_rest_route('vet/v1' , 'pets',[
		'methods' => 'POST',
		'callback' => 'updatePets',
	]);
	register_rest_route('vet/v1' , 'pets/remove',[
		'methods' => 'POST',
		'callback' => 'delete_pets',
	]);

	register_rest_route('vet/v1' , 'doctors/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'getDoctors',
	]);

	register_rest_route('vet/v1' , 'addnewEmployee',[
		'methods' => 'POST',
		'callback' => 'addnewEmployee',
	]);
	register_rest_route('vet/v1' , 'doctors',[
		'methods' => 'POST',
		'callback' => 'updateDoctors',
	]);
	register_rest_route('vet/v1' , 'doctor/remove',[
		'methods' => 'POST',
		'callback' => 'delete_doctor',
	]);

	register_rest_route('vet/v1' , 'waiverdata',[
		'methods' => 'POST',
		'callback' => 'get_waiverdatatable',
	]);
	register_rest_route('vet/v1' , 'statusdata',[
		'methods' => 'POST',
		'callback' => 'get_statusfields',
	]);
	register_rest_route('vet/v1' , 'messagedata',[
		'methods' => 'POST',
		'callback' => 'get_messagesdata',
	]);

	register_rest_route('vet/v1' , 'testdata',[
		'methods' => 'POST',
		'callback' => 'get_testfields',
	]);
	register_rest_route('vet/v1' , 'waiver/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'get_waiver',
	]);

	register_rest_route('vet/v1' , 'waiver',[
		'methods' => 'POST',
		'callback' => 'insertwaiver',
	]);
	register_rest_route('vet/v1' , 'waiver/redo',[
		'methods' => 'POST',
		'callback' => 'updatewaiver',
	]);
	register_rest_route('vet/v1' , 'waiver/remove',[
		'methods' => 'POST',
		'callback' => 'deletewaiver',
	]);

	register_rest_route('vet/v1' , 'consent',[
		'methods' => 'GET',
		'callback' => 'get_consent',
	]);

	register_rest_route('vet/v1' , 'consent',[
		'methods' => 'POST',
		'callback' => 'insertconsent',
	]);

	register_rest_route('vet/v1' , 'consent/redo',[
		'methods' => 'POST',
		'callback' => 'updateconsent',
	]);
	register_rest_route('vet/v1' , 'sms/remove/(?P<id>\d+)',[
		'methods' => 'POST',
		'callback' => 'deletemessage',
	]);
	register_rest_route('vet/v1' , 'statusfields/select/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'statusFields',
	]);
	register_rest_route('vet/v1' , 'statusfields/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'statusFieldsByID',
	]);
	register_rest_route('vet/v1' , 'testfields/select/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'testFields',
	]);
	register_rest_route('vet/v1' , 'testfields/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'testFieldsByID',
	]);
	register_rest_route('vet/v1' , 'filelists/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'fileslisttotable',
	]);
	register_rest_route('vet/v1' , 'appointmentInTable/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'putOldAppInTable',
	]);
	
	register_rest_route('vet/v1' , 'employees',[
		'methods' => 'POST',
		'callback' => 'wp_list_users',
	]);

	register_rest_route('vet/v1' , 'clinicupdate',[
		'methods' => 'POST',
		'callback' => 'updateClinic',
	]);
	register_rest_route('vet/v1' , 'remove/appointment/status',[
		'methods' => 'POST',
		'callback' => 'removeStatusinAp',
	]);
	register_rest_route('vet/v1' , 'remove/appointment/test',[
		'methods' => 'POST',
		'callback' => 'removeTestinAp',
	]);
	register_rest_route('vet/v1' , 'employeetable',[
		'methods' => 'GET',
		'callback' => 'getEmployees',
	]);
	register_rest_route('vet/v1' , 'messtemp/',[
		'methods' => 'POST',
		'callback' => 'updateMessageTempate',
	]);
	register_rest_route('vet/v1' , 'smsaccountapi/',[
		'methods' => 'POST',
		'callback' => 'updateSMSAccount',
	]);

	register_rest_route('vet/v1' , 'products/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'getAllProducts',
	]);
	register_rest_route('vet/v1' , 'inventory',[
		'methods' => 'POST',
		'callback' => 'display_products',
	]);
	
	register_rest_route('vet/v1' , 'modify/product',[
		'methods' => 'POST',
		'callback' => 'modifyProduct',
	]);

	register_rest_route('vet/v1' , 'products/',[
		'methods' => 'POST',
		'callback' => 'addproduct',
	]);
	register_rest_route('vet/v1' , 'petsdetails/',[
		'methods' => 'POST',
		'callback' => 'displayPetsName',
	]);

});

add_action('wp_logout','auto_redirect_after_logout');

function auto_redirect_after_logout(){
	wp_safe_redirect( home_url() );
	exit;
}

function getmyclinicid(){
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
return $myclinic_id;
}
?>
