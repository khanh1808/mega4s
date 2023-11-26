<?php 
/**
 * Out trình duyệt = logout
 * Bỏ checkout lưu mật khẩu
 * Thêm checkout ghi nhớ đăng nhập
 */

if(!defined('VNO_MAX_TIME_TUDONGDANGNHAP')){
	define('VNO_MAX_TIME_TUDONGDANGNHAP',5*60);
}

class VNO_Userlogin extends WP_User{
	public $max_timediff;
	public $lasttime;
	public $autologin;

	
	function __construct($user_id) {
		/*
			Option 1: 
				+ tắt trình duyệt thì logout = Ko thực hiện được
			Option 2: 
				+ Giảm thời gian cookie user xuống còn 5 phút: Không hợp lý vì gây phiền nhiễu
					+ ko can thiệp
			Option 3:				
				+ Thực hiện logout user dựa trên
					+ Template redirect
					+ user meta: last time (lưu lại sau mỗi làn redirect)
					+ user meta: autologin (Lưu lại từ checkbox login form)
					+ logout user sau khi đăng nhập lại quá 5 phút.
				+ Khi vào trang web thì check lasttime va autologin
					+ Nếu bật check
						+ check timediff và max_timediff
							+ Nhỏ hơn
								+ Cho phép đăng nhập luôn
							+ Lớn hơn
								+ Lưu lại lasttime
								+ Logout user 
								+ Redirect về form login
					+ Lưu lại lasttime
		*/


		parent::__construct($user_id);

		add_action('wp_login', [$this,'set_autologin'], 10,2);
		add_action('template_redirect', [$this,'init_data']);
	}

	function init_data(){
		$this->set_data();
		$this->load_data();
		$this->maybe_do_logout();
	}

	function maybe_do_logout(){
		if(!is_user_logged_in()) return;
		if($this->autologin == "true") return;
		$timediff = time() - $this->lasttime;
		if($timediff>=$this->max_timediff){
			$this->set_logout();
		}
	}

	function set_data(){
		$this->set_lasttime();		
	}

	function load_data(){
		$this->get_lasttime();
		$this->get_max_timediff();
		$this->get_autologin();
	}

	function set_logout(){		
		update_user_meta(get_current_user_id(),'current_url','');
		wp_logout();
		wp_redirect( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
		exit();
	}

	function set_lasttime(){
		update_user_meta(
			$this->ID,
			'old_url',
			get_user_meta($this->ID, 'current_url',true)
		);

		update_user_meta(
			$this->ID,
			'current_url',
			get_permalink()
		);

		$old_url = get_user_meta($this->ID, 'old_url',true);
		$current_url = get_user_meta($this->ID, 'current_url',true);

		// nếu nhảy sang link mới thì update 
		if($old_url != $current_url){
			update_user_meta($this->ID,'lasttime',time());
		}
	}

	function set_autologin($user_login,$user){
		$user_id = $user->data->ID;
		$autologin = 'false';

		if(isset($_POST['autologin']) and $_POST['autologin']) {
			$autologin = 'true';
		}
		update_user_meta($user_id,'autologin',$autologin);		
	}

	function get_lasttime(){
		$this->lasttime = get_user_meta($this->ID, 'lasttime',true);
	}

	function get_max_timediff(){
		$this->max_timediff = VNO_MAX_TIME_TUDONGDANGNHAP;
	}

	function get_autologin(){
		$this->autologin = get_user_meta($this->ID, 'autologin',true); 
	}
}

new VNO_Userlogin(get_current_user_id());