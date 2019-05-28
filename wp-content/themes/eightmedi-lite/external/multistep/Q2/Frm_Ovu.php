<?php session_start();
 global $wpdb; 
 
 	if(!isset($_SESSION["username"])){ 
		header("Location: /hmc_proj/login/");
		
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Ovulation Survery</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="colorlib.com">

		<!-- MATERIAL DESIGN ICONIC FONT -->
		<link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/fonts/material-design-iconic-font/css/material-design-iconic-font.css">

		<!-- DATE-PICKER -->
		<link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/vendor/date-picker/css/datepicker.min.css">

		<!-- STYLE CSS -->
		<link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/css/style.css">
		<script type="text/javascript"> 
		document.addEventListener("DOMContentLoaded", function(event) {  
		  	jQuery(document).ready(function($) {
			  	
		  		$("#frm_dif_day").focusout(function() { 
			  		var value =$("#frm_dif_day").val(); 
			  		
			  	    if($.isNumeric(value) == false){ 
				  	    if(value !== "") {
					  	 	$("#frm_dif_day").val('');
					  	 	alert('กรุณากรอกตัวเลข'); 
					  	 	$("#frm_dif_day").focus();
				  	    }
			  	    }else{
				  	    if(value > 50){
				  	    	alert('กรุณากรอกตัวเลข ไม่เกิน 50'); 
				  	    	$("#frm_dif_day").val('');
				  	    	$("#frm_dif_day").focus();
				  	    }
			  	    }
		  		}); 

		  		
		  		$('.tooltip-r').tooltip();
		  		
		  		$('.tooltip-dif').tooltip(); 
			});
		});
		</script>
		 
	<body>
 
		<div class="wrapper">
			<div class="image-holder">
				<img src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/images/whenisOvulation.png" alt="">
			</div>
            <form action="/hmc_proj/result-ovulation-cycle" method="post" id="frm_wizard"> 
            	<div class="form-header"> 
            		<h3>นับวันตกไข่</h3> 
            	</div>
            	<div id="wizard">
            		<!-- SECTION 1 -->
	                <h4></h4>
	                <section>
	                    <div class="form-row" style="margin-bottom: 26px;">
	                    	<label for="">
	                    		 วันแรกของประจำเดือนครั้งล่าสุด:
	                    		<span class="tooltip-r" data-toggle="tooltip" data-placement="left" title="วันที่ประจำเดือนมาวันแรก หมายถึง วันที่ประจำเดือนมาหยดแรก รวมทั้งเป็นลิ้มเลือดด้วย">
								 <img src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/images/information.png"/> 
								</span>
	                    	</label>
	                    	<div class="form-holder">
	                    		<input type="text" class="form-control datepicker-here" data-language='en' data-date-format="dd-mm-yyyy" id="dp1" name="dp1">
	                    	</div>
	                    </div>	 
	                    <div class="form-row">
	                    	<label for="">
	                    		ระยะห่างระหว่างรอบเดือน กี่วัน:
	                    		<span class="tooltip-r" data-toggle="tooltip-dif" data-placement="left" title="นับตั้งแต่ประจำเดือน">
								 <img src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/images/information.png"/> 
								</span>
	                    	</label>
	                    	<div class="form-holder">
	                    		<input type="text" class="form-control" id="frm_dif_day" maxlength="3" name="frm_dif_day">
	                    	</div>
	                    </div>	
	                </section>
	                
					<!-- SECTION 2 -->
					<!-- 
	                <h4></h4>
	                <section>
	                    <div class="form-row">
	                    	<label for="">
	                    		Date of Birth:
	                    	</label>
	                    	
	                    </div>	
	                    <div class="form-row">
	                    	<label for="">
	                    		Country of Birth:
	                    	</label>
	                    	<div class="form-holder">
	                    		<select name="" id="" class="form-control">
									<option value="united states" class="option">United States</option>
									<option value="united kingdom" class="option">United Kingdom</option>
									<option value="viet nam" class="option">Viet Nam</option>
								</select>
								<i class="zmdi zmdi-caret-down"></i>
	                    	</div>
	                    </div>	
	                    <div class="form-row">
	                    	<label for="">
	                    		Your Email:
	                    	</label>
	                    	<div class="form-holder">
	                    		<input type="text" class="form-control">
	                    	</div>
	                    </div>	
	                    <div class="form-row" style="margin-bottom: 3.4vh">
	                    	<label for="">
	                    		Phone Number:
	                    	</label>
	                    	<div class="form-holder">
	                    		<input type="text" class="form-control">
	                    	</div>
	                    </div>	
	                    <div class="form-row" style="margin-bottom: 50px;">
	                    	<label for="">
	                    		Gender:
	                    	</label>
	                    	<div class="form-holder">
	                    		<div class="checkbox-circle">
									<label class="male">
										<input type="radio" name="gender" value="male" checked> Male<br>
										<span class="checkmark"></span>
									</label>
									<label class="female">
										<input type="radio" name="gender" value="female"> Female<br>
										<span class="checkmark"></span>
									</label>
									<label>
										<input type="radio" name="gender" value="transgender">Transgender<br>
										<span class="checkmark"></span>
									</label>
								</div>
	                    	</div>
	                    </div>		
	                </section>
 -->
	                <!-- SECTION 3 -->
	                <!-- 
	                <h4></h4>
	                <section>
	                    <div class="form-row">
	                    	<label for="">
	                    		Course ID:
	                    	</label>
	                    	<div class="form-holder">
	                    		<input type="text" class="form-control" placeholder="Ex. abc 12345 or abc 1234L">
	                    	</div>
	                    </div>	
	                    <div class="form-row">
	                    	<label for="">
	                    		Course Title:
	                    	</label>
	                    	<div class="form-holder">
	                    		<input type="text" class="form-control" placeholder="Ex. Intro to physic">
	                    	</div>
	                    </div>	
                     	<div class="form-row">
	                    	<label for="">
	                    		Section(s):
	                    	</label>
	                    	<div class="form-holder">
	                    		<input type="text" class="form-control" placeholder="Ex. 3679 or 33fa, 4295">
	                    	</div>
	                    </div>	
	                    <div class="form-row" style="margin-bottom: 38px">
	                    	<label for="">
	                    		Select Teacher:
	                    	</label>
	                    	<div class="form-holder">
	                    		<select name="" id="" class="form-control">
	                    			<option value="frances meyer" class="option">Frances Meyer</option>
									<option value="johan lucas" class="option">Johan Lucas</option>
									<option value="merry linn" class="option">Merry Linn</option>
								</select>
								<i class="zmdi zmdi-caret-down"></i>
	                    	</div>
	                    </div>	
	                    <div class="checkbox-circle" style="margin-bottom: 48px;">
							<label>
								<input type="checkbox" checked>I agree all statement in Terms & Conditions
								<span class="checkmark"></span>
							</label>
						</div>
	                </section>
	                 -->
            	</div>
            	<?php 
            	//check ว่าเคยมีข้อมูลอยู่หรือไม่
            		$_id=$_SESSION["id"]; 
            		$table_name = "hmc_ovu"; 
					$sql= " SELECT *
							   FROM hmc_ovu
							   where hc_id ='$_id'";  
  					$rs_authen = $wpdb->get_results($sql); 
  					if($rs_authen[0]->hc_id){
            	?>
            	<span style="display: block;color: #fff500;float:left;clear:both;margin-bottom: 4px;margin: 0 0 20px;background-color:#337AFF;padding: 5px 15px;">
            		<a href=""><< ประวัติประจำเดือนย้อนหลัง</a>
            	</span>
            	<?php 
  					}
            	?>
            </form>
            
		</div>

		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/js/jquery-3.3.1.min.js"></script>
		
		<!-- JQUERY STEP -->
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/js/jquery.steps.js"></script>

		<!-- DATE-PICKER -->
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/vendor/date-picker/js/datepicker.js"></script>
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/vendor/date-picker/js/datepicker.en.js"></script>

		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/js/main.js"></script>
<!-- Template created and distributed by Colorlib -->
</body>
</html>