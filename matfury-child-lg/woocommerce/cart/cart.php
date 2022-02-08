<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
        <thead>
        <tr>
			<th class="product-thumbnail">&nbsp;</th>
            <th class="product-name"><?php esc_html_e( 'Product', 'martfury' ); ?></th>
			<!-- ADD NEW HERE (Haziq - 03/02/2022) add new column for UOM in cart page-->
            <th class="product-uom"><?php esc_html_e( 'UOM', 'martfury' ); ?></th>
            <th class="product-price"><?php esc_html_e( 'Price', 'martfury' ); ?></th>
            <th class="product-quantity"><?php esc_html_e( 'Quantity', 'martfury' ); ?></th>
            <th class="product-subtotal"><?php esc_html_e( 'Total', 'martfury' ); ?></th>
            <th class="product-remove">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
                <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                    <td class="product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo wp_kses_post( $thumbnail );
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
						}
						?>
                    </td>

                    <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'martfury' ); ?>">
                        <div class="product-item product-item-thumb product-detail-mobile">
							<?php
							if ( ! $product_permalink ) {
								echo wp_kses_post( $thumbnail );
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
							}

							do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
							?>
                        </div>
                        <div class="product-content-detail">
							<?php
							if ( ! $product_permalink ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
							} else {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
							}

							// Meta data
							echo wc_get_formatted_cart_item_data( $cart_item );

							// Backorder notification
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'martfury' ) . '</p>', $product_id ) );
							}
							?>
                            <div class="product-detail-mobile">
                                <div class="product-item">
                                    <label>
										<?php esc_html_e( 'Price:', 'martfury' ); ?>
                                    </label>
									<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
									?>
                                </div>

                                <div class="product-item">
                                    <label>
										<?php esc_html_e( 'Total:', 'martfury' ); ?>
                                    </label>
									<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
									?>
                                </div>
                            </div>
                        </div>
                    </td>
					
					<!-- ADD NEW HERE (Haziq - 03/02/2022) Display UOM in cart page-->
                    <td class="product-uom" data-title="<?php esc_attr_e( 'Unit', 'martfury' ); ?>">
						<?php
						$woo_uom_output = get_post_meta( $cart_item['product_id'], '_woo_uom_input', true );
						// display UOM if it exists.
						if ( $woo_uom_output ) {
							echo '<span>' . esc_attr( $woo_uom_output ) . '</span>';
						}
						// If UOM not set, display " - ".
						else {
							echo ' - ';
						}
						?>
                    </td>

                    <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'martfury' ); ?>">
						<?php
						echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
                    </td>

                    <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'martfury' ); ?>">
						<?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input( array(
								'input_name'   => "cart[{$cart_item_key}][qty]",
								'input_value'  => $cart_item['quantity'],
								'max_value'    => $_product->get_max_purchase_quantity(),
								'min_value'    => '0',
								'product_name' => $_product->get_name(),
							), $_product, false );
						}

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
						?>
                        <div class="product-remove">
							<?php
							echo apply_filters(
								'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="mf-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="icon-cross2"></i></a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_html__( 'Remove this item', 'martfury' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key
							);
							?>
                        </div>
                    </td>

                    <td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'martfury' ); ?>">
						<?php
						echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
                    </td>
                    <td class="product-remove">
						<?php
						echo apply_filters(
							'woocommerce_cart_item_remove_link', sprintf(
							'<a href="%s" class="mf-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="icon-cross2"></i></a>',
							esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
							esc_html__( 'Remove this item', 'martfury' ),
							esc_attr( $product_id ),
							esc_attr( $_product->get_sku() )
						), $cart_item_key
						);
						?>
                    </td>
                </tr>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_contents' ); ?>

        <tr>
            <td colspan="6" class="actions">

                <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ) ); ?>"
                   class="btn-shop"><i class="icon-arrow-left"></i> <?php esc_html_e( 'Back To Shop', 'martfury' ); ?>
                </a>

				<?php
				$hidden = '';
				if ( intval( martfury_get_option( 'quantity_ajax' ) ) ) {
					$hidden = 'hidden';
				}
				?>

                <button type="submit" class="button btn-update <?php echo esc_attr( $hidden ) ?>" name="update_cart"
                        value="<?php esc_attr_e( 'Update cart', 'martfury' ); ?>"><?php esc_html_e( 'Update cart', 'martfury' ); ?></button>

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            </td>
        </tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
        </tbody>
    </table>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
	<?php if ( wc_coupons_enabled() ) { ?>
        <div class="row">
            <div class="col-md-4 col-sm-12 col-coupon">
                <div class="coupon">
                    <label for="coupon_code"><?php esc_html_e( 'Coupon Discount', 'martfury' ); ?></label>
                    <input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
                           placeholder="<?php esc_attr_e( 'Coupon code', 'martfury' ); ?>"/>
                    <input type="submit" class="button" name="apply_coupon"
                           value="<?php esc_attr_e( 'Apply coupon', 'martfury' ); ?>"/>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
                </div>
            </div>
        </div>
	<?php } ?>
</form>
<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
<div class="cart-collaterals">
    <div class="row">
        <div class="col-md-4 col-sm-12 col-colla">

        </div>

		<?php
		$cart_class = 'col-md-8 col-sm-12 col-colla';
		if ( 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) && WC()->cart->needs_shipping() ) {
			$cart_class = 'col-md-4 col-sm-12 col-colla';
			?>
            <div class="col-md-4 col-sm-12 col-colla">
				<?php woocommerce_shipping_calculator(); ?>
            </div>
		<?php } ?>
        <div class="<?php echo esc_attr( $cart_class ); ?>">
			<?php do_action( 'woocommerce_cart_collaterals' );  ?>
        </div>
    </div>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
