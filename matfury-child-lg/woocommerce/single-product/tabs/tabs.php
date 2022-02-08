<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs   = apply_filters( 'woocommerce_product_tabs', array() );
$layout = apply_filters( 'martfury_product_tabs_layout', martfury_get_product_layout() );

$ch_down   = '';
$tab_class = '';
if ( intval( martfury_is_mobile() ) && intval( martfury_get_option( 'product_collapse_tab' ) ) ) {
	$ch_down  = '<span class="tab-toggle"><i class="icon-chevron-down"></i></span>';
	$tab_class = 'product-collapse-tab-enable';
}
if ( ! empty( $tabs ) ) :
	if ( $layout != '3' ) {
		?>
		<?php // ADD NEW HERE (Haziq - 02/02/2022) - init product knowledge variables
			$product_id = get_the_ID();

			$plastic_number = get_post_meta( $product_id, '_plastic_number', true );
			$lifespan = get_post_meta( $product_id, '_lifespan_duration', true );
			$lifespan_timeframe = get_post_meta( $product_id, '_lifespan_time', true );

			$bpa = get_post_meta( $product_id, '_bpa_free', true ); // radio button
			$reusable = get_post_meta( $product_id, '_reusable', true ); // radio button

			$temperature = get_post_meta( $product_id, '_temperature', true ); 

			$microwavable = get_post_meta( $product_id, '_microwave_safe', true ); // radio button
			$recycle_material = get_post_meta( $product_id, '_recycle_material', true ); // radio button

			$percentage_recyclable = get_post_meta( $product_id, '_percentage_recyclable', true );

			$recyclable = get_post_meta( $product_id, '_recyclable', true ); // radio button
			$disposable = get_post_meta( $product_id, '_disposable', true ); // radio button
			$biodegradable = get_post_meta( $product_id, '_biodegradable', true ); // radio button

			$content_formula = get_post_meta( $product_id, '_content_formula', true );
			$raw_material = get_post_meta( $product_id, '_raw_material', true );

			$product_knowledge = false;

			if ( $plastic_number || $lifespan || $lifespan_timeframe || ($bpa != 'undefined' && $bpa != '') || ($reusable != 'undefined' && $reusable != '') || $temperature || ($microwavable != 'undefined' && $microwavable != '') || ($recycle_material != 'undefined' && $recycle_material != '') || $percentage_recyclable || ($recyclable != 'undefined' && $recyclable != '') || ($disposable != 'undefined' && $disposable != '') || ($biodegradable != 'undefined' && $biodegradable != '') || $content_formula || $raw_material ) {
				$product_knowledge = true;
			}
		?>

        <div class="woocommerce-tabs wc-tabs-wrapper">
            <ul class="tabs wc-tabs" role="tablist">
				<?php foreach ( $tabs as $key => $tab ) : ?>
                    <li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>"
                        role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
                        <a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
                    </li>
				<?php endforeach; ?>
				<?php // ADD NEW HERE (Haziq - 02/02/2022) - add product knowledge tab
					if ($product_knowledge) :?>
					<li class="product_knowledge_tab" id="tab-title-product-knowledge" role="tab" aria-controls="tab-product-knowledge">
						<a href="#tab-product-knowledge">
							<?php echo "Product Knowledge"; ?>
						</a>
					</li>
				<?php endif; ?>
            </ul>
			<?php foreach ( $tabs as $key => $tab ) : ?>
                <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab"
                     id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
                     aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
					<?php if ( isset( $tab['callback'] ) ) {
						call_user_func( $tab['callback'], $key, $tab );
					} ?>
                </div>
			<?php endforeach; ?>
			<?php // ADD NEW HERE (Haziq - 02/02/2022) display product knowledge details ?>
			<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--product-knowledge; ?> panel entry-content wc-tab" id="tab-product-knowledge" role="tabpanel" aria-labelledby="tab-title-product-knowledge">
				<?php
					echo "<h4>Details</h4>";

					if ($plastic_number) {
						echo "<span><strong>Plastic Number: </strong>" . $plastic_number . "</span><br>";
					}

					if ($bpa != 'undefined' && $bpa != '') {
						echo "<span><strong>BPA Free: </strong>" . ucfirst($bpa) . "</span><br>";
					}

					if ($reusable != 'undefined' && $reusable != '') {
						echo "<span><strong>Safe To Reuse: </strong>" . ucfirst($reusable) . "</span><br>";
					}

					if ($lifespan && $lifespan_timeframe) {
						echo "<span><strong>Lifespan: </strong>" . $lifespan . " " . ucfirst($lifespan_timeframe) . "(s)</span><br>";
					}

					if ($temperature) {
						echo "<span><strong>Temperature: </strong>" . $temperature . " °C</span><br>";
					}

					if ($microwavable != 'undefined' && $microwavable != '') {
						echo "<span><strong>Microwave Safe: </strong>" . ucfirst($microwavable) . "</span><br>";
					}

					if ($recycle_material != 'undefined' && $recycle_material != '') {
						echo "<span><strong>Recycle Material: </strong>" . ucfirst($recycle_material) . "</span><br>";
					}

					if ($percentage_recyclable) {
						echo "<span><strong>Percentage Recyclable: </strong>" . $percentage_recyclable . "%</span><br>";
					}

					if ($recyclable != 'undefined' && $recyclable != '') {
						echo "<span><strong>Recyclable: </strong>" . ucfirst($recyclable) . "</span><br>";
					}

					if ($disposable != 'undefined' && $disposable != '') {
						echo "<span><strong>Disposable: </strong>" . ucfirst($disposable) . "</span><br>";
					}

					if ($biodegradable != 'undefined' && $biodegradable != '') {
						echo "<span><strong>Biodegradable: </strong>" . ucfirst($biodegradable) . "</span><br>";
					}

					if ($content_formula) {
						echo "<span><strong>Content Formula: </strong>" . $content_formula . "</span><br>";
					}

					if ($raw_material) {
						echo "<span><strong>Raw Material: </strong>" . $raw_material . "</span><br>";
					}
				?>
			</div>
			<?php do_action( 'woocommerce_product_after_tabs' ); ?>
        </div>

	<?php } else {
		?>
        <div class="mf-woo-tabs wc-tabs-wrapper <?php echo esc_attr( $tab_class ) ?>">
			<?php foreach ( $tabs as $key => $tab ) : ?>
                <div class="mf-Tabs-panel mf-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content"
                     id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
                     aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
                    <h3 class="tab-title"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?><?php echo '' . $ch_down ?></h3>
					<?php if ( isset( $tab['callback'] ) ) {
						echo '<div class="tab-content-wrapper">';
						call_user_func( $tab['callback'], $key, $tab );
						echo '</div>';
					} ?>
                </div>
			<?php endforeach; ?>
        </div>
		<?php
	}

endif; ?>

