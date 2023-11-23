<?php 
function woocommerce_template_single_button_seemore() {
    echo '<a href="'.get_the_permalink().'" class="button">'.__('Xem thêm', DOMAIN).'</a>';
}
function woocommerce_account_avt() {
    if(!is_user_logged_in()) return;

    $user = wp_get_current_user();

    ?>
    <div class="top_sidebar_account text-center">
        <figure class="acc_avt">
            <img src="<?php echo THEME_IMAGE.'/avt.svg' ?>" width="74" height="74"/>    
        </figure>    
        <h3 class="side_user_name is_xlarge"><?php echo $user->display_name; ?></h3>
    </div>
    <?php
}

function custom_woocommerce_account_content() {
    global $wp;

    if ( ! empty( $wp->query_vars ) ) {
        foreach ( $wp->query_vars as $key => $value ) {
            // Ignore pagename param.
            if ( 'pagename' === $key ) {
                continue;
            }

            if ( has_action( 'woocommerce_account_' . $key . '_endpoint' ) ) {
                do_action( 'woocommerce_account_' . $key . '_endpoint', $value );
                return;
            }
        }
    }

    // No endpoint found? Default to dashboard.
    WC_Shortcode_My_Account::edit_address();
}
