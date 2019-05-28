<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>FormWizard_v1</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="colorlib.com"> 
		
	<!-- MATERIAL DESIGN ICONIC FONT -->
	<link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/fonts/material-design-iconic-font/css/material-design-iconic-font.css">
 
	<!-- STYLE CSS -->
	<link rel="stylesheet" href="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/css/style.css"> 
	    
	</head>
	<style>
		a.rel_link { 
			color: #4D6AFF ; 
			text-indent: 50px;
			font-size: 14px; font-weight: normal; 
			line-height: 48px; margin: 0; 
		}
		a.back { 
			color: #4D6AFF ; 
			text-indent: 50px;
			font-size: 14px; font-weight: normal; 
			line-height: 48px; margin: 0; 
		}
		p.text-no-infer{ 
			text-indent: 50px;   
			color: #FF884D;
			background: #CCF7FF; 
			font-size: 20px; font-weight: bold; 
			line-height: 48px; margin: 0; 
		}
		p.text-infer{ 
			text-indent: 50px;   
			color: #226600;
			background: #CCF7FF; 
			font-size: 20px; font-weight: bold; 
			line-height: 48px; margin: 0; 
		}
	</style>
	<script>
	document.addEventListener("DOMContentLoaded", function(event) {  
	  	jQuery(document).ready(function($) {
	  				 
		});
	});
	</script>
	<body>
	<?php 
		/*echo "a01_1 - ".$_POST["a01_1"]."<hr>";
		echo "a01_2 - ".$_POST["a01_2"]."<hr>";
		echo "a01_3 - ".$_POST["a01_3"]."<hr>";
		*/
		$result=0;
		$result=$_POST["a01_1"]+$_POST["a01_2"]+$_POST["a01_3"];
 
	?>
		<div class="wrapper"> 
        		<!-- SECTION 1 -->
                <h2></h2>
                <section>
                    <div class="inner">
						<div class="image-holder">
						<?php  
							if($result <2){
						?>
							<img src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/images/No_Infertile.jpg" alt="" >
						<?php 
							}else{
						?>
						<img src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/images/Infertile.jpg" alt="" >
						<?php 
							}
							?>
						</div>
						<div class="form-content" >
							<div class="form-header">
								<h3>ผลการวินิฉัยภาวะมีบุตรยากเบื้องต้น</h3>
							</div>
						 <?php  
							if($result <2){
						?>
							<p class="text-infer">คุณไม่มีภาวะมีบุตรยาก</p>
							 <?php 
						}else{
						?>
							<p class="text-no-infer">คุณมีภาวะมีบุตรยาก</p>
						<?php 
						}
						?>
						 <br/><br/><br/><br/><br/>
						<span><a href="/hmc_proj/question01" class="rel_link"><< วินิฉัยเบื้องต้น คุณมีสภาวะผู้มีบุตรยาก ?</a></span>
						 <?php  
							if($result <2){
						?>
							<span class="col-sm-7 pull-right " ><a href="/hmc_proj/noproblem-but-no-pregnant/" class="rel_link">ไม่มีปัญหา แต่ทำไมถึงไม่ท้อง สักที ? >></a></span>
						<?php 
						}else{
						?>
							<span class="col-sm-7 pull-right " ><a href="/hmc_proj/increase-child-yourself/" class="rel_link">เพิ่มโอกาสการมีบุตรด้วยตัวเอง >></a></span>
						<?php 
						}
						?> 
			</div>
			
			</div> 
			</section>
				
        
		</div>

		<!-- JQUERY -->
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/js/jquery-3.3.1.min.js"></script>

		<!-- JQUERY STEP -->
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/js/jquery.steps.js"></script>
		<script src="<?=get_stylesheet_directory_uri()?>/external/multistep/Q1/js/main.js"></script>
		<!-- Template created and distributed by Colorlib -->
</body>
</html>
