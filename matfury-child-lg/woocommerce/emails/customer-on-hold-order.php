<?php
/**
 * Customer on-hold order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-on-hold-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<p><?php esc_html_e( 'Thanks for your order. It’s on-hold until we confirm that payment has been received. In the meantime, here’s a reminder of what you ordered:', 'woocommerce' ); ?></p>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

if ($order->get_payment_method() == 'bacs') {
	$order_date = $order->get_date_created();
	$order_date_time = $order_date->getTimestamp();
	$order_expired_date_time_local = $order_date_time + (get_option('gmt_offset') * 3600) + (24 * 3600); // plus 1 day

	$bacs_expired_date = date('d/m/Y', $order_expired_date_time_local);
	$bacs_expired_time = date('H:i', $order_expired_date_time_local);

	// convert into 12 hour format
	$bacs_expired_time_12 = date('g:i A', $order_expired_date_time_local);
	
	?>
	<span style='font-size: 15px; padding: 10px;'>Expired Date: <?php echo $bacs_expired_date . ' ' . $bacs_expired_time_12 ?></span>
	<?php
}


if ($order->get_payment_method_title() == 'JomPay' && $order->get_status() == 'on-hold') {
	$jompay = new Jompay();
	$rrn = new RRN();

	$order_id = $order->get_id();
	$sRRN = $rrn->getRRN($order_id);
	$due_date = $rrn->getDueDate($order_id);
	?>
	<div class="jompay-bill" id="jompay-bill" style="display: flex; align-items: center; margin-top: 20px;">
		<img style='width: 80px; height: 80px; margin-right: 10px; margin-bottom: 10px;' src='https://www.jompay.com.my/img/logo.png'>
		<div class="jompay-details">
			<div class="jompay-info" style="margin: auto; width: 100%; border: 3px solid #000; padding: 10px;">
				<h4>
					<b>Biller Code: </b>
					<span style="font-size: 15px; font-weight: normal;">
						<?php echo $jompay->get_option( 'biller_code' ); ?>
					</span>
				</h4>
				<h4>
					<b>Ref-1: </b>
					<span style="font-size: 15px; font-weight: normal;">
						<?php echo $sRRN;?>
					</span>
				</h4>
			</div>
		</div>
	</div>
	<span style='font-size: 15px; padding: 10px;'>Reference code valid until 11:50 P.M. <?php echo $due_date ?> only.  </span>
	<style>
		h4 {
			width: 100%;
			margin: auto;
			padding: 3px;
			font-size: 15px;
		}
		#jompay-bill {
			display: flex;
			align-items: center;
			margin-top: 20px;
		}
	</style>
<?php }

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
