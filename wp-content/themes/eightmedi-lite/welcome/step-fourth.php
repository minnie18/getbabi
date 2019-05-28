<?php
/**
 * Changelog
 */

$eightmedi_lite = wp_get_theme( 'eightmedi-lite' );
?>
<div class="featured-section changelog">
<?php
	WP_Filesystem();
	global $wp_filesystem;
	$eightmedi_lite_changelog       = $wp_filesystem->get_contents( get_template_directory() . '/readme.txt' );
	$changelog_start = strpos($eightmedi_lite_changelog,'== Changelog ==');
	$eightmedi_lite_changelog_before = substr($eightmedi_lite_changelog,0,($changelog_start+15));
	$eightmedi_lite_changelog = str_replace($eightmedi_lite_changelog_before,'',$eightmedi_lite_changelog);
	$eightmedi_lite_changelog = str_replace('**','<br/>**',$eightmedi_lite_changelog);
	$eightmedi_lite_changelog = str_replace('= 1.0','<br/><br/>= 1.0',$eightmedi_lite_changelog);
	$eightmedi_lite_changelog = str_replace('= 2.0','<br/><br/>= 2.0',$eightmedi_lite_changelog);
	echo wp_kses($eightmedi_lite_changelog,array('br'=>array()));
	echo '<hr />';
	?>
</div>