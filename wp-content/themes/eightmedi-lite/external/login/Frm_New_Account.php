<!doctype html>
<html class="no-js" lang="th">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Password Meter | </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
     <!-- MATERIAL DESIGN ICONIC FONT -->
	<!-- <link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/fonts/material-design-iconic-font/css/material-design-iconic-font.css"> -->
 
	<!-- STYLE CSS -->
	<!-- <link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/css/style.css">  -->
	<link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/css/style.css">
    <link rel="stylesheet" href="../wp-content/css/button.bootstrap.min.css">
    <link rel="stylesheet" href="../wp-content/css/font-awesome.min.css">
    <link rel="stylesheet" href="../wp-content/css/wave/waves.min.css">
    <link rel="stylesheet" href="../wp-content/css/wave/button.css">
 	<link rel="stylesheet" href="../wp-content/css/jquery.mCustomScrollbar.min.css">
</head>
         
   <?php 
  	 	global $wpdb;
  	 	$is_existing=false;
  	 	$fullname=  $_POST["fullname"]  ;
		$lastname=$_POST["lastname"];
		$email=$_POST['email'];
		$pwd=$_POST["password"];  
  	 	if($_POST["btn_submit"]=="submit"){
	  	 	if (!mysqli_connect_errno()){ 
				$table_name="hmc_client"; 
				$sql= " SELECT count(*)
						   FROM ".$table_name.
						  " where user_login ='".$email."'";
				//echo $sql."<hr>";
				$num_rows=$wpdb->get_var($sql); 
				//echo $num_rows."<hr>";
  				 if($num_rows > 0){  
  				 	$is_existing = true; 
  				 }else{  
					$result=@$wpdb->insert($table_name , array('user_login' =>$email, 'user_pass' => $pwd,'fullname' => $fullname,'lastname' => $lastname),array('%s','%s', '%s','%s'));
					if($result){ 
						$_SESSION["username"]=$email; 
						header("Location: /hmc_proj/welcometogabi/");
					}
  				 }
			}
  	 	}
  	 	  
  	?>
  	
 <p> </p>
 
<body class="materialdesign">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Header top area start-->
    
    <div class="wrapper_login"> 
            <!-- Breadcome End-->
            <!-- Password meter Start -->
            <div class="outline_login"> 
        	<div class="inner_login"> 
                        <div class="form-example-wrap col-lg-8">
                            <div class="sparkline12-list shadow-reset mg-t-30">
                                <div class="sparkline10-hd">
                                <?php 
                                if($is_existing){
                                ?>
                                <div class="col-lg-6">
		                            <div class="alert-wrap1 shadow-reset wrap-alert-b"> 
		                                <div class="alert alert-danger alert-mg-b" role="alert">
		                                    <strong>Oh existing email!</strong> email is already <b><a href="" style="color:#ffffff">forgot password</a></b>.
		                                </div>
		                            </div>
                        		</div>
						  	<?php 
								}
						  	?>
                                    <div class="main-sparkline10-hd">
                                        <h1>Create <span class="password-mt-none">New</span> Account</h1>
                                    </div>
                                </div>
                                <div class="sparkline10-graph col-lg-12" >
                                	<form method="post">
                                    <div id="pwd-container3">
                                    	 <div class="form-group col-lg-6">
                                            <label for="text">First Name</label>
                                            <input type="text" class="form-control " id="fullname" name="fullname" placeholder="Fullname"  value="<?=$fullname?>" required/>
                                         </div> 
                                          <div class="form-group col-lg-6">
                                            <label for="text">Last Name</label>
                                            <input type="text" class="form-control " id="lastname" name="lastname" placeholder="Lastname" maxlength="200"  value="<?=$lastname ?>" required/>
                                         </div> 
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="email white col-7 col-md-4 col-lg-7 ml-3 form-control" id="email"  name="email" placeholder="Email"  value="<?=$email ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required />
                                         </div> 
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control example3" id="password" name="password" placeholder="Password"  value="<?=$pwd ?>" onchange="return form.confirm_password.pattern=this.value"; required  name="confirm_password"  onchange="form.confirm_password.pattern = this.value;"/>
                                        </div> 
                                        <div class="form-group"><br/>
                                            <div class="pwstrength_viewport_progress2"></div>
                                        </div>
                                          <div class="form-group">
                                            <label for="password">Re-Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-Password" required >
                                        </div>
                                        <button type="submit" class="btn btn-success text-uppercase sbmt-btn col-3 col-md-2 col-lg-4 ml-1" value="submit" name="btn_submit">Submit</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
     <!-- jquery
		============================================ -->
    <script src="../wp-content/js/vendor/jquery-1.11.3.min.js"></script>
    <script src="../wp-content/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="../wp-content/js/vendor/modernizr-2.8.3.min.js"></script>
     <script src="../wp-content/js/jquery.sticky.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="../wp-content/js/jquery.scrollUp.min.js"></script>
    <!-- counterup JS 
		============================================ -->
    <script src="../wp-content/js/counterup/jquery.counterup.min.js"></script>
    <script src="../wp-content/js/counterup/waypoints.min.js"></script>
     <!-- pwstrength JS
		============================================ -->
    <script src="../wp-content/js/password-meter/pwstrength-bootstrap.min.js"></script>
    <script src="../wp-content/js/password-meter/zxcvbn.js"></script>
    <script src="../wp-content/js/password-meter/password-meter-active.js"></script>
            <!-- Password meter End-->
        </div>
    </div>
    
     
</body>

</html>