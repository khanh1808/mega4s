<?php
/**
 * Load Function
 * @since 1.0.0
 * @package HVK
 */

// Load Simplehtmldom Lib
// require_once HVK_DIR. '/inc/simplehtmldom/simple_html_dom.php';
require_once THEME_DIR. '/inc/enqueue.php';
require_once THEME_DIR. '/inc/acf/init.php';
require_once THEME_DIR. '/inc/shortcodes/init.php';
require_once THEME_DIR. '/inc/functions/init.php';
require_once THEME_DIR. '/inc/hook/init.php';
require_once THEME_DIR. '/inc/class/init.php';

if (version_compare(PHP_VERSION, '8.0.0') >= 0) {

}
