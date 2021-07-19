<?php 
/** 
* Template Name: Doctor Login
*/ 
// get_header(); 

// if(!is_user_logged_in()){
//   exit;
// }
?>


<head>
	<meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
	<title><?php the_title();?></title>
	<link rel="icon" href="<?php echo get_home_url(); ?>/wp-content/uploads/2020/03/vet-app-icon-removebg-preview-1.png">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
            crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">
</head>

<div id="form_wrapper">
	<form id="wp_login_form" action="" method="post">
		<div id="form_right">
		<center><img src="<?php echo get_home_url(); ?>/wp-content/uploads/2020/03/vet-app-icon-removebg-preview-1.png" width="150" height="150" alt="computer icon"></center>
	        <!-- <h4>Doctor Login</h4> -->
	        <div class="input_container"> 
				<i class="fas fa-envelope"></i> 
				<input type="text" name="username" class="input_field" value="" placeholder="Username" required=""><br>
			</div>

			<div class="input_container"> 
				<i class="fas fa-lock"></i>
				<input type="password" name="password" class="input_field" value="" placeholder="Passsword" required="">
			</div>
			<input type="submit" id="submitbtn" name="submitlogin" value="Login" class="input_field">
		</div> 
	</form>
</div>




<style>
		 :root {
        --body_gradient_left:#7200D0;
        --body_gradient_right: #C800C1;
        --form_bg: #ffffff;
        --input_bg: #E5E5E5;
        --input_hover:#eaeaea;
        --submit_bg: #209AA5;
        --submit_hover: #209AA5;
        --icon_color:#6b6b6b;
    }
     * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
    body {
        /* make the body full height*/
        height: 100vh;
        /* set our custom font */
        font-family: 'Roboto',
        sans-serif;
        /* create a linear gradient*/
        background-color: #209AA5;
        display:flex;
    }

    #form_wrapper {
        width: 400px;
        /*height: 700px;*/
        /* this will help us center it*/
        margin:auto;
        background-color: var(--form_bg);
        border-radius: 50px;
        /* make it a grid container*/
        display: grid;
        /* with two columns of same width*/
        /*grid-template-columns: 1fr 1fr;*/
        /* with a small gap in between them*/
        /*grid-gap: 5vw;*/
        /* add some padding around */
        /*padding: 5vh 15px*/;
        margin-top: 60px!Important;
    }
    

    
       #form_right {
        display: grid;
        /* single column layout */
        grid-template-columns: 1fr;
        /* have some gap in between elements*/
        grid-gap: 20px;
        padding: 10% 5%;
    }
    .input_container {
        background-color: var(--input_bg);
        /* vertically align icon and text inside the div*/
        display: flex;
        align-items: center;
        padding-left: 20px;
    }

    .input_container:hover {
        background-color: var(--input_hover);
    }

    .input_container,
    #input_submit {
        height: 45px;
        /* make the borders more round */
        border-radius: 50px;
        width: 100%;
    }

    .input_field {
        /* customize the input tag with lighter font and some padding*/
        color: var(--icon_color);
        background-color: inherit;
        width: 90%;
        border: none;
        font-size: 1.3rem;
        font-weight: 400;
        padding-left: 30px;
    }

    .input_field:hover,
    .input_field:focus {
        /* remove the outline */
        outline: none;
    }

    #input_submit {
        /* submit button has a different color and different padding */
        background-color: var(--submit_bg);
        padding-left: 0;
        font-weight: bold;
        color: white;
        text-transform: uppercase;
    }

    #input_submit:hover {
        background-color: var(--submit_hover);
        /* simple color transition on hover */
        transition: background-color,
            1s;
        cursor: pointer;
    }
     h1,
    span {
        text-align: center;
    }

    /* shift it a bit lower */
    #create_account {
        display: block;
        position: relative;
        top: 30px;
    }

    a {
        /* remove default underline */
        text-decoration: none;
        color: var(--submit_bg);
        font-weight: bold;
    }

    a:hover {
        color: var(--submit_hover);
    }

    i {
        color: var(--icon_color);
    }
     /* make it responsive */
    @media screen and (max-width:768px) {

        /* make the layout  a single column and add some margin to the wrapper */
        #form_wrapper {
            grid-template-columns: 1fr;
            margin-left: 10px;
            margin-right: 10px;
        }
        /* on small screen we don't display the image */
        #form_left {
            display: none;
        }
    }

    input#submitbtn {
	    background-color: #209AA5!important;
	    border: none;
	    color: white;
	    padding: 15px 32px;
	    text-align: center;
	    text-decoration: none;
	    display: inline-block;
	    font-size: 16px;
	    width: 99%!Important;
	    margin-left: 5px!Important;
	    border-radius: 50px!Important;
	    height: 45px!Important;
	}


	input.input_field {
    	font-size: 15px!important;
	}

</style>