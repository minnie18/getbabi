 <!doctype html>
<html class="no-js" lang="th">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>GetBabi | Login - Women's Health Care</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	<link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/fonts/material-design-iconic-font/css/material-design-iconic-font.css">
 
	<!-- STYLE CSS -->
	<link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/css/style.css"> 
    <link rel="stylesheet" href="../wp-content/css/button.bootstrap.min.css">
    <link rel="stylesheet" href="../wp-content/css/font-awesome.min.css">
    <link rel="stylesheet" href="../wp-content/css/wave/waves.min.css">
    <link rel="stylesheet" href="../wp-content/css/wave/button.css">
   <?php 
  	     //include "includes/css.header.inc.php";  
  	    include "wp-content/src/Facebook/autoload.php";
  	    include "wp-content/src/Google/settings.php";  
  	    
  	    global $wpdb; 
  	?>
  	 <script>
  	document.addEventListener("DOMContentLoaded", function(event) {  
	  	jQuery(document).ready(function($) {
		  	/*
	  		$('#fb_button').on('click', function(event) { 
	  	  		$(location).attr('href', 'login/login_fb.php');
	  		});
	  		*/
	  		$('#fb_button').on('click', function(event) { 
		  		url=$(this).val()
	  			$(location).attr('href', url);
	  	  		//alert($(this).val());
	  		}); 
	  		
  		});
	});

  	function onclick_submit(){ 
  		document.getElementById("wizard").submit();
  	}
</script>
</head>
 <br/><p> </p>
