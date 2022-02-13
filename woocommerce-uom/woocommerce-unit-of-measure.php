<?php
/**
 * Plugin Name: WooCommerce UOM
 * Plugin URI:
 * Description: WooCommerce UOM allows the user to add a unit of measure (or any text) after the price on WooCommerce products.
 * Version: 1.0.1
 * Author: Haziq Hapiz
 * Author URI: https://haziqhapiz.me
 * License: GPL3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: woocommerce-uom
 * WC requires at least: 3.0.0
 * WC tested up to: 5.1.0
 *
 * WooCommerce UOM. A Plugin that works with the WooCommerce plugin for WordPress.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://www.gnu.org/licenses/gpl-3.0.html.
 */

if ( ! defined( 'ABSPATH' ) ) :
	exit; // Exit if accessed directly.
endif;

/**
 * Check if WooCommerce is active.
 *
 * @since 1.0.0
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) :
	/**
	 * WooCommerce is installed so it is time to make it all happen.
	 */
	uom_class_loader();
endif;

/**
 * Add the classes that make the magic.
 *
 * @since 3.0.0
 */
function uom_class_loader() {
	require_once trailingslashit( dirname( __FILE__ ) ) . 'includes/class-wc-uom.php';
}
