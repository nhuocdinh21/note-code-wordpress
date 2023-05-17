<?php
/*
Plugin Name: Woocommerce QR Payment
Description: Customize QR Payment Method for Woocommerce
Author: Đoàn Lâm
Version: 1.0
Author URI: 
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Custom Payment Gateway.
 *
 * Provides a Custom Payment Gateway, mainly for testing purposes.
 */
add_action('plugins_loaded', 'init_custom_gateway_class');
function init_custom_gateway_class(){

    class WC_Gateway_QrCode extends WC_Payment_Gateway {

        public $domain;

        /**
         * Constructor for the gateway.
         */
        public function __construct() {

            $this->domain = 'qr_payment';

            $this->id                 = 'qr_code_payment';
            $this->icon               = apply_filters('woocommerce_custom_gateway_icon', '');
            $this->has_fields         = false;
            $this->method_title       = __( 'QR Code Payment', $this->domain );
            $this->method_description = __( 'Allows payments with QR code.', $this->domain );

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title              = $this->get_option( 'title' );
            $this->description        = $this->get_option( 'description' );
            $this->instructions       = $this->get_option( 'instructions', $this->description );
            $this->order_status       = $this->get_option( 'order_status', 'wc-pending' );
            $this->bank               = $this->get_option( 'bank' );
            $this->account_number     = $this->get_option( 'account_number' );
            $this->account_name       = $this->get_option( 'account_name' );
            $this->qr_code_type       = $this->get_option( 'qr_code_type', 'full' );

            // Actions
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

            // Customer Emails
            add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
        }

        /**
         * Initialise Gateway Settings Form Fields.
         */
        public function init_form_fields() {

            // get list bank
            $url = 'https://api.vieqr.com/list-banks';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, false);
            $response = curl_exec($curl);
            $data_responsive = json_decode($response, true);
            curl_close($curl);

            $list_bank_arr = $data_responsive['data'];  
            $list_bank = array();
            foreach ($list_bank_arr as $bank) {
                $list_bank[$bank['shortName']] = $bank['shortName'];
            }


            $this->form_fields = array(
                'enabled' => array(
                    'title'   => __( 'Enable/Disable', $this->domain ),
                    'type'    => 'checkbox',
                    'label'   => __( 'Enable Custom Payment', $this->domain ),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title'       => __( 'Title', $this->domain ),
                    'type'        => 'text',
                    'description' => __( 'This controls the title which the user sees during checkout.', $this->domain ),
                    'default'     => __( 'QR code Payment', $this->domain ),
                    'desc_tip'    => true,
                ),
                'order_status' => array(
                    'title'       => __( 'Order Status', $this->domain ),
                    'type'        => 'select',
                    'class'       => 'wc-enhanced-select',
                    'description' => __( 'Choose whether status you wish after checkout.', $this->domain ),
                    'default'     => 'wc-pending',
                    'desc_tip'    => true,
                    'options'     => wc_get_order_statuses()
                ),
                'description' => array(
                    'title'       => __( 'Description', $this->domain ),
                    'type'        => 'textarea',
                    'description' => __( 'Payment method description that the customer will see on your checkout.', $this->domain ),
                    'default'     => __('Payment Information', $this->domain),
                    'desc_tip'    => true,
                ),
                'instructions' => array(
                    'title'       => __( 'Instructions', $this->domain ),
                    'type'        => 'textarea',
                    'description' => __( 'Instructions that will be added to the thank you page and emails.', $this->domain ),
                    'default'     => '',
                    'desc_tip'    => true,
                ),
                'bank' => array(
                    'title'       => __( 'Bank', $this->domain ),
                    'type'        => 'select',
                    'class'       => 'wc-enhanced-select',
                    'description' => __( 'Choose whether bank you wish payment.', $this->domain ),
                    'default'     => 'VietinBank',
                    'desc_tip'    => true,
                    'options'     => $list_bank
                ),
                'account_number' => array(
                    'title'       => __( 'Account Number', $this->domain ),
                    'type'        => 'text',
                    'description' => __( 'This controls the account number which the user will payment.', $this->domain ),
                    'default'     => '113366668888',
                    'desc_tip'    => true,
                ),
                'account_name' => array(
                    'title'       => __( 'Account Name', $this->domain ),
                    'type'        => 'text',
                    'description' => __( 'This controls the account name which the user will payment.', $this->domain ),
                    'desc_tip'    => true,
                ),
                'qr_code_type' => array(
                    'title'       => __( 'QR code type', $this->domain ),
                    'type'        => 'select',
                    'class'       => 'wc-enhanced-select',
                    'description' => __( 'Choose whether QR code you wish show.', $this->domain ),
                    'default'     => 'full',
                    'desc_tip'    => true,
                    'options'     => array(
                        'full' => 'Full',
                        'compact' => 'Compact',
                        'compact2' => 'Compact2',
                        'qr_only' => 'QR Only'
                    )
                )
            );
        }

        /**
         * Output for the order received page.
         */
        public function thankyou_page($order_id) {            

            $order = wc_get_order( $order_id );

            $bank             = $this->bank;
            $account_number   = $this->account_number;
            $account_name     = $this->account_name;
            $qr_code_type     = $this->qr_code_type;

            global $woocommerce;   

            $note = __( 'Please forward the correct content <span>DRS%1$sDRS</span> for us to confirm payment.', $this->domain );  

            ?>
                <?php if( $order->get_status() == 'pending' ): ?>
                    <?php if( $order && $bank && $account_number && $account_name ): ?>
                        <?php 
                            if ( $this->instructions ) {                
                                echo wpautop( wptexturize( $this->instructions ) );
                            }
                        ?>
                        <div class="qr_code_payment text-center">
                            <img src="https://api.vieqr.com/vietqr/<?php echo $bank; ?>/<?php echo $account_number; ?>/<?php echo $order->get_total(); ?>/<?php echo $qr_code_type; ?>.jpg?NDck=DRS<?php echo $order_id; ?>DRS&FullName=<?php echo $account_name; ?>">
                        </div>
                        <div class="payment_info">
                            <div class="title"><?php echo __('Bank transfer information', $this->domain); ?></div>
                            <div class="note" style="">
                                <?php printf( $note, $order_id ); ?>
                            </div>
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="text-right"><?php echo __('Account name', $this->domain); ?></td>
                                        <td><?php echo $account_name; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><?php echo __('Account number', $this->domain); ?></td>
                                        <td><?php echo $account_number; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><?php echo __('Bank', $this->domain); ?></td>
                                        <td><?php echo $bank; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><?php echo __('Amount of money', $this->domain); ?></td>
                                        <td><?php echo $order->get_formatted_order_total(); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><?php echo __('Content', $this->domain); ?> *</td>
                                        <td><span class="content">DRS<?php echo $order_id; ?>DRS</span></td>
                                    </tr>
                                </tbody>
                            </table>                        
                            <div class="btn_action">                            
                                <a class="button primary" id="paid_order" data-id="<?php echo $order_id; ?>" data-total="<?php echo $order->get_total(); ?>"><?php echo __('I have already paid', $this->domain); ?></a>
                            </div>                        
                        </div>
                        <script type="text/javascript">
                            jQuery(function($) {
                                if( $('#paid_order').length > 0 ){
                                    $('body').on('click', '#paid_order', function() {
                                        $(this).attr('disabled', 'disabled');

                                        let orderid = $(this).data('id');
                                        let total = $(this).data('total');

                                        let timer2 = "5:00";
                                        let interval = setInterval(function() {
                                            let timer = timer2.split(':');
                                            //by parsing integer, I avoid all extra string processing
                                            let minutes = parseInt(timer[0], 10);
                                            let seconds = parseInt(timer[1], 10);
                                            --seconds;
                                            minutes = (seconds < 0) ? --minutes : minutes;
                                            if (minutes < 0) clearInterval(interval);
                                            seconds = (seconds < 0) ? 59 : seconds;
                                            seconds = (seconds < 10) ? '0' + seconds : seconds;
                                            //minutes = (minutes < 10) ?  minutes : minutes;
                                            $('#paid_order').html('<?php echo __('Remaining payment confirmation time ', $this->domain); ?>' + minutes + ':' + seconds);
                                            if (minutes < 0) clearInterval(interval);
                                            //check if both minutes and seconds are 0
                                            if ((seconds <= 0) && (minutes <= 0)) clearInterval(interval);
                                            timer2 = minutes + ':' + seconds;
                                        }, 1000);

                                        let counter = 0;
                                        let check_order = setInterval(function(){
                                            counter++;                                            
                                            if( counter <= 60 ){
                                                check_order_paid(orderid, total);
                                                clearInterval(counter);                                                
                                            }
                                        }, 5000);

                                        // over 5 minute change to cancelled status
                                        setTimeout(() => { 
                                            auto_update_cancelled(orderid, total);
                                        }, 5 * 60 * 1000);

                                        return false;
                                    });
                                }

                                let check_order_paid = (orderid, total) => {
                                    $.ajax({
                                        type : "post",
                                        dataType : "json",
                                        url : '<?php echo admin_url('admin-ajax.php');?>',
                                        data : {
                                            action  : 'check_order_paid',
                                            orderid : orderid,
                                            total   : total
                                        },
                                        context: this,
                                        beforeSend: function(){

                                        },
                                        success: function(response) {
                                            console.log(response);
                                            if( response.status !== '' ){
                                                let url = window.location.href; 
                                                window.location.replace(url);
                                            }
                                        },
                                        error: function( jqXHR, textStatus, errorThrown ){
                                            console.log( 'The following error occured: ' + textStatus, errorThrown );
                                        }
                                    });
                                }

                                let auto_update_cancelled = (orderid, total) => {
                                    $.ajax({
                                        type : "post",
                                        dataType : "json",
                                        url : '<?php echo admin_url('admin-ajax.php');?>',
                                        data : {
                                            action  : 'auto_update_cancelled',
                                            orderid : orderid,
                                            total   : total
                                        },
                                        context: this,
                                        beforeSend: function(){

                                        },
                                        success: function(response) {
                                            console.log(response);
                                            if( response.status == true ){
                                                let url = window.location.href; 
                                                window.location.replace(url);
                                            }
                                        },
                                        error: function( jqXHR, textStatus, errorThrown ){
                                            console.log( 'The following error occured: ' + textStatus, errorThrown );
                                        }
                                    });
                                }
                            });
                        </script>
                        <style type="text/css">
                            .qr_code_payment {
                                margin: 15px 0;
                            }
                            .qr_code_payment img {
                                max-width: 265px;
                            }
                            .payment_info {
                                margin: 0 0 20px;
                            }
                            .payment_info .title {
                                font-size: 1.25em;
                                font-weight: 700;
                                text-align: center;
                                margin: 0 0 10px;
                            }
                            .payment_info .note {
                                background-color: #feedbe;
                                border-radius: 5px;
                                color: #333;
                                text-align: center;
                                padding: 5px 0;
                                margin: 0 0 7px;
                            }
                            .payment_info .note span {
                                color: #b71814;
                                font-weight: 700;
                            } 
                            .payment_info table tbody tr td {
                                width: 50%;
                                color: #000;
                                padding: 7px;
                                font-size: 1em;
                            }
                            .payment_info table tbody tr td span.content {
                                color: #b71814;
                                font-weight: 700;
                                font-size: 1.25em;
                            }   
                            .payment_info .btn_action {
                                text-align: center;
                            }                     
                            .payment_info .btn_action a {
                                display: inline-block;
                                border-radius: 5px;
                                margin: 0;
                                border-color: transparent;
                                letter-spacing: 0;
                                font-size: 1em;
                                text-transform: unset;
                                font-weight: 400;
                                cursor: pointer;
                            } 
                            .payment_info .btn_action a[disabled="disabled"] {
                                cursor: no-drop;
                                border-color: var(--primary-color);
                                background-color: transparent;
                                color: var(--primary-color);
                                opacity: 1;
                            }
                            .payment_info .btn_action a[disabled="disabled"]:hover {
                                box-shadow: none;
                            }
                        </style>
                    <?php endif; ?>
                <?php elseif( $order->get_status() == 'processing' ): ?>
                    <div class="payment_status">
                        <div class="title"><?php echo __('You have successfully paid for your order.', $this->domain); ?></div>
                        <div class="status_icon text-center">
                            <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/assets/images/success.png">
                        </div>
                    </div>
                    <style type="text/css">
                        .payment_status {
                            margin: 0 0 20px;
                        }
                        .payment_status .title {
                            font-size: 1.15em;
                            font-weight: 700;
                            text-align: center;
                            margin: 0 0 10px;
                        }
                    </style>
                <?php elseif( $order->get_status() == 'cancelled' ): ?>
                    <div class="payment_status">
                        <div class="title"><?php echo __('You have not made the order payment yet.', $this->domain); ?></div>
                        <div class="status_icon text-center">
                            <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/assets/images/cancel.png">
                        </div>
                    </div>
                    <style type="text/css">
                        .payment_status {
                            margin: 0 0 20px;
                        }
                        .payment_status .title {
                            font-size: 1.15em;
                            font-weight: 700;
                            text-align: center;
                            margin: 0 0 10px;
                        }
                    </style>
                <?php endif; ?>
            <?php
        }

        /**
         * Add content to the WC emails.
         *
         * @access public
         * @param WC_Order $order
         * @param bool $sent_to_admin
         * @param bool $plain_text
         */
        public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
            if ( $this->instructions && ! $sent_to_admin && 'qr_code_payment' === $order->payment_method && $order->has_status( 'on-hold' ) ) {
                echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
            }
        }

        public function payment_fields(){

            if ( $description = $this->get_description() ) {
                echo wpautop( wptexturize( $description ) );
            }
            
        }

        /**
         * Process the payment and return the result.
         *
         * @param int $order_id
         * @return array
         */
        public function process_payment( $order_id ) {

            $order = wc_get_order( $order_id );

            $status = 'wc-' === substr( $this->order_status, 0, 3 ) ? substr( $this->order_status, 3 ) : $this->order_status;

            // Set order status
            $order->update_status( $this->order_status );

            // set post meta
            // add_post_meta($order_id, 'payment_check', 0);

            // // or call the Payment complete
            // $order->payment_complete();

            // Reduce stock levels
            $order->reduce_order_stock();

            // Remove cart
            WC()->cart->empty_cart();

            // Return thankyou redirect
            return array(
                'result'    => 'success',
                'redirect'  => $this->get_return_url( $order )
            );
        }

    }
}