<body  class="materialdesign">  
    <div class="wrapper_login"> 
    <?php
									$fb = new Facebook\Facebook([
									  'app_id' => '1520129421554218', // Replace {app-id} with your app id
									  'app_secret' => '9a3e0adc58188364ebe4b6a645275bf5',
									  'default_graph_version' => 'v2.2',
									  ]);
									$helper = $fb->getRedirectLoginHelper(); 
									$permissions = ['email']; // Optional permissions
									$loginUrl = $helper->getLoginUrl('https://example.com/login/fb-callback.php', $permissions);

									//google
									$google_api="https://accounts.google.com/o/oauth2/v2/auth?scope=".urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online' ;
									
									//$_onsubmit=(isset($_POST["onsubmit"])?$_POST["onsubmit"]:"");
									//$_txt_email=(isset($_POST["txt_email"])?$_POST["txt_email"]:"");
									//$_txt_pwd=(isset($_POST["txt_pwd"])?$_POST["txt_pwd"]:""); 
									//$_prev_page=(isset($_POST["prev_page"])?$_POST["prev_page"]:""); 
									$_onsubmit=$_POST["onsubmit"]; 
									$_txt_email= $_POST["txt_email"];
 									$_txt_pwd=$_POST["txt_pwd"];
 									$_prev_page=($_POST["prev_page"])?$_POST["prev_page"]:"";
									//if($_onsubmit=="submit"){
									//echo "POST->email->".$_txt_email."-pws->".$_txt_pwd."==prev->".$_prev_page."<hr>";
									//echo "GET->email->".$_GET["txt_email"]."-pws->".$_GET["txt_pwd"]."==prev->".$_GET["prev_page"]."<hr>";
										if(isset($_txt_email) && isset($_txt_pwd)){
											 
											$table_name = "hmc_client"; 
											$sql= " SELECT *
													   FROM ".$table_name.
													  " where user_login ='".$_txt_email."' and user_pass='".$_txt_pwd."'";
						 					//echo $sql."<hr>";
	  									    $rs_authen = $wpdb->get_results($sql); 
	  									    //echo "rec count:".$wpdb->num_rows."<hr>";
	  									   	if((int)$wpdb->num_rows > 0){ 
	  									   		$_SESSION["username"]=$_txt_email;  
	  									   		$_SESSION['id']=$rs_authen[0]->id;
	  									   		$_SESSION['fullname']=$rs_authen[0]->fullname;
	  									   		//echo "id->".$rs_authen[0]->id;
	  									   		$_txt_email="";
	  									   		echo " match";
	  									   		if(isset($_prev_page)){ 
	  									   			$link = "Location: /hmc_proj/".$_prev_page;
	  									   			 
	  									   			header($link);
	  									   			//header("Location: /hmc_proj/");
	  									   		}
	  									   	} 
										}
									//}
									
							 if($_txt_email){ //alert message 
                             	?>
    						<div class="col-lg-6">  
                            	<div class="alert-wrap2 shadow-reset wrap-alert-b"> 
	                                <div class="alert alert-danger alert-mg-b"> 
	                                    <strong>รหัสผ่านไม่ถูกต้อง!</strong> กรุณาตรวจสอบ username และ password อีกครั้ง <a href="" style='background-color:green;border: 0px solid #0099CC;color:#ffffff;border-radius:2px;font-size:16px;padding:3px;'>ลืมรหัสผ่าน </a>
	                                </div>
                            	</div> 
                        	</div>
                        <?php }?>
                        
    	<div class="outline_login"> 
        	<div class="inner_login"> 
                            <h2><center>เข้าสู่ระบบ  </center></h2> <br/>
                             	
                             <span class="button-style-four btn-mg-b-10" id="btn_login"> 
                             	<button type="button" class="btn btn-custon-four btn-primary"  id="fb_button"  value="<?= htmlspecialchars($loginUrl)?>">
                             		<span class="fa fa-facebook-square"></span>&nbsp;Login Facebook
                             	</button>
                             	
                               	<!-- <button type="button" class="btn btn-info btn-primary"><span class="fa fa-twitter-square"></span>&nbsp;Login Twitter</button>  -->
                             	<button type="button" class="btn btn-custon-four btn-danger"><span class="fa fa-google-plus-square"></span>&nbsp; Login Google</button>
                             	<!-- <button type="button" class="btn btn-custon-four btn-bg-cl-linkedin-tw btn-default"><span class="fa fa-instagram"></span>&nbsp; Login Instagram</button>
                             	 -->
                        	</span>  
                            <p> </p>
                       <form method="POST" name="wizard" id="wizard">
                        <input type="hidden" name="onsubmit" id="onsubmit" value="submit">
                        <div class="form-example-int">
                            <div class="form-group">
                                <label>Email Address</label>
                             <input type="text" class="form-control" placeholder="Enter Email" name="txt_email" id="txt_email" >
                            
                            </div>
                        </div>
                        <div class="form-example-int">
                            <div class="form-group">
                                <label>Password</label> 
                                    <input type="password" class="form-control input-sm" placeholder="Password" id="txt_pwd" name="txt_pwd">
                            </div>
                        </div>
                        <div class="form-example-int mg-t-15">
                            <div class="fm-checkbox">
                                <label><input type="checkbox" class="i-checks"> <i></i> Remeber me</label>
                                <span class="mg-t-15 pull-right" style="color: #FF0000;"><b><a  href="hmc_proj/forgot-password/">Forgot password ?</b> &nbsp;</a></span>
                            </div>
                        </div> 
                            <span  class="mg-t-15" ><a  class="btn btn-success sbmt-btn col-3 col-md-2 col-lg-4 ml-1" onclick="onclick_submit();">Submit</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <span class="mg-t-15 pull-right" ><b><a class="col-3 col-md-2 col-lg-4 ml-1"  href="hmc_proj/new_user" ><br/>Create New Account </b> &nbsp;<span class="fa fa-user-plus big-icon"></a></span>
 
						<!-- 
                        <div id="form-example-int mg-t-15">
							  <span><a  class="btn btn-success sbmt-btn col-3 col-md-2 col-lg-4 ml-1" href="multistep/Q1/Frm_Question1.php">Submit</a></span>
							  <span class="pull-right"><b><a  href="Frm_New_Account.php">Create New Account </b> &nbsp;<span class="fa fa-user-plus big-icon"></a></span>
						</div> 
						 -->
                </div>
                <?php 
                if(isset($_GET["p"])){
                	$_prev_page=$_GET["p"];
                }
                ?> 
                <input type="hidden" name="prev_page" id="prev_page" value="<?=$_prev_page?>">
               
                </form>
            </div>
         </div> 
    <?php 
         
        //include "../../includes/js.inc.php";
    ?>
    <!-- End Footer area-->
</body>
    