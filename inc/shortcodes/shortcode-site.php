<?php 
function yoast_breadcrumb_func($attr){
	ob_start();
    if ( function_exists( 'yoast_breadcrumb' ) ) { yoast_breadcrumb('','');  }; 
    return ob_get_clean();
}
add_shortcode('yoast_breadcrumb_shortcode', 'yoast_breadcrumb_func');

function footer_menu_func($attr){
    ob_start();
    if(function_exists('wp_nav_menu')){
        $args = array(
            'theme_location' => 'footer',
            'link_before'=>'',
            'link_after'=>'',
            'container_class'=>'',
            'menu_class'=>'menu_footer',
            'menu_id'=>'',
            'container'=>'ul',
            'before'=>'',
            'after'=>'',
            'depth' => 1
        );
        wp_nav_menu( $args );
    }
    return ob_get_clean();
}
add_shortcode('footer_menu', 'footer_menu_func');

function hdbq_product_func($attr){
    if(!is_singular('product')) return;
	echo get_field('hdbq', get_the_ID());
}
add_shortcode('hdbq_product', 'hdbq_product_func');