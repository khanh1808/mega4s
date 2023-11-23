<?php
/**
 * Widget API: WP_Widget_Recent_Posts class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */

/**
 * Core class used to implement a Recent Posts widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class WP_Widget_Recent_Posts_By_Category extends WP_Widget {

    /**
     * Sets up a new Recent Posts widget instance.
     *
     * @since 2.8.0
     */
    public function __construct() {
        $widget_ops = array(
            'classname'                   => 'widget_recent_entries',
            'description'                 => __( 'Your site&#8217;s most recent Posts by Category' ),
            'customize_selective_refresh' => true,
            'show_instance_in_rest'       => true,
        );
        parent::__construct( 'recent-posts-by-category', __( 'Bài viết theo chuyên mục' ), $widget_ops );
        $this->alt_option_name = 'widget_recent_entries';
    }

    /**
     * Outputs the content for the current Recent Posts widget instance.
     *
     * @since 2.8.0
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Recent Posts widget instance.
     */
    public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $default_title = __( 'Recent Posts' );
        $title         = ( ! empty( $instance['title'] ) ) ? $instance['title'] : $default_title;

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number ) {
            $number = 5;
        }
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

        $cat = isset( $instance['category'] ) ? $instance['category'] : false;   

        $args_query = array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        );

        if ($cat && $cat != 0) {
            $args_query['cat'] = $cat;
        }

        $r = new WP_Query(
            /**
             * Filters the arguments for the Recent Posts widget.
             *
             * @since 3.4.0
             * @since 4.9.0 Added the `$instance` parameter.
             *
             * @see WP_Query::get_posts()
             *
             * @param array $args     An array of arguments used to retrieve the recent posts.
             * @param array $instance Array of settings for the current widget.
             */
            apply_filters(
                'widget_posts_args',
                $args_query,
                $instance
            )
        );

        if ( ! $r->have_posts() ) {
            return;
        }
        ?>

        <?php echo $args['before_widget']; ?>

        <?php
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $format = current_theme_supports( 'html5', 'navigation-widgets' ) ? 'html5' : 'xhtml';

        /** This filter is documented in wp-includes/widgets/class-wp-nav-menu-widget.php */
        $format = apply_filters( 'navigation_widgets_format', $format );

        if ( 'html5' === $format ) {
            // The title may be filtered: Strip out HTML and make sure the aria-label is never empty.
            $title      = trim( strip_tags( $title ) );
            $aria_label = $title ? $title : $default_title;
            echo '<nav aria-label="' . esc_attr( $aria_label ) . '">';
        }
        ?>

        <ul>
            <?php foreach ( $r->posts as $recent_post ) : ?>
                <?php
                $post_title   = get_the_title( $recent_post->ID );
                $title        = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
                $aria_current = '';

                if ( get_queried_object_id() === $recent_post->ID ) {
                    $aria_current = ' aria-current="page"';
                }
                ?>
                <li>
                    <a href="<?php the_permalink( $recent_post->ID ); ?>"<?php echo $aria_current; ?>><?php echo $title; ?></a>
                    <?php if ( $show_date ) : ?>
                        <span class="post-date"><?php echo get_the_date( '', $recent_post->ID ); ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php
        if ( 'html5' === $format ) {
            echo '</nav>';
        }

        echo $args['after_widget'];
    }

    /**
     * Handles updating the settings for the current Recent Posts widget instance.
     *
     * @since 2.8.0
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings to save.
     */
    public function update( $new_instance, $old_instance ) {
        $instance              = $old_instance;
        $instance['title']     = sanitize_text_field( $new_instance['title'] );
        $instance['number']    = (int) $new_instance['number'];
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $instance['category'] = isset( $new_instance['category'] ) ? (int) $new_instance['category'] : false;
        return $instance;
    }

    /**
     * Outputs the settings form for the Recent Posts widget.
     *
     * @since 2.8.0
     *
     * @param array $instance Current settings.
     */
    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;

        $all_cats = get_terms( array( 'taxonomy' => 'category' ) );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
        </p>

        <p>
            <input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Select Category' ); ?>:</label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>">
                <option value=""><?php _e( 'All' ); ?></option>
                <?php foreach ( $all_cats as $cat ) : ?>
                    <option value="<?php echo (int) $cat->term_id; ?>" <?php selected( $instance['category'], $cat->term_id ); ?>>
                        <?php echo esc_html( $cat->name ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }
}


