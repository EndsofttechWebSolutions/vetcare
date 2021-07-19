<?php
/*
Plugin Name: Custom API
Plugin URI:
Description: Custom API beginner 101
Author: Endsofttech Web Solutions
Author URI: : https://www.endsofttech.com
Version: 0.1
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function checkValidity($clinic_email){
	global $wpdb;
	$table=$wpdb->prefix.'vet_serial';
	$today = date("Y-m-d"); 
	$serial = $wpdb->get_results("SELECT * FROM {$table} WHERE customer_email='{$clinic_email}' AND validity > CURRENT_DATE() AND status = 1 ");
	return $serial;
}

function serialKeyAuthentication(){
	global $wpdb;
	$data = json_decode(file_get_contents("php://input"));
	$table=$wpdb->prefix.'vet_serial';
	$serialdata = $wpdb->get_results("SELECT * FROM {$table} WHERE customer_email='{$data->customer_email}' AND serial_number = '{$data->serial_number}' AND status = 1 ");
	if(count($serialdata) > 0){
		$info = ['status'=>200,'message'=>'Found', 'data'=>$serialdata];

	}else{
		$info = ['status'=>404,'message'=>'Not Found'];
	}
	return $info;
}

function updatelocalTable(){
	global $wpdb;
	$data = json_decode(file_get_contents("php://input"));
	$table=$wpdb->prefix.'vet_serial';
	$serialdata = $wpdb->get_results("SELECT * FROM {$table} WHERE customer_email='{$data->customer_email}'");
	if(count($serialdata) > 0){
		$post_data=[
			'serial_number' => $data->serial_number,
			'customer_id' => '45',
			'customer_email' => 'clinic@gmail.com',
			'validity' => $data->validity,
			'status' => $data->status
		];
		$where = array('customer_email' => 'clinic@gmail.com');
		$wpdb->update( $table, $post_data,$where,array('%s','%s','%s','%s','%s'));
	}else{
		$insert_data=[
			'serial_number' => $data->serial_number,
			'customer_id' => '45',
			'customer_email' => 'clinic@gmail.com',
			'validity' => $data->validity,
			'status' => $data->status,
			'date_created' => $data->date_created
		];
		$wpdb->insert( $table,$insert_data,array('%s','%s','%s','%s','%s','%s'));
	}
	
}


function insertAppointment(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_appointments';
	$pet_id = isset( $_POST['petID'] ) ? sanitize_text_field( $_POST['petID'] ) : '';
	$staff_id = isset( $_POST['staffid'] ) ? sanitize_text_field( $_POST['staffid'] ) : '';
	$services = isset( $_POST['servicename'] ) ? sanitize_text_field( $_POST['servicename'] ) : '';
	$complaints = isset( $_POST['complaints'] ) ? sanitize_text_field( $_POST['complaints'] ) : '';
	$schedule = isset( $_POST['schedule'] ) ? sanitize_text_field( $_POST['schedule'] ) : '';
	$from = isset( $_POST['from_time'] ) ? sanitize_text_field( $_POST['from_time'] ) : '';
	$clinic_id = isset( $_POST['clinic_id'] ) ? sanitize_text_field( $_POST['clinic_id'] ) : '';
	$daysnotif = isset( $_POST['daysnotif'] ) ? sanitize_text_field( $_POST['daysnotif'] ) : '';
	$number = isset( $_POST['number'] ) ? sanitize_text_field( $_POST['number'] ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_text_field( $_POST['message'] ) : '';

	$post_data=array(
		'appointment_id'=>NULL,
		'pet_id' => $pet_id,
		'emp_id' => $staff_id,
		'service_name' => $services,
		'complaints' => $complaints,
		'start_date' => $schedule,
		'end_date' => $schedule.' '.date("H:i:s", strtotime($from)),
		'from_time' => $from,
		'Remarks'=> 'Upcoming',
		'clinic_id' => $clinic_id
	);
	$wpdb->insert( $table, $post_data,array('%s','%s','%s','%s','%s','%s','%s','%s','%s'));
	$appointment_number = $wpdb->insert_id;

	$petinfo = getDetails(array($pet_id,'pet_id','vet_pets'));
	foreach ($petinfo as $key ) {
		$owner_id = $key->owner_id;
		$pet_name = $key->pet_name;
	}
	$owner_info = getDetails(array($owner_id,'owner_id','vet_owners'));
	foreach ($owner_info as $key ) {
		$owner_name =  $key->first_name;
		$number = $key->mobile_no;
	}

	$mobile_number = get_user_meta($clinic_id,'mobile_number',true);

	if(strpos($message,'{firstname}') !== false) {
		$message = str_replace('{firstname}', $owner_name, $message);
	}
	if(strpos($message,'{services}') !== false) {
		$message = str_replace('{services}', $services, $message);
	}
	if(strpos($message,'{daysnotif}') !== false) {
		$message = str_replace('{daysnotif}', $daysnotif, $message);
	}
	if(strpos($message,'{pet_name}') !== false) {
		$message = str_replace('{pet_name}', $pet_name, $message);
	}
	if(strpos($message,'{contact_number}') !== false) {
		$message = str_replace('{contact_number}', $mobile_number, $message);
	}
	if(strpos($number,' ') !== false) {
		$number = str_replace(' ','', $number);
	}
	if($schedule !== date("Y-m-d",strtotime("-1 day")) ){
		$sms = array(
			'sms_id'=>NULL,
			'appointment_id'=>$appointment_number,
			'contact_number'=>$number,
			'message'=>$message,
			'date_to_send'=>$daysnotif,
			'status' => 'On-queue'
		);
		$sms_id = insert_some(['vet_sms',$sms]);
	}else{
		$sms_id = 0;
	}

	$general = array(
		'general_id' => NULL,
		'appointment_id'=>$appointment_number,
	);
	$general_id = insert_some(['vet_pet_general',$general]);

	$diag = array(
		'diagnostic_id' => NULL,
		'appointment_id'=>$appointment_number,
	);
	$diag_id = insert_some(['vet_pet_diagnostics',$diag]);

	$update_diag=array(
		'pet_general_id' => $general_id,
		'pet_status_id' => $status_id,
		'pet_diagnostics_id' => $diag_id,
		'pet_test_id' => $test_id,
		'pet_sms_id' => $sms_id,
	);
	update_some(['vet_appointments',$update_diag,'appointment_id',$appointment_number]);

	if($appointment_number != null){
		return ['status'=>201, 'message'=>"Data Created",'id'=>$appointment_number];
	}else{
		return ['status'=>404, 'message'=>"Not Found"];
	}

}
function insertTestFields($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_test_fields';
	$action = isset( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) : '';

	if($action == "Insert Default"){
		$test_fields = [
			'Blood Exam' => '',
			'Skin Scrapping' => '',
			'Distemper Test'=>'',
			'Stool Exam'=>'',
			'Ear Swabbing' => '',
			'Ultrasound'=>'',
			'Ehrlichia Test'=>'',
			'Urine Exam' =>'',
			'Heartworm Test' =>'',
			'X-Rays' => '',
			'Parvo Test'=>'',
			'Other Test'=>''
		];
		// var_dump($status_fields);
		foreach ($test_fields as $key => $value) {
			$test_fields_data = [
				'test_field_id' => NULL,
				'clinic_id' => $data['id'],
				'meta_key' => $key
			];
			// var_dump($status_fields_data);
			$wpdb->insert( $table, $test_fields_data,array('%s','%s','%s'));
		}
	}
	return array('status'=>200,'message'=>'OK');
}
function insertSingleFieldTest(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_test_fields';
	$clinic_id = isset( $_POST['clinic_id'] ) ? sanitize_text_field( $_POST['clinic_id'] ) : '';
	$fldname = isset( $_POST['fldname'] ) ? sanitize_text_field( $_POST['fldname'] ) : '';
	$price = isset( $_POST['price'] ) ? sanitize_text_field( $_POST['price'] ) : '';
	$select = $wpdb->get_results("SELECT * FROM {$table} WHERE meta_key='{$fldname}' ");
	if(count($select) === 0){
		$test_fields_data = [
			'test_field_id' => NULL,
			'clinic_id' => $clinic_id,
			'meta_key' => $fldname,
			'meta_value' => $price
		];
		$wpdb->insert( $table, $test_fields_data,array('%s','%s','%s'));
		return array('status'=>200,'message'=>'OK');
	}else{
		return array('status'=>404,'message'=>'Data Exist');
	}
	
}
function deleteTestFields(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_test_fields';
	if($_POST['action']=="delete"){
		$test_field_id = isset( $_POST['test_field_id'] ) ? sanitize_text_field( $_POST['test_field_id'] ) : '';
		$ID = array('test_field_id' => $test_field_id);
		$wpdb->delete( $table, $ID);
	}
	return array('status'=>200,'message'=>'OK');
}
function insertStatusFields($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_status_fields';
	$action = isset( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) : '';

	if($action == "Insert Default"){
		$status_fields = [
			'Temperature' => '',
			'Respiratory' => '',
			'Pulse'=>'',
			'General Condition'=>'',
			'General Attribute' => '',
			'Hydration'=>'',
			'Mucous Membrane'=>'',
			'Head / Neck' =>'',
			'Eyes' =>'',
			'Ears' => '',
			'Gastrointestinal'=>'',
			'Urogenitals'=>'',
			'Circulatory'=>'',
			'Muskoloskeleton'=>'',
			'Lymph Nodes'=>'',
			'Venous Return'=>'',
			'Integumentary Skin' =>'',
			'Description'=>''
		];
		// var_dump($status_fields);
		foreach ($status_fields as $key => $value) {
			$status_fields_data = [
				'status_field_id' => NULL,
				'clinic_id' => $data['id'],
				'meta_key' => $key,
				'meta_value' => $value
			];
			// var_dump($status_fields_data);
			$wpdb->insert( $table, $status_fields_data,array('%s','%s','%s','%s'));
		}
	}
	return array('status'=>200,'message'=>'OK');
}
function insertSingleField(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_status_fields';
	$clinic_id = isset( $_POST['clinic_id'] ) ? sanitize_text_field( $_POST['clinic_id'] ) : '';
	$fldname = isset( $_POST['fldname'] ) ? sanitize_text_field( $_POST['fldname'] ) : '';
	$price = isset( $_POST['price'] ) ? sanitize_text_field( $_POST['price'] ) : '';
	$select = $wpdb->get_results("SELECT * FROM {$table} WHERE meta_key='{$fldname}' where clinic_id = {$clinic_id}");
	if(count($select) === 0){
		$status_fields_data = [
			'status_field_id' => NULL,
			'clinic_id' => $clinic_id,
			'meta_key' => $fldname,
			'meta_value' => $price
		];
		$wpdb->insert( $table, $status_fields_data,array('%s','%s','%s','%s'));
		return array('status'=>200,'message'=>'OK');
	}else{
		return array('status'=>404,'message'=>'Data Exist');
	}
	
}
function deleteStatusFields(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_status_fields';
	if($_POST['action']=="delete"){
		$status_field_id = isset( $_POST['status_field_id'] ) ? sanitize_text_field( $_POST['status_field_id'] ) : '';
		$ID = array('status_field_id' => $status_field_id);
		$wpdb->delete( $table, $ID);
	}
	return array('status'=>200,'message'=>'OK');

}
function checkStatus($status){
	if($status == "Upcoming"){
		return "#ffc107";
	}else if($status == "Completed"){
		return "#28A745";
	}else{
		return "red";
	}
}
function get_appointments($data){
	global $wpdb;
	$table=$wpdb->prefix.'vet_appointments';
	$past_date = $wpdb->get_results("SELECT * FROM {$table} WHERE clinic_id = {$data['id']} AND ( end_date < CURRENT_DATE()  OR ( end_date = CURRENT_DATE()
		AND from_time <= CURRENT_TIME()) )");
	foreach ($past_date as $key ) {
		if($key->Remarks != "Completed"){
			$wpdb->query("UPDATE {$table} set Remarks='Absent' WHERE appointment_id= {$key->appointment_id} ");
		}
	}

	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE clinic_id = {$data['id']} ");
	$arrayData = array();
	foreach ($results as $key ) {
		if($key->service_name == "Board"){
			$key->service_name = "Board & Lodging";
		}

		$petinfo = getDetails(array($key->pet_id,'pet_id','vet_pets'));
		foreach ($petinfo as $skey) {
			$pet_name = $skey->pet_name;
			$owner_id = $skey->owner_id;
		}
		$owners_info= getDetails(array($owner_id,'owner_id','vet_owners'));
		foreach ($owners_info as $vkey) {
			$owner_name = $vkey->first_name.' '.$vkey->last_name;
		}
		$arrayDatas = [
			'title' => $key->service_name,
			'start' => $key->start_date.'T'.date("H:i", strtotime($key->from_time)),
			'end' => $key->start_date.'T'.date("H:i", strtotime($key->from_time)),
			'id' => $key->appointment_id,
			'backgroundColor' => checkStatus($key->Remarks),
			'description' => $key->complaints,
			'ownername' => $owner_name,
			'petname' => $pet_name,
			'textColor' =>'white',
			'editable' => true
		];
		array_push($arrayData, $arrayDatas);
	}
	return $arrayData;
}
function get_appointments_all(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_appointments';
	$results = $wpdb->get_results("SELECT * FROM {$table}");
	$arrayData = array();
	foreach ($results as $key ) {
		if($key->service_name == "Board"){
			$key->service_name = "Board & Lodging";
		}
		$arrayDatas = [
			'appointment_id' => $key->appointment_id,
			'pet_id' => $key->pet_id,
			'staff_id' => $key->emp_id,
			'service_name' => $key->service_name,
			'complaints' => $key->complaints,
			'start_date' =>$key->start_date,
			'end_date' => $key->start_date,
			'from_time' => $key->from_time,
			'Remarks' => $key->Remarks,
			'pet_general_id' => $key->pet_general_id,
			'pet_status_id' => $key->pet_status_id,
			'pet_diagnostics_id' => $key->pet_diagnostics_id,
			'pet_test_id' => $key->pet_test_id,
		];
		array_push($arrayData, $arrayDatas);
	}
	return $arrayData;
}


function get_history(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_history';
	$results = $wpdb->get_results("SELECT * FROM {$table}");
	$arrayData = array();
	foreach ($results as $key ) {
		$arrayDatas = [
			"Action" => $key->action,
			"Date" => $key->date_created
		];
		array_push($arrayData, $arrayDatas);
	}
	echo json_encode($arrayData);
}

function update_appointments(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_appointments';
	$data = json_decode(file_get_contents("php://input"));
	$update_data=array(
		'pet_id' => $data->up_petID,
		'emp_id' => $data->up_staffid,
		'service_name' => $data->up_servicename,
		'complaints' => $data->up_complaints,
		'start_date' => $data->up_schedule,
		'end_date' => $data->up_schedule,
		'from_time' => $data->up_from_time,
		'Remarks' => $data->up_appointmentRem,
		'pet_general_id' => $data->up_pet_general_id,
		'pet_status_id' => $data->up_pet_status_id,
		'pet_diagnostics_id' => $data->up_pet_diagnostics_id,
		'pet_test_id' => $data->up_pet_test_id,
		'notes' => $data->up_notes
	);
	// var_dump($update_data);
	$where = array('appointment_id' => $data->up_appointmentID);
	$wpdb->update( $table, $update_data,$where,array('%d','%d','%s','%s','%s','%s','%s','%s','%d','%d','%d','%d','%s'));
	
	// historyInsert(["action"=>"Update Appointment with appointment # ". $appointment_id]);

	$appointmentinfo = getDetails(array($data->up_appointmentID,'appointment_id','vet_appointments'));
	foreach($appointmentinfo as $key){
		$up_sms_id = $key->pet_sms_id;
		$clinic_id = $key->clinic_id;
	}
	$petinfo = getDetails(array($data->up_petID,'pet_id','vet_pets'));
	foreach ($petinfo as $key ) {
		$owner_id = $key->owner_id;
		$pet_name = $key->pet_name;
	}
	$owner_info = getDetails(array($owner_id,'owner_id','vet_owners'));
	foreach ($owner_info as $key ) {
		$owner_name =  $key->first_name;
		$number = $key->mobile_no;
	}
	$clinicinfo = getDetails(array("1",'clinic_id','vet_clinic'));
	foreach ($clinicinfo as $key ) {
		$mobile_number = $key->mobile_number;
		$landline = $key->landline;
	}
	$content_message = get_post_meta( 130, 'content_message', true );
	if(strpos($content_message,'{firstname}') !== false) {
		$content_message = str_replace('{firstname}', $owner_name, $content_message);
	}
	if(strpos($content_message,'{services}') !== false) {
		$content_message = str_replace('{services}', $data->up_servicename, $content_message);
	}
	if(strpos($content_message,'{daysnotif}') !== false) {
		$content_message = str_replace('{daysnotif}', $data->up_schedule, $content_message);
	}
	if(strpos($content_message,'{pet_name}') !== false) {
		$content_message = str_replace('{pet_name}', $pet_name, $content_message);
	}
	if(strpos($content_message,'{contact_number}') !== false) {
		$content_message = str_replace('{contact_number}', $mobile_number, $content_message);
	}
	if(strpos($number,' ') !== false) {
		$number = str_replace(' ','', $number);
	}
	$smsinfo = getDetails(array($up_sms_id,'sms_id','vet_sms'));
	
	foreach($smsinfo as $key){
		$status = $key->status;
	}

	$sms = array(
		'appointment_id'=>$data->up_appointmentID,
		'contact_number'=>$number,
		'message'=>$content_message,
		'date_to_send'=>$data->up_schedule,
		'status' => $status
	);
	if($data->up_schedule !== date("Y-m-d",strtotime("-1 day")) ){
		update_some(['vet_sms',$sms,'sms_id',$up_sms_id]);
		updateBulkSMS($up_sms_id,130);
		
		
	}
	return array('status'=>200,'message'=>'OK');
}
function delete_appointments(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_appointments';
	$table_sms=$wpdb->prefix.'vet_sms';
	if($_POST['action']=="delete"){
		$appointment_id = isset( $_POST['up_appointmentID'] ) ? sanitize_text_field( $_POST['up_appointmentID'] ) : '';
		$ID = array('appointment_id' => $appointment_id);
		
		$appointmentinfo = getDetails(array($appointment_id,'appointment_id','vet_appointments'));
		foreach($appointmentinfo as $key){
			$xsms_id = $key->pet_sms_id;
			$sms_id = array('sms_id' => $key->pet_sms_id);
		}
		deleteBulkSMS($xsms_id);
		$wpdb->delete( $table_sms, $sms_id);
		$wpdb->delete( $table, $ID);
		
		//historyInsert(["action"=>"Deleted Appointment with appointment # ". $appointment_id]);
	}

	return array('status'=>200,'message'=>'OK');

}
function delete_eachAppointment(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_appointments';
	$table_sms=$wpdb->prefix.'vet_sms';
	$appointment_id = isset( $_POST['appointment_id'] ) ? sanitize_text_field( $_POST['appointment_id'] ) : '';
	$ID = array('appointment_id' => isset( $_POST['appointment_id'] ) ? sanitize_text_field( $_POST['appointment_id'] ) : '');
	

	$appointmentinfo = getDetails(array($appointment_id,'appointment_id','vet_appointments'));
	foreach($appointmentinfo as $key){
		$xsms_id = $key->pet_sms_id;
		$sms_id = array('sms_id' => $key->pet_sms_id);
	}
	deleteBulkSMS($xsms_id);
	$wpdb->delete( $table_sms, $sms_id);
	$wpdb->delete( $table, $ID);
	
	//historyInsert(["action"=>"Deleted Appointment with appointment # ". $appointment_id]);
	return array('status'=>200,'message'=>'OK'); 
}

function historyInsert($history = array() ){
	global $wpdb;
	$table=$wpdb->prefix.'vet_history';
	$wpdb->insert( $table, $history,array('%s'));
}

function insert_some($arr){
	global $wpdb;
	$table=$wpdb->prefix.$arr[0];
	$wpdb->insert( $table, $arr[1]);
	$unique_id = $wpdb->insert_id;
	return $unique_id;
}

function update_some($arr){
	global $wpdb;
	$table=$wpdb->prefix.$arr[0];
	$where = array($arr[2] => $arr[3]);
	$wpdb->update( $table, $arr[1],$where,array('%s'));
}


function getDetails($atts){
	global $wpdb;
	$table=$wpdb->prefix.$atts[2];
	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE {$atts[1]} = {$atts[0]} ");
	return $results;
}

function insert_general(){
	$data = json_decode(file_get_contents("php://input"));

	$general_id = $data->general_id;
	$appointment_id = $data->appointment_id;
	
	$general = [
		'medication_owner' => $data->medication_given,
		'vaccine_given' => $data->vaccine_given,
		'allergies' => $data->allergies,
		'weight' => $data->weight,
		'temperature' => $data->temperature,
	];
	// $appointments = array('Remarks'=>$remarks);
	// update_some(['vet_appointments',$appointments,'appointment_id',$appointment_id]);
	update_some(['vet_pet_general',$general,'general_id',$general_id]);


}

function insert_status(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_pet_status';

	$data = json_decode(file_get_contents("php://input"));
	// var_dump($data);
	$mycount=0;
	$alldata="";
	$appointment_id = 0;
	$remarks = "";
	foreach($data as $key=>$value){
		if($key == "appointment_id"){
			$appointment_id = $value;
		}if($skey == "remarks"){
			$remarks = $value;
		}
	}
	foreach($data as $key=>$value){
		if($key == 'remarks' || $key == 'appointment_id'){
		}else{
			if($mycount==0){
				$alldata .= '(NULL,"'.$appointment_id.'","'.$key.'", "'.$value.'")';
				$mycount +=1;
			}else{
				$alldata .= ', (NULL,"'.$appointment_id.'","'.$key.'", "'.$value.'")';
			}
		}
	}
	// var_dump($alldata);
	// $appointments = array('Remarks'=>$remarks);
	// update_some(['vet_appointments',$appointments,'appointment_id',$appointment_id]);
	$wpdb->query("INSERT INTO $table VALUES $alldata");

}
function checkFieldExist($atts){
	global $wpdb;
	$table=$wpdb->prefix.$atts[0];
	$container = [];
	$results = $wpdb->get_results("SELECT * FROM {$table} WHERE {$atts[1]} = {$atts[2]} ");
	return $results;

}

function insert_test(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_pet_test';

	$data = json_decode(file_get_contents("php://input"));
	// var_dump($data);
	$mycount=0;
	$alldata="";
	$appointment_id = 0;
	$remarks = "";
	foreach($data as $key=>$value){
		if($key == "appointment_id"){
			$appointment_id = $value;
		}if($skey == "remarks"){
			$remarks = $value;
		}
	}
	foreach($data as $key=>$value){
		if($key == 'remarks' || $key == 'appointment_id'){
		}else{
			if($mycount==0){
				$alldata .= '(NULL,"'.$appointment_id.'","'.$key.'", "'.$value.'")';
				$mycount +=1;
			}else{
				$alldata .= ', (NULL,"'.$appointment_id.'","'.$key.'", "'.$value.'")';
			}
		}
	}
	// var_dump($alldata);
	// $appointments = array('Remarks'=>$remarks);
	// update_some(['vet_appointments',$appointments,'appointment_id',$appointment_id]);
	$wpdb->query("INSERT INTO $table VALUES $alldata");

}

function insert_diagnostics(){
	$data = json_decode(file_get_contents("php://input"));
	$diag_id = $data->diag_id;
	$appointment_id = $data->appointment_id;
	// $remarks = isset( $_POST['remarks'] ) ? sanitize_text_field( $_POST['remarks'] ) : '';
	$diag = [
		'procedure_done' => $data->procedure,
		'tentative' => $data->tentative,
		'medication' => $data->medication,
		'prescription' => $data->prescription,
	];
	// $appointments = array('Remarks'=>$remarks);
	// update_some(['vet_appointments',$appointments,'appointment_id',$appointment_id]);
	update_some(['vet_pet_diagnostics',$diag,'diagnostic_id',$diag_id]);

}

function insert_nextappointment(){
	$pet_id = isset( $_POST['next_pet'] ) ? sanitize_text_field( $_POST['next_pet'] ) : '';
	$staff_id = isset( $_POST['next_staff'] ) ? sanitize_text_field( $_POST['next_staff'] ) : '';
	$service = isset( $_POST['next_servicename'] ) ? sanitize_text_field( $_POST['next_servicename'] ) : '';
	$complaints = isset( $_POST['next_complaints'] ) ? sanitize_text_field( $_POST['next_complaints'] ) : '';
	$schedule = isset( $_POST['next_schedule'] ) ? sanitize_text_field( $_POST['next_schedule'] ) : '';
	$from = isset( $_POST['next_time'] ) ? sanitize_text_field( $_POST['next_time'] ) : '';
	$post_data=array(
		'appointment_id'=>NULL,
		'pet_id' => $pet_id,
		'emp_id' => $staff_id,
		'service_name' => $service,
		'complaints' => $complaints,
		'start_date' => $schedule,
		'end_date' => $schedule.' '.date("H:i:s", strtotime($from)),
		'from_time' => $from,
		'Remarks'=> 'Upcoming'
	);
	insert_appointments([$post_data]);
}
function base64ToImage($base64_string, $output_file) {
	$file = fopen($output_file, "wb");

	$data = explode(',', $base64_string);

	fwrite($file, base64_decode($data[1]));
	fclose($file);

	return $output_file;
}
function insert_attachment(){
	$appointment_id = isset( $_POST['appointment_id'] ) ? sanitize_text_field( $_POST['appointment_id'] ) : '';
	$signature_data = isset( $_POST['image_data'] ) ? sanitize_text_field( $_POST['image_data'] ) : '';
	$pet_id = isset( $_POST['pet_id'] ) ? sanitize_text_field( $_POST['pet_id'] ) : '';
	$owner_id = isset( $_POST['owner_id'] ) ? sanitize_text_field( $_POST['owner_id'] ) : '';
	$upload_dir = wp_upload_dir();
	$name = "IMG_".time().'.jpg';
	// $file = $upload_dir['baseurl'] .'/vetcare'. $upload_dir['subdir'].'/' . $name;
	$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

	$decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signature_data));

// @new
	$image_upload = file_put_contents( $upload_path . $name, $decoded );
	add_filter('upload_dir', 'my_upload_dir');
//HANDLE UPLOADED FILE
	if( !function_exists( 'wp_handle_sideload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}

// Without that I'm getting a debug error!?
	if( !function_exists( 'wp_get_current_user' ) ) {
		require_once( ABSPATH . 'wp-includes/pluggable.php' );
	}

// @new
	$file             = array();
	$file['error']    = '';
	$file['tmp_name'] = $upload_path . $name;
	$file['name']     = $name;
	$file['type']     = 'image/jpg';
	$file['size']     = filesize( $upload_path . $name );

// upload file to server
// @new use $file instead of $image_upload
	$file_return = wp_handle_sideload( $file, array( 'test_form' => false ) );

	$filename = $file_return['file'];
	$attachment = array(
		'post_mime_type' => $file_return['type'],
		'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
		'post_content' => '',
		'post_status' => 'inherit',
		'guid' => $wp_upload_dir['url'] . '/' . basename($filename)
	);
	$attach_id = wp_insert_attachment( $attachment, $filename );
	remove_filter('upload_dir', 'my_upload_dir');
	$post_data=array(
		'attach_id'=>NULL,
		'appointment_id' => $appointment_id,
		'pet_id' => $pet_id,
		'owner_id' => $owner_id,
		'uploaded_file' => $file['name'],
		'file_size' => $file['size'],
		'file_type' => $file['type']
	);

	$attach_id = insert_some(['vet_pet_attachments',$post_data]);
	$appointments = array('pet_attach_id'=>$attach_id);
	update_some(['vet_appointments',$appointments,'appointment_id',$appointment_id]);

	$info = ['status'=>200,'d'=>'Success'];
	return $info;
}

function delete_attachment(){
	global $wpdb;
	$table=$wpdb->prefix.'vet_pet_attachments';
	$attach_id = isset( $_POST['attach_id'] ) ? sanitize_text_field( $_POST['attach_id'] ) : '';
	$ID = array('attach_id' => $attach_id);
	$wpdb->delete( $table, $ID);
	$info = ['status'=>200, 'message'=>'deleted'];
	return $info;
}

function insert_file(){
	$appointment_id = isset( $_POST['appointment_id'] ) ? sanitize_text_field( $_POST['appointment_id'] ) : '';
	$pet_id = isset( $_POST['pet_id'] ) ? sanitize_text_field( $_POST['pet_id'] ) : '';
	$owner_id = isset( $_POST['owner_id'] ) ? sanitize_text_field( $_POST['owner_id'] ) : '';
	$file_size = $_FILES['file']['size'];
	$file_type = $_FILES['file']['type'];
	$file_name = $_FILES['file']['name'];
	$_FILES['file']['name'] = str_replace(" ", "-", $file_name);
	$file_name = str_replace(" ", "-", $file_name);
	
	$upload_dir   = wp_upload_dir();
	if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$uploadedfile = $_FILES['file'];
	$upload_overrides = array( 'test_form' => false );
	add_filter('upload_dir', 'my_upload_dir');
	$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
	remove_filter('upload_dir', 'my_upload_dir');

	if ( $movefile ) {
		$post_data=array(
			'attach_id'=>NULL,
			'appointment_id' => $appointment_id,
			'pet_id' => $pet_id,
			'owner_id' => $owner_id,
			'uploaded_file' => $file_name,
			'file_size' => $file_size,
			'file_type' => $file_type
		);
		$attach_id = insert_some(['vet_pet_attachments',$post_data]);
		$appointments = array('pet_attach_id'=>$attach_id);
		update_some(['vet_appointments',$appointments,'appointment_id',$appointment_id]);

		$info = ['status'=>200,'d'=>'Success'];
		return $info;
		// return $upload_dir['basedir'] .'/vetcare'. $upload_dir['subdir'].'/'.  $post_data['uploaded_file'];
	} else {
		echo "Possible file upload attack!\n";
	}
}
function my_upload_dir($upload) {
	$clinic_id = getmyclinicid();
	$upload['subdir'] = '/';
	$upload['path']   = $upload['basedir'] . $upload['subdir'];
	$upload['url']    = $upload['baseurl'] . $upload['subdir'];
	return $upload;
}


function insert_capture(){
	global $pdf;
	$appointment_id = isset( $_POST['appointment_id'] ) ? sanitize_text_field( $_POST['appointment_id'] ) : '';
	$image_data = isset( $_POST['image_data'] ) ? sanitize_text_field( $_POST['image_data'] ) : '';
	$pet_id = isset( $_POST['pet_id'] ) ? sanitize_text_field( $_POST['pet_id'] ) : '';
	$owner_id = isset( $_POST['owner_id'] ) ? sanitize_text_field( $_POST['owner_id'] ) : '';

	$TEMPIMGLOC = 'capture.png';
	$dataURI= "data:image/png;base64,{$image_data}";
	$dataPieces = explode(',',$dataURI);
	$encodedImg = $dataPieces[1];
	$decodedImg = base64_decode($encodedImg);
	$file_size = getimagesize($dataURI);

	$file_name = "IMG-".time().".jpeg";

	$post_data=array(
		'attach_id'=>NULL,
		'appointment_id' => $appointment_id,
		'pet_id' => $pet_id,
		'owner_id' => $owner_id,
		'uploaded_file' => $file_name,
		'file_size' => $file_size,
		'file_type' => "image/jpeg"
	);

	$attach_id = insert_some(['vet_pet_attachments',$post_data]);
	$appointments = array('pet_attach_id'=>$attach_id);
	update_some(['vet_appointments',$appointments,'appointment_id',$appointment_id]);

	$info = ['status'=>200,'d'=>'Success'];
	return $info;
}
	function ismscURL($username_sms,$password_sms){
	   $url = "https://www.isms.com.my/isms_balance.php?un=".urlencode($username_sms)."&pwd=".urlencode($password_sms);
       $http = curl_init($url);
       curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
       $http_result = curl_exec($http);
       $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
       curl_close($http);
       return $http_result;
	}
function checkSMSBal(){
    $data = json_decode(file_get_contents("php://input"));
	$username_sms = $data->name;
	$password_sms = $data->password;
	$result = ismscURL($username_sms,$password_sms);
	$info = ['status'=>200,'balance'=>$result];
	return $info;
}

function sendSMS(){
	$destination = urlencode($_POST["dest"]);
	$message = $_POST["msg"];
	$message = html_entity_decode($message, ENT_QUOTES, 'utf-8');
	$message = urlencode($message);
	$username = urlencode($username_sms);
	$password = urlencode($password_sms);
	$sender_id = urlencode($sender_id);
	$type = (int)$_POST['type'];
	$fp = "https://www.isms.com.my/isms_send.php";
	$fp .= "?un=$username&pwd=$password&dstno=$destination&msg=$message&type=$type&sendid=$sender_id";
      //echo $fp;
	$result = ismscURL($fp);
	echo $result;
}

function createBulkSMS(){
	global $wpdb;
    	//$myclinic_id = isset( $_POST['clinic_id'] ) ? sanitize_text_field( $_POST['clinic_id'] ) : '';
	$myclinic_id = '130';
	$username_sms = get_post_meta( $myclinic_id, 'username_sms', true );
	$password_sms = get_post_meta( $myclinic_id, 'password_sms', true );
	$sender_id = get_post_meta( $myclinic_id, 'sender_id', true );
	//$time_sms = get_post_meta( $myclinic_id, 'time_sms', true );
	$time = explode(':',$time_sms);
	$det = "Task1";
	$tr = "onetime";
	$type = 1;
	$hour = 8;
	$min = 00;
	$week = 1;
	$month = 1;
	$day = 1;
	$arrayData = [];
	$table_sms=$wpdb->prefix.'vet_sms';
	$results_sms = $wpdb->get_results("SELECT * FROM {$table_sms} where status = 'On-queue' ");
	foreach ($results_sms as $key ) {

		$arrayDatas = [
			"sms_id" => $key->sms_id,
			"number" => $key->contact_number,
			"message"=> $key->message,
			"date_to_send" => $key->date_to_send
		];
		array_push($arrayData, $arrayDatas);
	}

	foreach ($arrayData as $key) {

		$message = $key['message'];
		$contact = $key['number'];
		$date_to_send = $key['date_to_send'];

		$sendlink = "http://www.isms.com.my/isms_scheduler.php?un=".urlencode($username_sms)."&pwd=".urlencode($password_sms)."&dstno=".$contact."&msg=".urlencode($message)."&det=".urlencode($det)."&tr=".$tr."&type=".$type."&sendid=".urlencode($sender_id)."&date=".$date_to_send."&hour=".$hour."&min=".$min."&week=".$week."&month=".$month."&day=".$day;
		$handle = fopen($sendlink, "r");
		if ($handle) {
			while (($buffer = fgets($handle, 4096)) !== false) {
				$sms = array('status'=>'Sent','scheduler_id'=>$buffer);
				update_some(['vet_sms',$sms,'sms_id',$key['sms_id']]);
			}
			if (!feof($handle)) {
				echo "Error: unexpected fgets() fail\n";
			}
			fclose($handle);
		}
	}
	return array('status'=>200, 'message'=>'OK');
    
}

function updateBulkSMS($sms_id,$clinic_id){
	global $wpdb;
	$username_sms = get_post_meta( '130', 'username_sms', true );
	$password_sms = get_post_meta( '130', 'password_sms', true );
	$sender_id = get_post_meta( '130', 'sender_id', true );
	$time_sms = get_post_meta( '130', 'time_sms', true );
	$time = explode(':',$time_sms);
	$smsinfo = getDetails(array($sms_id,'sms_id','vet_sms'));
	
	foreach($smsinfo as $key){
		$sms_id = $key->sms_id;
		$number = $key->contact_number;
		$message = $key->message;
		$schedule = $key->date_to_send;
		$status = $key->status;
		$scheduler_id = $key->scheduler_id;
	}
	if($scheduler_id !== ""){
		$dstno = $number;
		$msg = $message;
		$det = "";
		$tr = "onetime";
		$type = 1;
		$date = $schedule;
		$hour = 8;
		$min = 00;
		$week = 1;
		$month = 1;
		$day = 1;
		$scid = $scheduler_id ; //scid get from the task scheduler you created
		$action = "update";
$sendlink = "http://www.isms.com.my/isms_scheduler.php?un=".urlencode($username_sms)."&pwd=".urlencode($password_sms)."&dstno="
.$dstno."&msg=".urlencode($msg)."&det=".urlencode($det)."&tr=".$tr."&type=".$type."&sendid="
.urlencode($sender_id)."&date=".$date."&hour=".$hour."&min=".$min."&week=".$week."&month="
.$month."&day=".$day."&scid=".$scid."&action=".$action;

fopen($sendlink, "r");
}


}

function deleteBulkSMS($sms_id){
	global $wpdb;
	$username_sms = get_post_meta( '130', 'username_sms', true );
	$password_sms = get_post_meta( '130', 'password_sms', true );
	$sender_id = get_post_meta( '130', 'sender_id', true );
	$smsinfo = getDetails(array($sms_id,'sms_id','vet_sms'));

	foreach($smsinfo as $key){
		$sms_id = $key->sms_id;
		$number = $key->contact_number;
		$message = $key->message;
		$schedule = $key->date_to_send;
		$status = $key->status;
		$scheduler_id = $key->scheduler_id;
	}
	if($scheduler_id !== ""){
		$scid = $scheduler_id;
		$action = "delete";
		$sendlink = "http://www.isms.com.my/isms_scheduler.php?un=".urlencode($username_sms)."&pwd=".urlencode($password_sms)."&scid=".$scid."&action=".$action;
		fopen($sendlink, "r");
	}
	
}

add_action('rest_api_init',function(){
	register_rest_route('vet/v1' , 'appointment/',[
		'methods' => 'POST',
		'callback' => 'insertAppointment',
	]);

	register_rest_route('vet/v1' , 'appointment/(?P<id>\d+)',[
		'methods' => 'GET',
		'callback' => 'get_appointments',
	]);

	register_rest_route('vet/v1' , 'appointment_all/',[
		'methods' => 'GET',
		'callback' => 'get_appointments_all',
	]);
	register_rest_route('vet/v1' , 'appointment/edit',[
		'methods' => 'POST',
		'callback' => 'update_appointments',
	]);

	register_rest_route('vet/v1' , 'appointment/remove',[
		'methods' => 'POST',
		'callback' => 'delete_appointments',
	]);

	register_rest_route('vet/v1' , 'appointment/general',[
		'methods' => 'POST',
		'callback' => 'insert_general',
	]);

	register_rest_route('vet/v1' , 'appointment/status',[
		'methods' => 'POST',
		'callback' => 'insert_status',
	]);

	register_rest_route('vet/v1' , 'appointment/test',[
		'methods' => 'POST',
		'callback' => 'insert_test',
	]);

	register_rest_route('vet/v1' , 'appointment/diagnostics',[
		'methods' => 'POST',
		'callback' => 'insert_diagnostics',
	]);

	register_rest_route('vet/v1' , 'appointment/next',[
		'methods' => 'POST',
		'callback' => 'insert_nextappointment',
	]);

	register_rest_route('vet/v1' , 'appointment/removeeach',[
		'methods' => 'POST',
		'callback' => 'delete_eachAppointment',
	]);

	register_rest_route('vet/v1' , 'image/attach',[
		'methods' => 'POST',
		'callback' => 'insert_attachment',
	]);
	register_rest_route('vet/v1' , 'appointment/attach',[
		'methods' => 'POST',
		'callback' => 'insert_file',
	]);
	register_rest_route('vet/v1' , 'appointment/attach/removeeach',[
		'methods' => 'POST',
		'callback' => 'delete_attachment',
	]);



	register_rest_route('vet/v1' , 'sms/balance',[
		'methods' => 'POST',
		'callback' => 'checkSMSBal',
	]);
	register_rest_route('vet/v1' , 'sms/scheduler',[
		'methods' => 'POST',
		'callback' => 'createBulkSMS',
	]);

	register_rest_route('vet/v1' , 'status/fields/(?P<id>\d+)',[
		'methods' => 'POST',
		'callback' => 'insertStatusFields',
	]);
	register_rest_route('vet/v1' , 'status/fields/remove',[
		'methods' => 'POST',
		'callback' => 'deleteStatusFields',
	]);

	register_rest_route('vet/v1' , 'status/single',[
		'methods' => 'POST',
		'callback' => 'insertSingleField',
	]);

	register_rest_route('vet/v1' , 'test/fields/(?P<id>\d+)',[
		'methods' => 'POST',
		'callback' => 'insertTestFields',
	]);
	register_rest_route('vet/v1' , 'test/fields/remove',[
		'methods' => 'POST',
		'callback' => 'deleteTestFields',
	]);
	register_rest_route('vet/v1' , 'test/single',[
		'methods' => 'POST',
		'callback' => 'insertSingleFieldTest',
	]);
	register_rest_route('vet/v1' , 'serialverify',[
		'methods' => 'POST',
		'callback' => 'serialKeyAuthentication',
	]);

	register_rest_route('vet/v1' , 'updateseriallocal',[
		'methods' => 'POST',
		'callback' => 'updatelocalTable',
	]);



});




?>
