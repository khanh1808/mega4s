<?php
add_action( 'wp_footer', function() {
    $popup = '
        [lightbox id="popup_notice" width="650px" padding="15px" class="text-center border-radius-10"]
            <figure class="text-center">
                <img src="'.THEME_URL.'/assets/images/submit-successfully.png" width="100">
            </figure>
            <h3>Gửi thông tin thành công!</h3>
            <p>
                Chúng tôi đã nhận được thông tin và sẽ liên hệ lại với bạn trong thời gian sớm nhất. <br> Xin cảm ơn!
            </p>
            [ux_stack distribute="center" gap="1"]
                [button text="Về trang chủ" letter_case="lowercase" color="secondary" radius="10" link="/"]
                [button text="Xác nhận" letter_case="lowercase" radius="10" class="close_popup"]
            [/ux_stack]
        [/lightbox]
    ';
    echo '<a href="#popup_notice" id="open_notice" class="hidden"></a>';
    echo do_shortcode($popup);
} );

add_filter( 'flatsome_lightbox_close_btn_inside', '__return_true' );