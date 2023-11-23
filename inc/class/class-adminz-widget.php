<?php
use Adminz\Inc\Widget\ADMINZ_Inc_Widget_Filter_Product_Taxonomy;
use WP_Widget;

class HTE_Inc_Widget_Filter_Product_Taxonomy extends ADMINZ_Inc_Widget_Filter_Product_Taxonomy {
    function __construct() {		
    	$widget_ops = [ 
	      	'classname' => 'adminz_woocommerce_filter_taxonomy', 
	      	'description' => __("A list or dropdown of product categories.",'administrator-z'). " search by title, price, custom taxonomy, rating star, product attributes, product tag.",
	      	'customize_selective_refresh' => true
	    	];
	    $control_ops = ['id_base' => 'adminz_woocommerce_filter_taxonomy' ];
	    $title = __("Filter products",'administrator-z'). " - NEW";
	    WP_Widget::__construct( 
	    	'adminz_woocommerce_filter_taxonomy', 
	    	$title,
	    	$widget_ops, 
	    	$control_ops 
	    );
	    add_action( 'widgets_init', function (){
            unregister_widget( 'ADMINZ_Inc_Widget_Filter_Product_Taxonomy' );
	    	register_widget( $this );
	    }, 9);

        remove_filter( 'widget_title', 'esc_html' );
  	}

    function update( $new_instance, $old_instance ) {
	    WP_Widget::update( $new_instance, $old_instance );
		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];
		$instance['taxonomy'] = strip_tags($new_instance['taxonomy']);
		$instance['style'] = strip_tags($new_instance['style']);
		$instance['step'] = strip_tags($new_instance['step']);
		$instance['global_filter_price'] = strip_tags($new_instance['global_filter_price']);
		$instance['query_type'] = strip_tags($new_instance['query_type']);

		return $instance;
  	}

    function remove_esc_html_title_widget() {

    }
}

new HTE_Inc_Widget_Filter_Product_Taxonomy;
