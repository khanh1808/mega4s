<?php 
/**
 * Click vào thì đổi sang text và ngược lại
 * 
 */
class Vno_password_input_eye{
	public $eye_url;
	public $eye_off_url;
	
	function __construct(){
		$this->eye_url = get_stylesheet_directory_uri()."/assets/images/eye-off-svgrepo-com.svg";
		$this->eye_off_url = get_stylesheet_directory_uri()."/assets/images/eye-svgrepo-com.svg";
		add_action('wp_footer', [$this,'add_css']);
		add_action('wp_footer', [$this,'add_js']);
	}
	function add_css(){
		?>
		<style type="text/css">
			.password-input{
				position: relative;
			}
			.password-input{
				background: lightgray;
			}
			.show-password-input{
			    width: 1.2em;
			    height: 1.2em;
			    cursor: pointer;
			    display: inline-block;
			    position: absolute;
			    top: -0.6em; /* = 1/2 height*/
			    right: 1em;
			}
			.show-password-input::after{
				<?php 
				$svg = '<?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools --> <svg fill="#000000" width="" height="" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path d="M8 2.5a9.77 9.77 0 0 0-2.53.32l1 1.05A8.78 8.78 0 0 1 8 3.75 6.91 6.91 0 0 1 13.4 6a7.2 7.2 0 0 1 1.27 2 7.2 7.2 0 0 1-1.27 2c-.12.13-.24.26-.37.38l.89.89A8.24 8.24 0 0 0 16 8a8.11 8.11 0 0 0-8-5.5zm5 9.56-.9-.9-6.97-6.91-1-1-1.19-1.19-.88.88 1 1A8.25 8.25 0 0 0 0 8a8.11 8.11 0 0 0 8 5.5 9.05 9.05 0 0 0 3.82-.79l1.24 1.23.88-.88-1-1zM6.66 7.54l1.67 1.67a1.47 1.47 0 0 1-.36 0A1.35 1.35 0 0 1 6.55 8a1.07 1.07 0 0 1 .11-.46zM8 12.25A6.91 6.91 0 0 1 2.6 10a7.2 7.2 0 0 1-1.27-2A7.2 7.2 0 0 1 2.6 6 6.49 6.49 0 0 1 4 4.84l1.74 1.79A2.33 2.33 0 0 0 5.3 8 2.59 2.59 0 0 0 8 10.5a2.78 2.78 0 0 0 1.32-.33l1.58 1.58a8 8 0 0 1-2.9.5z"/></svg>'; 
				$encodedSVG = rawurlencode(str_replace(["\r", "\n"], ' ', $svg));
			 	?>
			 	content: url("data:image/svg+xml;charset=UTF-8,<?php echo $encodedSVG; ?>");
				opacity: 0.5;
			}
			.show-password-input.display-password::after{
				<?php 				
				$svg = '<?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools --> <svg width="" height="" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M6.30147 15.5771C4.77832 14.2684 3.6904 12.7726 3.18002 12C3.6904 11.2274 4.77832 9.73158 6.30147 8.42294C7.87402 7.07185 9.81574 6 12 6C14.1843 6 16.1261 7.07185 17.6986 8.42294C19.2218 9.73158 20.3097 11.2274 20.8201 12C20.3097 12.7726 19.2218 14.2684 17.6986 15.5771C16.1261 16.9282 14.1843 18 12 18C9.81574 18 7.87402 16.9282 6.30147 15.5771ZM12 4C9.14754 4 6.75717 5.39462 4.99812 6.90595C3.23268 8.42276 2.00757 10.1376 1.46387 10.9698C1.05306 11.5985 1.05306 12.4015 1.46387 13.0302C2.00757 13.8624 3.23268 15.5772 4.99812 17.0941C6.75717 18.6054 9.14754 20 12 20C14.8525 20 17.2429 18.6054 19.002 17.0941C20.7674 15.5772 21.9925 13.8624 22.5362 13.0302C22.947 12.4015 22.947 11.5985 22.5362 10.9698C21.9925 10.1376 20.7674 8.42276 19.002 6.90595C17.2429 5.39462 14.8525 4 12 4ZM10 12C10 10.8954 10.8955 10 12 10C13.1046 10 14 10.8954 14 12C14 13.1046 13.1046 14 12 14C10.8955 14 10 13.1046 10 12ZM12 8C9.7909 8 8.00004 9.79086 8.00004 12C8.00004 14.2091 9.7909 16 12 16C14.2092 16 16 14.2091 16 12C16 9.79086 14.2092 8 12 8Z" fill="#232323"/> </svg>';
				$encodedSVG = rawurlencode(str_replace(["\r", "\n"], ' ', $svg));
				?>
				content: url("data:image/svg+xml;charset=UTF-8,<?php echo $encodedSVG; ?>");
				opacity: 0.5;
				padding: 1px;
			}
		</style>
		<?php
	}
	function add_js(){
		?>
		<script type="text/javascript">
			/*Compality with woocommerce js*/
			jQuery( function( $ ) {
				if($('[type="password"]').length){
					$('[type="password"]').each(function(){
						if(!$(this).hasClass('woocommerce-Input')){
							$(this).wrap( '<span class="password-input"></span>' );
							if(typeof woocommerce_params == 'undefined'){
								$(this).parent('span').addClass('password-input').append( '<span class="show-password-input"></span>' );
							}
						}
					});
				}

				// check is woocommerce active
				if(typeof woocommerce_params == 'undefined'){
					$( '.show-password-input' ).on( 'click',
						function() {
							if ( $( this ).hasClass( 'display-password' ) ) {
								$( this ).removeClass( 'display-password' );
							} else {
								$( this ).addClass( 'display-password' );
							}
							if ( $( this ).hasClass( 'display-password' ) ) {
								$( this ).siblings( ['input[type="password"]'] ).prop( 'type', 'text' );
							} else {
								$( this ).siblings( 'input[type="text"]' ).prop( 'type', 'password' );
							}
						}
					);
				}
			});
		</script>
		<?php
	}
}
new Vno_password_input_eye;