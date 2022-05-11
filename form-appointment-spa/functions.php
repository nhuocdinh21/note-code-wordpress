<?php
// add style common
function website_enqueue_style() {
	// add toastr
	wp_enqueue_style( 'toastr_css', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css', 'all' );
}
if ( !is_admin() ) add_action( 'wp_enqueue_scripts', 'website_enqueue_style' );

// Enqueue scripts
function website_enqueue_scripts() {
	// add toastr
	wp_enqueue_script( 'toastr_js', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js', array('jquery'));
}
add_action( 'wp_footer', 'website_enqueue_scripts' );

// Mail send phone and info
add_action( 'wp_ajax_ajax_send_ton', 'set_send_ton' );
add_action( 'wp_ajax_nopriv_send_ton', 'set_send_ton' );
function set_send_ton(){
     $phone_cus = $_POST['phonecus'];
     $name_cus = $_POST['name_cus'];
     $khunggio = $_POST['khunggios'];
     $datebooking = $_POST['dateb'];
     $monthbooking = $_POST['monthb'];
     $yearbooking = $_POST['yearb'];
     $to = get_field('email','option');
     $subject = 'Đặt lịch nhanh gửi từ website';



     $body .=  '<table border="0" cellpadding="0" cellspacing="0" width="600" id="m_4536084796235965082template_container" style="background-color:#ffffff;border:1px solid #dedede;border-radius:3px!important">';
     $body .=  '<tbody>';
     $body .=  '<tr>';
     $body .=  '<td align="center" valign="top">';
     $body .=  '<table border="0" cellpadding="0" cellspacing="0" width="600" id="m_4536084796235965082template_header" style="background-color:#6db405;border-radius:3px 3px 0 0!important;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif">';
     $body .=  '<tbody>';
     $body .=  '<tr>';
     $body .=  '<td id="m_4536084796235965082header_wrapper" style="padding: 17px 48px;display:block;">';
     $body .=  '<h1 style="color:#ffffff;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:25px;font-weight:300;line-height:150%;margin:0;text-align:left">ĐƠN ĐẶT LỊCH TỪ WEBSITE CỦA BẠN</h1>';
     $body .=  '</td></tr></tbody></table></td></tr>';
     $body .=  '<tr>';
     $body .=  '<td align="center" valign="top" >';
     $body .=  '<table border="0" cellpadding="0" cellspacing="0" width="600" id="m_4536084796235965082template_body">';
     $body .=  ' <tbody><tr>';
     $body .=  '<td valign="top" id="m_4536084796235965082body_content" style="background-color:#ffffff">';
     $body .=  '<table border="0" cellpadding="20" cellspacing="0" width="100%">';
     $body .=  '<tbody><tr><td valign="top" style="padding:26px 48px 0">';
     $body .=  '<div id="m_4536084796235965082body_content_inner" style="color:#636363;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">';
     $body .=  '<p style="margin:0 0 16px">Bạn vừa nhận được thông báo mail cho việc đặt lịch của website. Nội dung mail như sau:</p>';
     $body .=  '<h2 style="color:#96588a;display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">Đặt lịch hẹn Ngày '.$datebooking.' Tháng '.$monthbooking.' Năm '.$yearbooking.'</h2>';
     $body .=  '<div style="margin-bottom:40px">';
     $body .=  '<table class="m_4536084796235965082td" cellspacing="0" cellpadding="6" border="1" style="width: 100%;">';

     $body .=  '<tfoot><tr><th class="m_1052824449939981279m_4536084796235965082td" scope="row" colspan="2" style="text-align:left;color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px">Họ và Tên:</th><td class="m_1052824449939981279m_4536084796235965082td" style="text-align:left;color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px"><span>'.$name_cus.'</span></td></tr><tr><th class="m_1052824449939981279m_4536084796235965082td" scope="row" colspan="2" style="text-align:left;color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px">Số điện thoại:</th><td class="m_1052824449939981279m_4536084796235965082td" style="text-align:left;color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px"><span>'.$phone_cus.'</span></td></tr><tr><th class="m_1052824449939981279m_4536084796235965082td" scope="row" colspan="2" style="text-align:left;color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px">Khung giờ:</th><td class="m_1052824449939981279m_4536084796235965082td" style="text-align:left;color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px"><span>'.$khunggio.'</span></td></tr></tfoot>';

     $headers = array('Content-Type: text/html; charset=UTF-8');
 
     wp_mail( 'lamdx@vinahost.vn', $subject, $body, $headers );
     die();
}