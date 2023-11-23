<?php

function kh_style_script() {
	wp_register_style( 'header-style', THEME_CSS . "/header.css", false, HTECOM_VERSION, 'all' );
	wp_enqueue_style('header-style');
	wp_register_style( 'footer-style', THEME_CSS . "/footer.css", false, HTECOM_VERSION, 'all' );
	wp_enqueue_style('footer-style');
	wp_register_style( 'main-style', THEME_CSS . "/main.css", false, HTECOM_VERSION, 'all' );	
	wp_enqueue_style('main-style');
	wp_register_style( 'classes', THEME_CSS . "/classes.css", false, HTECOM_VERSION, 'all' );
	wp_enqueue_style('classes');

	wp_register_style( 'home-style', THEME_CSS . "/layout/layout-home.css", false, HTECOM_VERSION, 'all' );
	wp_enqueue_style('home-style');

	wp_register_style( 'page-style', THEME_CSS . "/layout/layout-page.css", false, HTECOM_VERSION, 'all' );
	wp_enqueue_style('page-style');		

	wp_register_script( 'cf7-script', THEME_JS  . "/cf7.js", array('jquery'), HTECOM_VERSION, true );
	wp_enqueue_script('cf7-script');

	wp_register_script( 'main-script', THEME_JS  . "/main.js", array('jquery'), HTECOM_VERSION, true );
	wp_enqueue_script('main-script');

	// wp_register_script( 'ajax-script', THEME_JS  . "/ajax.js", array('jquery'), HTECOM_VERSION, true );
	// wp_enqueue_script('ajax-script');
	
	wp_enqueue_style( 'blog-style', THEME_CSS."/blog.css", false, false, 'all' );
	wp_enqueue_style( 'widget-style', THEME_CSS."/widget.css", false, false, 'all' );

	// if(is_woocommerce()){
		wp_enqueue_style( 'woocommerce-style', THEME_CSS."/woocommerce.css", false, false, 'all' );
	// }

	if(is_singular() && comments_open() ) {
		wp_register_script( 'validate-form-script', THEME_JS  . "/lib/jquery.validate.min.js", array('jquery'), HTECOM_VERSION, true );
		wp_enqueue_script('validate-form-script');
	}

}
add_action( 'wp_enqueue_scripts', 'kh_style_script' );

// function kh_style_script_admin() {
// 	wp_register_style('admin-style', THEME_URL . "/admin/admin.css", false, 'all');
//     wp_enqueue_style('admin-style');
// }
// add_action( 'admin_enqueue_scripts', 'kh_style_script_admin' );

add_action('wp_head', function(){
	?>
	<style type="text/css">
		
	</style>
	<?php
});

add_filter( 'tiny_mce_before_init', function( $initArray ) {
    $initArray['font_formats'] = 'Cormorant Upright=Cormorant Upright;Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';
	return $initArray;
} );

add_action( 'admin_init', function() {
	$font_url = THEME_URL.'/admin/admin-font.css';
	add_editor_style( str_replace( ',', '%2C', $font_url ) );
} );