add_filter( 'woocommerce_payment_gateways', 'add_custom_gateway_class' );
function add_custom_gateway_class( $methods ) {
    $methods[] = 'WC_Gateway_QrCode'; 
    return $methods;
}

add_action( 'wp_ajax_check_order_paid', 'check_order_paid_function_callback' );
add_action( 'wp_ajax_nopriv_check_order_paid', 'check_order_paid_function_callback' );
function check_order_paid_function_callback() {
    $orderid = ( isset($_POST['orderid']) ) ? esc_attr($_POST['orderid']) : '';
    $total = ( isset($_POST['total']) ) ? esc_attr($_POST['total']) : '';

    $status = false;

    date_default_timezone_set('Asia/Ho_Chi_Minh');

    global $wpdb;
    $today = date('d/m/Y');
    $order = $wpdb->get_results( "SELECT * FROM tb_transactions WHERE order_id=$orderid AND amount_in=$total AND transaction_date LIKE '%$today%'", OBJECT );
    if( $order ){        
        $transaction_date = date_create( str_replace('/', '-', $order[0]->transaction_date) );
        $current_date = date_create(date("H:i d-m-Y"));
        $diff = date_diff($transaction_date, $current_date);

        $hours   = $diff->format('%h'); 
        $minutes = $hours * 60 + $diff->format('%i');

        $order_info = wc_get_order( $orderid );

        if( $minutes <= 5 ){            
            if( $order_info->get_status() == 'pending' ){
                $status = true;
                $order_info->update_status('wc-processing');
            }
        }
        else {
            if( $order_info->get_status() == 'pending' ){
                $status = true;
                $order_info->update_status('wc-cancelled');
            }
        }
    }
    echo json_encode(array('status' => $status, 'order' => $order_info));
    die();
}

