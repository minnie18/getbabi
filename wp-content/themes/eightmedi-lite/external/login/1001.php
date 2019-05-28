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

</head>
         
   <?php 
  	 	global $wpdb;
  	 	if($_POST["btn_submit"]=="submit"){
	  	 	if (!mysqli_connect_errno()){ 
				$table_name="hmc_client";
				$fullname=  $_POST["fullname"]  ;
				$lastname=$_POST["lastname"];
				$email=$_POST['email'];
				$pwd=$_POST["password"];
				//echo $fullname."=".$email."=".$pwd."<hr>";
				$result=@$wpdb->insert($table_name , array('user_login' =>$email, 'user_pass' => $pwd,'fullname' => $fullname,'lastname' => $lastname, 'user_email' => email),array('%s','%s', '%s','%s'));
			 
				if($result){
					
				}
			}
  	 	}
		
  	?>
 <br/><p> </p>
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
                                    <div class="main-sparkline10-hd">
                                    <?php 
                                    $table_name = "hmc_client"; 
                                    $sql= " SELECT *
											   FROM ".$table_name.
											  " where user_login ='".$_SESSION["username"]."'"; 
                                   
                                    $rs_authen = $wpdb->get_results($sql);
                                    foreach ($rs_authen as $details) {
    								 	$fullname= $details->fullname . ' ' . $details->lastname;
                                    }
                                    ?>
                                        <h1>Welecome <?= $fullname;?> <span class="password-mt-none">to</span> GetBabi</h1>
                                    </div>
                                </div>
                                <div class="sparkline10-graph col-lg-12" >
                                	 
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