<?php 
/**
 * Quy định level của user
 */
class VNO_USER{
	public $user_id; // User id hiện tại. 	
	public $packages; // thông tin các gói đã mua
	public $package; // thông tin gói hiện tại


	
	function __construct(){
		add_action('init',[$this,'init']);		
	}

	function init($user_id = false){
		if(!$user_id){
			$user_id = get_current_user_id();
		}
		$this->user_id = $user_id;
		$this->load_package();
	}

	function vno_kiemtra_quyen($quyen){
		$return = false;
		$pack = $this->package;
		if(isset($pack['data']['quyen']) and in_array($quyen, $pack['data']['quyen'])){
			if(isset($pack['order_date_completed'])){
				$order_date_completed = $pack['order_date_completed']->format('U');
				if(isset($pack['data']['thoihan'])){
					$ngay_het_han = $order_date_completed + $pack['data']['thoihan']*24*60*60;
					if($ngay_het_han >= time()){
						$return = true;
					}
				}
				
			}
		}

		return $return;
	}

	function load_package(){
		// Toàn bộ order woo
		$orders = vno_load_orders();
		if( !$orders ){
			return false;
		}
		$packages = vno_load_user_packages($orders);
		
		// Chỉ lấy gói mới nhất và còn hạn
		if(!empty($packages) and is_array($packages)){
			foreach ($packages as $key => $pack) {
				if(isset($pack['order_date_completed'])){
					$order_date_completed = $pack['order_date_completed']->format('U');
					if(isset($pack['data']['thoihan'])){
						$ngay_het_han = $order_date_completed + $pack['data']['thoihan']*24*60*60;
						if($ngay_het_han >= time()){							
							$this->package = $pack;
							return;
						}
					}
				}
			}
		}
	}
}
/*
 * Biến Vno là toàn cục
 * Biến được khai báo khi khởi chạy PHP
 * Biến được load trong action init (Mục đich để gọi global)
 */
$Vno_user = new VNO_USER;

// Ví dụ gọi trong template
/*
	global $Vno_user;
	echo '<pre>'; print_r($Vno_user); echo '</pre>';
*/

// Ví dụ gọi trong action hook
/*add_action('wp_footer',function(){
	global $Vno_user;
	echo '<pre>'; print_r($Vno_user); echo '</pre>';
});*/