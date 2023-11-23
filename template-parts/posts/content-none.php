<?php
/**
 * Template part for displaying a message that posts cannot be found.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

?>
<section class="no-results not-found">
	<header class="page-title">
		<h1 class="page-title"><?php esc_html_e( 'Không tìm thấy kết quả', DOMAIN ); ?></h1>
	</header>

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( wp_kses( __( 'Bạn đã chuẩn bị bài đăng đầu tiên? <a href="%1$s">Hãy bắt đầu ngay!</a>.', DOMAIN ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Xin lỗi, nhưng không có gì phù hợp với cụm từ tìm kiếm của bạn. Vui lòng thử lại với một số từ khóa khác!.', DOMAIN ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'Có vẻ như chúng tôi không thể tìm thấy những gì bạn đang tìm kiếm.', DOMAIN ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div>
</section>
