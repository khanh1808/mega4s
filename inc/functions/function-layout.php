<?php
 
// Close comments on the front-end
// add_filter('comments_open', function($open, $post_id) {
//     $open = (is_singular('product')) ? true : false;

//     return $open;
// }, 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
 
// Hide existing comments
// add_filter('comments_array', '__return_empty_array', 10, 2);
 
// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

function add_content_after_header() {
    if (is_front_page() || is_admin() || is_account_page()) return;
    if (is_singular('post')) return;

    // if (!is_archive() && !is_singular()) return;
    if (isset($_GET['uxb_iframe'])) return;

    $query = get_queried_object();

    $tag_name = 'h1';
    $title = get_the_title($query);
    if (isset($query->label)) {
        $title = $query->label;
    }
    elseif (isset($query->term_id)) {
        $title = $query->name;  
    }
    elseif (is_singular('post')) {
        $title = __('Tin tức', DOMAIN);
        $tag_name = 'h2';
    }
    elseif (is_singular('product')) {
        $title = __('Sản phẩm', DOMAIN);
        $tag_name = 'h2';
    }
    elseif (is_month()) {
        $title = get_the_archive_title();
        $tag_name = 'h2';
    }
    if (is_search()) {
        $title = __('Tìm kiếm', DOMAIN);
    }

    // if (is_archive()) {
    //     $banner = get_field('banner_site', $query);
    // }
    // else {
    //     $banner = get_field('banner_site', get_the_ID());
    // }

    // $id_banner = ($banner) ? $banner : 176;

    if (is_singular('page') && !get_field('show_breadcrumb', get_the_ID())) return;

    $content_banner = '
        [section bg_color="rgb(246, 246, 246)" padding="35px" padding__sm="20px"]

            [row]

                [col span__sm="12" align="center" class="pb-0"]

                    [ux_text font_size="1" font_size__sm="0.75" font_size__md="0.9"]

                        <p><span style="color: #4e3629; font-family: trebuchet ms, geneva; font-size: 140%;">ĐỒNG GIAO ĐƯỜNG - Trị liệu Đông Y</span></p>
                    
                    [/ux_text]
                    
                    [ux_text font_size="1.8" font_size__md="1.25" text_color="rgb(0,0,0)"]

                        <'.$tag_name.' class="uppercase">'.$title.'</'.$tag_name.'>

                    [/ux_text]

                [/col]

            [/row]

        [/section]
    ';
    echo do_shortcode( $content_banner );
}
// add_action ('flatsome_after_header' , 'add_content_after_header');