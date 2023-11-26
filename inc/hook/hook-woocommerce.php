<?php
function hte_remove_action_woo() {
	// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_share', 'flatsome_product_share',  10 );
	remove_action( 'flatsome_sale_flash','woocommerce_show_product_sale_flash',10 );
	remove_action('woocommerce_after_main_content','flatsome_pages_in_search_results', 10);
	remove_action( 'flatsome_product_box_after', 'flatsome_woocommerce_shop_loop_excerpt', 20 );
	remove_action( 'flatsome_product_box_actions', 'flatsome_product_box_actions_add_to_cart', 1 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	// remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

	add_action('woocommerce_shop_loop_item_title', 'flatsome_woocommerce_shop_loop_excerpt', 30);

}
add_action( 'init', 'hte_remove_action_woo' );

add_action( 'woocommerce_after_shop_loop_item_title', function() {
	echo '<div class="wrap_right_action">';
		woocommerce_template_loop_rating();
}, 90 );

add_action( 'woocommerce_after_shop_loop_item_title', function() {
		flatsome_product_box_actions_add_to_cart();
	echo '</div>';
}, 100 );

add_filter('woocommerce_loop_add_to_cart_link', function($btn_html, $product, $args) {
	$btn_html = sprintf(
		'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		'<img class="oc_add_to_cart_icon" src="'.THEME_IMAGE.'/cart.svg" width="18" height="18"/>'
	);

	return $btn_html;
}, 10, 3);
	
add_action( 'woocommerce_share', function() {
	?>
	<div class="share-bottom-product flex align-middle justify-start">
		<div class="uppercase is-larger text-dark mr-half"><?php _e('Share on:', DOMAIN) ?></div>
		<?php echo do_shortcode('[share]'); ?>
	</div>
	<?php
}, 10 );

add_filter( 'woocommerce_gallery_thumbnail_size', function($size) {
	$size = 'medium';

	return $size;
}, 10); 


add_action('woocommerce_after_main_content', function() {
	if(!is_search() || !get_theme_mod('search_result', 1)) return;
	global $post;
	?>
	<?php if( get_search_query() ) : ?>
		<?php
	  /**
	   * Include pages and posts in search
	   */
	  query_posts( array( 'post_type' => array( 'post'), 's' => get_search_query() ) );

	  $posts = array();
	  while ( have_posts() ) : the_post();
	  	array_push($posts, $post->ID);
	  endwhile;

	  wp_reset_query();

	  if ( ! empty( $posts ) ) {
	  	echo '<hr/><h4 class="uppercase">' . esc_html__( 'Bài viết liên quan:', DOMAIN ) . '</h4>';
	  	echo flatsome_apply_shortcode( 'blog_posts', array(
	  		'columns'      => '3',
	  		'columns__md'  => '3',
	  		'columns__sm'  => '2',
	  		'type'         => get_theme_mod( 'search_result_style', 'slider' ),
	  		'image_height' => '56.25%',
	  		'show_date'    => get_theme_mod( 'blog_badge', 1 ) ? 'true' : 'false',
	  		'ids'          => implode( ',', $posts ),
	  	) );
	  }
	  ?>
	<?php endif;
}, 10);

/*
Add to functions.php
*/
function comment_validation_init() {
	if(is_singular() && comments_open() ) { ?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$.validator.addMethod(
				    /* The value you can use inside the email object in the validator. */
				    "regex",

				    /* The function that tests a given string against a given regEx. */
				    function(value, element, regexp)  {
				        /* Check if the value is truthy (avoid null.constructor) & if it's not a RegEx. (Edited: regex --> regexp)*/

				        if (regexp && regexp.constructor != RegExp) {
				           /* Create a new regular expression using the regex argument. */
				           regexp = new RegExp(regexp);
				        }

				        /* Check whether the argument is global and, if so set its last index to 0. */
				        else if (regexp.global) regexp.lastIndex = 0;

				        /* Return whether the element is optional or the result of the validation. */
				        return this.optional(element) || regexp.test(value);
				    }
				);
				$('#commentform').validate({

					onfocusout: function(element) {
						this.element(element);
					},
					rules: {
						author: {
							required: true,
							minlength: 2
						},
						email: {
							required: true,
							email: true,
							regex: /^\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
						},
						comment: {
							required: true,
						}
					},
					messages: {
						author: "<?php _e("Vui lòng nhập tên của bạn", DOMAIN) ?>",
						email: "<?php _e("Vui lòng nhập email", DOMAIN) ?>",
						comment: "<?php _e("Hãy nhập nhận xét của bạn", DOMAIN) ?>"
					},
					errorElement: "div",
					errorPlacement: function(error, element) {
						element.after(error);
					}
				});
			});
		</script>
		<?php
	}
}
add_action('wp_footer', 'comment_validation_init');