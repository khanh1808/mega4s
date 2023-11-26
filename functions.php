<?php 

define( 'HTECOM_VERSION', '1.0.0');
define( 'THEME_URL', get_stylesheet_directory_uri() );
define( 'THEME_CSS', THEME_URL.'/assets/css' );
define( 'THEME_JS', THEME_URL.'/assets/js' );
define( 'THEME_IMAGE', THEME_URL.'/assets/images' );
define( 'THEME_DIR', get_stylesheet_directory());
define( 'DOMAIN', 'htecom');

function my_theme_load_theme_textdomain() {
    load_theme_textdomain( 'htecom', THEME_DIR . '/languages' );

    add_theme_support( 'post-formats', array(
        //'aside',
        // 'image',
        'video',
        // 'link',
        'gallery',
    ) );


}
add_action( 'after_setup_theme', 'my_theme_load_theme_textdomain', 20 );

require THEME_DIR . '/inc/init.php';

// Add custom Theme Functions here
require_once(dirname(__FILE__)."/shortcode-nhansu.php");

add_action( 'flatsome_product_box_after',function(){
	echo get_field('type',get_the_ID());
});