add_action( 'wp_ajax_auto_update_cancelled', 'auto_update_cancelled_function_callback' );
add_action( 'wp_ajax_nopriv_auto_update_cancelled', 'auto_update_cancelled_function_callback' );
function auto_update_cancelled_function_callback() {
    $orderid = ( isset($_POST['orderid']) ) ? esc_attr($_POST['orderid']) : '';
    $total = ( isset($_POST['total']) ) ? esc_attr($_POST['total']) : '';

    $status = false;

    date_default_timezone_set('Asia/Ho_Chi_Minh');

    global $wpdb;
    $today = date('d/m/Y');
    $order = $wpdb->get_results( "SELECT * FROM tb_transactions WHERE order_id=$orderid AND amount_in=$total AND transaction_date LIKE '%$today%'", OBJECT );
    if( $order ){
        $transaction_date = date_create( str_replace('/', '-', $order[0]->transaction_date) );
        $current_date = date_create(date("H:i d-m-Y"));
        $diff = date_diff($transaction_date, $current_date);

        $hours   = $diff->format('%h'); 
        $minutes = $hours * 60 + $diff->format('%i');

        if( $minutes > 5 ){
            $order_info = wc_get_order( $orderid );
            if( $order_info->get_status() == 'pending' ){
                $status = true;
                $order_info->update_status('wc-cancelled');
            }
        }
    }
    echo json_encode(array('status' => $status, 'order' => $transaction_date));
    die();
}