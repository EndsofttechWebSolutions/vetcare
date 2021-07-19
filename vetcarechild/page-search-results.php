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



	body{
		background:#FCFCFC;    
	}
	.pr-12 {
		padding-right: 12px !important;
	}

	.mb-20 {
		margin-bottom: 20px !important;
	}

	.b-1 {
		border: 1px solid #ebebeb !important;
	}

	.card {
		border: 0;
		border-radius: 0;
		margin-bottom: 30px;
		-webkit-transition: .5s;
		transition: .5s;
	}

	.card {
		position: relative;
		display: -ms-flexbox;
		display: flex;
		-ms-flex-direction: column;
		flex-direction: column;
		min-width: 0;
		word-wrap: break-word;
		background-color: #fff;
		background-clip: border-box;
		border: 1px solid rgba(0,0,0,.125);
		border-radius: .25rem;
	}

	.media {
		padding: 16px 12px;
		-webkit-transition: background-color .2s linear;
		transition: background-color .2s linear;
	}

	.media {
		display: -ms-flexbox;
		display: flex;
		-ms-flex-align: start;
		align-items: flex-start;
	}

	.card-body {
		-ms-flex: 1 1 auto;
		flex: 1 1 auto;
		padding: 1.25rem;
	}

	.media .avatar {
		flex-shrink: 0;
	}

	.no-radius {
		border-radius: 0 !important;
	}

	.avatar-xl {
		width: 64px;
		height: 64px;
		line-height: 64px;
		font-size: 1.25rem;
	}

	.avatar {
		position: relative;
		display: inline-block;
		width: 66px;
		height: 66px;
		line-height: 36px;
		text-align: center;
		border-radius: 100%;
		background-color: #f5f6f7;
		color: #8b95a5;
		text-transform: uppercase;
		margin-top: 20px;
	}

	img {
		max-width: 100%;
	}

	img {
		vertical-align: middle;
		border-style: none;
	}

	.mb-2 {
		margin-bottom: .5rem!important;
	}

	.fs-20 {
		font-size: 20px !important;
	}

	.pr-16 {
		padding-right: 16px !important;
	}

	.ls-1 {
		letter-spacing: 1px !important;
	}

	.fw-300 {
		font-weight: 300 !important;
	}

	.fs-16 {
		font-size: 16px !important;
	}

	.media-body>* {
		margin-bottom: 0;
	}

	small, time, .small {
		font-family: Roboto,sans-serif;
		font-weight: 400;
		font-size: 11px;
		color: #8b95a5;
	}

	.fs-14 {
		font-size: 14px !important;
	}

	.mb-12 {
		margin-bottom: 12px !important;
	}

	.text-fade {
		color: rgba(77,82,89,0.7) !important;
	}

	.card-footer:last-child {
		border-radius: 0 0 calc(.25rem - 1px) calc(.25rem - 1px);
	}

	.card-footer {
		background-color: #fcfdfe;
		border-top: 1px solid rgba(77,82,89,0.07);
		color: #8b95a5;
		padding: 10px 20px;
	}

	.flexbox {
		display: -webkit-box;
		display: flex;
		-webkit-box-pack: justify;
		justify-content: space-between;
	}

	.align-items-center {
		-ms-flex-align: center!important;
		align-items: center!important;
	}

	.card-footer {
		padding: .75rem 1.25rem;
		background-color: rgba(0,0,0,.03);
		border-top: 1px solid rgba(0,0,0,.125);
	}


	.card-footer {
		background-color: #fcfdfe;
		border-top: 1px solid rgba(77, 82, 89, 0.07);
		color: #8b95a5;
		padding: 10px 20px
	}

	.card-footer>*:last-child {
		margin-bottom: 0
	}

	.hover-shadow {
		-webkit-box-shadow: 0 0 35px rgba(0, 0, 0, 0.11);
		box-shadow: 0 0 35px rgba(0, 0, 0, 0.11)
	}

	.fs-10 {
		font-size: 10px !important;
	}
	h5 span {
		font-family: Roboto,sans-serif;
		font-weight: 400;
		font-size: 11px;
		color: #8b95a5;
	}

	h5 span {
		font-family: Roboto,sans-serif;
		font-weight: 400;
		font-size: 11px;
		color: #8b95a5;
	}
	h5 span {
		font-family: Roboto,sans-serif;
		font-weight: 400;
		font-size: 11px;
		color: #8b95a5;
	}
	h5 a {
		color: #8b95a5;
	}

</style>



