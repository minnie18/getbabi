<?php session_start();?>
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
		 
		 
	<body>
 
		<div class="wrapper">
			<div class="image-holder">
				<img src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/images/whenisOvulation.png" alt="">
			</div>
            <form action="/hmc_proj/result-ovulation-cycle" method="post" id="frm_wizard"> 
            	<div class="form-header"> 
            		<h3>ผลการคำนวณวันตกไข่</h3>
            	</div>
            	<div id="wizard">
            		<!-- SECTION 1 -->
	                <h4></h4>
	                <section>
	                    <div class="form-row" style="margin-bottom: 26px;">
	                    	<label for="">
	                    		 วันตกไข่: 
	                    		 
	                    	</label>
	                    	
	                    	<div class="form-holder">
	                    		<?php 
	                    		global $wpdb;
	                    		$_id=$_SESSION["id"];
	                    		$_first_date=strtotime($_POST["dp1"]);
	                    		$_dif_day=$_POST["frm_dif_day"];
	                    		//Get email 
	                     //echo "hc_id ->".$_id."<br>";
	                     //echo "first->".$_POST["dp1"]."<br>";
	                    // echo "day->".$_dif_day."<hr>";
	                    if($_id){
	                    	 $result=$wpdb->insert("hmc_ovu" , array('hc_id' =>$_id, 'first_men_date' => $_POST["dp1"],'mem_cyc' => $_dif_day),array('%s','%s', '%s'));
	                    	 if($result){
	                    	 		echo "save ok";
	                    	 }  
	                    }
								$date = date('Y-m-d',$_first_date);
	                    		  
	                    		$diff_date = $_dif_day - 14;
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
	                     	
	                </section>
	                 
            	</div>
            </form>
		</div>


		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/js/jquery-3.3.1.min.js"></script>
		
		 
		<!-- DATE-PICKER -->
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/vendor/date-picker/js/datepicker.js"></script>
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/vendor/date-picker/js/datepicker.en.js"></script>

		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q2/js/main.js"></script>
<!-- Template created and distributed by Colorlib -->
</body>
</html>