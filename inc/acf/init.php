<?php 
if ( ! defined( 'ABSPATH' ) ) {exit; }
if(!function_exists('get_field')){return ; }
require_once THEME_DIR. '/inc/acf/acf-user.php';
acf_add_options_page(array(
	'page_title' 	=> 'General Settings',
	'menu_title'	=> 'General Settings',
	'menu_slug' 	=> 'theme-general-settings',
	'capability'	=> 'edit_posts',
	'position' => 30,
	'redirect'		=> false
));