<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid">
			<h1 class="mt-4"></h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">Owner Search Results</li>
			</ol>
			<br>
			<!--Owner-->
			
			<div class="row">
				<?php 
				global $wpdb;
				$query = $_GET['q'];
				$table = $wpdb->prefix.'vet_owners';
				$owners = $wpdb->get_results("SELECT * FROM {$table} WHERE clinic_id  = {$myclinic_id} and (first_name LIKE '%{$query}%' or last_name LIKE '%{$query}%') ");
				if(count($owners) > 0){
					foreach ($owners as $key ) {
						?>
						<div class="col-md-4">
							<div class="card hover-shadow">
								<div class="card-body text-center pt-1 pb-20">
									<a href="#">
										<?php if($key->image == ''){
											$key->image = get_site_url().'/wp-content/uploads/2020/03/vet-app-icon-removebg-preview-1.png';
										} ?>
										<img class="avatar avatar-xxl" src="<?php echo $key->image;?>">
									</a>
									<h5 class="mt-2 mb-0"><a class="hover-primary" href="#"><?php echo $key->first_name.' '.$key->last_name; ?></a></h5>
									<span><?php echo $key->email; ?></span>

								</div>
								<footer class="card-footer flexbox">
									<div>
										<i class="fa fa-map-marker pr-1"></i>
										<span><?php echo $key->address; ?></span>
									</div>
									<div>
										<i class="fa fa-money pr-1"></i>
										<span><?php echo $key->mobile_no; ?></span>
									</div>
									<div>
										<i class="fa fa-eye pr-1"></i>
										<a href="<?php echo get_home_url().'/owners-details?id='.$key->owner_id; ?>"><button class="btn btn-outline-primary"> Visit</button></a>
									</div>
								</footer>
							</div>
						</div>
					<?php }
				}else{ ?>
					<div class="col-md-12">
						<div class="alert alert-danger" role="alert">
							No Results Found!
						</div>
					</div>	
					
				<?php } ?>
			</div>
			
			<h1 class="mt-4"></h1>
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item active">Pet Search Results</li>
			</ol>
			<br>
			<!--Pet-->
			
			<div class="row">
				<?php 
				global $wpdb;
				$query = $_GET['q'];
				$tablepets = $wpdb->prefix.'vet_pets';
				$pets = $wpdb->get_results("SELECT * FROM {$tablepets} WHERE clinic_id  = {$myclinic_id} AND pet_name LIKE '%{$query}%' ");
				if(count($pets) > 0){
					foreach ($pets as $key ) {
						$owner_info = getDetails(array($key->owner_id,'owner_id','vet_owners'));
						foreach ($owner_info as $skey ) {
							$owner_name = $skey->first_name.' '.$skey->last_name;

						}
						?>
						<div class="col-md-4">
							<div class="card hover-shadow">
								<div class="card-body text-center pt-1 pb-20">
									<a href="#">
										<?php if($key->pet_image == ''){
											$key->pet_image = get_site_url().'/wp-content/uploads/2020/03/vet-app-icon-removebg-preview-1.png';
										} ?>
										<img class="avatar avatar-xxl" src="<?php echo $key->pet_image;?>">
									</a>
									<h5 class="mt-2 mb-0"><a class="hover-primary" href="#"><?php echo $key->pet_name; ?></a></h5>
									<span><?php echo $key->pet_breed;?></span>
									<!--<div class="mt-20">-->
										<!--  <span class="badge badge-default">Pet Name</span>-->
										<!--  <span class="badge badge-default">Pet Name</span>-->
										<!--  <span class="badge badge-default">Pet Name</span>-->
										<!--</div>-->
									</div>
									<footer class="card-footer flexbox">
										<div>
											<i class="fa fa-paw pr-1"></i>
											<span><?php echo $key->pet_type;?></span>
											
										</div>
										<div>
											<!-- <i class="fa fa-user pr-1"></i> -->
											<span>Owner: <?php echo $owner_name; ?></span>
										</div>
										<div>
											<i class="fa fa-eye pr-1"></i>
											<a href="<?php echo get_home_url().'/pet-details?id='.$key->pet_id; ?>"><button class="btn btn-outline-primary"> Visit</button></a>
										</div>
									</footer>
								</div>
							</div>
						<?php }
					}else{ ?>
						<div class="col-md-12">
							<div class="alert alert-danger" role="alert">
								No Results Found!
							</div>
						</div>	
					<?php } ?>
				</div>




			</div>
		</main>

		<?php include "page-footer.php"; ?>