// include_once get_template_directory() . '/inc/widgets/widget-recent-posts.php';
/**
 * Recent_Posts widget class
 *
 * @since 2.8.0
 */
class Flatsome_Custom_Recent_Post_Widget extends WP_Widget {
    function __construct() {
		$widget_ops = array( 'classname' => 'flatsome_custom_recent_posts', 'description' => __('A widget that displays recent posts ', 'flatsome'), 'customize_selective_refresh' => true);

		$control_ops = array( 'id_base' => 'flatsome_custom_recent_posts' );

		parent::__construct( 'flatsome_custom_recent_posts', __('Flatsome Custom Recent Posts', 'flatsome'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {

		$cache = wp_cache_get('widget_recent_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( !isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		if ( empty( $instance['image'] ) ) $instance['image'] = false;
		$is_image = $instance['image'] ? 'true' : 'false';
        
        if ( empty( $instance['date-stamp'] ) ) $instance['date-stamp'] = false;
		$is_date_stamp = $instance['date-stamp'] ? 'true' : 'false';

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts', 'flatsome') : $instance['title'], $instance, $this->id_base);
		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );

		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<?php echo '<ul>'; ?>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>

		<?php
            $image_style = '';
            if($is_image == 'true' && has_post_thumbnail() && $is_date_stamp == 'true') {
                $image_style = 'style="background: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.2) ), url('.wp_get_attachment_thumb_url(get_post_thumbnail_id(get_the_ID()) ).'); color:#fff; text-shadow:1px 1px 0px rgba(0,0,0,.5); border:0;"';
            }
            else if($is_image == 'true' && has_post_thumbnail() && $is_date_stamp == 'false') {
                $image_style = 'style="background: url('.wp_get_attachment_thumb_url(get_post_thumbnail_id(get_the_ID()) ).'); border:0;"';
            }
        ?>

		<li class="recent-blog-posts-li">
			<div class="flex-row recent-blog-posts align-top pt-half pb-half">
				<div class="flex-col mr-half">
					<div class="badge post-date <?php if($is_image == 'false') echo 'badge-small';?> badge-<?php echo flatsome_option('blog_badge_style'); ?>">
                        <div class="badge-inner bg-fill" <?php echo $image_style;?>>
                            
                        </div>
					</div>
				</div>
				<div class="flex-col flex-grow">
                    <span class="post_date"><?php echo get_the_date(); ?></span>
                    <h3 class="post_title">
                        <a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>">
                            <?php if ( get_the_title() ) the_title(); else the_ID(); ?>
                        </a>
                    </h3>
                    <span class="post_comments op-7 block is-xsmall"><?php comments_popup_link( '', __( '<strong>1</strong> Comment', 'flatsome' ), __( '<strong>%</strong> Comments', 'flatsome' ) ); ?></span>
				</div>
			</div>
		</li>
		<?php endwhile; ?>
		<?php echo '</ul>'; ?>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
	    $instance['image'] = $new_instance['image'];
        $instance['date-stamp'] = $new_instance['date-stamp'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_posts', 'widget');
	}

	function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$instance['image'] = isset( $instance['image'] ) ? $instance['image'] : false;
        $instance['date-stamp'] = isset( $instance['date-stamp'] ) ? $instance['date-stamp'] : false;

?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'flatsome' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'flatsome' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

 		<p><input class="checkbox" type="checkbox" <?php checked($instance['image'], 'on'); ?> id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" />
		<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Show thumbnail', 'flatsome' ); ?></label></p>

        <p><input class="checkbox" type="checkbox" <?php checked($instance['date-stamp'], 'on'); ?> id="<?php echo $this->get_field_id('date-stamp'); ?>" name="<?php echo $this->get_field_name('date-stamp'); ?>" />
		<label for="<?php echo $this->get_field_id( 'date-stamp' ); ?>"><?php _e( 'Show date stamp on thumbnail', 'flatsome' ); ?></label>
        <?php echo '<p><small>' . __('* If a featured image is not set or the "Show Thumbnail" option is disabled, the date stamp will always be displayed.', 'flatsome') . '</small></p>'; ?></p>

<?php
	}
}


// register Custom Widget
function register_custom_widget() {
    register_widget( 'WP_Widget_Recent_Posts_By_Category' );
    register_widget( 'Flatsome_Custom_Recent_Post_Widget' );
}
add_action( 'widgets_init', 'register_custom_widget', 10 );