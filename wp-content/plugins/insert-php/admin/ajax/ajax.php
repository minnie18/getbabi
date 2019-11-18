<?php
/**
 * Ajax requests handler
 * @author Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 2018 Webraftic Ltd
 * @version 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns a list of available roles.
 */
function wbcr_inp_ajax_get_user_roles() {
	global $wp_roles;
	$roles = $wp_roles->roles;

	$values = array();
	foreach ( $roles as $role_id => $role ) {
		$values[] = array(
			'value' => $role_id,
			'title' => $role['name'],
		);
	}

	$values[] = array(
		'value' => 'guest',
		'title' => __( 'Guest', 'insert-php' ),
	);

	$result = array(
		'values' => $values,
	);

	echo json_encode( $result );
	exit;
}
add_action( 'wp_ajax_wbcr_inp_ajax_get_user_roles', 'wbcr_inp_ajax_get_user_roles' );

/**
 * Returns a list of public post types.
 */
function wbcr_inp_ajax_get_post_types() {
	$values     = array();
	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	if ( ! empty( $post_types ) ) {
		foreach ( $post_types as $key => $value ) {
			$values[] = array(
				'value' => $key,
				'title' => $value->label,
			);
		}
	}

	$result = array(
		'values' => $values,
	);

	echo json_encode( $result );
	exit;
}
add_action( 'wp_ajax_wbcr_inp_ajax_get_post_types', 'wbcr_inp_ajax_get_post_types' );

/**
 * Returns a list of public taxonomies.
 */
function wbcr_inp_ajax_get_taxonomies() {
	$values     = array();
	$categories = get_categories( array( 'hide_empty' => false ) );

	if ( ! empty( $categories ) ) {
		foreach ( $categories as $cat ) {
			$values[] = array(
				'value' => $cat->term_id,
				'title' => $cat->name,
			);
		}
	}

	$result = array(
		'values' => $values,
	);

	echo json_encode( $result );
	exit;
}
add_action( 'wp_ajax_wbcr_inp_ajax_get_taxonomies', 'wbcr_inp_ajax_get_taxonomies' );

/**
 * Returns a list of page list values
 */
function wbcr_inp_ajax_get_page_list() {
	$result = array(
		'values' => array(
			'Basic'         => array(
				array(
					'value' => 'base_web',
					'title' => __( 'Entire Website', 'insert-php' ),
				),
				array(
					'value' => 'base_sing',
					'title' => __( 'All Singulars', 'insert-php' ),
				),
				array(
					'value' => 'base_arch',
					'title' => __( 'All Archives', 'insert-php' ),
				),
			),
			'Special Pages' => array(
				array(
					'value' => 'spec_404',
					'title' => __( '404 Page', 'insert-php' ),
				),
				array(
					'value' => 'spec_search',
					'title' => __( 'Search Page', 'insert-php' ),
				),
				array(
					'value' => 'spec_blog',
					'title' => __( 'Blog / Posts Page', 'insert-php' ),
				),
				array(
					'value' => 'spec_front',
					'title' => __( 'Front Page', 'insert-php' ),
				),
				array(
					'value' => 'spec_date',
					'title' => __( 'Date Archive', 'insert-php' ),
				),
				array(
					'value' => 'spec_auth',
					'title' => __( 'Author Archive', 'insert-php' ),
				),
			),
			'Posts'         => array(
				array(
					'value' => 'post_all',
					'title' => __( 'All Posts', 'insert-php' ),
				),
				array(
					'value' => 'post_arch',
					'title' => __( 'All Posts Archive', 'insert-php' ),
				),
				array(
					'value' => 'post_cat',
					'title' => __( 'All Categories Archive', 'insert-php' ),
				),
				array(
					'value' => 'post_tag',
					'title' => __( 'All Tags Archive', 'insert-php' ),
				),
			),
			'Pages'         => array(
				array(
					'value' => 'page_all',
					'title' => __( 'All Pages', 'insert-php' ),
				),
				array(
					'value' => 'page_arch',
					'title' => __( 'All Pages Archive', 'insert-php' ),
				),
			),
		),
	);

	echo json_encode( $result );
	exit;
}
add_action( 'wp_ajax_wbcr_inp_ajax_get_page_list', 'wbcr_inp_ajax_get_page_list' );

/**
 * Save the Permalink slug
 */
function wbcr_inp_ajax_save_permalink() {
	check_ajax_referer( 'winp-permalink', 'winp_permalink_nonce' );

	$code_id   = WINP_Plugin::app()->request->post( 'code_id', 0 );
	$permalink = WINP_Plugin::app()->request->post( 'permalink', null, true );
	$slug      = WINP_Plugin::app()->request->post( 'new_slug', null, 'sanitize_file_name' );
	$filetype  = WINP_Plugin::app()->request->post( 'filetype', 'css', true );

	WINP_Helper::updateMetaOption( $code_id, 'filetype', $filetype );

	if ( empty( $slug ) ) {
		$slug = (string) $code_id;
		WINP_Helper::updateMetaOption( $code_id, 'css_js_slug', '' );
	} else {
		WINP_Helper::updateMetaOption( $code_id, 'css_js_slug', $slug );
	}
	WINP_Plugin::app()->get_common_object()->editFormBeforePermalink( $slug, $permalink, $filetype );

	wp_die();
}
add_action( 'wp_ajax_winp_permalink', 'wbcr_inp_ajax_save_permalink' );