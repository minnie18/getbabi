<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package 8Medi Lite
 */
session_start();
global $session;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	 <script>
  	document.addEventListener("DOMContentLoaded", function(event) {  
	  	jQuery(document).ready(function($) {
		  	/*
	  		$('#fb_button').on('click', function(event) { 
	  	  		$(location).attr('href', 'login/login_fb.php');
	  		});
	  		*/
	  		jQuery(function($) {
	  		   //alert('kkk');
	  		});  
	  		$('#logout').on('click', function(event) { 
	  			 if(confirm("คุณต้องการออกจากระบบหรือไม่?")){
	  		        $("#delete-button").attr("href", "query.php?ACTION=delete&ID='1'");
	  		        url='?logout=1';
	  		      	$(location).attr('href', url);
	  		    }
	  		    else{
	  		        return false;
	  		    }
	  		});
	  		
	  		$('#login').on('click', function(event) { 
		  		url=$(this).val();
		  //	alert($(this).val());
	  			$(location).attr('href', url);
	  	  		
	  		});
  		});
	});
</script>
	<?php 
	if($_GET['logout']==1){
		 unset($_SESSION['username']);
		 unset($_SESSION['fullname']);
		 unset($_SESSION['id']);
	}
	wp_head(); 
	?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'eightmedi-lite' ); ?></a>
		<?php if(get_theme_mod('eightmedi_lite_top_header_setting','1')==1): ?>
			<div class="top-header">
				<div class="ed-container-home">
					<div class="header-callto clear">
						<div class="callto-left">
							<?php echo wp_kses_post(get_theme_mod('eightmedi_lite_callto_text',''));?>
						</div>
						<div class="callto-right">
							<div class="cta">
								<?php echo wp_kses_post(get_theme_mod('eightmedi_lite_callto_text_right',''));?>
							</div>
							<?php if(get_theme_mod('eightmedi_lite_social_icons_in_header','1')==1){ ?>
								<div class="header-social social-links">
									<?php do_action('eightmedi_lite_social_links');?>
								</div>
								<?php }
								//session_destroy();
								
								$_username=$_SESSION["username"];
								$_fullname=$_SESSION['fullname'];
								  
								if($_username){
									 
									echo"<span  style='background-color:green;border: 0px solid #0099CC;color:#ffffff;border-radius:2px;font-size:16px;padding:3px;'>ยินดีต้อนรับคุณ <a href='#' id='logout' name='logout'><u>".$_fullname."</u></a></span>";
								 ?> 
								 <?php 
								}else{
								?>						
									<button type="button" style="background-color:red;border: 0px solid #0099CC;border-radius:2px;" id="login" name="login" value="/hmc_proj/login" ><b>Login</b></button>
								
								<?php
								}
								if(get_theme_mod('eightmedi_lite_hide_header_search')!='1'){
									?>
									<div class="header-search">
										<i class="fa fa-search"></i>
										<?php get_search_form();?>
									</div>
									<?php
								}?>
								 						
							</div>
						</div>
					</div>
				</div>
			<?php endif;?>
			
			<header id="masthead" class="site-header" role="banner">
				<?php $logo_align = get_theme_mod('eightmedi_lite_logo_alignment_setting','1');
				
				if($logo_align == 0){ $logo_align_class='center-align'; }
				else{ $logo_align_class='left-align'; }
				?>
				<div class="ed-container-home <?php echo esc_attr($logo_align_class);?>">
					<div class="site-branding">
						<div class="site-logo">
							<?php
							if ( function_exists( 'the_custom_logo' ) ) {
								if ( has_custom_logo() ) : ?>
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
										<?php the_custom_logo(); ?>
									</a> 
									<?php 
									endif;
								}
								?>
							</div> 
							<div class="site-text">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
									<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
									<p class="site-description"><?php bloginfo( 'description' ); ?></p>
								</a>
							</div>
						</div><!-- .site-branding -->

						<nav id="site-navigation" class="main-navigation" role="navigation">
							<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
								<?php //esc_html_e( 'Primary Menu', 'eightmedi-lite' ); ?>
								<span class="menu-bar menubar-first"></span>
								<span class="menu-bar menubar-second"></span>
								<span class="menu-bar menubar-third"></span>
							</button>
							<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
						</nav><!-- #site-navigation -->
					</div>
				</header><!-- #masthead -->
				<?php 
				$no_margin = "";
				if(is_page_template( 'template-home.php' ) || is_page_template('template-boxedhome.php')){
					if(is_home() || is_front_page() || is_page_template('template-boxedhome.php')){
						$yes_slider = esc_attr(get_theme_mod('eightmedi_lite_display_slider','1'));
						if($yes_slider==1){
							$no_margin=' no-margin';
						}
					}
				}
				?>
				<div id="content" class="site-content<?php echo esc_attr($no_margin);?>">
		 
				
				
