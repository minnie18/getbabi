<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Result Ovulation</title>
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
			  	
		  		 
			});
		});
		</script>
		 
	<body>
 
		<div class="wrapper">
			<div class="image-holder">
				<img src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/images/whenisOvulation.png" alt="">
			</div>
            <form action="/hmc_proj/result-ovulation-cycle" method="post" id="frm_wizard"> 
            	 
            	<center><h1>ผลการคำนวณวันตกไข่</h1></center>
             
            	<div id="wizard"> 
	                    <div class="form-row" style="margin-bottom: 26px;">
	                    	<label for="">
	                    		 วันตกไข่: 
	                    	</label>
	                    	<div class="form-holder">
	                    		 <b> </b>
	               			<?php 
	                    		$time = strtotime($_POST["dp1"]);
								$date = date('Y-m-d',$time);
	                    		 
	                    		$diff_day = $_POST["frm_dif_day"];
	                    		$diff_date = $diff_day - 14;
	                    	 /*
	                    		 echo "date->".$date."<br>";
	                    		 echo "diff_day ->".$diff_day."<br>";
	                    		 echo "diff_date -> ".$diff_date."<hr>";
	                    		 
	                    		 
	                    		 echo "<hr><hr>";
	                    	 
	                    		  */
	                    		 $date=date_create($date); 
								 date_add($date,date_interval_create_from_date_string("$diff_date days"));
								 echo  date_format($date,"Y-m-d");
								 
								 
	                    		?>
	                    	</div>
	                    </div>	 
	                   
	                 
            	</div>
            
		</div>

		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/js/jquery-3.3.1.min.js"></script>
		
		 
		<!-- DATE-PICKER -->
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/vendor/date-picker/js/datepicker.js"></script>
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/vendor/date-picker/js/datepicker.en.js"></script>

		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/js/main.js"></script>
<!-- Template created and distributed by Colorlib -->
</body>
</html>