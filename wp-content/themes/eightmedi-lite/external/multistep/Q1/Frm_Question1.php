<?php 
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>วินิฉัยเบื้องต้น คุณมีสภาวะมีบุตรยาก ?</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="colorlib.com"> 
		
	<!-- MATERIAL DESIGN ICONIC FONT -->
	<link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/fonts/material-design-iconic-font/css/material-design-iconic-font.css">
 
	<!-- STYLE CSS -->
	<link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/css/style.css"> 
    
	</head>
	<script>
	document.addEventListener("DOMContentLoaded", function(event) {  
	  	jQuery(document).ready(function($) {
	  				 
		});
	});
	</script>
	<body>
	<?php 
	 // unset($_SESSION['username']);
	if(!isset($_SESSION["username"])){ 
		header("Location: /hmc_proj/login/?p=question01");
	}
	?>
		<div class="wrapper">
            <form id="wizard" name="wizard" action="/hmc_proj/Ans01" method="post">
        		<!-- SECTION 1 -->
                <h2></h2>
                <section>
                    <div class="inner">
						<div class="image-holder">
							<img src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/images/is_infertility_survery.jpg" alt="" >
						</div>
						<div class="form-content" >
							<div class="form-header">
								<h3>คุณมีภาวะผู้มีบุตรยาก ?</h3>
							</div>
							<p>กรุณากรอกรายละเอียด</p>
							<div class="form-row">
								<div class="form-holder w-100">
									<!-- <input type="text" placeholder="First Name" class="form-control"> -->
									คุณอายุมากกว่า 35 ปี ?
								</div>
								<div class="form-holder">
									<!-- <input type="text" placeholder="Last Name" class="form-control"> -->
									<div class="checkbox-tick">
										<label class="a01">
											<input type="radio" name="a01_1" value="1"> ใช่ &nbsp;<br>
											<span class="checkmark"></span>
										</label>
										<label class="female">
											<input type="radio" name="a01_1" value="0"> ไม่ใช่<br>
											<span class="checkmark"></span>
										</label>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-holder w-100">
									คุณไม่มีเพศสัมพันธ์อย่างน้อย 6 เดือน ?
								</div>
								<div class="form-holder">
									<div class="checkbox-tick">
										<label class="a01">
											<input type="radio" name="a01_2" value="1"> ใช่&nbsp;<br>
											<span class="checkmark"></span>
										</label>
										<label class="female">
											<input type="radio" name="a01_2" value="0"> ไม่ใช่<br>
											<span class="checkmark"></span>
										</label>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-holder w-100">
									คุณมีประวัติเนื้อบุโพรงมดลูกเจริญผิดที่ ?
								</div>
								<div class="form-holder">
									<div class="checkbox-tick">
										<label class="a01">
											<input type="radio" name="a01_3" value="1"> ใช่&nbsp;<br>
											<span class="checkmark"></span>
										</label>
										<label class="female">
											<input type="radio" name="a01_3" value="0"> ไม่ใช่<br>
											<span class="checkmark"></span>
										</label>
									</div>
								</div>
							</div> 
						</div>
					</div>
                </section>

				 
            </form>
		</div>

		<!-- JQUERY -->
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/js/jquery-3.3.1.min.js"></script>

		<!-- JQUERY STEP -->
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/js/jquery.steps.js"></script>
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/js/main.js"></script>
		<!-- Template created and distributed by Colorlib -->
</body>
</html>
