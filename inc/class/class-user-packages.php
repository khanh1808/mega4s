<?php 
/**
 * 
 */
class VNO_USER_PACKAGES {
	
	function __construct(){
		add_filter('vno_user_packges_quyen_arr',[$this,'get_packges_arr'],10,1);
		add_filter('vno_all_packages',[$this,'add_guest_packages'],10,1);
	}

	function get_packges_arr(){
		return array(
			'tracuu' => 'Tra cứu thông tin tài liệu',
			'timkiem' => 'Tham khảo thông tin tìm kiếm',
			'thongke_coban' => 'Thống kê dữ liệu cơ bản',
			'trichxuat_baocao_coban' => 'Trích xuất báo cáo cơ bản',
			'thongke_nangcao' => 'Thống kê dữ liệu nâng cao',
			'trichxuat_baocao_nangcao' => 'Trích xuất báo cáo nâng cao',
			'trichxuat_dulieu' => 'Trích xuất dữ liệu',
		);
	}

	function add_guest_packages($return){
		$guest = [
			'name' => 'Guest',
			'price' => false,
			'product_id' => false,
			'quyen'=> [
				'tracuu',
				'timkiem',
				'thongke_coban'
			]
		];
		$return = array_merge([$guest],$return);
		return $return;
	}
}

new VNO_USER_PACKAGES;