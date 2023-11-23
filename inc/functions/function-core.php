<?php
function gda_get_thumbnail_url( $size = 'full', $pt = '') {
    global $post;
    if (has_post_thumbnail( $post->ID ) ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size );
        return $image[0];
    }

    // use first attached image
    $images = get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post->ID );
    if (!empty($images)) {
        $image = reset($images);
        $image_data = wp_get_attachment_image_src( $image->ID, $size );
        return $image_data[0];
    }

    $pre_file = ($pt == '') ? '' : '-'.$pt;
    // use no preview fallback
    if ( file_exists( get_template_directory().'/images/nothumb-'.$size.$pre_file.'.jpg' ) )
        return THEME_IMAGE.'/nothumb-'.$size.$pre_file.'.jpg';
    else
        return THEME_IMAGE.'/no-image'.$pre_file.'.jpg';
}

if( !function_exists('get_post_thumb') ) {
	function get_post_thumb(){
		global $post ;
		if ( has_post_thumbnail($post->ID) ){
			$image_id = get_post_thumbnail_id($post->ID);
			$image_url = wp_get_attachment_image_src($image_id,'full');
			$image_url = $image_url[0];
			return $image_url;
		}
	}
}

//Lấy ID link youtube
function gda_get_youtube_id($youtubeURL){
  $url = urldecode(rawurldecode($youtubeURL));
  preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
  return $matches[1];
}

//Lấy ID link youtube
function isValidYoutubeUrl($youtubeURL){
    $url = urldecode(rawurldecode($youtubeURL));
    return  preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url);
}

function getFistYoutubeUrl($content){
  preg_match("/(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $content);
  return $matches[1];
}

//Lấy ảnh mặc định video youtube
function gda_get_youtube_default_thumb($youtubeURL){
  $youTubeThumb = gda_get_youtube_id($youtubeURL);
  return 'http://img.youtube.com/vi/'.$youTubeThumb.'/mqdefault.jpg';
}

function kh_create_attachment($filename) {
    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype(basename($filename), null);

    // Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();

    $attachFileName = $wp_upload_dir['path'] . '/' . basename($filename);
    $attachFileName = apply_filters('kh_create_attachment_file_name', $attachFileName);
    copy($filename, $attachFileName);
    // Prepare an array of post data for the attachment.
    $attachment = array(
        'guid'           => $attachFileName,
        'post_mime_type' => $filetype['type'],
        'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );
    // Notify subscribers with attachment data
    $attachment = apply_filters('nmr_before_insert_attachment', $attachment);
    // Insert the attachment.
    $attach_id = wp_insert_attachment($attachment, $attachFileName);
    
    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    // Required for audio attachments
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata($attach_id, $attachFileName);
    wp_update_attachment_metadata($attach_id, $attach_data);
    do_action('kh_create_attachment_id_generated', $attach_id);
    return $attach_id;
}

function hexToRgb($hex, $alpha = false) {
   $hex      = str_replace('#', '', $hex);
   $length   = strlen($hex);
   $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
   $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
   $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
   if ( $alpha ) {
      $rgb['a'] = $alpha;
   }
   return $rgb;
}
function imagettfmultilinetext($image, $size, $angle, $x, $y, $color, $fontfile,  $text, $spacing=1.8) {
	$lines = explode("\n",$text);
	
	for ($i = 0; $i < count($lines); $i++) {
		$newY = $y + ($i * $size * $spacing);

		$box = imagettfbbox($size, 0, $fontfile, $lines[$i]);
		$text_width = abs($box[2]) - abs($box[0]);
		$image_width = imagesx($image);
		$newX = ($image_width - $text_width) / 2;

		imagettftext($image, $size, $angle, $newX, $newY, $color, $fontfile,  $lines[$i]);
	}
	return null;
}
function writing_text_on_default_image($text, $color, $output) {
	$color_arr = hexToRgb($color);

	$imgPath = THEME_DIR.'/inc/import/temp/thumbnail.jpg';
	$image = imagecreatefromjpeg($imgPath);

	$set_color = imagecolorallocate($image, $color_arr['r'], $color_arr['g'], $color_arr['b']);

	// $string = strtoupper($text);
	$string = $text;
	$new_text = wordwrap($string, 20, "\n");

	$fontSize = 60;
	$x = 321;
	$y = 400;
	$font = THEME_DIR.'/inc/import/temp/SFUFutura.ttf';

	imagettfmultilinetext($image, $fontSize, 0, $x, $y, $set_color, $font, $new_text);

	$return = imagejpeg($image, $output);

	imagedestroy($image);

	return $return;
}

// Hàm convert chuỗi SQL sang Arry và ngược lại
// true - serialize (Array -> Convert SQL String)
// false - unserialize (Convert SQL String -> Array)
function kh_convert_serialize($input, $output_type = true) {
    if ($output_type == true) {
        $output = serialize($input);
    }
    else {
        $output = unserialize($input);
    }
    return $output; 
}

function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}

function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}