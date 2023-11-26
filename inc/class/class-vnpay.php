<?php 
/**
 * localhost: IPN url không được khai báo => ko chạy vào 'payment_response_vnpay' => ko update status order
 * No Fix anymore
 */

class Vno_vnpay{
	
	function __construct(){
		
	}
	
}
new Vno_vnpay;